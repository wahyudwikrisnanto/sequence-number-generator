<?php

namespace WahyuDwiKrisnanto\InvoiceNumberGenerator\Facades;

use Illuminate\Support\Facades\Facade;

class SequenceGenerator extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sequenceGenerator';
    }
}
