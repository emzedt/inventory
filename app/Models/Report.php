<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    protected $fillable = ['period', 'starting_stock', 'stock_in', 'stock_out', 'slug'];

    public $casts = [
        'period' => 'string',
        'starting_stock' => 'integer',
        'stock_in' => 'integer',
        'stock_out' => 'integer',
        'slug' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime' // Menggunakan format default datetime untuk soft deletes
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
}
