<?php
header('Content-Type: application/json');

echo json_encode([
    'message' => 'PHP Quotes API',
    'endpoints' => [
        'authors' => '/api/authors/',
        'categories' => '/api/categories/',
        'quotes' => '/api/quotes/'
    ]
]);
?>