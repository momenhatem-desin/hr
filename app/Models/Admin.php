<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory;
    use Notifiable;
     
    protected $table="admins";
    protected $guarded=[];

       public function added(){
        return $this->belongsTo('\App\Models\Admin','added_by');
     }
     public function updatedby(){
        return $this->belongsTo('\App\Models\Admin','updated_by');
     }

      // ⭐ الفروع المرتبط بيها المستخدم
    public function branches()
    {
        return $this->belongsToMany(
            Branche::class,
            'admin_branches',
            'admin_id',
            'branch_id'
        );
    }

}
