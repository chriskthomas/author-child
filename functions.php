<?php

/*
// Load main css for parent and child theme (only needed if we don't want to build a new style.css file)
add_action('wp_enqueue_scripts', 'my_theme_enqueue_styles');
function my_theme_enqueue_styles()
{
    $parenthandle = 'ct-author-style'; // This is 'twentyfifteen-style' for the Twenty Fifteen theme.
    $theme = wp_get_theme();
    // Parent theme css
    wp_enqueue_style($parenthandle,
        get_template_directory_uri() . '/style.min.css',
        array(), // If the parent theme code has a dependency, copy it to here.
        $theme->parent()->get('Version')
    );
    // Child theme css
    wp_enqueue_style('child-style',
        get_stylesheet_uri(),
        array($parenthandle),
        $theme->get('Version') // This only works if you have Version defined in the style header.
    );
}
*/

// Minify CSS
add_action('wp_enqueue_scripts', 'ckt_minify_css', 20);
function ckt_minify_css()
{
    // Remove parent theme css
    wp_dequeue_style('ct-author-style');
    // Add child theme css
    wp_enqueue_style('author-child-style',
        get_stylesheet_directory_uri() . '/style.min.css',
        array(),
        wp_get_theme()->get('Version')
    );
}

// Remove fonts from parent theme
add_action('wp_enqueue_scripts', 'ckt_remove_parent_styles', 20);
function ckt_remove_parent_styles()
{
    // Google fonts
    wp_dequeue_style('ct-author-google-fonts');
    // Font Awesome
    wp_dequeue_style('ct-author-font-awesome');
}

// Customize footer
function author_footer_callback()
{
    return empty(get_bloginfo('description')) ? get_bloginfo('name') : get_bloginfo('name') . " | " . get_bloginfo('description');
}
add_filter('ct_author_footer_text', 'author_footer_callback');

//Remove Gutenberg Block Library CSS from loading on the frontend
function remove_wp_block_library_css(){
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
} 
add_action( 'wp_enqueue_scripts', 'remove_wp_block_library_css', 100 );
