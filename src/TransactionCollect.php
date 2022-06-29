<?php

namespace Commission\Calculator;

use Commission\Calculator\Exceptions\FileNotFoundException;
use Commission\Calculator\Models\Amount;
use Commission\Calculator\Models\Transaction;
use League\Csv\Reader;

class TransactionCollect
{
    /**
     * @var array
     */
    protected array $transactions = [];

    /**
     * @param $path
     * @param bool $append
     * @return void
     * @throws FileNotFoundException
     * @throws \Exception
     */
    public function parseFromCSV($path, bool $append = false)
    {
        if (!file_exists($path)) {
            throw new FileNotFoundException;
        }
        $this->transactions = $append ? $this->transactions : [];
        foreach (Reader::createFromPath($path) as $csvLine) {
            $this->add(new Transaction(
                $this->generateTransactionID(),
                new \DateTime($csvLine[0]),
                $csvLine[1],
                $csvLine[2],
                $csvLine[3],
                new Amount($csvLine[4], $csvLine[5])
            ));
        }
    }

    /**
     * @return int
     */
    private function generateTransactionID(): int
    {
        $array = $this->getTransactions();
        return $this->isEmpty() ? 1 : end($array)->getTransactionID() + 1;
    }

    /**
     * @return array
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }

    /**
     * @param Transaction $transaction
     * @return $this
     */
    public function add(Transaction $transaction): TransactionCollect
    {
        $this->transactions[] = $transaction;

        return $this;
    }

    /**
     * @return bool
     */
    private function isEmpty(): bool
    {
        return empty($this->getTransactions());
    }
}

