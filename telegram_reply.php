<?php
include('vendor/autoload.php');
include('bbcode.php');
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use Symfony\Component\HttpFoundation\Response;
$LICENSE_KEY = "NFR-ML-P6LBRM1NWISF9MQT";
$config = [
    "telegram" => [
        "token" => "498804212:AAFvJimKzUpV9VMLXycVF25spMli0b02n98"
    ]
];

$configMB = [
    "telegram" => [
        "token" => "572315310:AAEsfl5CyLpDK-BwEd7yVoveXREf6pIi1bw"
    ]
];

$configMT = [
    "telegram" => [
        "token" => "566637779:AAFfCrcOOrr_63zS-s45O4fxDxKytYLLvj0"
    ]
];

$configMN = [
    "telegram" => [
        "token" => "584136507:AAE9KlH3wRlxiGou5x1H1dy7L9IYAf0HtDE"
    ]
];

$arrConfig = [
    "1" => $configMB,
    "6" => $configMT,
    "7" => $configMN,
    "9" => $config
];

$line = $_REQUEST["LINE"];
$command = $_REQUEST["BX_COMMAND"];
if($command == "sendMessage"){
    // Load the driver(s) you want to use
    DriverManager::loadDriver(\BotMan\Drivers\Telegram\TelegramDriver::class);
    
    // Create an instance
    $botman = BotManFactory::create($arrConfig["$line"]);
    try{
        error_log("\n check " . "BOT REC "  . date("d/m/Y h:i:s") . "  : " . "lay data"  . "\n", 3, "/var/log/bitrix/my.log");	
        //[[{"im":{"chat_id":113,"message_id":2196},"message":{"user_id":3,"text":"[b]Qu\u1ea3n tr\u1ecb MB:[\/b][br] hhhhhh","files":"#EMPTY#","attachments":"#EMPTY#","params":"#EMPTY#"},"chat":{"id":"29:14qP0BjSJ4-P3-eIyOtxvQDT0Ydyv0NHnBKypX4QMhBQE8zFiqq5j0kfeCuh0OVdx"},"from":{"id":"28:acad8131-113d-4cec-a097-6b676c9bb38b","name":"binuctBot"},"service_url":"https:\/\/smba.trafficmanager.net\/apis\/"}]]
        $data = unserialize(base64_decode($_REQUEST["DATA"]));
        error_log("\n check " . "BOT REC "  . date("d/m/Y h:i:s") . "  : " . "lay data ok"  . "\n", 3, "/var/log/bitrix/my.log");	
        if(is_array($data)){
            error_log("\n check " . "BOT REC "  . date("d/m/Y h:i:s") . "  : " . "data is array"  . "\n", 3, "/var/log/bitrix/my.log");
            $arrMsg = $data[0];
            foreach ($arrMsg as $item)
            {
                error_log("\n check " . "BOT REC "  . date("d/m/Y h:i:s") . "  : " . "get array item"  . "\n", 3, "/var/log/bitrix/my.log");
                $msgText = $item["message"]["text"];
                error_log("\n check " . "BOT REC "  . date("d/m/Y h:i:s") . "  : " . "msgText:" . $msgText  . "\n", 3, "/var/log/bitrix/my.log");
                $msgText = BBCode::convertForTelegram($msgText);
                $chatId = $item["chat"]["id"];
                error_log("\n check " . "BOT REC "  . date("d/m/Y h:i:s") . "  : " . "chatId:" . $chatId  . "\n", 3, "/var/log/bitrix/my.log");
//                $response = $botman->say($msgText, $chatId, \BotMan\Drivers\BotFramework\BotFrameworkDriver::class, ['serviceUrl'=>$serviceUrl]);
//                $content = $response->getContent();
//                $object = json_decode($content);
//
//                echo $object->id;
                $mode = [
                    'parse_mode'=> 'Markdown'
                ];
                 
                $response = $botman->say($msgText, $chatId, \BotMan\Drivers\Telegram\TelegramDriver::class, $mode);
                $content = $response->getContent();
                $object = json_decode($content);
                $result = $object->result;
                $receivingData = array(
                    'im' =>
                        array(
                            'chat_id' => $item["im"]["chat_id"],
                            'message_id' => $item["im"]["message_id"]
                        ),
                    'message' =>
                        array(
                            'id' => $result->message_id
                        ),
                    'chat' =>
                        array(
                            'id' => $chatId,
                        )
                );
                $params = [
                        'BX_COMMAND' => 'receivingStatusDelivery',
                        'BX_TYPE' => 'CP',
                        'LINE' => $line,
                        'CONNECTOR' => 'telegrambot',
                        'BX_VERSION' => '17.5.2',
                        'DATA' => base64_encode(serialize(array($receivingData)))
                    ];

                $str = md5(implode("|", $params));
                $params["BX_HASH"] = md5($str.md5($LICENSE_KEY));

                $client = new \GuzzleHttp\Client();
                $res = $client->request('POST', 'https://demo1.ipcc.space/pub/imconnector/', [
                    'form_params' => $params
                ]);
            }
        }else{
            error_log("\n check " . "BOT REC "  . date("d/m/Y h:i:s") . "  : " . "data isn't array"  . "\n", 3, "/var/log/bitrix/my.log");
        }
    } catch (Exception $e){
        error_log("\n check " . "BOT REC "  . date("d/m/Y h:i:s") . "  : " . $e->getMessage()  . "\n", 3, "/var/log/bitrix/my.log");	
    }
}