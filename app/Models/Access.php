<?php

namespace App\Models;

use App\Enums\AccessStatusEnum;
use App\Enums\UserStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Access extends Pivot
{
    use HasFactory;

    protected $table = "accesses";
    protected $fillable = ['role','room_id','user_id','status'];
    protected $casts = [
        'role' => UserStatusEnum::class,
        'status'=>AccessStatusEnum::class
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
