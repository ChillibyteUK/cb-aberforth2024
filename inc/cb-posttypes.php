<?php

function cb_register_post_types() {

    $labels = [
        "name" => "People",
        "singular_name" => "Person",
    ];

    $args = [
        "label" => "People",
        "labels" => $labels,
        "description" => "",
        "public" => true,
        "publicly_queryable" => true,
        "show_ui" => true,
        "show_in_rest" => true,
        "rest_base" => "",
        "rest_controller_class" => "WP_REST_Posts_Controller",
        "has_archive" => true,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "menu_icon" => "dashicons-open-folder",
        "delete_with_user" => false,
        "exclude_from_search" => false,
        "capability_type" => "post",
        "map_meta_cap" => true,
        "hierarchical" => false,
        "rewrite" => false,
        "query_var" => true,
        "supports" => [ "title",  "thumbnail", "editor" ],
        "show_in_graphql" => false,
        "exclude_from_search" => true
    ];

    register_post_type( "people", $args );

}

add_action( 'init', 'cb_register_post_types' );