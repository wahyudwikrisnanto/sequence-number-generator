<?php

namespace WahyuDwiKrisnanto\SequenceNumberGenerator\Facades;

use Illuminate\Support\Facades\Facade;

class SequenceGenerator extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'SequenceGenerator';
    }
}
