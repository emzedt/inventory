<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Outgoing extends Model
{
    protected $fillable = ['image', 'reference', 'quantity', 'date_sent', 'slug', 'user_id', 'customer_id', 'product_id'];

    public $casts = [
        'image' => 'string',
        'reference' => 'string',
        'quantity' => 'integer',
        'date_sent' => 'datetime',
        'slug' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime', // Menggunakan format default datetime untuk soft deletes
        'user_id' => 'integer',
        'customer_id' => 'integer',
        'product_id' => 'integer'
    ];

    protected static function boot() //uuid bisa di define sendiri oleh model
    {
        parent::boot();

        // Generate UUID saat membuat instance baru
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid()->toString();
            }
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
