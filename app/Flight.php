<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    protected $table = 'flights';
    protected $fillable = [
      'uniqueID',
      'airline',
      'flight_id',
      'dom_int',
      'schedule_time',
      'arr_dep',
      'airport_code', // this has name airport // unique identifier
      'check_in',
      'gate',
      'status',
      'delayed'
    ];

    public function airport() {
      return $this->hasOne('App\Airport');
    }



    // Airline
    // public function airline() {
    //   return $this->belongsToOne('App\Airline');
    // }


}
