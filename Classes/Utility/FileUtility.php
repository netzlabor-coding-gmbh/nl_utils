<?php

declare(strict_types=1);

namespace NL\NlUtils\Utility;

class FileUtility
{
    /**
     * @param string $name
     * @param string $content
     * @return string
     */
    public static function temporaryFile(string $name, string $content): string
    {
        $file = DIRECTORY_SEPARATOR .
            trim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) .
            DIRECTORY_SEPARATOR .
            ltrim($name, DIRECTORY_SEPARATOR);

        file_put_contents($file, $content);

        register_shutdown_function(function() use($file) {
            unlink($file);
        });

        return $file;
    }
}
