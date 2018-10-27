<?php

namespace MWW;

class Template
{
    /**
     * Includes a template file
     *
     *  @param string $file Name of the view to load
     *  @param array $data Data to be passed to the view
     */
    public function include($file, $data = [])
    {
        // Allow passing custom data to our view
        extract($data);

        // Allows for subdirectory includes, such as "partials.header"
        $file = str_replace('.', '/', $file);

        $file_path = get_mww_path() . '/views/' .$file.'.php';

        if (file_exists($file_path)) {
            include($file_path);
        } else {
            echo 'Error loading view: '.$file.'<br>';
        }
    }
}
