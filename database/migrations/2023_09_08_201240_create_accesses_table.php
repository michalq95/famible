<?php

use App\Enums\AccessStatusEnum;
use App\Enums\UserStatusEnum;
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
        Schema::create('accesses', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Room::class, "room_id")->onDelete("cascade");
            $table->foreignIdFor(User::class, "user_id")->onDelete("cascade");
            $table->integer("role")->default(2);
            $table->integer("status")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accesses');
    }
};
