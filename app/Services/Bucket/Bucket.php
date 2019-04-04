<?php

namespace App\Services\Bucket;

use Illuminate\Support\Facades\Facade;

/**
 * Class Bucket
 *
 * @author Davyd Holovii <mirage.present@gmail.com>
 * @since  03.04.2019
 */
class Bucket extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "bucket";
    }
}
