<?php
error_log("\n check " . "BOT REC "  . date("d/m/Y h:i:s") . "  : " . json_encode($_REQUEST)  . "\n", 3, "/var/log/bitrix/my.log");			
include('bbcode.php');
include('vendor/autoload.php');
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;

$LICENSE_KEY = "NFR-ML-P6LBRM1NWISF9MQT";

$config = [
    // Your driver-specific configuration
	'botframework' => [
		'microsoft_bot_handle' => 'binuctBot',
		'app_id' => 'acad8131-113d-4cec-a097-6b676c9bb38b',
		'app_key' => 'H/9+rDgi1qGcR/mF',
	]
];
$configMB = [
    // Your driver-specific configuration
	'botframework' => [
		'microsoft_bot_handle' => 'tongdai-mienbac',
		'app_id' => '5e4f7241-0832-4cc9-9261-2e23d6ee6cad',
		'app_key' => 'm50w6}*TJpeb5$BU',
	]
];
$configMN = [
    // Your driver-specific configuration
	'botframework' => [
		'microsoft_bot_handle' => 'tongdai-miennam',
		'app_id' => '310953c5-5f9b-4b92-b8e3-584e522e4290',
		'app_key' => 'dmj^=}CLWLxsT%kZ',
	]
];
$configMT = [
    // Your driver-specific configuration
	'botframework' => [
		'microsoft_bot_handle' => 'tongdai-mientrung',
		'app_id' => 'd28fbeb3-9195-4e8b-a447-5c0f994024e8',
		'app_key' => 'x4SA/wWh6/k0vfq2',
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
    DriverManager::loadDriver(\BotMan\Drivers\BotFramework\BotFrameworkDriver::class);

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
                $msgText = BBCode::convert($msgText);
                $chatId = $item["chat"]["id"];
                error_log("\n check " . "BOT REC "  . date("d/m/Y h:i:s") . "  : " . "chatId:" . $chatId  . "\n", 3, "/var/log/bitrix/my.log");
                $serviceUrl = $item["service_url"];
                error_log("\n check " . "BOT REC "  . date("d/m/Y h:i:s") . "  : " . "serviceUrl:" . $serviceUrl  . "\n", 3, "/var/log/bitrix/my.log");
                $response = $botman->say($msgText, $chatId, \BotMan\Drivers\BotFramework\BotFrameworkDriver::class, ['serviceUrl'=>$serviceUrl]);
                $content = $response->getContent();
                $object = json_decode($content);

                echo $object->id;
                $receivingData = array(
                    'im' =>
                        array(
                            'chat_id' => $item["im"]["chat_id"],
                            'message_id' => $item["im"]["message_id"]
                        ),
                    'message' =>
                        array(
                            'id' => $object->id
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
                        'CONNECTOR' => 'botframework',
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
