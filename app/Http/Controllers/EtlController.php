<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\ViewFacilities2;


use App\Periods;
use App\ViewFacilities;

class EtlController extends Controller
{
    public function getRecord()
    {

        $blogModel = new ViewFacilities();
        $blogModel->setConnection('mysql_etl');

        $find = $blogModel->all();

        return $find;

    }

}
