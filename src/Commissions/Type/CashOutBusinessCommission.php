<?php

namespace Commission\Calculator\Commissions\Type;

use Commission\Calculator\Commissions\Commission;
use Commission\Calculator\Commissions\CommissionTypeInterface;
use Commission\Calculator\Models\Amount;

class CashOutBusinessCommission extends Commission implements CommissionTypeInterface
{
    /**
     * @var float
     */
    const COMMISSION_PERCENTAGE = 0.5;

    /**
     * @var array
     */
    const MIN_COMMISSION = [
        'currency' => 'EUR',
        'fee'      => 0.5
    ];

    /**
     * @return Amount
     */
    public function calculate()
    {
        $commission    = $this->getFee(self::COMMISSION_PERCENTAGE);
        $minCommission = new Amount(self::MIN_COMMISSION['fee'], self::MIN_COMMISSION['currency']);

        if ($this->currencyService->isGreater($minCommission, $commission)) {
            return $minCommission;
        }

        return $commission;
    }
}

