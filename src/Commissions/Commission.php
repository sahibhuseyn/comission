<?php

namespace Commission\Calculator\Commissions;


use Commission\Calculator\Models\Amount;
use Commission\Calculator\Models\Transaction;
use Commission\Calculator\Services\CurrencyService;

abstract class Commission
{

    /**
     * @var Transaction
     */
    protected $transaction;


    /**
     * @var CurrencyService
     */
    protected $currencyService;


    /**
     * @param Transaction $transaction
     * @param CurrencyService $currencyService
     */
    public function __construct(Transaction $transaction, CurrencyService $currencyService)
    {
        $this->transaction     = $transaction;
        $this->currencyService = $currencyService;
    }


    /**
     * @param $rate
     * @param $feeAbleAmount
     * @return Amount
     */
    protected function getFee($rate, $feeAbleAmount = null): Amount
    {
        $amount = $feeAbleAmount ?? $this->transaction->getAmount();

        return $this->currencyService->getPercentageOfAmount($amount, $rate);
    }

}

