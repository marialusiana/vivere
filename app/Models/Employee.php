<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    protected $table = 'employee';
    protected $primaryKey = 'id';
    
    protected $casts = [];
    
    protected $fillable = ['id', 'nama', 'alamat',
    'no_hp', 'gender', 'hobi'];
}
