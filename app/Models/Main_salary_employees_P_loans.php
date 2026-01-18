<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Main_salary_P_loans_akast;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Main_salary_employees_P_loans extends Model
{
    use HasFactory;
    protected $table="main_salary_employees_p_loans";
    protected $guarded=[];
    
   public function added(){
    return $this->belongsTo('\App\Models\Admin','added_by');
 }
 public function updatedby(){
    return $this->belongsTo('\App\Models\Admin','updated_by');
 }
 public function dismissailby(){
    return $this->belongsTo('\App\Models\Admin','dismissail_by');
 }
 //علاقة 
  public function aksat(){
    return $this->hasMany(Main_salary_P_loans_akast::class,'main_salary_p_loans_id');
 }
}
