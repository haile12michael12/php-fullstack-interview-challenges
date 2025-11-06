<?php

header('Content-Type: application/json');

echo json_encode([
    'status' => 'healthy',
    'timestamp' => time(),
    'service' => 'challenge-03-long-polling-notify'
]);