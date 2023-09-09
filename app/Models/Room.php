<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;


    protected $fillable = [
        "name","description"
    ];

    // public function users()
    // {
    //     return $this->belongsToMany(User::class)->withPivot('role')->withTimestamps();
    // }
    public function accesses()
    {
        return $this->hasMany(Access::class);   
     }
    public function invitations()
    {
        return $this->hasMany(Invitation::class);   
     }

    // public function users()
    // {
    //     return $this->belongsToMany(User::class);
    // }
}
