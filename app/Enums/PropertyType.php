<?php

namespace App\Enums;

enum PropertyType: int
{
    case APARTMENT = 0;
    case HOUSE = 1;
    case CHALET = 2;
    case LAND = 3;
    case COMMERCIAL = 4;
    case GARAGE = 5;
    case STORAGE = 6;
}
