<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Attenance_departure_actions_excel extends Model
{
      use HasFactory;
     protected $table="attenance_departure_actions_excel";
     protected $guarded=[];
     public $timestamps=false;
     
     public function added(){
        return $this->belongsTo('\App\Models\Admin','added_by');
     }
   
}
