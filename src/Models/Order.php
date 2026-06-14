<?php

namespace Praktika\Orders\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Praktika\Orders\Models\OrderStatus;
use Praktika\Orders\Models\OrderItem;

class Order extends Model
{
    // tikslus lentles pavadinimas skirtas uzsakymams
    protected $table = 'orders';

    // sukuriame masyva su lentles stulpelio pavadinimais
    protected $fillable = [
        'user_id',
        'customer_first_name',
        'customer_last_name',
        'customer_email',
        'customer_phone',
        'order_status_id',
        'total_amount',
        'notes',
    ];

// statuso reiskmes

    public function status(): BelongsTo
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id');
    }

// pilnas vardas
    public function getCustomerFullNameAttribute()
    {
        return "{$this->customer_first_name} {$this->customer_last_name}";
    }

// uzsakytos prekes pagal id, gali buti daug

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    // susiejimas su varotoju

    public function user(): BelongsTo
    {
        // paimame modeli iš kponfiguracijos, o jei ten nera naudojame standartini App\Models\User
        $userModel = config('orders.user_model', \App\Models\User::class);

        // jei modelis neegzistuoja priskirmsime default
        if (!class_exists($userModel)) {
            return $this->belongsTo(self::class, 'user_id')->withDefault();
        }

        return $this->belongsTo($userModel, 'user_id');
    }

    // visos prekiu  sumos skuskaiciavimas
    
    public function recalculateTotal(): void
    {
        $total = $this->items()->sum(\DB::raw('quantity * price'));
        
        $this->update([
            'total_amount' => $total
        ]);
    }
}