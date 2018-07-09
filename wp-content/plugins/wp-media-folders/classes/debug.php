<?php
/* Prohibit direct script loading */
defined('ABSPATH') || die('No direct script access allowed!');

/**
 * Class WP_Media_Folders_Debug
 *
 * Handles debuging and logging features
 */
class WP_Media_Folders_Debug
{
    /**
     * If debug is enabled or not
     *
     * @var boolean
     */
    public static $debug_enabled = false;

    /**
     * Debug file
     *
     * @var string
     */
    public static $debug_file;

    /**
     * Log into a debug file
     *
     * @return void
     */
    public static function log()
    {
        // Do nothing if not enabled
        if (!self::$debug_enabled) {
            return;
        }

        // Retrieve arguments passed
        $args = func_get_args();

        // Check that we have at least a string to log
        if ($args<2) {
            return;
        }

        $dir = dirname(self::$debug_file);
        if (!$dir) {
            mkdir($dir, 0777, true);
        }

        if (!file_exists(self::$debug_file)) {
            file_put_contents(self::$debug_file, '<?php die(); ?>'.PHP_EOL);
        }

        $arguments = array();
        // phpcs:ignore Generic.CodeAnalysis.ForLoopWithTestFunctionCall -- Allow using count
        for ($ij=1; $ij < count($args); $ij++) {
            $arguments[] = $args[$ij];
        }

        $fp = fopen(self::$debug_file, 'a+');
        fwrite($fp, vsprintf($args[0], $arguments).PHP_EOL);
        fclose($fp);
    }
}
