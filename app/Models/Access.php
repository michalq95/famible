<?php

namespace App\Models;

use App\Enums\InvitationStatusEnum;
use App\Enums\UserRoomEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Access extends Model
{
    use HasFactory;

    protected $fillable = ['role','room_id','user_id','status'];
    protected $casts = [
        'role' => UserRoomEnum::class,
        'status'=>InvitationStatusEnum::class
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
