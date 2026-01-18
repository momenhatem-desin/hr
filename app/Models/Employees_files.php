<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employees_files extends Model
{
   protected $table='employees_files';
    protected $guarded=[];

    public function added(){
        return $this->belongsTo('\App\Models\Admin','added_by');
     }
     public function updatedby(){
        return $this->belongsTo('\App\Models\Admin','updated_by');
     }
     public function employee(){
        return $this->belongsTo('\App\Models\Employee','employees_id');
     }
}
