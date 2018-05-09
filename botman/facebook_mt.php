<?php
error_log("\n check " . "BOT REC "  . date("d/m/Y h:i:s") . " === da vao dau nhan Facebook cuong === : \n", 3, "/var/log/bitrix/my.log");
include 'vendor/autoload.php';
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;

$config = [
	// Your driver-specific configuration
	'facebook' => [
		'token' => 'EAAXeahTdvhQBAHY97CVpoOA4ssnDcg1VmcQCJ24tMRrTInoKRd7xWBBBsmKzZCSd4u4Q0fOEz13Ai6xl9JZAR3cbNlogXDq9EZBHaWZCGcxvsZCrIOTMuFHyZAQt7N938D4RIr82ZCHvQ8Tq16OMn2wUq5OwV32eJZAYYRqhl4fZCXwZDZD',
		'app_secret' => '9035c7e7f8eed2da64eba7ff75bc9814',
		'verification' => '776831290',
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
    $bot->reply('Xin chào ' . $user->getLastName());
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
