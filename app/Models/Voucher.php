<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    protected $table = 'vocher';
    protected $primaryKey = 'id';
    
    protected $casts = [];
    
    protected $fillable = ['id', 'Voucher_Number', 'Voucher_Material',
    'Basic_Material', 'Valid_From', 'Reg_Date', 'Status', 'Voucher_Value', 
    'Currency', 'Voucher_Type'];
}
