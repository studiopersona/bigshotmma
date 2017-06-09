<?php
namespace Bsmma\divStrong\Reports;

use Bsmma\Contest;
use Bsmma\ContestParticipant;
use Bsmma\ContestUserBalance;
use Bsmma\Event;
use Bsmma\FightResult;
use Bsmma\Pick;
use Bsmma\UserBalance;
use Bsmma\divStrong\Scoring\FightScoring;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Collection;

class ContestStandings
{
	private $contestList;
	private $participantsPicks;
	private $contestScores;
	private $fightsList;
	private $fightResults;
	private $contestWinners;
	private $winningsByPlayer;

	public function __construct(
		FightScoring $fightScoring,
		Event $event,
		Contest $contest,
		ContestParticipant $contestParticipant,
		ContestUserBalance $contestUserBalance,
		FightResult $fightResult,
		Pick $pick,
		UserBalance $userBalance
	)
	{
		$this->fightScoring       = $fightScoring;
		$this->event              = $event;
		$this->contest            = $contest;
		$this->contestParticipant = $contestParticipant;
		$this->contestUserBalance = $contestUserBalance;
		$this->fightResult        = $fightResult;
		$this->pick               = $pick;
		$this->userBalance        = $userBalance;
	}

	public function handle($eventId, $contestId = null)
	{
		$getList = (is_null($contestId)) ? $this->getContestsByEventId($eventId) : $this->getContestById($contestId);
		if ( isset($getList['failed']) ) return ['failed' => true, 'msg' => $getList['msg']];

		$this->getFightResults();

		$participantsPicks = $this->getParticipantsPicks();
		if ( isset($participantPicks['failed']) ) return ['failed' => true, 'msg' => $participantPicks['msg']];

		$getScores = $this->getContestParticipantsScores();

		$this->getContestScores();
		$this->determineWinningParticipants();
		$this->determinePayouts();

		if ( is_null($contestId) ) $this->distributeWinnings();

		return $this->winningsByPlayer;
	}

	protected function getContestsByEventId($eventId)
	{
		$event = $this->event->with([
								'contests.users',
								'fights.fightResults.powerUps',
								'fights.fighters'
							])
							->where('id', $eventId)
							->get();

		if ( $event->isEmpty() ) return ['failed' => true, 'msg' => 'Could not find this event'];

		$this->contestList = $event[0]->contests;
		$this->fightsList = $event[0]->fights;

		return;
	}

	protected function getContestById($contestId)
	{
		$contest = $this->contest->with([
			'users',
			'event.fights.fightResults.powerUps',
			'event.fights.fighters',
		])
		->where('id', $contestId)
		->get();

		$this->contestList = $contest;
		$this->fightsList = $contest[0]->event->fights;
	}

	protected function getParticipantsPicks()
	{
		$this->contestList->each(function($contest, $contestKey) {
			// if the contest has participants add their picks
			// if ( isset($contest->users[$contestKey]) ) {
				$contest->users->each(function ($user, $key) use (&$contest) {
					$contest->users[$key]['picks'] = $this->pick->where('contest_id', $contest->id)
									->where('user_id', (int)$user->id)
									->get()->toArray();
				});
			// }
		});

		return;
	}

	protected function getContestParticipantsScores()
	{
		if ( is_null($this->fightResults) || is_null($this->contestList ) ) return ['failed' => true, 'msg' => 'Either Fight Results or Contests List was not set'];
		$this->contestList->each(function($contest, $contestKey) {
			if ( count($contest->users) !== 0 ) {
				$contest->users->each(function($participant, $key) use (&$contest, $contestKey) {
					$participant = $participant->toArray();
					if ( isset($participant['picks']) ) {
						$contest->users[$key]['score'] = $this->getParticipantScore($participant['picks']);
					}
				});
			}
		});

		return;
	}

	protected function getParticipantScore($picks)
	{
		$score = 0;
		$picks = new Collection($picks);

		$picks->each(function($pick, $key) use (&$score) {
			$this->fightResults->each(function($fightResult, $key) use (&$score, $pick) {
				if ( isset($fightResult['results'][0]) ) {
					$results = $fightResult['results'][0];

					$results = ( is_null($results) ) ? [] : $results->toArray();

					if ( (int)$fightResult['fight_id'] === (int)$pick['fight_id'] ) {
						$score += $this->fightScoring->handle($pick, $results, $fightResult['fighters']->toArray());
					}
				}
			});
		});

		return $score;
	}

	protected function getContestScores()
	{
		$this->contestScores = $this->contestList->map(function($contest, $contestKey) {
			$userScores = $contest->users->map(function ($user, $key) {
				return ['userId' => $user->id, 'score' => $user->score];
			})->sortByDesc('score')->values();

			return [
				'contestId'       => $contest->id,
				'contestTypeId'   => $contest->contest_type_id,
				'maxParticipants' => $contest->max_participants,
				'users'           => $userScores,
				'entryFee'        => $contest->entry_fee,
			];
		});

		return;
	}

