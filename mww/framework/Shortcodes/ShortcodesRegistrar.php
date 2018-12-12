<?php

namespace MWW\Shortcodes;

/**
 * Class ShortcodesRegistrar
 * @package MWW\Shortcodes;
 */
class ShortcodesRegistrar
{
    /**
     * Registers all files in app\Shortcodes folder, ending with
     * "Shortcode.php" and extending ShortcodeAbstract class.
     */
    public function registerAll()
    {
        $dir = new \DirectoryIterator(MWW_PATH . '/app/Shortcodes');

        $shortcode_files = new \RegexIterator(
            $dir,
            '/Shortcode\.php$/',
            \RegexIterator::MATCH
        );

        foreach ($shortcode_files as $shortcode_file) {
            $class = '\\App\\Shortcodes\\' . $shortcode_file->getBasename('.php');
            try {
                $r = new \ReflectionClass($class);
                if ($r->isSubclassOf(Shortcode::class)) {
                    $r->newInstance();
                }
            } catch(\ReflectionException $e) {
                continue;
            }

        }
    }
}
