<?php
/**
 * Created by PhpStorm.
 * User: javaci
 * Date: 2020-05-10
 * Time: 04:49
 */


use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Telegram;

require __DIR__ . '/vendor/autoload.php';

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram(Constants::$botApiKey, Constants::$botUsername);
    $result = $telegram->setWebhook(Constants::$hookUrl);
    if ($result->isOk()) {
        echo "Successfuly set your hook url";
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    echo $e->getMessage();
}