	protected function getFightResults()
	{
		$this->fightResults = $this->fightsList->map(function($fight, $key) {
			// dump($fight);
			return ['fight_id' => $fight->id, 'results' => $fight->fightResults, 'fighters' => $fight->fighters ];
		});

		return;
	}

	protected function determineWinningParticipants()
	{
		$contestWinners = [];

		$this->contestScores->each(function($contest) use (&$contestWinners) {

			switch ($contest['contestTypeId']) {
				case 1:
					$percentWinners = 0.20;
					break;

				case 2:
					$percentWinners = 0.50;
					break;

				case 3:
					$percentWinners = 0;
					break;

				default:
					$percentWinners = 0;
					break;
			}

			$winningPlaces = $contest['maxParticipants'] * $percentWinners;
			$place = 0;
			$currentScore = 0;
			$standings = [];

			for ($i = 0; $i <= $winningPlaces; ++$i) {
				$standings[$i] = [];
			}

			$contest['users']->each(function($user, $index) use ($winningPlaces, &$currentScore, &$standings, &$place, &$firstPass) {
				if ( $place > $winningPlaces ) return false;

				if ( $currentScore === $user['score'] || $index === 0 ){
					$firstPass = false;
					$currentScore = $user['score'];
					if ($currentScore > 0) array_push($standings[$place], $user);
				} else {
					$place += 1;
					if ( $place === (int)$winningPlaces || $place > (int)$winningPlaces ) {
						return false;
					} else {

						$currentScore = $user['score'];
						if ($currentScore > 0) array_push($standings[$place], $user);
					}
				}

			});

			$contestWinners[] = [
				'contestId' => $contest['contestId'],
				'contestTypeId' => $contest['contestTypeId'],
				'maxParticipants' => $contest['maxParticipants'],
				'places'    => $winningPlaces,
				'entryFee'  => $contest['entryFee'],
				'winners'   => $standings
			];
		});

		$this->contestWinners = new Collection($contestWinners);
	}

	protected function determinePayouts()
	{
		if ( is_null($this->contestWinners) ) return ['failed' => true, 'msg' => 'There were no contest winners.'];

		$winningsByPlayer = [];

		$this->contestWinners->each(function($contest, $index) use (&$winningsByPlayer) {
			$winningPlaces = ($contest['places'] === 0) ? 1 : $contest['places'];
			$payoutBreakdown = config('payouts.'.($contest['contestTypeId'] - 1).'.'.$contest['maxParticipants']);
			$totalPool = ($contest['maxParticipants'] * $contest['entryFee'])*0.85;

			$currentPlaceIndex = 0;

			for ($i = 0; $i < $winningPlaces; ++$i) {
				// if there weren't any winners stop
				if ( count($contest['winners'][$i]) === 0 ) break;
				// if the place only has one winner calculate the payout and move to next place
				if ( count($contest['winners'][$i]) === 1 ) {
					$winningsByPlayer[] = [
						'userId' => $contest['winners'][$i][0]['userId'],
						'payout' => $totalPool * $payoutBreakdown[$currentPlaceIndex],
						'place' => $currentPlaceIndex + 1,
						'contestId' => $contest['contestId'],
					];
					$currentPlaceIndex += 1;
					continue;
				}
				// place has multiple winners
				$winnersCount = count($contest['winners'][$i]);
				$placePrizePercent = 0.00;

				$loopStop = ( $winnersCount + $currentPlaceIndex > count($payoutBreakdown) ) ? count($payoutBreakdown) - 1 : ($winnersCount - 1) + $currentPlaceIndex;

				for ($j = $currentPlaceIndex; $j <= $loopStop; ++$j) {
					if ( $j >= count($payoutBreakdown) ) break;
					$placePrizePercent += $payoutBreakdown[$j];
				}

				foreach ( $contest['winners'][$i] as $key => $winner ) {
					$winningsByPlayer[] = [
						'userId' => $winner['userId'],
						'payout' => ($totalPool * $placePrizePercent)/$winnersCount,
						'place' => $currentPlaceIndex + 1,
						'contestId' => $contest['contestId'],
					];
				}

				$currentPlaceIndex += $winnersCount;

				if ( $currentPlaceIndex > $winningPlaces || $currentPlaceIndex >= count($payoutBreakdown) ) break;
			}
		});

		$this->winningsByPlayer = new Collection($winningsByPlayer);
	}

	protected function distributeWinnings()
	{
		DB::beginTransaction();

		try {
			$this->winningsByPlayer->each(function($player) {
				$this->userBalance->create([
					'user_id' => $player['userId'],
					'transaction_type_id' => 2,
					'amount' => $player['payout']*100,
					'is_credit' => 1,
				]);
			});
		}
		catch (\Exception $e) {
			DB::rollback();
			// log the error
		}

		DB::commit();
	}
}