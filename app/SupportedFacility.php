<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupportedFacility extends BaseModel
{

	public function partner()
	{
		return $this->hasMany('App\Partner');
	}

	public function facility()
	{
		return $this->hasMany('App\Facility');
	}

	public function fill_original()
	{
		$facilities = Facility::all();

		foreach ($facilities as $key => $facility) {
			$sf = new SupportedFacility;
			$sf->fill([
				'partner_id' => $facility->partner,
				'facility_id' => $facility->id,
			]);
			$sf->save();
		}
	}
}
