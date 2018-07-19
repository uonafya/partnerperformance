<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataSet extends Model
{

	public function element()
	{
		return $this->hasMany('App\DataSetElement');
	}
}
