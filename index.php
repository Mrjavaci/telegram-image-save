<?php
/**
 * Created by PhpStorm.
 * User: mrJavaci
 * Date: 2020-05-10
 * Time: 04:46
 */


use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Telegram;

ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php-error.log');


require __DIR__ . '/vendor/autoload.php';

try {
    $telegram = new Longman\TelegramBot\Telegram(Constants::$botApiKey, Constants::$botUsername);
    $telegram->handle();
    $json = json_decode(file_get_contents('php://input'), true);
    $chat_id = $json["message"]["from"]["id"];
    try {
        $i = 0;
        $date = date("YmdHis");
        if (!empty($json["message"]["photo"])) {

            foreach ($json["message"]["photo"] as $item) {
                $client = new \GuzzleHttp\Client();
                $telegram_link = 'https://api.telegram.org/bot' . Constants::$botApiKey . '/getFile?file_id=' . $item['file_id'];
                $request = $client->get($telegram_link);
                $json_response = json_decode($request->getBody(), true);
                if ($json_response['ok'] == 'true') {
                    $telegram_file_link = 'https://api.telegram.org/file/bot' . Constants::$botApiKey . '/' . $json_response['result']['file_path'];
                    $path = __DIR__ . '/images/' . $i . "/" . $date . ".jpg";  //0-1-2 folder
                    file_put_contents($path, file_get_contents($telegram_file_link));
                    $i++;
                }
            }
            \Longman\TelegramBot\Request::sendMessage([
                'chat_id' => $chat_id,
                'text' => "A image has been saved to the server..",
            ]);
        }else{
            \Longman\TelegramBot\Request::sendMessage([
                'chat_id' => $chat_id,
                'text' => "no photos found.",
            ]);
        }
    } catch (Exception $e) {
        \Longman\TelegramBot\Request::sendMessage([
            'chat_id' => $chat_id,
            'text' => "An error was encountered..",
        ]);
    }

} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    echo $e->getMessage();
}