<?php
/**
 * Minimal .env file loader for Core PHP.
 * No external packages used, as per project requirement (Core PHP only).
 */
class Env
{
    protected static $loaded = false;

    public static function load($path)
    {
        if (self::$loaded) {
            return;
        }

        if (!file_exists($path)) {
            // Fall back silently; getenv() with defaults will still work.
            self::$loaded = true;
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $line = trim($line);

            // Skip comments
            if ($line === '' || strpos($line, '#') === 0) {
                continue;
            }

            if (strpos($line, '=') === false) {
                continue;
            }

            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            // Strip optional surrounding quotes
            $value = trim($value, "\"'");

            if (!array_key_exists($name, $_ENV) && getenv($name) === false) {
                putenv("{$name}={$value}");
                $_ENV[$name] = $value;
            }
        }

        self::$loaded = true;
    }

    public static function get($key, $default = null)
    {
        $value = getenv($key);
        return $value === false ? $default : $value;
    }
}
