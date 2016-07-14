<?php
namespace Bsmma\Contracts;

interface MerchantContract {

	public function charge();

	public function setChargeData(array $input);

}