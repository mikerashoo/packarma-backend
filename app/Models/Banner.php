<?php
/*
    *	Developed by : Sagar Thokal - Mypcot Infotech
    *	Project Name : RRPL
    *	File Name : Banner.php
    *	File Path : app\Models\Banner.php
    *	Created On : 28-01-2022
    *	http ://www.mypcot.com
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
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
        'description',
        'start_date_time',
        'end_date_time',
        'app_page_id',

    ];
    protected $appends = ['click_count', 'view_count', 'is_solution', 'in_app_link', 'banner_image_link'];


    public function getStartDateTimeAttribute()
    {
        return $this->start_date_time ?? $this->created_at;
    }


    public function getClickCountAttribute()
    {
        return BannerClick::ofBanner($this->id)->count();
    }


    public function getViewCountAttribute()
    {
        return BannerView::ofBanner($this->id)->count();
    }




    public function getInAppLinkAttribute()
    {
        $appPage =  AppPage::find($this->app_page_id);
        return $appPage ? $appPage->pageName : null;
    }


    public function getIsSolutionAttribute()
    {
        return false;
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
