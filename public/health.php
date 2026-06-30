<?php
// Simple health check - bypasses Laravel entirely
// Apache serves this directly - no framework boot needed
http_response_code(200);
header('Content-Type: application/json');
echo json_encode(['status' => 'ok', 'service' => 'Project2-Steva']);
