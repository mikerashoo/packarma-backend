<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BannerReport extends Model
{
    use HasFactory;

    /**
     * Get the user that owns the BannerClick
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the banner that owns the BannerClick
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function banner(): BelongsTo
    {
        return $this->belongsTo(Banner::class, 'banner_id');
    }


    /**
     * Get the solutionBanner that owns the BannerClick
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function solutionBanner(): BelongsTo
    {
        return $this->belongsTo(SolutionBanner::class, 'solution_banner_id');
    }


    public function scopeOfUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeOfBanner($query, $bannerId)
    {
        return $query->where('banner_id', $bannerId);
    }


    public function scopeOfSolutionBanner($query, $bannerId)
    {
        return $query->where('solution_banner_id', $bannerId);
    }
}
