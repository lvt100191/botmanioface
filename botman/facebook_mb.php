<?php
error_log("\n check " . "BOT REC "  . date("d/m/Y h:i:s") . " === da vao dau nhan Facebook MB === : \n", 3, "/var/log/bitrix/my.log");
include 'vendor/autoload.php';
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;

$config = [
	// Your driver-specific configuration
	'facebook' => [
		'token' => 'EAAC9N35ZCnTABAKn08JVEB7Gtd1XMYCjmtyXbHFll2tcCCaZCQhVDUztftYNzp7cSkPAT9Vw9xEEJ51CwNT8kuI4SdmWjpUC0lexSC18n0CtFkN77gNxSWA28Kr4XGtXvZCqQ2nRODu9OzkzKaXxvyAuyyPLLNq97u4yP8QB4KU1gXVZCiMUOOit0JFDLBIZD',
		'app_secret' => '4981c5c94303fd38e1b92cf4eb2e9ffc',
		'verification' => '90217375',
	],
];

// Load the driver(s) you want to use
DriverManager::loadDriver(\BotMan\Drivers\Facebook\FacebookDriver::class);

// Create an instance
$botman = BotManFactory::create($config);

// Give the bot something to listen for.
$botman->hears('2', function (BotMan $bot) {
    $user = $bot->getUser();
    $bot->reply('Xin chào ' . $user->getLastName());
});
$botman->hears('hi', function (BotMan $bot) {
    $user = $bot->getUser();
    $bot->reply('Xin chào ' . $user->getLastName());
});
$botman->hears('hello', function (BotMan $bot) {
    $user = $bot->getUser();
    $bot->reply('Xin chào bạn');
});
// $botman->hears('{name}', function ($bot, $name) {
// 	$user = $bot->getUser();
// 	ob_flush();
// 	ob_start();
// 	print_r($user);
// 	file_put_contents("log.txt", ob_get_flush() . "\n", FILE_APPEND);
// 	$bot->reply('Your said: ' . $name);
// 	$bot->reply('Your ID is: ' . $user->getId());
// });

// Start listening
$botman->listen();
