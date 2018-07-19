<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataSetElement extends Model
{

	public function dataset()
	{
		return $this->belongsTo('App\DataSet');
	}
}
