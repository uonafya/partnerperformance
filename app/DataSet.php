<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataSet extends Model
{

    protected $connection = 'mysql_wr';
    
	public function element()
	{
		return $this->hasMany('App\DataSetElement');
	}
}
