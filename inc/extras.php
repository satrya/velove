<?php

/**
 * Custom functions that act independently of the theme templates
 * Eventually, some of the functionality here could be replaced by core features
 */

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function velove_pingback_header() {
    if (is_singular() && pings_open()) {
        echo '<link rel="pingback" href="', bloginfo('pingback_url'), '">';
    }
}
add_action('wp_head', 'velove_pingback_header');

/**
 * Adds custom classes to the array of body classes.
 */
function velove_body_classes($classes) {

    // Adds a class of multi-author to blogs with more than 1 published author.
    if (is_multi_author()) {
        $classes[] = 'multi-author';
    }

    // Adds a class if a post or page has featured image.
    if (has_post_thumbnail()) {
        $classes[] = 'has-featured-image';
    }

    // Adds a class for the container style.
    $container = get_theme_mod('velove_container_style', 'fullwidth');
    if ($container == 'fullwidth') {
        $classes[] = 'full-width-container';
    } elseif ($container == 'boxed') {
        $classes[] = 'boxed-container';
    } else {
        $classes[] = 'framed-container';
    }

    // Adds a class for the header style.
    $header = get_theme_mod('velove_header_style', 'default');
    if ($header == 'style_2') {
        $classes[] = 'header-style-two';
    } elseif ($header == 'style_3') {
        $classes[] = 'header-style-three';
    }

    // Adds a class for the blog layouts.
    $blog_layout = get_theme_mod('velove_blog_layouts', 'default');
    if (is_home()) {
        $classes[] = 'layout-' . sanitize_html_class($blog_layout);

        if (
            $blog_layout == 'grid-two-left-sidebar' ||
            $blog_layout == 'masonry-two-left-sidebar' ||
            $blog_layout == 'list-left-sidebar'
        ) {
            $classes[] = 'layout-left-sidebar';
        }

        if (
            $blog_layout == 'grid-three' ||
            $blog_layout == 'grid-four' ||
            $blog_layout == 'masonry-three' ||
            $blog_layout == 'masonry-four' ||
            $blog_layout == 'list-full-width'
        ) {
            $classes[] = 'layout-full-width';
        }

        if ($blog_layout == 'list-full-width-narrow') {
            $classes[] = 'layout-full-width-narrow';
        }
    }


    return $classes;
}
add_filter('body_class', 'velove_body_classes');

/**
 * Adds custom classes to the array of post classes.
 */
function velove_post_classes($classes) {

    // Adds a class if a post hasn't a thumbnail.
    if (has_post_thumbnail()) {
        $classes[] = 'has-post-thumbnail';
    }

    // Replace hentry class with entry.
    $classes[] = 'entry';

    // Adds a class if the post author box disable
    if (is_single()) {
        if (!get_the_author_meta('description')) {
            $classes[] = 'post-author-box-disabled';
        }
    }

    return $classes;
}
add_filter('post_class', 'velove_post_classes');

/**
 * Remove 'hentry' from post_class()
 */
function velove_remove_hentry($class) {
    $class = array_diff($class, array('hentry'));
    return $class;
}
add_filter('post_class', 'velove_remove_hentry');

/**
 * Change the excerpt more string.
 */
function velove_excerpt_more($more) {
    return '&hellip;';
}
add_filter('excerpt_more', 'velove_excerpt_more');

/**
 * Filter the except length to 20 words.
 */
function velove_custom_excerpt_length($length) {
    return 28;
}
add_filter('excerpt_length', 'velove_custom_excerpt_length', 999);

/**
 * Extend archive title
 */
function velove_extend_archive_title($title) {
    if (is_category()) {
        $title = single_cat_title('', false);
    } elseif (is_tag()) {
        $title = single_tag_title('', false);
    } elseif (is_author()) {
        $title = get_the_author();
    }
    return $title;
}
add_filter('get_the_archive_title', 'velove_extend_archive_title');

/**
 * Customize tag cloud widget
 */
function velove_customize_tag_cloud($args) {
    $args['largest']  = 13;
    $args['smallest'] = 13;
    $args['unit']     = 'px';
    $args['number']   = 20;
    return $args;
}
add_filter('widget_tag_cloud_args', 'velove_customize_tag_cloud');

/**
 * Modifies the theme layout.
 */
function velove_mod_theme_layout($layout) {

    // Reset the layout, use the blog layout
    if (is_home()) {
        $post_layout = get_post_layout(get_queried_object_id());
        $blog_layout = get_theme_mod('velove_blog_layouts', 'default');

        if ('default' == $post_layout) {
            $layout = $blog_layout;
        }
    }

    // Change the layout to Full Width.
    if (is_404() || is_archive() || is_search() || is_attachment()) {
        $post_layout = get_post_layout(get_queried_object_id());
        if ('default' == $post_layout) {
            $layout = 'full-width';
        }
    }

    // Change the layout to Full Width Narrow.
    if (!is_active_sidebar('primary')) {
        if (is_single() || is_page() || is_home()) {
            $post_layout = get_post_layout(get_queried_object_id());
            if ('default' == $post_layout) {
                $layout = 'full-width-narrow';
            }
        }
    }

    return $layout;
}
add_filter('theme_mod_theme_layout', 'velove_mod_theme_layout', 15);

/**
 * Remove theme-layouts meta box on attachment and bbPress post type.
 */
function velove_remove_theme_layout_metabox() {
    remove_post_type_support('attachment', 'theme-layouts');

    // bbPress
    remove_post_type_support('forum', 'theme-layouts');
    remove_post_type_support('topic', 'theme-layouts');
    remove_post_type_support('reply', 'theme-layouts');
}
add_action('init', 'velove_remove_theme_layout_metabox', 11);

/**
 * Exclude featured post from the main query
 */
function velove_exclude_feature_posts($query) {

    // Get the tag id
    $name = get_theme_mod('velove_featured_posts_tag', 'featured');

    // Set empty variable
    $term = '';

    if ($name) {
        $term = get_term_by('name', $name, 'post_tag');
    }

    if (!is_admin() && $query->is_home() && $query->is_main_query()) {

        if ($term != '') {
            $taxquery = array(
                array(
                    'taxonomy' => 'post_tag',
                    'field'    => 'id',
                    'terms'    => array($term->term_id),
                    'operator' => 'IN'
                )
            );

            $posts = get_posts(array(
                'tax_query' => $taxquery
            ));

            $query->set('post__not_in', array($posts[0]->ID));
        } else {
            $query->set('ignore_sticky_posts', 1);
        }
    }
}
add_action('pre_get_posts', 'velove_exclude_feature_posts');
