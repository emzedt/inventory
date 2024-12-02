<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Incoming extends Model
{
    protected $fillable = ['image', 'reference', 'date_received', 'quantity', 'slug', 'user_id', 'supplier_id', 'product_id'];

    public $casts = [
        'image' => 'string',
        'reference' => 'string',
        'date_received' => 'datetime',
        'quantity' => 'integer',
        'slug' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime', // Menggunakan format default datetime untuk soft deletes
        'user_id' => 'integer',
        'supplier_id' => 'integer',
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

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id'); //perlu lgsg di define primary key, dan foreign key di dalam sini. Ini kan primary key skrg, nah tabahin foreing keynya
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
