<?php
error_log("\n check " . "BOT REC "  . date("d/m/Y h:i:s") . " === da vao dau nhan binuct === : \n", 3, "/var/log/bitrix/my.log");
include('vendor/autoload.php');
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;

$config = [
    // Your driver-specific configuration
    'botframework' => [
        'microsoft_bot_handle' => 'binuctBot',
        'app_id' => 'acad8131-113d-4cec-a097-6b676c9bb38b',
        'app_key' => 'H/9+rDgi1qGcR/mF',
    ]
];

// Load the driver(s) you want to use
DriverManager::loadDriver(\BotMan\Drivers\BotFramework\BotFrameworkDriver::class);

// Create an instance
$botman = BotManFactory::create($config);

// Give the bot something to listen for.
//$botman->hears('hello', function (BotMan $bot) {
//    $bot->reply('Hello yourself.');
//});
//$botman->hears('call me {name}', function ($bot, $name) {
//    $bot->reply('Your name is: ' . $name);
//});
$botman->hears('{message}', function ($bot, $message) {
    $user = $bot->getUser();
    $payload = $bot->getMessage()->getPayload();

    if(isset($payload)){
//        ob_flush();
//        ob_start();
//        var_dump($user);
//        file_put_contents("log.txt", ob_get_flush() . "\n", FILE_APPEND);
        $CONFIG_ID = "9";
        $LICENSE_KEY = "NFR-ML-P6LBRM1NWISF9MQT";
        $data = array(
            'user' =>
            array(
                'id' => $user->getId(),
                'name' => !empty($user->getFirstName()) ? $user->getFirstName() : $user->getUsername(),
                'last_name' => $user->getLastName()
            ),
            'message' =>
            array(
                'id' => $payload->get('id'),
                //'timestamp' => $payload->get('timestamp'),
                'date' => time(),
                'files' => '',
                'text' => $message,
            ),
            'chat' =>
            array(
                'id' => $user->getId(),
            ),
            'recipient' =>
            array(
                'id' => $payload->get('recipient')['id'], //'28:59e31386-8f8c-46de-82b1-cafb2c13f7eb',
                'name' => $payload->get('recipient')['name'], //'botman-ipcc-gov',
            ),
            'service_url' => $payload->get('serviceUrl'), // 'https://smba.trafficmanager.net/apis/',
            'type_message' => $payload->get('type'), // 'message'
        );
        error_log("\n check " . "BOT REC "  . date("d/m/Y h:i:s") . " === Config_ID : " . $CONFIG_ID . "\n", 3, "/var/log/bitrix/my.log");
        $params = [
                    'BX_COMMAND' => 'receivingMessage',
                    'BX_TYPE' => 'CP',
                    'LINE' => $CONFIG_ID,
                    'CONNECTOR' => 'botframework.skype',
                    'BX_VERSION' => '17.5.2',
                    'DATA' => base64_encode(serialize(array($data)))
                ];
        error_log("\n check " . "BOT REC "  . date("d/m/Y h:i:s") . " === LICENSE_KEY : " . $LICENSE_KEY . "\n", 3, "/var/log/bitrix/my.log");
        $str = md5(implode("|", $params));
        $params["BX_HASH"] = md5($str.md5($LICENSE_KEY));

        $client = new \GuzzleHttp\Client();
        $res = $client->request('POST', 'https://demo1.ipcc.space/pub/imconnector/', [
            'form_params' => $params
        ]);

//        $bot->reply('Your said: ' . $message);
//        $bot->reply('Your ID is: ' . $user->getId());
//        $bot->reply('Your JSON DATA is: ' . json_encode($data));
    }
});
$botman->fallback(function(BotMan $bot) {
    $bot->reply('Sorry, I did not understand these commands. Here is a list of commands I understand: ...');
});
// Start listening
$botman->listen();
