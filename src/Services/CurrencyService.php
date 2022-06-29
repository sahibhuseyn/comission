<?php

namespace Commission\Calculator\Services;

use Commission\Calculator\Exceptions\InvalidCurrencyException;
use Commission\Calculator\Models\Amount;
use Commission\Calculator\Models\Currency;

class CurrencyService
{
    /**
     * @var int
     */

    const ARITHMETIC_SCALE = 10;

    /**
     * @var array
     */

    protected $currencies = [];

    /**
     * @param array $currencies
     * @return $this
     */
    public function collectCurrenciesFromArray(array $currencies): CurrencyService
    {
        foreach ($currencies as $currency) {
            $this->currencies[$currency['symbol']] = new Currency(...array_values($currency));
        }

        return $this;
    }


    /**
     * @param Amount $amount
     * @param $symbol
     * @return Amount
     * @throws InvalidCurrencyException
     */
    public function convert(Amount $amount, $symbol): Amount
    {
        $multiplier = bcdiv(
            $this->getCurrencyRateForSymbol($symbol),
            $this->getCurrencyRateForSymbol($amount->getSymbol()),
            self::ARITHMETIC_SCALE
        );

        return new Amount(
            bcmul($amount->getAmount(), $multiplier, self::ARITHMETIC_SCALE),
            $symbol
        );
    }

    /**
     * @param Amount $amount
     * @param string $decimalPoint
     * @param string $thousandsSeparator
     * @return string
     * @throws InvalidCurrencyException
     */
    public function roundAndFormat(Amount $amount, string $decimalPoint = '.', string $thousandsSeparator = ''): string
    {
        $precision = $this->getCurrencyPrecisionForSymbol($amount->getSymbol());
        $multiplier = bcpow(self::ARITHMETIC_SCALE, $precision);
        $newAmount = bcdiv(
            ceil(bcmul($amount->getAmount(), $multiplier, self::ARITHMETIC_SCALE)),
            $multiplier,
            self::ARITHMETIC_SCALE
        );

        return number_format($newAmount, $precision, $decimalPoint, $thousandsSeparator);
    }

    /**
     * @param Amount $amount
     * @param $percentage
     * @return Amount
     */
    public function getPercentageOfAmount(Amount $amount, $percentage): Amount
    {
        return new Amount(
            bcmul(
                bcdiv($amount->getAmount(), 100, self::ARITHMETIC_SCALE),
                $percentage,
                self::ARITHMETIC_SCALE
            ),
            $amount->getSymbol()
        );
    }

    /**
     * @param Amount $firstAmount
     * @param Amount $secondAmount
     * @return bool
     * @throws InvalidCurrencyException
     */
    public function isGreater(Amount $firstAmount, Amount $secondAmount): bool
    {
        return bccomp(
                $firstAmount->getAmount(),
                $this->convert($secondAmount, $firstAmount->getSymbol())->getAmount(),
                self::ARITHMETIC_SCALE
            ) === 1;
    }

    /**
     * @param Amount $firstAmount
     * @param Amount $secondAmount
     * @param $symbol
     * @return Amount
     * @throws InvalidCurrencyException
     */
    public function sumAmounts(Amount $firstAmount, Amount $secondAmount, $symbol): Amount
    {
        return new Amount(
            bcadd(
                $this->convert($firstAmount, $symbol)->getAmount(),
                $this->convert($secondAmount, $symbol)->getAmount(),
                self::ARITHMETIC_SCALE
            ),
            $symbol
        );
    }

    /**
     * @param Amount $firstAmount
     * @param Amount $secondAmount
     * @param $currencySymbol
     * @return Amount
     * @throws InvalidCurrencyException
     */
    public function subAmount(Amount $firstAmount, Amount $secondAmount, $currencySymbol): Amount
    {
        return new Amount(
            bcsub(
                $this->convert($firstAmount, $currencySymbol)->getAmount(),
                $this->convert($secondAmount, $currencySymbol)->getAmount(),
                self::ARITHMETIC_SCALE
            ),
            $currencySymbol
        );
    }

    /**
     * @throws InvalidCurrencyException
     */
    private function getCurrencyRateForSymbol($symbol)
    {
        return $this->getCurrencyOfSymbol($symbol)->getRate();
    }

    /**
     * @throws InvalidCurrencyException
     */
    private function getCurrencyPrecisionForSymbol($symbol)
    {
        return $this->getCurrencyOfSymbol($symbol)->getPrecision();
    }

    /**
     * @param $symbol
     * @return mixed
     * @throws InvalidCurrencyException
     */
    private function getCurrencyOfSymbol($symbol)
    {
        if (isset($this->currencies[$symbol])) {
            return $this->currencies[$symbol];
        }

        throw new InvalidCurrencyException;
    }
}
