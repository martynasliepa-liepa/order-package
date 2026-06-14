<?php

namespace Praktika\Orders\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderStatus extends Model
{
    // nurodome tikslu lenteles pavadinima
    protected $table = 'order_statuses';

    // status lenteles laukai
    protected $fillable = [
        'name',
        'slug',
    ];

    // risys su orders
    
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'order_status_id');
    }
}