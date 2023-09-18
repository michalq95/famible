<?php

use App\Enums\PostStatusEnum;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("title", 255);
            $table->text("description")->nullable();
            $table->timestamp('expire_date')->nullable();
            $table->integer("status")->default(PostStatusEnum::Pending);
            $table->foreignIdFor(Image::class, "image_id")->onDelete("cascade");
            $table->foreignIdFor(Room::class, "room_id");
            $table->foreignIdFor(User::class, "added_by");
            $table->foreignIdFor(User::class, "user_handling")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
