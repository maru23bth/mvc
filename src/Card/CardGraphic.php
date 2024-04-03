<?php

namespace App\Card;

use App\Card\Card;

class CardGraphic extends Card
{
    public function __construct($suit = null, $number = null)
    {
        parent::__construct($suit, $number);
    }
}
