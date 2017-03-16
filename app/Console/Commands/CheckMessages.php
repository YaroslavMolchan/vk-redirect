<?php

namespace App\Console\Commands;

use App\Helpers;
use Illuminate\Console\Command;

class CheckMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:messages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for messages';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $receiver = new Helpers\VkHelper(env('VK_APP_ID'), env('VK_API_SECRET'), env('VK_ACCESS_TOKEN'));
        $sender = new Helpers\TelegramHelper(env('TELEGRAM_BOT_API'), env('TELEGRAM_CHAT_ID'));
        $redirect = new Helpers\MessagesRedirect($receiver, $sender);

        echo $redirect->process();
    }
}
