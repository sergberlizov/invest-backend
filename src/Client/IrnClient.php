<?php

declare(strict_types=1);

namespace App\Client;

use GuzzleHttp\Client;

class IrnClient extends Client
{
    public const URL = 'https://www.irn.ru/iprice/?todo=calc&method=simple&knownprice=1000&dateknown=01.01.2000&calcdate=%s';
}
