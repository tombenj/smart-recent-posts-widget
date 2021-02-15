<?php

/**
 * Function helper.
 */

/**
 * Display list of taxonomy for widget.
 */
function srpw_taxonomy_list($tax = 'post_tag') {

    // Arguments
    $args = array(
        'number' => 99
    );

    // Allow dev to filter the arguments
    $args = apply_filters('srpw_tags_list_args', $args);

    // Get the tags
    $tags = get_terms($tax, $args);

    return $tags;
}
