<?php
/**
 * ارسال پیامک پترنی توسط SMS.ir
 * --------------------------------
 * @param string $mobile  شمارهٔ همراه گیرنده (مثلاً "09121234567")
 * @param int    $patternId آی‌دی الگو (مثلاً 620909)
 * @param array  $data  ['Link' => 'https://...']  یا هر جفت name/value دیگری که در الگو تعریف کرده‌ای
 * @return bool  true اگر API با موفقیت پاسخی 20x برگرداند
 */
function sms_pattern_send(string $mobile, int $patternId, array $data): bool
{
    $url = 'https://api.sms.ir/v1/send/verify';

    // تبدیل آرایه‌ی انجمنی به آرایه‌ی [{name, value}, …]
    $parameters = [];
    foreach ($data as $name => $value) {
        $parameters[] = ['name' => $name, 'value' => $value];
    }

    $payload = json_encode([
        'mobile'     => $mobile,
        'templateId' => $patternId,
        'parameters' => $parameters,
    ], JSON_UNESCAPED_UNICODE);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_SSL_VERIFYPEER => true, // برای محیط‌های توسعه، در محیط‌های تولید حتماً باید true باشد
        CURLOPT_SSL_VERIFYHOST => 2,     // برای محیط‌های توسعه، در محیط‌های تولید حتماً باید 2 باشد
        CURLOPT_POST           => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/json',
            'Accept: application/json',
            'x-api-key: ' . SMS_API_KEY,   // حتماً حروف کوچک!
        ],
        CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_TIMEOUT        => 15,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    // لاگ کردن خطاها
    if ($response === false || !in_array($httpCode, [200, 201, 202], true)) {
        $log = date('Y-m-d H:i:s') . " SMS Error:\n";
        $log .= "Mobile: $mobile\n";
        $log .= "Pattern: $patternId\n";
        $log .= "Data: " . json_encode($data, JSON_UNESCAPED_UNICODE) . "\n";
        $log .= "Response: " . ($response ?: 'false') . "\n";
        $log .= "HTTP Code: $httpCode\n";
        if ($error) $log .= "CURL Error: $error\n";
        $log .= "-------------------\n";
        file_put_contents(__DIR__.'/../sms.log', $log, FILE_APPEND);
        return false;
    }

    return true;
}
