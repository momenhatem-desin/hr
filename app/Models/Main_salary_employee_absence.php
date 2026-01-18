<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Main_salary_employee_absence extends Model
{
        use HasFactory;
    protected $table="main_salary_employee_absence";
    protected $guarded=[];
    
   public function added(){
    return $this->belongsTo('\App\Models\Admin','added_by');
 }
 public function updatedby(){
    return $this->belongsTo('\App\Models\Admin','updated_by');
 }
}
