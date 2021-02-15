<?php

/**
 * Plugin Name:  Smart Recent Posts Widget
 * Plugin URI:   https://idenovasi.com/projects/smart-recent-posts-widget/
 * Description:  Enables advanced widget that gives you total control over the output of your site’s most recent Posts.
 * Version:      1.0.1
 * Author:       Idenovasi
 * Author URI:   https://idenovasi.com/
 * Author Email: satrya@idenovasi.com
 * Text Domain:  smart-recent-posts-widget
 * Domain Path:  /languages
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License as published by the Free Software Foundation; either version 2 of the License,
 * or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write
 * to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class SMART_RPW {

    /**
     * Constructor method.
     */
    public function __construct() {

        // Set the constants needed by the plugin.
        add_action('plugins_loaded', array(&$this, 'constants'), 1);

        // Internationalize the text strings used.
        add_action('plugins_loaded', array(&$this, 'i18n'), 2);

        // Load the functions files.
        add_action('plugins_loaded', array(&$this, 'includes'), 3);

        // Load the admin style and script.
        add_action('admin_enqueue_scripts', array(&$this, 'admin_scripts'));
        add_action('customize_controls_enqueue_scripts', array(&$this, 'admin_scripts'));

        // Register widget.
        add_action('widgets_init', array(&$this, 'register_widget'));

        // Enqueue the front-end styles.
        add_action('wp_enqueue_scripts', array(&$this, 'plugin_style'), 99);
    }

    /**
     * Defines constants used by the plugin.
     */
    public function constants() {

        // Set constant path to the plugin directory.
        define('SRPW_DIR', trailingslashit(plugin_dir_path(__FILE__)));

        // Set the constant path to the plugin directory URI.
        define('SRPW_URI', trailingslashit(plugin_dir_url(__FILE__)));

        // Set the constant path to the includes directory.
        define('SRPW_INCLUDES', SRPW_DIR . trailingslashit('includes'));

        // Set the constant path to the assets directory.
        define('SRPW_ASSETS', SRPW_URI . trailingslashit('assets'));
    }

    /**
     * Loads the translation files.
     */
    public function i18n() {
        load_plugin_textdomain('smart-recent-posts-widget', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    /**
     * Loads the initial files needed by the plugin.
     */
    public function includes() {
        require_once(SRPW_INCLUDES . 'functions.php');
        require_once(SRPW_INCLUDES . 'helpers.php');
        require_once(SRPW_INCLUDES . 'widget.php');
    }

    /**
     * Register custom style and script for the widget settings.
     */
    public function admin_scripts() {
        wp_enqueue_style('srpw-admin-style', trailingslashit(SRPW_ASSETS) . 'css/srpw-admin.css', null, null);
        wp_enqueue_script('srpw-cookie-script', trailingslashit(SRPW_ASSETS) . 'js/cookie.js', array('jquery-ui-tabs'));
    }

    /**
     * Register the widget.
     */
    public function register_widget() {
        register_widget('SMART_RECENT_POSTS_WIDGET');
    }

    /**
     * Enqueue front-end style.
     */
    public function plugin_style() {
        wp_enqueue_style('srpw-style', trailingslashit(SRPW_ASSETS) . 'css/srpw-frontend.css');
    }
}

new SMART_RPW;
