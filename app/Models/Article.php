<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = ["body", "heading", "subheading", "slug", "meta", "version", "user_id", "photo_id"];

    public function photo()
    {
        return $this->belongsTo('App\Models\Photo');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
