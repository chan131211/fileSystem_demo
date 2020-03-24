<?php

require_once '../11-file_func.php';
$fileInfo = $_FILES['myFile'];
var_dump(upload_file($fileInfo));