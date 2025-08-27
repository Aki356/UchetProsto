<?php
declare(strict_types=1);
require_once __DIR__ . '/../lib/KB.php';

header('Content-Type: application/json; charset=utf-8');

$kb = new KnowledgeBase();
$action = $_GET['action'] ?? '';

try {
    if ($action === 'topics') {
        echo json_encode([
            'ok' => true,
            'topics' => array_map(fn(Topic $t) => $t->toArray(), $kb->topics()),
            'defaults' => [
                'topicId' => $kb->defaultTopicId(),
                'subtopicCode' => $kb->defaultSubtopicCode($kb->defaultTopicId())
            ]
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($action === 'content') {
        $code = (string)($_GET['sid'] ?? '');
        $svc = new ContentService();
        echo json_encode(['ok'=>true,'content'=>$svc->getBySubtopic($code)], JSON_UNESCAPED_UNICODE);
        exit;
    }

    http_response_code(400);
    echo json_encode(['ok'=>false,'error'=>'Unknown action']);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok'=>false,'error'=>'Server error']);
}
