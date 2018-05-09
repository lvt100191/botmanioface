<?php


file_put_contents("loglvt.txt", "namnamnam" . "\n", FILE_APPEND);
include('vendor/autoload.php');

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;

$config = [
    // Your driver-specific configuration
    "telegram" => [
        "token" => "532533623:AAFdQgbOGenvjAcuAkARL-cPnhw1ALYb9bY"
    ]
];

// Load the driver(s) you want to use
DriverManager::loadDriver(\BotMan\Drivers\Telegram\TelegramDriver::class);

// Create an instance
$botman = BotManFactory::create($config);

// Give the bot something to listen for.
$botman->hears('hello', function (BotMan $bot) {
    $bot->reply('Hello yourself.');
});

$botman->hears('call me {name}', function ($bot, $name) {
    $bot->reply('Your name is: '.$name);
});

$botman->fallback(function($bot) {
    $bot->reply('Chung toi nhan duoc tin nhan cua ban thong tin nhu sau: ...'.$bot->getMessage()->getPayload());
});

file_put_contents("loglvt.txt", $botman->getMessage()->getPayload() . "\n", FILE_APPEND);
// $botman->hears('{name}', function ($bot, $name) {
//     $user = $bot->getUser();
//       ob_flush();
//       ob_start();
//       print_r($user);
//       file_put_contents("log.txt", ob_get_flush() . "\n", FILE_APPEND);
//     $bot->reply('Your said: '.$name);
//     $bot->reply('Your ID is: '.$user->getId());
// });


// Start listening
$botman->listen();
