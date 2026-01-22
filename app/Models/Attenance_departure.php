<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Attenance_departure extends Model
{
       use HasFactory;
     protected $table="attenance_departure";
     protected $guarded=[];
     
     public function added(){
        return $this->belongsTo('\App\Models\Admin','added_by');
     }
     public function updatedby(){
        return $this->belongsTo('\App\Models\Admin','updated_by');
     }
    public function updatedbyforaction(){
        return $this->belongsTo('\App\Models\Admin','is_updated_active_by');
     }
}
