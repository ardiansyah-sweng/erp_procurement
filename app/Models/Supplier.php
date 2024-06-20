<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    
    protected $table = 'supplier';

    protected $fillable = [
        'supplier_id', 'name', 'address', 'telephone'
    ];

    /**
     * Mendefinisikan relasi dengan tabel supplier_pic
     */
    public function supplierPics()
    {
        return $this->hasMany(SupplierPic::class, 'supplier_id', 'supplier_id');
    }
    /**
     * Method untuk mendapatkan jumlah total pic untuk setiap supplier
     */
    public function getTotalPicAttribute()
    {
        return $this->supplierPics()->count();
    }
}
