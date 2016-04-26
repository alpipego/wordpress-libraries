<?php

namespace Alpipego\WpLib;

class AcfBoilerplate extends Boilerplate
{
    public function __construct($file)
    {
        // check if acf is active on activation
        parent::__construct($file, $this->checkDependencies());

        // setup acf to import this plugins fields
        $this->includeFields();
    }

    private function includeFields()
    {
        $this->path = \plugin_basename($this->file);
        add_filter('acf/settings/load_json', function($paths) {
            $paths[] = $this->getPath() . '/inc';

            return $paths;
        }, 9);
    }

    private function checkDependencies()
    {
        add_action('admin_init', function() {
            if (!class_exists('\acf')) {
                deactivate_plugins($this->path);
                add_action('admin_notices', function() {
                    echo '<div class="error"><p>Please activate <a href="https://wordpress.org/plugins/advanced-custom-fields/" target="_blank">Advanced Custom Fields</a> first.</p></div>';
                });
            }
        });
    }
}
