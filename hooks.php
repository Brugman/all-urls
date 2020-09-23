<?php

if ( !defined( 'ABSPATH' ) )
    exit;

/**
 * Register menu items.
 */

add_action( 'admin_menu', function () {
    add_management_page(
        'All URLs', // page title
        'All URLs', // menu title
        'manage_options', // capability
        'timbrau', // menu slug
        'timbrau_controller', // function
        null // position
    );
});

/**
 * Trigger download.
 */

add_action( 'admin_init', function ( $foo ) {

    if ( $_GET['page'] == 'timbrau' && $_GET['action'] == 'trigger_download' )
    {
        if ( current_user_can( 'manage_options' ) )
        {
            timbrau_trigger_download();
            exit;
        }
    }
});

