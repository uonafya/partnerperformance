<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{

    // use \Venturecraft\Revisionable\RevisionableTrait;
    // protected $revisionEnabled = true;
    // protected $revisionCleanup = true; 
    // protected $historyLimit = 500; 

    protected $guarded = [];
    protected $connection = 'mysql_wr';
    public $timestamps = false;

    public function my_date_format($value)
    {
        if($this->$value) return date('d-M-Y', strtotime($this->$value));

        return '';
    }

    public function unspaced($value)
    {
        if($this->$value) return str_replace(' ', '_', strtolower($this->$value));
        return '';
    }
}
