<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function added(){
        return $this->belongsTo('\App\Models\Admin','added_by');
     }
     public function updatedby(){
        return $this->belongsTo('\App\Models\Admin','updated_by');
     }

        public function Branch(){
        return $this->belongsTo('\App\Models\Branche','branch_id');
     }
       public function Departement(){
        return $this->belongsTo('\App\Models\Departement','emp_Departments_code');
     }

          public function jobs_categorie(){
        return $this->belongsTo('\App\Models\jobs_categorie','emp_jobs_id');
     }

}
