<?php
/**
 * Lightweight JWT Helper (HS256)
 * No external composer dependency required.
 * Handles: encode, decode, verify, expiry check.
 */

class JWTHelper
{
    // IMPORTANT: move this to an environment variable in production
    private static string $secret = '7f3A9dQx!2mR8kLpZ6vB1nC4tYs5eWj0uHg7Ff2Kd9Xa3Nm6Rq';
    private static int $expirySeconds = 3600; // 1 hour

    private static function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function base64UrlDecode(string $data): string
    {
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $data .= str_repeat('=', 4 - $remainder);
        }
        return base64_decode(strtr($data, '-_', '+/'));
    }

    /**
     * Generate a JWT for a given user payload.
     * @param array $payload e.g. ['user_id' => 1, 'role' => 'internal_bd']
     */
    public static function generateToken(array $payload): string
    {
        $header = ['typ' => 'JWT', 'alg' => 'HS256'];

        $issuedAt  = time();
        $expiresAt = $issuedAt + self::$expirySeconds;

        $payload['iat'] = $issuedAt;
        $payload['exp'] = $expiresAt;

        $headerEncoded  = self::base64UrlEncode(json_encode($header));
        $payloadEncoded = self::base64UrlEncode(json_encode($payload));

        $signature = hash_hmac(
            'sha256',
            "{$headerEncoded}.{$payloadEncoded}",
            self::$secret,
            true
        );
        $signatureEncoded = self::base64UrlEncode($signature);

        return "{$headerEncoded}.{$payloadEncoded}.{$signatureEncoded}";
    }

    /**
     * Verify token signature + expiry.
     * Returns decoded payload array on success, or false on failure.
     */
    public static function verifyToken(string $token)
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return false;
        }

        [$headerEncoded, $payloadEncoded, $signatureEncoded] = $parts;

        $expectedSignature = hash_hmac(
            'sha256',
            "{$headerEncoded}.{$payloadEncoded}",
            self::$secret,
            true
        );
        $expectedSignatureEncoded = self::base64UrlEncode($expectedSignature);

        if (!hash_equals($expectedSignatureEncoded, $signatureEncoded)) {
            return false; // invalid signature
        }

        $payload = json_decode(self::base64UrlDecode($payloadEncoded), true);

        if (!$payload || !isset($payload['exp']) || $payload['exp'] < time()) {
            return false; // expired or malformed
        }

        return $payload;
    }
}
