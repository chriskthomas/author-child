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
add_action('wp_print_styles', 'ckt_minify_css', 20);
function ckt_minify_css()
{
    // Remove parent theme css
    wp_dequeue_style('ct-author-style');
    // Add child theme css
    wp_enqueue_style(
        'author-child-style',
        get_stylesheet_directory_uri() . '/style.min.css',
        array(),
        wp_get_theme()->get('Version')
    );
}

// Remove fonts from parent theme
add_action('wp_print_styles', 'ckt_remove_parent_styles', 20);
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
    return empty(get_option('admin_email', false)) ? get_bloginfo('name') : get_bloginfo('name') . ' | <a href="mailto:' . get_option('admin_email') . '">' . get_option('admin_email') . '</a>';
}
add_filter('ct_author_footer_text', 'author_footer_callback');

// Remove Gutenberg Block Library CSS from loading on the frontend
function remove_wp_block_library_css()
{
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
}
add_action('wp_print_styles', 'remove_wp_block_library_css', 100);

/**
 * This function will connect wp_mail to your authenticated
 * SMTP server. This improves reliability of wp_mail, and 
 * avoids many potential problems.
 *
 * For instructions on the use of this script, see:
 * https://butlerblog.com/easy-smtp-email-wordpress-wp_mail/
 * 
 * Values for constants are set in wp-config.php
 */
add_action('phpmailer_init', 'send_smtp_email');
function send_smtp_email($phpmailer)
{
    $phpmailer->isSMTP();
    $phpmailer->Host       = SMTP_HOST;
    $phpmailer->SMTPAuth   = SMTP_AUTH;
    $phpmailer->Port       = SMTP_PORT;
    $phpmailer->Username   = SMTP_USER;
    $phpmailer->Password   = SMTP_PASS;
    $phpmailer->SMTPSecure = SMTP_SECURE;
    $phpmailer->From       = SMTP_FROM;
    $phpmailer->FromName   = SMTP_NAME;
}

// 404 on date and author pages
add_action('template_redirect', 'ckt_archive_404');
function ckt_archive_404() {
    if (is_date() || is_author()) {
        global $wp_query;
        $wp_query->set_404();
    }
}
