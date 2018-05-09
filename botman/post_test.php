<?php
$url = 'https://demo1.ipcc.space/ext/botman/botframework_reply_test.php';
$data = array('field1' => 'value', 'field2' => 'value');
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