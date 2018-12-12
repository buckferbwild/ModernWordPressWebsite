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
}