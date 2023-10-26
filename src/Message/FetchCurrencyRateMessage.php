<?php

namespace App\Message;

class FetchCurrencyRateMessage
{
    public function __construct(private readonly string $date) { }

    public function getDate(): string
    {
        return $this->date;
    }
}