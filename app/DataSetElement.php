<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataSetElement extends Model
{

    protected $connection = 'mysql_wr';
    
	public function dataset()
	{
		return $this->belongsTo('App\DataSet');
	}
}
