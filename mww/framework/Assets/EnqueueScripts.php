<?php

namespace MWW\Assets;

class EnqueueScripts
{
    /**
    *   Enqueues a Single CSS
    *   https://developer.wordpress.org/reference/functions/wp_enqueue_style/
    *
    *   @param string $file      Name of the file
    *   @param array $dependency Will load after selected handlers
    *   @param string $path      Path to load from, starting from MWW_PATH
    */
    public function enqueueStyle(
        string $file,
        array $dependency = array(),
        string $path = '/public/css/'
    ) {
        wp_enqueue_style(
            $file,
            get_mww_url() . $path . $file,
            $dependency,
            filemtime(get_mww_path() . $path . $file)
        );
    }

    /**
    *   Enqueues a Single Remote CSS
    *   https://developer.wordpress.org/reference/functions/wp_enqueue_style/
    *
    *   @param string $name      Name of the handler, example: bootstrap
    *   @param string $url       URL of the remote resource
    *   @param array $dependency Will load after selected handlers
    */
    public function enqueueRemoteStyle(
        string $name,
        string $url,
        array $dependency = array()
    ) {
        wp_enqueue_style(
            $name,
            $url,
            $dependency
        );
    }

    /**
    *   Enqueues a Single Javascript
    *   https://developer.wordpress.org/reference/functions/wp_enqueue_script/
    *
    *   @param string $file       Name of the file
    *   @param array $dependency  Will load after selected handlers
    *   @param string $path       Path to load from, starting from MWW_PATH
    *   @param boolean $in_footer Wether to load the script in the footer or not
    */
    public function enqueueJavascript(
        string $file,
        array $dependency = array('jquery'),
        string $path = '/public/js/',
        bool $in_footer = true
    ) {
        wp_enqueue_script(
            $file,
            get_mww_url() . $path . $file,
            $dependency,
            filemtime(get_mww_path() . $path . $file),
            $in_footer
        );
    }

    /**
    *   Enqueues a Remote Javascript
    *   @param string $file       Name of the handler, example: bootstrap-js
    *   @param string $url        URL of the remote resource
    *   @param array $dependency  Will load after selected handlers
    *   @param boolean $in_footer Wether to load the script in the footer or not
    */
    public function enqueueRemoteJavascript(
        string $name,
        string $url,
        array $dependency = array('jquery'),
        bool $in_footer = true
    ) {
        wp_enqueue_script(
            $name,
            $url,
            $dependency,
            null,
            $in_footer
        );
    }
}
