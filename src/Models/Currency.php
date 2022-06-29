<?php

namespace Commission\Calculator\Models;

class Currency
{
    protected $symbol;
    protected $rate;
    protected $precision;

    public function __construct($symbol, $rate, $precision)
    {
        $this->symbol = $symbol;
        $this->rate = $rate;
        $this->precision = $precision;
    }

    public function getSymbol()
    {
        return $this->symbol;
    }

    public function getRate()
    {
        return $this->rate;
    }

    public function getPrecision()
    {
        return $this->precision;
    }
}
