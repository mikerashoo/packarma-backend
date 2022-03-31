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

class Banner extends Model
{
    use HasFactory;
    protected $table = 'banners';
    protected $fillable = [
        'title',
        'banner_image',
        'type'
        
    ];
 
}
