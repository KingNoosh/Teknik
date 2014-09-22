<?php
include('../includes/config.php');

header('Content-Type: application/json');
echo json_encode(array('error' => $CONF['errors']['InvRequest']));
?>