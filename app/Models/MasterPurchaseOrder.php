<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterPurchaseOrder extends Model
{
    use HasFactory;

    protected $table = 'master_purchase_order';
    
    protected $fillable = [
        'supplier_id', 'po_number', 'total'
    ];    

}
