<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$r1 = \Illuminate\Support\Facades\Http::post('https://ulss104-chatbot-kalibrasi.hf.space/gradio_api/call/predict', [
    'data' => ['Apakah ada pajak dalam pembayaran jasa kalibrasi']
]);
$eventId = $r1->json('event_id');
echo "Event ID: $eventId\n";
if ($eventId) {
    $r2 = \Illuminate\Support\Facades\Http::get("https://ulss104-chatbot-kalibrasi.hf.space/gradio_api/call/predict/$eventId");
    echo "Body:\n" . $r2->body() . "\n";
} else {
    echo "No event ID. Response: " . $r1->body() . "\n";
}
