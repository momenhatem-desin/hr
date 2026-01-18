<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employees_fixed_suits extends Model
{
      use HasFactory;
    protected $table='employees_fixed_suits';
    protected $guarded=[];

    public function added(){
        return $this->belongsTo('\App\Models\Admin','added_by');
     }
     public function updatedby(){
        return $this->belongsTo('\App\Models\Admin','updated_by');
     }
     public function allowances(){
        return $this->belongsTo('\App\Models\Allowances','allowances_id');
     }
}
