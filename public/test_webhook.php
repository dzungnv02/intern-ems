<?php
if (isset($_POST['crm'])) {
    $f_handle = fopen('/apps/icanread/intern-ems/storage/webhook.log', 'a+');
    $data = json_encode(['input' => $_POST['crm'], 'time' => date('Y-m-d H:i:s')]) . "\n";

    fwrite($f_handle, json_encode($data));
    fclose($f_handle);
    }
else 
    echo json_encode(['result' => 'no input']);