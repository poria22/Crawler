<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TelegramBotController;

//Route::get('/', [\App\Http\Controllers\CrawlerController::class, 'index']);

Route::post('/webhook', [TelegramBotController::class, 'handleWebhook']);
