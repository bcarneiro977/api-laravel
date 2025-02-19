<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Address extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'addresses';
    protected $keyType = 'string';
    public $incrementing = false; 

    protected $fillable = [
        'id',
        'customer_id',
        'street',
        'complement',
        'neighborhood',
        'zip_code',
        'is_default'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = Str::uuid();
            }
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
