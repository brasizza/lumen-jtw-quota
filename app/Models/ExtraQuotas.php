<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ExtraQuotas extends Model
{
    protected $fillable = [

        'user_id',  'quantity'
    ];

     //REFERENCE OF USER
 public function user(){
    return $this->hasOne(User::class,'user_id');
 }


}
