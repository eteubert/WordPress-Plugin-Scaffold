<?php
/*
Plugin Name: <%= name %>
Plugin URI:
Description:
Author: 
Version: 1.0
Author URI:
*/

if (!function_exists('<%= name.slug %>_register_frontend_scripts')) {
  function <%= name.slug %>_register_frontend_scripts()
  {
    if (!is_admin()) {
      // stylesheets
      wp_register_style('<%= name.slug %>_style',
        WP_PLUGIN_URL . '/<%= name.slug %>/public/css/<%= name.slug %>.css');
      wp_enqueue_style('<%= name.slug %>_style');
      
      // javascript
      wp_enqueue_script(
        '<%= name.slug %>_script',
          WP_PLUGIN_URL . '/<%= name.slug %>/public/javascript/<%= name.slug %>.js',
        array('jquery')
        );
        
      // declare the URL to the file that handles the AJAX request (wp-admin/admin-ajax.php)
      wp_localize_script(
        '<%= name.slug %>_locale', 
        '<%= name.slug %>_ajax',
        array(
          'url' => admin_url('admin-ajax.php')
        )
      );
    }
  }
}

load_theme_textdomain('<%= name.slug %>', dirname(__FILE__) . '/languages/');

add_action('init', '<%= name.slug %>_register_frontend_scripts');

// load and invoke controllers
require_once dirname(__FILE__) . '/app/controllers/ApplicationController.php';

// add your own controller requires here and then instanciate them anonymously
// e.g.:
// new FooController();

if (!function_exists('<%= name.slug %>_add_default_view_directory')) {
  function <%= name.slug %>_add_default_view_directory($directories)
  {
    $child_theme_view_dir = dirname(__FILE__) . '/app/views/';
    array_unshift($directories, $child_theme_view_dir);
    return $directories;
  }
}
add_filter('app-view-directories', '<%= name.slug %>_add_default_view_directory');