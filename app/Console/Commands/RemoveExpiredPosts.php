<?php

namespace App\Console\Commands;

use App\Enums\AccessStatusEnum;
use App\Models\Access;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RemoveExpiredPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:remove-expired';
    
    protected $description = 'Remove posts past their expiry date';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $now = Carbon::now();
        Post::where('expire_date', '<', $now)->delete();
        Access::where('status',AccessStatusEnum::Blocked)->delete();
        $this->info('Expired posts have been removed.');
    }
}
