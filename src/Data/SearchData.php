<?php

namespace App\Data;

use App\Entity\City;
use App\Entity\Food;

class SearchData
{
    /**
     * @var City|null;
     */
    public $city;

    /**
     * @var Food|null;
     */
    public $food;

    public function __construct()
    {
        $this->city = null;
    }
}
