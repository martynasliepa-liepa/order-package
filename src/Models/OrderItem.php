<?php

namespace Praktika\Orders\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    // tikslus lenteles uzsakytu prekiu pavadinimas
    protected $table = 'order_items';

    // lenteles struktura irsoma i masyva
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'quantity',
        'price',
    ];

    // rysys su order lentele pagal id

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // Universalaus rysio metodas su prekemis
    // Jei pagrindine sistema turi savo prekiu kataloga, susiesime irasa su juo.
    // Jei neturi rysys tiesiog grazins tuscia rezultata, informacija bus imama is `product_name`.
    
public function originalItem()
{
    $itemModel = config('orders.item_model');
    
    // tikriname ar toks modelis ar klase yra sitemoje
    if (!$itemModel || !class_exists($itemModel)) {
        // graziname tuscia rysi
        return $this->belongsTo(self::class, 'product_id')->whereRaw('1 = 0');
    }
    
    return $this->belongsTo($itemModel, 'product_id');
}

    // metodas naudotas gauti pilna suma
    
    public function getSubtotalAttribute(): float
    {
        return $this->price * $this->quantity;
    }
}