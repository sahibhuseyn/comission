<?php

namespace Commission\Calculator\Models;

class Amount
{
    protected $amount;

    protected $symbol;

    public function __construct($amount, $symbol)
    {
        $this->amount = $amount;
        $this->symbol = $symbol;
    }

    public function getSymbol()
    {
        return $this->symbol;
    }

    public function getAmount()
    {
        return $this->amount;
    }
}
