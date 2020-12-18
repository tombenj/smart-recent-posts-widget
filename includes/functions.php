<?php

/**
 * Various functions used by the plugin.
 */

/**
 * Sets up the default arguments.
 */
function srpw_get_default_args() {

    $css_defaults = ".srpw-thumbnail{\nwidth: 60px;\nheight: 60px;\n}";

    $defaults = array(

        // General tab
        'title'            => esc_html__('Recent Posts', 'smart-recent-posts-widget'),
        'title_url'        => '',
        'css_class'        => '',
        'before'           => '',
        'after'            => '',

        // Posts tab
        'ignore_sticky'    => true,
        'exclude_current'  => true,
        'limit'            => 5,
        'offset'           => 0,
        'post_type'        => array('post'),
        'post_status'      => 'publish',
        'order'            => 'DESC',
        'orderby'          => 'date',

        // Taxonomy tab
        'cat'              => array(),
        'tag'              => array(),
        'cat_exclude'      => array(),
        'tag_exclude'      => array(),

        // Thumbnail tab
        'thumbnail'         => true,
        'thumbnail_size'    => 'thumbnail',
        'thumbnail_default' => '//placehold.it/45x45/f0f0f0/ccc',
        'thumbnail_align'   => 'srpw-alignleft',

        // Excerpt tab
        'excerpt'          => false,
        'length'           => 10,
        'readmore'         => false,
        'readmore_text'    => esc_html__('Read More &raquo;', 'smart-recent-posts-widget'),

        // Display tab
        'post_title'       => true,
        'date'             => true,
        'date_relative'    => false,
        'date_modified'    => false,
        'comment_count'    => false,
        'author'           => false,

        // Appearance tab
        'style'            => 'default',
        'new_tab'          => false,
        'css'              => $css_defaults,
    );

    // Allow plugins/themes developer to filter the default arguments.
    return apply_filters('srpw_default_args', $defaults);
}

/**
 * Outputs the recent posts.
 */
function srpw_recent_posts($args = array()) {
    echo srpw_get_recent_posts($args);
}

/**
 * Generates the posts markup.
 */
