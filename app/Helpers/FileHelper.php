<?php

namespace App\Helpers;

class FileHelper
{
    /**
     * Get the maximum file upload size in KB
     */
    public static function getMaxUploadSizeKB(): int
    {
        $maxSize = min(
            (int) ini_get('upload_max_filesize'),
            (int) ini_get('post_max_size'),
            (int) ini_get('memory_limit')
        );
        
        // Convert to KB if in MB or GB
        if (strpos(ini_get('upload_max_filesize'), 'M') !== false) {
            $maxSize = (int) ini_get('upload_max_filesize') * 1024;
        } elseif (strpos(ini_get('upload_max_filesize'), 'G') !== false) {
            $maxSize = (int) ini_get('upload_max_filesize') * 1024 * 1024;
        }
        
        return $maxSize;
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