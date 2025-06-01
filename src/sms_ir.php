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

        CURLOPT_SSL_VERIFYPEER => false, // برای محیط‌های توسعه، در محیط‌های تولید حتماً باید true باشد
        CURLOPT_SSL_VERIFYHOST => 0,     // برای محیط‌های توسعه، در محیط‌های تولید حتماً باید 2 باشد

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
    curl_close($ch);

    // اگر کد 200 یا 201 یا 202 بود، موفق در نظر بگیر
    return $response !== false && in_array($httpCode, [200, 201, 202], true);
}
