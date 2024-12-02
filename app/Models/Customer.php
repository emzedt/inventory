<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = ['name', 'address', 'contact', 'slug', 'user_id'];

    public $casts = [
        'name' => 'string',
        'address' => 'string',
        'contact' => 'string',
        'slug' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime', // Menggunakan format default datetime untuk soft deletes
        'user_id' => 'integer'
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

    public function outgoing(): HasMany
    {
        return $this->hasMany(Outgoing::class, 'customer_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