function srpw_get_recent_posts($args = array()) {

    // Set up a default, empty variable.
    $html = '';

    // Merge the input arguments and the defaults.
    $args = wp_parse_args($args, srpw_get_default_args());

    // Extract the array to allow easy use of variables.
    extract($args);

    // Allow devs to hook in stuff before the loop.
    do_action('srpw_before_loop');

    // Link target
    $target = '_self';
    if ($args['new_tab']) {
        $target = '_blank';
    }

    // Get the posts query.
    $posts = srpw_get_posts($args);

    if ($posts->have_posts()) :

        // Recent posts wrapper
        $html = '<div class="srpw-block srpw-' . sanitize_html_class($args['style']) . '-style ' . (!empty($args['css_class']) ? '' . sanitize_html_class($args['css_class']) . '' : '') . '">';

        // Custom CSS.
        if (!empty($args['css'])) {
            $html .= '<style>' . $args['css'] . '</style>';
        }

        $html .= '<ul class="srpw-ul">';

        while ($posts->have_posts()) : $posts->the_post();

            // Start recent posts markup.
            $html .= '<li class="srpw-li srpw-clearfix">';

            if ($args['thumbnail']) :

                // Check if post has post thumbnail.
                if (has_post_thumbnail()) :
                    $html .= '<a class="srpw-img ' . esc_attr($args['thumbnail_align']) . '" href="' . esc_url(get_permalink()) . '" target="' . $target . '">';
                    $html .= get_the_post_thumbnail(
                        get_the_ID(),
                        $args['thumbnail_size'],
                        array(
                            'class' => ' srpw-thumbnail',
                            'alt'   => esc_attr(get_the_title())
                        )
                    );
                    $html .= '</a>';

                // Display default image.
                elseif (!empty($args['thumbnail_default'])) :
                    $html .= sprintf(
                        '<a class="srpw-img ' . esc_attr($args['thumbnail_align']) . '" href="%1$s" target="' . $target . '" rel="bookmark"><img class="srpw-thumbnail srpw-default-thumbnail" src="%2$s" alt="%3$s"></a>',
                        esc_url(get_permalink()),
                        esc_url($args['thumbnail_default']),
                        esc_attr(get_the_title())
                    );

                endif;

            endif;

            $html .= '<div class="srpw-content">';

            if ($args['post_title']) :
                $html .= '<a class="srpw-title" href="' . esc_url(get_permalink()) . '" target="' . $target . '">' . esc_attr(get_the_title()) . '</a>';
            endif;

            $html .= '<div class="srpw-meta">';

            if ($args['date']) :
                $date = get_the_date();
                if ($args['date_relative']) :
                    $date = sprintf(esc_html__('%s ago', 'smart-recent-posts-widget'), human_time_diff(get_the_date('U'), current_time('timestamp')));
                endif;
                $html .= '<time class="srpw-time published" datetime="' . esc_html(get_the_date('c')) . '">' . esc_html($date) . '</time>';

            elseif ($args['date_modified']) : // if both date functions are provided, we use date to be backwards compatible
                $date = get_the_modified_date();
                if ($args['date_relative']) :
                    $date = sprintf(esc_html__('%s ago', 'smart-recent-posts-widget'), human_time_diff(get_the_modified_date('U'), current_time('timestamp')));
                endif;
                $html .= '<time class="srpw-time modified" datetime="' . esc_html(get_the_modified_date('c')) . '">' . esc_html($date) . '</time>';
            endif;

            if ($args['comment_count']) :
                if (get_comments_number() == 0) {
                    $comments = esc_html__('No Comments', 'smart-recent-posts-widget');
                } elseif (get_comments_number() > 1) {
                    $comments = sprintf(esc_html__('%s Comments', 'smart-recent-posts-widget'), get_comments_number());
                } else {
                    $comments = esc_html__('1 Comment', 'smart-recent-posts-widget');
                }
                $html .= '<a class="srpw-comment comment-count" href="' . get_comments_link() . '" target="' . $target . '">' . $comments . '</a>';
            endif;

            if ($args['author']) :
                $html .= '<a class="srpw-author" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '" target="' . $target . '">' . get_the_author() . '</a>';
            endif;

            $html .= "</div>";

            if ($args['excerpt']) :
                $html .= '<div class="srpw-summary">';
                $html .= '<p>' . wp_trim_words(apply_filters('srpw_excerpt', get_the_excerpt()), $args['length']) . '</p>';
                if ($args['readmore']) :
                    $html .= '<a href="' . esc_url(get_permalink()) . '" class="srpw-more-link" target="' . $target . '">' . $args['readmore_text'] . '</a>';
                endif;
                $html .= '</div>';
            endif;

            $html .= '</div>';

            $html .= '</li>';

        endwhile;

        $html .= '</ul>';

        $html .= '</div><!-- Generated by http://wordpress.org/plugins/smart-recent-posts-widget/ -->';

    endif;

    // Restore original Post Data.
    wp_reset_postdata();

    // Allow devs to hook in stuff after the loop.
    do_action('srpw_after_loop');

    // Return the  posts markup.
    return $args['before'] . apply_filters('srpw_markup', $html) . $args['after'];
}

/**
 * The posts query.
 */
function srpw_get_posts($args = array()) {

    // Query arguments.
    $query = array(
        'offset'              => $args['offset'],
        'posts_per_page'      => $args['limit'],
        'orderby'             => $args['orderby'],
        'order'               => $args['order'],
        'post_type'           => $args['post_type'],
        'post_status'         => $args['post_status'],
        'ignore_sticky_posts' => $args['ignore_sticky'],
    );

    // Exclude current post
    if ($args['exclude_current']) {
        $query['post__not_in'] = array(get_the_ID());
    }

    // Include posts based on selected categories.
    if (!empty($args['cat'])) {
        $query['category__in'] = $args['cat'];
    }

    // Include posts based on selected post tags.
    if (!empty($args['tag'])) {
        $query['tag__in'] = $args['tag'];
    }

    // Exlucde posts based on selected categories.
    if (!empty($args['cat_exclude'])) {
        $query['category__not_in'] = $args['cat_exclude'];
    }

    // Exclude posts based on selected post tags.
    if (!empty($args['tag_exclude'])) {
        $query['tag__not_in'] = $args['tag_exclude'];
    }

    // Allow plugins/themes developer to filter the default query.
    $query = apply_filters('srpw_default_query_arguments', $query);

    // Perform the query.
    $posts = new WP_Query($query);

    return $posts;
}
