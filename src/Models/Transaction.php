<?php

namespace Commission\Calculator\Models;

class Transaction
{

    /**
     * @var
     */
    protected $transactionID;

    /**
     * @var \DateTime
     */
    protected $transactionDate;

    /**
     * @var
     */
    protected $identificationNumber;

    /**
     * @var
     */
    protected $userType;

    /**
     * @var
     */
    protected $operationType;

    /**
     * @var Amount
     */
    protected $amount;

    /**
     * @param $transactionID
     * @param \DateTime $transactionDate
     * @param $identificationNumber
     * @param $userType
     * @param $operationType
     * @param Amount $amount
     */
    public function __construct(
        $transactionID,
        \DateTime $transactionDate,
        $identificationNumber,
        $userType,
        $operationType,
        Amount $amount
    )
    {
        $this->transactionID = $transactionID;
        $this->transactionDate = $transactionDate;
        $this->identificationNumber = $identificationNumber;
        $this->userType = $userType;
        $this->operationType = $operationType;
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getTransactionID()
    {
        return $this->transactionID;
    }

    /**
     * @return mixed
     */
    public function getIdentificationNumber()
    {
        return $this->identificationNumber;
    }

    /**
     * @return mixed
     */
    public function getOperationType()
    {
        return $this->operationType;
    }

    /**
     * @return mixed
     */
    public function getUserType()
    {
        return $this->userType;
    }

    /**
     * @return \DateTime
     */
    public function getTransactionDate(): \DateTime
    {
        return $this->transactionDate;
    }

    /**
     * @return Amount
     */
    public function getAmount(): Amount
    {
        return $this->amount;
    }

    /**
     * @return mixed
     */
    public function getAmountSymbol()
    {
        return $this->amount->getSymbol();
    }
}
