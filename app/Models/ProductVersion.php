<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'changes',
    ];

    protected $casts = [
        'changes' => 'json',
    ];

    /**
     * Get the product that the version belongs to.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user who made the change.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
