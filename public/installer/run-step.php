<?php
// Disabled: the legacy endpoint executed shell commands through unauthenticated HTTP.
http_response_code(410);
header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-store, max-age=0');
echo json_encode(['success' => false, 'message' => 'The browser-based installer is disabled. Use the reviewed CLI installation guide.']);
