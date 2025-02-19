<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id', 'name', 'price', 'photo'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->id)) {
                $product->id = (string) Str::uuid();
            }
        });
    }

    public function getPhotoUrlAttribute()
    {
        return asset("storage/{$this->photo}");
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }
}
