```php
<?php
/**
 * Lightweight JWT Helper (HS256)
 * No external composer dependency required.
 * Handles: encode, decode, verify, expiry check.
 */

class JWTHelper
{
    // JWT secret is loaded from environment variable
    private static string $secret = '';

    private static int $expirySeconds = 3600; // 1 hour

    private static function getSecret(): string
    {
        if (self::$secret === '') {
            self::$secret = getenv('JWT_SECRET') ?: '';
        }

        if (self::$secret === '') {
            throw new RuntimeException('JWT_SECRET is not configured.');
        }

        return self::$secret;
    }

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
            self::getSecret(),
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
            self::getSecret(),
            true
        );

        $expectedSignatureEncoded = self::base64UrlEncode($expectedSignature);

        if (!hash_equals($expectedSignatureEncoded, $signatureEncoded)) {
            return false;
        }

        $payload = json_decode(
            self::base64UrlDecode($payloadEncoded),
            true
        );

        if (!$payload || !isset($payload['exp']) || $payload['exp'] < time()) {
            return false;
        }

        return $payload;
    }
}
```
