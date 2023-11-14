<?php

namespace App\Models;

use Facade\IgnitionContracts\Solution;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SolutionBanner extends Model
{

    use HasFactory;
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'banner_image',
        'link',
        'app_page_id',
        'description',
        'start_date_time',
        'end_date_time'
    ];

    protected $appends = ['click_count', 'view_count',  'is_solution', 'in_app_link', 'banner_image_link'];

    public function getStartDateTimeAttribute()
    {
        return $this->start_date_time ?? $this->created_at;
    }


    public function getClickCountAttribute()
    {
        return BannerClick::ofSolutionBanner($this->id)->count();
    }


    public function getViewCountAttribute()
    {
        return BannerView::ofSolutionBanner($this->id)->count();
    }


    public function getInAppLinkAttribute()
    {
        $appPage =  AppPage::find($this->app_page_id);
        return $appPage ? $appPage->pageName : null;
    }


    public function getIsSolutionAttribute()
    {
        return true;
    }


    public function getBannerImageLinkAttribute()
    {

        $imageUrl = getFile($this->banner_image, 'banner', true);

        return str_replace("\\", "", $imageUrl);
    }

    /**
     * Get the AppPage that owns the SolutionBanner
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(AppPage::class, 'app_page_id');
    }

    /**
     * The products that belong to the SolutionBanner
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'banner_products', 'solution_banner_id', 'product_id');
    }

    /**
     * Get all of the clicks for the SolutionBanner
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clicks(): HasMany
    {
        return $this->hasMany(BannerClick::class, 'solution_banner_id');
    }

    /**
     * Get all of the views for the SolutionBanner
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function views(): HasMany
    {
        return $this->hasMany(BannerView::class, 'solution_banner_id');
    }

    /**
     * Developed By : Maaz Ansari
     * Created On : 01-Aug-2022
     * uses : to change name to Camel Casing
     */

    // mutators start
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = ucwords(strtolower($value));
    }

    public function setMetaTitleAttribute($value)
    {
        $this->attributes['meta_title'] = ucwords(strtolower($value));
    }

    public function setMetaDescriptionAttribute($value)
    {
        $this->attributes['meta_description'] = ucwords(strtolower($value));
    }

    // mutators end
}
