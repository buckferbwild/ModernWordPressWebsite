<?php

namespace MWW\Templating;

class Template
{
    /**
     * Includes a template file
     *
     *  @param string $file Name of the view to load
     *  @param array $data Data to be passed to the view
     *  @param string $hook_to_fire Fires this hook upon loading this template
     */
    public function include($file, $data = [], string $hook_to_fire = '')
    {
        // Allow passing custom data to our view
        extract($data);

        // Allows for subdirectory includes, such as "partials.header"
        $file = str_replace('.', '/', $file);

        $file_path = MWW_PATH . '/views/' .$file.'.php';

        if (file_exists($file_path)) {
            if (!empty($hook)) {
                do_action($hook);
            }
            load_template($file_path);
        } else {
            echo 'Error loading view: '.$file.'<br>';
        }
    }
}
