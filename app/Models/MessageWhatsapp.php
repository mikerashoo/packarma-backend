<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MessageWhatsapp extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    /**
        * Developed By : Pradyumn Dwivedi
        * Created On : 16-april-2022
        * uses : to get data of language in whatsapp message table
    */
    public function language()
    {
        return $this->belongsTo('App\Models\Language');
    }
}
