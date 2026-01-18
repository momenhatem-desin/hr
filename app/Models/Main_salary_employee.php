<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Main_salary_employee extends Model
{
        use HasFactory;
    protected $table="main_salary_employee";
    protected $guarded=[];
    
   public function added(){
    return $this->belongsTo('\App\Models\Admin','added_by');
 }
 public function updatedby(){
    return $this->belongsTo('\App\Models\Admin','updated_by');
 }
  public function arcivedBy(){
    return $this->belongsTo('\App\Models\Admin','archived_by');
 }

}
