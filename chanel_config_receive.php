<?php
error_log("\n check " . "da nhan request gui den "  . date("d/m/Y h:i:s") . "  : " . json_encode($_REQUEST)  . "\n", 3, "/home/bitrix/www/ext/botman/vt.log");          
include('vendor/autoload.php');
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;

$line = $_REQUEST["LINE"];

$connector = $_REQUEST["CONNECTOR"];

$command = $_REQUEST["BX_COMMAND"];

$data = unserialize(base64_decode($_REQUEST["DATA"]));

if(strcmp ($connector,"telegrambot")==0){
    //error_log("Nhay vao day:", 3 , "/var/log/bitrix/vt.log");
    $receivingData = array();
    switch ($command) {
        case "readSettings":
        $receivingData = ChanelConfig::readSettings($data);
        break;
        case "unregister":
        $receivingData = ChanelConfig::unregister($data);
        break;
        case "saveSettings":
        $receivingData = ChanelConfig::saveSettings($data);
        break;
        case "testConnect":
        $receivingData = ChanelConfig::testConnect($data);
        break;

        case "register":
        $receivingData = ChanelConfig::register($data);
        break;

        case "infoConnect":
        $receivingData = ChanelConfig::infoConnect($data);
        break;

        case "deleteConnector":
        $receivingData = ChanelConfig::deleteConnector($data);
        break;
    }
    echo json_encode($receivingData);
    //echo json_encode(array('line'=>12,'msg'=>'da nhan dc roi nhe'));
}


class ChanelConfig{
    public static function readSettings($data)
    {
        error_log("\n".date("d/m/Y h:i:s") ."=====> Xu ly readSettings:". json_encode($data)  . "\n", 3 , "/var/log/bitrix/vt.log");
        $receivingData = array(
            'OK' => true,
            'DATA' =>
            array(
                'RESULT' => array(api_token =>'#HIDDEN#'
            )
            )
        );
        return $receivingData;
    }
//DATA: {"OK":false,"DATA":[],"ERROR":[{"CODE":"CONNECTOR_SETTINGS_INCORRECT"}]}
    public static function unregister($data)
    {
        error_log("\n".date("d/m/Y h:i:s") ."=====> Xu ly unregister:". json_encode($data)  . "\n", 3 , "/var/log/bitrix/vt.log");
        $receivingData = array(
            'OK' => false,
            'DATA' => array(),
            'ERROR' =>
            array(
                'CODE' => 'CONNECTOR_SETTINGS_INCORRECT'
            )

        );
        return $receivingData;
    }

    //DATA: {"OK":true,"DATA":{"api_token":true}}
    public static function saveSettings($data)
    {
        error_log("\n".date("d/m/Y h:i:s") ."=====> Xu ly saveSettings:". json_encode($data)  . "\n", 3 , "/var/log/bitrix/vt.log");
        $receivingData = array(
            'OK' => true,
            'DATA' =>
            array(
                'api_token' => true
            )

        );
        return $receivingData;
    }

        //DATA: {"OK":true,"DATA":{"RESULT":{"id":532533623,"is_bot":true,"first_name":"bot_tele_ipcc","username":"IpccTeleBot"}}}
    public static function testConnect($data)
    {
         error_log("\n".date("d/m/Y h:i:s") ."=====> Xu ly testConnect:". json_encode($data)  . "\n", 3 , "/var/log/bitrix/vt.log");
        $receivingData = array(
            'OK' => true,
            'DATA' =>
            array(
                'RESULT' => array(
                    'id'=>532533623,
                    'is_bot'=>true,
                    'first_name'=>'bot_tele_ipcc',
                    'username'=>'IpccTeleBot'
                )
            )

        );
        return $receivingData;
    }



    //DATA: {"OK":true,"DATA":{"RESULT":true}}
    public static function register($data)
    {
        error_log("\n".date("d/m/Y h:i:s") ."=====> Xu ly register:". json_encode($data)  . "\n", 3 , "/var/log/bitrix/vt.log");
        $receivingData = array(
            'OK' => true,
            'DATA' =>
            array(
                'RESULT' => true
            )

        );
        return $receivingData;
    }
    //DATA: {"OK":true,"DATA":{"id":532533623,"name":" bot_tele_ipcc","url":"https:\/\/telegram.me\/IpccTeleBot","url_im":"https:\/\/telegram.me\/IpccTeleBot"}}
    public static function infoConnect($data)
    {
        error_log("\n".date("d/m/Y h:i:s") ."=====> Xu ly infoConnect:". json_encode($data)  . "\n", 3 , "/var/log/bitrix/vt.log");
        $receivingData = array(
            'OK' => true,
            'DATA' =>
            array(
                'id' => 532533623,
                'name'=>'bot_tele_ipcc',
                'url'=>'https://telegram.me/IpccTeleBot',
                'url_im'=> 'https://telegram.me/IpccTeleBot'
            )

        );
        return $receivingData;
    }

        //DATA: {"OK":true,"DATA":[]}
    public static function deleteConnector($data)
    {
        error_log("\n".date("d/m/Y h:i:s") ."=====> Xu ly deleteConnector:". json_encode($data)  . "\n", 3 , "/var/log/bitrix/vt.log");
        $receivingData = array(
            'OK' => true,
            'DATA' =>array()
        );
        return $receivingData;
    }
}




