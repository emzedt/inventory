<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = ['image', 'category', 'unit', 'name', 'selling_price', 'purchase_price', 'published', 'slug', 'user_id', 'threshold_id'];

    public $casts = [
        'image' => 'string',
        'category' => 'string',
        'unit' => 'string',
        'name' => 'string',
        'selling_price' => 'double',
        'purchase_price' => 'double',
        'published' => 'boolean',
        'slug' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime', // Menggunakan format default datetime untuk soft deletee
        'user_id' => 'integer',
        'threshold_id' => 'integer'
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function threshold(): HasOne
    {
        return $this->hasOne(Threshold::class, 'product_id', 'id');
    }

    public function incoming(): HasMany
    {
        return $this->hasMany(Incoming::class, 'product_id', 'id');
    }

    public function outgoing(): HasMany
    {
        return $this->hasMany(Outgoing::class, 'outgoing_id', 'id');
    }

    public function stock(): HasMany
    {
        return $this->hasMany(Stock::class, 'product_id', 'id');
    }
}
