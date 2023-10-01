<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\AccessStatusEnum;
use App\Traits\HasImages;
use Illuminate\Console\Application;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasImages;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $with = ['image'];


    public function accesses()
    {
        return $this->hasMany(Access::class)->with('room');
    }

    public function accepted_accesses()
    {
        // return $this->accesses()->where("status", AccessStatusEnum::Accepted)->get();
        return $this->hasMany(Access::class)->with('room')->where("status", AccessStatusEnum::Accepted);
    }
    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'accesses')->withPivot(["role", "status"])->using(Access::class);
    }
    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}
