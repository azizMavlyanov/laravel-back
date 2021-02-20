<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = ['image_path'];

     /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        // 'created_at',
        // 'updated_at',
    ];

    public function users()
    {
        return $this->hasMany('App\Models\User');
    }

    public function articles()
    {
        return $this->hasMany('App\Models\Article');
    }
}
