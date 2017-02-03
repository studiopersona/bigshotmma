<h1 style="font-size: 18px;color:#222;">Withdrawl Request Received</h1>
<p style="font-size:14px;color:#222;margin:18px 0;">
	A withdrawl request has been received from {{ $user['firstname'] }} {{ $user['lastname'] }}.
</p>
<table style="width:60%;border-collapse:collapse">
	<tr>
		<td style="padding: 5px;">Name</td>
		<td style="padding: 5px;">{{ $user->firstname }} {{ $user->lastname }}</td>
	</tr>
	<tr>
		<td style="padding: 5px;">Amount</td>
		<td style="padding: 5px;">${{ number_format((float)$amount, 2) }}</td>
	</tr>
	<tr>
		<td style="padding: 5px;">Fund Method</td>
		<td style="padding: 5px;">{{ $fundsMethod }}</td>
	</tr>
	@if ($fundsMethod === 'Check')
		<tr>
			<td style="padding: 5px;">Address</td>
			<td style="padding: 5px;">
				{{ $user->address1 }}
				@if ( $user->address2 !== '' && ! is_null($user->address2) )
					<br>
					{{ $user->address2 }}
				@endif
				<br>
				{{ $user->city }},{{ $user->state }} {{ $user->zipcode }}
			</td>
		</tr>
	@else
		<tr>
			<td style="padding: 5px;">PayPal Email</td>
			<td style="padding: 5px;">{{ $user->paypalEmail->email }}</td>
		</tr>
	@endif
</table>