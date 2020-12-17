<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model{

    protected $fillable = [

        'user_id' , 'service'
    ];

    protected $hidden = [

         'id',  'user_id', 'updated_at'
    ];

 //REFERENCE OF USER
 public function user(){
    return $this->hasOne(User::class,'user_id');
 }

}
