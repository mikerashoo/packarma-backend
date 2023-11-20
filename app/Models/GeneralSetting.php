<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    use HasFactory;

      /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'value',

    ];


    public function scopeOfCreditPrice($query)
    {
        $creditPrice = config('constants.CREDIT_PRICE');

        return $query->where("type", $creditPrice);
    }


    public function scopeOfCreditPercent($query)
    {
        $crditDiscount = config('constants.CREDIT_DISCOUNT_PRICE');

        return $query->where("type", $crditDiscount);
    }
}
