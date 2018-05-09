<?php
error_log("\n check " . "BOT REC "  . date("d/m/Y h:i:s") . " === da vao dau nhan binuct === : \n", 3, "/var/log/bitrix/my.log");
file_put_contents("logC.txt", "=== da vao dau nhan binuct === " . "\n", FILE_APPEND);
include('vendor/autoload.php');

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
//binuct
$TOKEN = "498804212:AAFvJimKzUpV9VMLXycVF25spMli0b02n98";
$FILE_API_URL = 'https://api.telegram.org/file/bot';
$config = [
    // Your driver-specific configuration
    "telegram" => [
        "token" => $TOKEN
    ]
];

// Load the driver(s) you want to use
DriverManager::loadDriver(\BotMan\Drivers\Telegram\TelegramDriver::class);

// Create an instance
$botman = BotManFactory::create($config);

// Give the bot something to listen for.
//$botman->hears('hello', function (BotMan $bot) {
//    $bot->reply('Hello yourself.');
//    
//file_put_contents("log.txt", "Helo Yourself" . "\n", FILE_APPEND);
//});
$botman->hears('{message}', function ($bot, $message) use ($TOKEN,$FILE_API_URL){
    $user = $bot->getUser();
//      ob_flush();
//      ob_start();
//      print_r($user);
//      file_put_contents("logC.txt", ob_get_flush() . "\n", FILE_APPEND);
    error_log("\n check " . "BOT REC "  . date("d/m/Y h:i:s") . " === getUser ok === : " . serialize($user->getInfo()) . " \n", 3, "/var/log/bitrix/my.log");
    $payload = $bot->getMessage()->getPayload();
    if(isset($payload)){
//      ob_flush();
//      ob_start();
//      print_r($payload);
//      file_put_contents("logC.txt", ob_get_flush() . "\n", FILE_APPEND);
        $paramsBot = [
            'user_id'=> $user->getId(),
            'offset' => 0,
            'limit'=> 1
        ];
        $response = $bot->sendRequest('getUserProfilePhotos', $paramsBot);
        $jsonData = json_decode($response->getContent());
        $profilePhoto = "";
        if($jsonData !=null){
            if($jsonData->ok == true){
                $result = $jsonData->result;
                $photos = $result->photos;
                $photo = $photos[0][0];
                $fileId = $photo->file_id;
                if($fileId !=null){
                    $paramFile = [
                        'file_id'=> $fileId
                    ];
                    $response = $bot->sendRequest('getFile', $paramFile);
                    $jsonData = json_decode($response->getContent());
                    if($jsonData->ok == true){
                        $result = $jsonData->result;
                        $filePath = $result->file_path;
                        $profilePhoto = $FILE_API_URL . $TOKEN . "/" . $filePath;
                    }
                }
            }
        }
        $CONFIG_ID = "9";
        $LICENSE_KEY = "NFR-ML-P6LBRM1NWISF9MQT";
        $firstName = !empty($user->getFirstName()) ? $user->getFirstName() : $user->getUsername();
        $data = array(
            'user' =>
            array(
                'id' => $user->getId(),
                'name' => $firstName,
                'last_name' => $user->getLastName(),
                'url' => 'https://telegram.me/' . $user->getUsername(),
                'picture' => array(
                    'url' => $profilePhoto,
                    'description' => ''
                )
            ),
            'message' =>
            array(
                'id' => $payload->get('message_id'),
                //'timestamp' => $payload->get('timestamp'),
                'date' => time(),
                'files' => '',
                'text' => $message,
            ),
            'chat' =>
            array(
                'id' => $user->getId(),
                'name' => $firstName . " " . $user->getLastName(),
                'picture' => array(
                    'url' => $profilePhoto,
                    'description' => ''
                )
            ),
            'type_message' => 'message', // 'message'
        );
        //error_log("\n check " . "BOT REC "  . date("d/m/Y h:i:s") . " === Config_ID : " . $CONFIG_ID . "\n", 3, "/var/log/bitrix/my.log");
        $params = [
                    'BX_COMMAND' => 'receivingMessage',
                    'BX_TYPE' => 'CP',
                    'LINE' => $CONFIG_ID,
                    'CONNECTOR' => 'telegrambot',
                    'BX_VERSION' => '17.5.2',
                    'DATA' => base64_encode(serialize(array($data)))
                ];
        //error_log("\n check " . "BOT REC "  . date("d/m/Y h:i:s") . " === LICENSE_KEY : " . $LICENSE_KEY . "\n", 3, "/var/log/bitrix/my.log");
        $str = md5(implode("|", $params));
        $params["BX_HASH"] = md5($str.md5($LICENSE_KEY));

        $client = new \GuzzleHttp\Client();
        $res = $client->request('POST', 'https://demo1.ipcc.space/pub/imconnector/', [
            'form_params' => $params
        ]);
    }
});

// Start listening
$botman->listen();
