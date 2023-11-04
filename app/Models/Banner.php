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
        'description'
    ];



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
