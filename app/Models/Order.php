<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'status', 'total', 'delivery_address', 'notes', 'updated_by_staff_id',
    ];

    protected $casts = [
        'total' => 'decimal:2',
    ];

    // An order belongs to one user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // An order has many line items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getCustomerOrderNumberAttribute()
    {
        return self::where('user_id', $this->user_id)
            ->where('created_at', '<=', $this->created_at)
            ->count();
    }

    // App\Models\Order.php

    public function updatedByStaff()
    {
        return $this->belongsTo(User::class, 'updated_by_staff_id'); 
    }
}