<?php

namespace App\Etl\Contracts;

interface EtlContract
{
    // public  $connection;
    public static function transform($load);

}
