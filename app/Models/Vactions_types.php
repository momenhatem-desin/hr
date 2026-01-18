<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Vactions_types extends Model
{
    use HasFactory;
    protected $table = "vactions_types";
    protected $guarded = [];

    public function updatedby()
    {
        return $this->belongsTo('\App\Models\Admin', 'updated_by');
    }
}
