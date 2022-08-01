<?php

namespace App\Etl\Contracts;

interface EtlContract 
{

    public static function transform($load);

}