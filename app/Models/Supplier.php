<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Supplier extends Model
{
    protected $fillable = ['name', 'address', 'contact', 'published', 'slug', 'user_id'];

    public $casts = [
        'name' => 'string',
        'address' => 'string',
        'contact' => 'string',
        'published' => 'boolean',
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

    public function incoming(): HasMany
    {
        return $this->hasMany(Incoming::class, 'supplier_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
