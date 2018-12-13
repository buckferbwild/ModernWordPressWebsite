<?php

namespace MWW\Container;

/**
 * Class MWW_Container
 */
class MWW_Container extends \tad_DI52_Container {
    /**
     * @var MWW_Container
     */
    protected static $instance;

    /**
     * @return MWW_Container
     */
    public static function init() {
        if ( empty( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Registers the bindings from the bindings file
     */
    public function registerBindings()
    {
        /** File that declares $singletons and $registers arrays */
        require_once(MWW_PATH . '/app/bindings.php');

        foreach ($singletons as $slug => $class) {
            mww_singleton($slug, $class);
        }

        foreach ($registers as $slug => $class) {
            mww_register($slug, $class);
        }

    }
}