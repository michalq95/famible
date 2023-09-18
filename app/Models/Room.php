<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Room extends Model
{
    use HasFactory;


    protected $fillable = [
        "name", "description", "expire_date"
    ];

    // public function users()
    // {
    //     return $this->belongsToMany(User::class)->withPivot('role')->withTimestamps();
    // }
    public function accesses()
    {
        return $this->hasMany(Access::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'accesses')->withPivot(["role", "status"])->using(Access::class);
    }
    public function posts()
    {
        return $this->hasMany(Post::class); //->with('user');
    }
    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}
