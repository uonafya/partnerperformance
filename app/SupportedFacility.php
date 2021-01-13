<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupportedFacility extends BaseModel
{
	protected $dates = ['start_of_support', 'end_of_support'];

    public $timestamps = true;

	public function partner()
	{
		return $this->belongsTo('App\Partner');
	}

	public function facility()
	{
		return $this->belongsTo('App\Facility');
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
