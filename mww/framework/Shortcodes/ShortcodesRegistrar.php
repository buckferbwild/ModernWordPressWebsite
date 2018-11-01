<?php

namespace MWW\Shortcodes;

use DirectoryIterator;

/**
 * Class ShortcodesRegistrar
 * @package MWW\Shortcodes;
 */
class ShortcodesRegistrar
{
    public function registerAll()
    {
        $dir = new DirectoryIterator(MWW_PATH . '/app/Shortcodes');
        foreach ($dir as $key => $file) {
            $class = $file->getBasename('.php');
            if (endsWith($class, 'Shortcode')) {
                $class = '\\App\\Shortcodes\\' . $class;
                new $class;
            }
        }
    }
}
