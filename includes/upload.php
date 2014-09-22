<?php
include('config.php');

echo json_encode(upload($_FILES, $CONF, $db));
?>