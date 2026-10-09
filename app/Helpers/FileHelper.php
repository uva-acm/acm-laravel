<?php

namespace App\Helpers;

class FileHelper
{
    /**
     * Get the maximum file upload size in KB
     */
    public static function getMaxUploadSizeKB(): int
    {
        $limits = array_filter([
            self::iniSizeToKB(ini_get('upload_max_filesize')),
            self::iniSizeToKB(ini_get('post_max_size')),
            self::iniSizeToKB(ini_get('memory_limit')),
        ], fn (int $limit) => $limit > 0);

        return $limits === [] ? 2048 : min($limits);
    }

    /**
     * Convert a PHP ini size such as 2M or 1G to kilobytes.
     * A value of -1 means unlimited and is excluded from the effective limit.
     */
    private static function iniSizeToKB(string|false $value): int
    {
        if ($value === false || trim($value) === '' || trim($value) === '-1') {
            return -1;
        }

        $value = trim($value);
        $number = (float) $value;
        $unit = strtolower(substr($value, -1));

        return match ($unit) {
            'g' => (int) ($number * 1024 * 1024),
            'm' => (int) ($number * 1024),
            'k' => (int) $number,
            default => (int) ceil($number / 1024),
        };
    }
    
    /**
     * Get the maximum file upload size in MB
     */
    public static function getMaxUploadSizeMB(): float
    {
        return self::getMaxUploadSizeKB() / 1024;
    }
    
    /**
     * Get the maximum file upload size formatted for display
     */
    public static function getMaxUploadSizeFormatted(): string
    {
        $sizeKB = self::getMaxUploadSizeKB();
        
        if ($sizeKB >= 1024) {
            return round($sizeKB / 1024, 1) . ' MB';
        }
        
        return $sizeKB . ' KB';
    }
}
