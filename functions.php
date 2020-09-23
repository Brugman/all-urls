<?php

if ( !defined( 'ABSPATH' ) )
    exit;

function timbrau_textdomain()
{
    return 'timbrau';
}

function timbrau_get_post_types()
{
    return get_post_types( [ 'public' => true ] );
}

function timbrau_get_attachment_permalinks()
{
    $permalinks = [];

    $ids = get_posts([
        'post_type'      => 'attachment',
        'posts_per_page' => -1,
        'fields'         => 'ids',
    ]);

    foreach ( $ids as $id )
        $permalinks[] = wp_get_attachment_url( $id );

    return $permalinks;
}

function timbrau_get_post_type_permalinks( $post_type )
{
    if ( $post_type == 'attachment' )
        return timbrau_get_attachment_permalinks();

    $permalinks = [];

    $multilingual = ( function_exists( 'icl_object_id' ) && is_post_type_translated( $post_type ) );

    if ( !$multilingual )
    {
        $ids = get_posts([
            'post_type'      => $post_type,
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'fields'         => 'ids',
        ]);

        foreach ( $ids as $id )
            $permalinks[] = get_permalink( $id );
    }

    if ( $multilingual )
    {
        $wpml_langauges = apply_filters( 'wpml_active_languages', NULL, [] );

        foreach ( $wpml_langauges as $lang )
        {
            do_action( 'wpml_switch_language', $lang['code'] );

            $ids = get_posts([
                'post_type'      => $post_type,
                'post_status'    => 'publish',
                'posts_per_page' => -1,
                'fields'         => 'ids',
            ]);

            foreach ( $ids as $id )
                $permalinks[] = get_permalink( $id );
        }

        do_action( 'wpml_switch_language', NULL );
    }

    return $permalinks;
}

function timbrau_get_taxonomies()
{
    return get_taxonomies( [ 'public' => true ] );
}

function timbrau_get_taxonomy_permalinks( $taxonomy )
{
    $permalinks = [];

    $multilingual = ( function_exists( 'icl_object_id' ) && is_taxonomy_translated( $taxonomy ) );

    if ( !$multilingual )
    {
        $ids = get_terms([
            'taxonomy'   => $taxonomy,
            'hide_empty' => false,
            'fields'     => 'ids',
        ]);

        foreach ( $ids as $id )
            $permalinks[] = get_term_link( $id, $taxonomy );
    }

    if ( $multilingual )
    {
        $wpml_langauges = apply_filters( 'wpml_active_languages', NULL, [] );

        foreach ( $wpml_langauges as $lang )
        {
            do_action( 'wpml_switch_language', $lang['code'] );

            $ids = get_terms([
                'taxonomy'   => $taxonomy,
                'hide_empty' => false,
                'fields'     => 'ids',
            ]);

            foreach ( $ids as $id )
                $permalinks[] = get_term_link( $id, $taxonomy );
        }

        do_action( 'wpml_switch_language', NULL );
    }

    return $permalinks;
}

function timbrau_all_urls()
{
    $all_urls = [];

    $post_types = timbrau_get_post_types();

    foreach ( $post_types as $post_type )
    {
        $permalinks = timbrau_get_post_type_permalinks( $post_type );

        foreach ( $permalinks as $permalink )
        {
            $all_urls[] = [
                'container' => 'Post Type',
                'post_type' => $post_type,
                'taxonomy'  => false,
                'permalink' => $permalink,
            ];
        }
    }

    $taxonomies = timbrau_get_taxonomies();

    foreach ( $taxonomies as $taxonomy )
    {
        $permalinks = timbrau_get_taxonomy_permalinks( $taxonomy );

        foreach ( $permalinks as $permalink )
        {
            $all_urls[] = [
                'container' => 'Taxonomy',
                'post_type' => false,
                'taxonomy'  => $taxonomy,
                'permalink' => $permalink,
            ];
        }
    }

    return $all_urls;
}

function timbrau_convert_to_csv( $array )
{
    $csv = '';

    foreach ( $array as $line )
    {
        foreach ( $line as $cell )
            $csv .= $cell.",";

        $csv .= "\n";
    }

    return $csv;
}

function timbrau_clean_file_name( $string )
{
    $string = preg_replace( '/[^A-Za-z0-9\-]/', '-', $string );
    $string = preg_replace( '/-+/', '-', $string );
    $string = trim( $string, '-' );
    $string = strtolower( $string );

    return $string;
}

function timbrau_trigger_download()
{
    $all_urls = timbrau_all_urls();

    $headings = [
        [
            'Container',
            'Post Type',
            'Taxonomy',
            'Permalink',
        ]
    ];

    $all_urls_csv = timbrau_convert_to_csv( array_merge( $headings, $all_urls ) );

    $site_url = parse_url( get_option( 'siteurl' ), PHP_URL_HOST );
    $filename = $site_url.'-all-urls-'.date( 'Y-m-d-Hi' ).'.csv';

    header( 'Content-Type: application/csv' );
    header( 'Content-Disposition: attachment; filename="'.$filename.'"' );
    echo $all_urls_csv;
    exit;
}

