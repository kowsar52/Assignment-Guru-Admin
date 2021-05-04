<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDeliveryFile extends Model
{
    use HasFactory;
    protected $table = 'order_delivery_files';
    protected $fillable = [
        'id', 'order_id', 'file', 'original_name', 'format', 'size', 'details', 'created_at', 'updated_at'
    ];
}
