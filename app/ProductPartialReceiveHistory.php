<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductPartialReceiveHistory extends Model
{
    use SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'datetime',
    ];

    /**
     * Get the transaction associated with this partial receive history.
     */
    public function transaction()
    {
        return $this->belongsTo(\App\Transaction::class);
    }

    /**
     * Get the product associated with this partial receive history.
     */
    public function product()
    {
        return $this->belongsTo(\App\Product::class);
    }

    /**
     * Get the purchase line associated with this partial receive history.
     */
    public function purchaseLine()
    {
        return $this->belongsTo(\App\PurchaseLine::class);
    }

    /**
     * Get the user who received the product.
     */
    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
}
