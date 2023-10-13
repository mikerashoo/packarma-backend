<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorNotificationHistory extends Model
{
    use HasFactory;

    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 14-Oct-2022
     * Use : Fillable attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'imei_no',
        'language_id',
        'notification_name',
        'page_name',
        'type_id',
        'title',
        'body',
        'notification_image',
        'notification_thumb_image',
        'notification_date',
        'trigger',
        'is_read',
        'is_discard',
        'status',
        'created_by',
        'deleted_at',
        'created_at',
        'updated_at'
    ];
}
