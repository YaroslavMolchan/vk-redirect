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
        $telegram_api = new BotApi(env('TELEGRAM_BOT_API'));
        $vk_api = new VK(env('VK_APP_ID'), env('VK_API_SECRET'), env('VK_ACCESS_TOKEN'));
        $receiver = new Helpers\Vk\Helper();
        $receiver->setReceiver($vk_api);

        $sender = new Helpers\Telegram\Helper();
        $sender->setSender($telegram_api);
        $sender->setReceiverId(env('TELEGRAM_CHAT_ID'));

        $redirect = new Helpers\VkToTelegramRedirect($receiver, $sender);
        $redirect->process();

        return 'All done';
    }
}
