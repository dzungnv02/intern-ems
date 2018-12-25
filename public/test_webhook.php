<?php

use function GuzzleHttp\json_encode;
if (isset($_POST['crm']))
    echo json_encode(['input' => $_POST['crm']]);
else 
    echo json_encode(['result' => 'no input']);