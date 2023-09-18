<?php

namespace App\Models;

use App\Enums\PostStatusEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Post extends Model
{
    use HasFactory;



    protected $fillable = ['title', 'description', 'status', 'room_id', 'added_by'];
    protected $dates = ['expire_date', 'created_at', 'updated_at'];
    protected $casts = [
        'status' => PostStatusEnum::class
    ];
    protected $with = ['author', 'handler'];
    protected $attributes = [
        'status' => PostStatusEnum::Pending,
    ];

    protected static function booted()
    {
        static::updated(function (Post $post) {
            if ($post->status == PostStatusEnum::Done)
                $post->expire_date = Carbon::now()->addMonth();
        });

        parent::boot();
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
    public function author()
    {
        return $this->belongsTo(User::class, "added_by");
    }
    public function handler()
    {
        return $this->belongsTo(User::class, "user_handling");
    }
    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}
