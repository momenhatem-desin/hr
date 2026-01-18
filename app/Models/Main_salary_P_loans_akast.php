<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Main_salary_employees_P_loans;
class Main_salary_P_loans_akast extends Model
{
        use HasFactory;
    protected $table="main_salary_p_loans_akast";
    protected $guarded=[];
    
   public function added(){
    return $this->belongsTo('\App\Models\Admin','added_by');
 }
 public function updatedby(){
    return $this->belongsTo('\App\Models\Admin','updated_by');
 }
  public function Parentloan(){
    return $this->belongsTo(Main_salary_employees_P_loans::class,'main_salary_p_loans_id');
 }
}
