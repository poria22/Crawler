<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;

class TelegramBotController extends Controller
{
    protected $telegram;
    protected $client;

    public function __construct(Api $telegram)
    {
        $this->telegram = $telegram;
        $this->client = new Client([
            'proxy' => 'http://127.0.0.1:1080',
            'timeout' => 60,
        ]);
    }

    public function sendMenu($chatId)
    {
        $keyboard = [
            'inline_keyboard' => [
                [
                    ['text' => 'دکمه 1', 'callback_data' => 'button_1'],
                ],
            ],
        ];

        $this->sendMessageWithInlineKeyboard($chatId, 'لطفاً یکی از گزینه‌ها را انتخاب کنید:', $keyboard);
    }

    public function sendMessageWithInlineKeyboard($chatId, $text, $keyboard)
    {
        $client = new \GuzzleHttp\Client();

        $response = $this->client->post('https://api.telegram.org/bot7909935949:AAF58WnXmB5gD2FNiNYuP1ccLzHuC8KybsI/sendMessage', [
            'form_params' => [
                'chat_id' => $chatId,
                'text' => $text,
                'reply_markup' => json_encode($keyboard),
            ],
        ]);

        return $response->getBody();
    }

    public function sendMessage($chatId, $text)
    {
        $response = $this->client->post('https://api.telegram.org/bot7909935949:AAF58WnXmB5gD2FNiNYuP1ccLzHuC8KybsI/sendMessage', [
            'form_params' => [
                'chat_id' => $chatId,
                'text' => $text,
            ],
        ]);

        return $response->getBody();
    }

    public function handleWebhook(Request $request)
    {
        try {
            $update = $request->all();

            if (!empty($update['message'])) {
                $message = $update['message'];
                $text = $message['text'] ?? null;
                $chatId = $message['chat']['id'] ?? null;

                if ($text == "/start" && $chatId) {
                    Log::info('ss');
                    $this->sendMenu($chatId);
                } else {
                    Log::info('nn');

                    $this->sendMessage($chatId, 'پیام شما دریافت شد: ' . $text);
                }
            }

            if (!empty($update['callback_query'])) {
                $callbackQuery = $update['callback_query'];
                $chatId = $callbackQuery['message']['chat']['id'];

                $firstName = $callbackQuery['from']['first_name'];
                $username = $callbackQuery['from']['username'] ?? 'کاربر';

                $buttonClicked = $callbackQuery['data'];
                Log::info('ss');
                if ($buttonClicked == 'button_1') {
                    Log::info($username);
                        $this->sendMessage($chatId, 'چخه');
                }
            }
        } catch (\Exception $e) {
            Log::error('Error handling webhook: ' . $e->getMessage());
        }
    }
}
