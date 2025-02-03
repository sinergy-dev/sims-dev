<?php

namespace App\Service;

use GuzzleHttp\Client;

class TelegramService
{
    protected $client;
    protected $botToken;

    public function __construct()
    {
        $this->client = new Client();
        $this->botToken = env('TELEGRAM_BOT_TOKEN');
    }

    public function sendMessage($chatId, $message)
    {
        $response = $this->client->post("https://api.telegram.org/bot{$this->botToken}/sendMessage",[
            'json' => [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'HTML'
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
