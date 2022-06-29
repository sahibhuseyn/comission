<?php

namespace Commission\Calculator\Commissions\Type;

use Commission\Calculator\Exceptions\InvalidCurrencyException;
use Commission\Calculator\Models\Amount;
use Commission\Calculator\Models\Transaction;
use Commission\Calculator\Services\CurrencyService;

class CashInCommission
{
    /**
     * @var float
     */
    const COMMISSION_PERCENTAGE = 0.03;

    /**
     * @var array
     */
    const MAX_COMMISSION = [
        'currency' => 'EUR',
        'fee'      => 5
    ];

    /**
     * @var CurrencyService
     */
    private CurrencyService $currencyService;

    /**
     * @var Transaction
     */
    private Transaction $transaction;


    public function __construct(Transaction $transaction,CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
        $this->transaction = $transaction;
    }


    /**
     * @return Amount
     * @throws InvalidCurrencyException
     */
    public function calculate(): Amount
    {
        $commission    = $this->getFee(self::COMMISSION_PERCENTAGE);
        $maxCommission = new Amount(self::MAX_COMMISSION['fee'], self::MAX_COMMISSION['currency']);

        if ($this->currencyService->isGreater($commission, $maxCommission)) {
            return $maxCommission;
        }

        return $commission;
    }

    protected function getFee($rate, $feeAbleAmount = null): Amount
    {
        $amount = $feeAbleAmount ?? $this->transaction->getAmount();

        return $this->currencyService->getPercentageOfAmount($amount, $rate);
    }
}

