<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

function register_my_session() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}
add_action('init', 'register_my_session');

function myEndSession()
{
    unset($_SESSION["cart_item"]);
}


require 'update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://solusidesain-update-theme.netlify.app/scientina/theme.json',
    __FILE__, //Full path to the main plugin file or functions.php.
    'scientina'
);

if (!defined('WP_DEBUG')) {
    die('Direct access forbidden.');
}

function remove_dashboard_meta()
{
    remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
    remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
    remove_meta_box('dashboard_primary', 'dashboard', 'side');
    remove_meta_box('dashboard_secondary', 'dashboard', 'normal');
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
    remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side');
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
    remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
    remove_meta_box('dashboard_activity', 'dashboard', 'normal'); //since 3.8
    remove_meta_box('e-dashboard-overview', 'dashboard', 'normal');
}
add_action('admin_init', 'remove_dashboard_meta');

function add_dashboard_widget()
{
    wp_add_dashboard_widget("rss-feed", "Admin Scientina", "display_rss_dashboard_widget");
}

function display_rss_dashboard_widget()
{ ?>

    <h1>Selamat Datang di Scientinaskill.com</h1>
    <p>Halaman ini merupakan halaman admin</p>


<?php
}
add_action("wp_dashboard_setup", "add_dashboard_widget");


add_action('wp_enqueue_scripts', 'sctn_child_enqueue_styles', 99);
function sctn_child_enqueue_styles()
{
    // CSS
    wp_enqueue_style('style', get_stylesheet_directory_uri() . '/style.css');
    wp_enqueue_style('bs-css', get_template_directory_uri() . '/css/bootstrap.min.css');
    //JS
    wp_enqueue_script('bs-js', get_template_directory_uri() . '/js/bootstrap.min.js');     
}

add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
if (!current_user_can('administrator') && !is_admin()) {
  show_admin_bar(false);
}
}


/* -- SET VARIABLE -- */
function add_custom_query_vars_filter($vars)
{
    $vars[] .= 'harga';
    $vars[] .= 'post_id';
    $vars[] .= 'training_id';
    $vars[] .= 'status';
    $vars[] .= 'id';
    return $vars;
}
add_filter('query_vars', 'add_custom_query_vars_filter');
