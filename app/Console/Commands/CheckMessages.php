<?php

namespace App\Console\Commands;

use App\Helpers;
use Illuminate\Console\Command;
use TelegramBot\Api\BotApi;
use VK\VK;

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
        $sender_provider = new BotApi(env('TELEGRAM_BOT_API'));
        $receiver_provider = new VK(env('VK_APP_ID'), env('VK_API_SECRET'), env('VK_ACCESS_TOKEN'));
        $receiver = new Helpers\VkHelper();
        $receiver->setReceiver($receiver_provider);
        $receiver->setSender($sender_provider);
        $receiver->setReceiverId(env('VK_ID'));

        $sender = new Helpers\Telegram();
        $sender->setSender($sender_provider);
        $sender->setReceiverId(env('TELEGRAM_CHAT_ID'));

        $redirect = new Helpers\MessagesRedirect($receiver, $sender);

        $redirect->process();
    }
}
