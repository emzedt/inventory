<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Str;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'slug'
    ];

    public $casts = [
        'name' => 'string',
        'email' => 'string',
        'password' => 'string',
        'type' => 'string',
        'slug' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime' // Menggunakan format default datetime untuk soft deletes
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function boot() //uuid bisa di define sendiri oleh model
    {
        parent::boot();

        // Generate UUID saat membuat instance baru
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid()->toString();
            }
        });

        static::creating(function ($user) {
            // Membuat slug dari nama
            $slug = Str::slug($user->name);

            // Pastikan slug unik
            $originalSlug = $slug;
            $counter = 1;

            // Periksa apakah slug sudah ada
            while (User::where('slug', $slug)->exists()) {
                // Tambahkan angka untuk memastikan slug unik
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            // Tetapkan slug yang unik
            $user->slug = $slug;
        });
    }

    public function product(): HasMany
    {
        return $this->hasMany(Product::class, 'user_id', 'id');
    }

    public function report(): HasMany
    {
        return $this->hasMany(Report::class, 'user_id', 'id');
    }

    public function customer(): HasMany
    {
        return $this->hasMany(Customer::class, 'user_id', 'id');
    }

    public function incoming(): HasMany
    {
        return $this->hasMany(Incoming::class, 'user_id', 'id');
    }

    public function outgoing(): HasMany
    {
        return $this->hasMany(Outgoing::class, 'user_id', 'id');
    }

    public function stock(): HasMany
    {
        return $this->hasMany(Stock::class, 'user_id', 'id');
    }

    public function supplier(): HasMany
    {
        return $this->hasMany(Supplier::class, 'user_id', 'id');
    }

    public function threshold(): HasMany
    {
        return $this->hasMany(Threshold::class, 'user_id', 'id');
    }
}
