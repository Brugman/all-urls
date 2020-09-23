<?php

if ( !defined( 'ABSPATH' ) )
    exit;

/**
 * Controller.
 */

function timbrau_controller()
{
    switch ( $_GET['action'] )
    {
        case 'display':
            timbrau_page_display();
            break;
        case 'download':
            timbrau_page_download();
            break;
        default:
            timbrau_page_lobby();
            break;
    }
}

/**
 * Lobby.
 */

function timbrau_page_lobby()
{
    $link_display = admin_url( 'tools.php?page=timbrau&action=display' );
    $link_download = admin_url( 'tools.php?page=timbrau&action=download' );
?>
<div class="wrap fjh-wrapper">

    <h1><?php _e( 'All URLs', timbrau_textdomain() ); ?></h1>

    <p><a class="button" href="<?=$link_display;?>">Display all URLs</a><a class="button" href="<?=$link_download;?>">Download all URLs</a></p>

</div><!-- wrap -->
<?php
}

/**
 * Display.
 */

function timbrau_page_display()
{
    $all_urls = timbrau_all_urls();
?>
<div class="wrap fjh-wrapper">

    <h1><?php _e( 'All URLs', timbrau_textdomain() ); ?></h1>

<?php if ( !empty( $all_urls ) ): ?>

    <table class="wp-list-table widefat fixed striped" style="width: auto; margin-top: 16px;">
        <thead>
            <tr>
                <td><?php _e( 'Container', timbrau_textdomain() ); ?></td>
                <td><?php _e( 'Post Type / Taxonomy', timbrau_textdomain() ); ?></td>
                <td><?php _e( 'Permalink', timbrau_textdomain() ); ?></td>
            </tr>
        </thead>
        <tbody>
<?php foreach ( $all_urls as $url ): ?>
            <tr>
                <td><?=$url['container'];?></td>
                <td><?=$url['post_type'];?><?=$url['taxonomy'];?></td>
                <td><?=$url['permalink'];?></td>
            </tr>
<?php endforeach; // $all_urls ?>
        </tbody>
    </table>

<?php else: // $all_urls is empty ?>

    <p><?php _e( 'Not a single permalink was found. Strange!', timbrau_textdomain() ); ?></p>

<?php endif; // $all_urls ?>

</div><!-- wrap -->
<?php
}

/**
 * Download.
 */

function timbrau_page_download()
{
?>
<div class="wrap fjh-wrapper">

    <h1><?php _e( 'All URLs', timbrau_textdomain() ); ?></h1>

    <p>Download should be starting.</p>

<script>
window.location.href = '<?=admin_url( 'tools.php?page=timbrau&action=trigger_download' );?>';
</script>

</div><!-- wrap -->
<?php
}

