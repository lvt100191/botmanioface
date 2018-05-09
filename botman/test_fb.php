<?php
include 'vendor/autoload.php';

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;

require $_SERVER["DOCUMENT_ROOT"] . "/ext/botman/vendor/botman/driver-facebook/src/FacebookDriver.php";

$config = [
	// Your driver-specific configuration
	'facebook' => [
		'token' => 'EAAFzZB8ZAqdDUBAPdwhoKi3kOJwubxXZAmYIGGmW1FYcJqixw0uIyE8ShRXeJbMOCrsWRGpVH2I96DgGTLcZCY1zO3dmN7WMyYKps6EIIYHM23ZAIdk48dG9aTsOryNFA9MkvrUFABFdz7r2lhSyBNZAztvgvCCic4lTvPZBL2NsgZDZD',
		'app_secret' => '0e5e604642d293d79fcf9c1c0dc4574d',
		'verification' => '409000179561525',
	],
];

// Load the driver(s) you want to use
DriverManager::loadDriver(\BotMan\Drivers\Facebook\FacebookDriver::class);

// Create an instance
$botman = BotManFactory::create($config);

// Give the bot something to listen for.
$botman->hears('2', function (BotMan $bot) {
	$bot->reply('xin chao toi la nhan vien cskh ipcc.');

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
