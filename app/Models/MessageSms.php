<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageSms extends Model
{
    use HasFactory;

    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 16-april-2022
        * uses : to get data of language in sms message table
    */
    public function language()
    {
        return $this->belongsTo('App\Models\Language');
    }
}
