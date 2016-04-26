<?php

namespace Alpipego\WpLib;

class Boilerplate
{
    protected $file;
    protected $path;

    public function __construct($file, $func = null)
    {
        // parent variable
        $this->file = $file;

        // check if acf plugin is active and pass custom (static) activation function on activation
        $this->activationHook($func);
    }

    public function getPath()
    {
        $path = explode('/', $this->path);

        return trailingslashit(WP_PLUGIN_DIR) . $path[0];
    }

    private function activationHook($func)
    {
        \register_activation_hook($this->file, function() use ($func) {
            if (!is_null($func)) {
                call_user_func($func);
            }
        });
    }
}
