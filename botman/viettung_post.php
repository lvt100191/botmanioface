<?php
$url = 'https://api.telegram.org/bot532533623:AAFdQgbOGenvjAcuAkARL-cPnhw1ALYb9bY/setWebhook';
$data = array('url' => 'https://demo1.ipcc.space/ext/botman/viettung_test.php');
$options = array(
        'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
    )
);

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
echo $result;
?>