<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    protected $table = 'airports';

    protected $fillable = [
      'code',
      'name'
    ];


    public function flights() {
      return $this->hasMany('App\Flight', 'airport_code');
    }


}
