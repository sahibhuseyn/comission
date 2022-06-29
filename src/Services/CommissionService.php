<?php

namespace Commission\Calculator\Services;

use Commission\Calculator\Commissions\Type\CashInCommission;
use Commission\Calculator\Commissions\Type\CashOutBusinessCommission;
use Commission\Calculator\Commissions\Type\CashOutPrivateCommission;
use Commission\Calculator\Exceptions\InvalidCurrencyException;
use Commission\Calculator\Exceptions\InvalidOperationTypeException;
use Commission\Calculator\Exceptions\InvalidUserTypeException;
use Commission\Calculator\Models\Transaction;
use Commission\Calculator\TransactionCollect;

class CommissionService
{

    protected $transactionCollection;


    protected $currencyService;


    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /**
     * @throws InvalidOperationTypeException
     * @throws InvalidUserTypeException
     * @throws InvalidCurrencyException
     */
    public function calculateFeesFromCollection(TransactionCollect $collection): array
    {
        $fees = [];
        foreach ($collection->getTransactions() as $transaction) {
            $commission = $this->generateCommission(
                $this->currencyService,
                $transaction,
                $collection
            );
            $fees[]     = $this->currencyService->roundAndFormat($commission->calculate());
        }

        return $fees;
    }


    /**
     * @throws InvalidOperationTypeException
     * @throws InvalidUserTypeException
     */
    public function generateCommission(
        CurrencyService $currencyService,
        Transaction $transaction,
        TransactionCollect $transactionCollection
    ) {
        switch ($transaction->getOperationType()) {
            case 'cash_in':
                $commission = new CashInCommission($transaction, $currencyService);
                break;
            case 'cash_out':
                switch ($transaction->getUserType()) {
                    case 'private':
                        $commission = new CashOutPrivateCommission(
                            $transaction,
                            $currencyService,
                            $transactionCollection
                        );
                        break;
                    case 'business':
                        $commission = new CashOutBusinessCommission($transaction, $currencyService);
                        break;
                    default:
                        throw new InvalidUserTypeException;
                }
                break;
            default:
                throw new InvalidOperationTypeException;
        }

        return $commission;
    }
}

