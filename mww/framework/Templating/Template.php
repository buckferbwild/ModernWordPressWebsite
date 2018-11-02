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
        // Allows for subdirectory includes, such as "partials.header"
        $file = str_replace('.', '/', $file);

        $file_path = MWW_PATH . '/views/' .$file.'.php';

        if (file_exists($file_path)) {
            if (!empty($hook)) {
                do_action($hook);
            }
            if (empty($data)) {
                load_template($file_path, false);
            } else {
                // Merge our $data into $wp_query->query_vars so it's available with load_template
                global $wp_query;
                $original = $wp_query->query_vars;
                $this->checkForQueryVarsConflicts($original, $data);
                $wp_query->query_vars = array_merge($original, $data);
                load_template($file_path, false);
                $wp_query->query_vars = $original;
            }
        } else {
            if (defined('WP_DEBUG') && WP_DEBUG) {
                echo 'Error loading view: '.$file.'<br>';
                if (endswith($file, '/php')) {
                    echo 'Maybe you meant to add <strong>' . rtrim($file, '/php') . '</strong> instead?<br>';
                }
            }
        }
    }

    /**
    * Warns for conflicts with $data and $wp_query->query_vars
    */
    private function checkForQueryVarsConflicts($query_vars, $data)
    {
        foreach ($data as $key => &$value) {
        if (array_key_exists($key, $query_vars)) {
            $message = 'You should rename variable "' . $key . '", as it conflicts with existing $wp_query->query_vars["' . $key . '"] key.';
            error_log($message);
            if (defined('WP_DEBUG') && WP_DEBUG) {
                echo $message;
            }
        }
    }
    }
}
