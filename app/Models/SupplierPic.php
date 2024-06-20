<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierPic extends Model
{
    protected $table = 'supplier_pic';

    protected $fillable = [
        'supplier_id', 'pic_name', 'pic_telephone', 'pic_email', 'pic_assignment_date'
    ];
    use HasFactory;
}
