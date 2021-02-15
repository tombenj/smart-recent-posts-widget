<?php

/**
 * The custom recent posts widget.
 * This widget gives total control over the output to the user.
 */
class SMART_RECENT_POSTS_WIDGET extends WP_Widget {

    /**
     * Sets up the widgets.
     */
    public function __construct() {

        // Set up the widget options.
        $widget_options = array(
            'classname'   => 'widget_smart_recent_entries smart_recent_posts',
            'description' => __('An advanced widget that gives you total control over the output of your site’s most recent Posts.', 'smart-recent-posts-widget'),
            'customize_selective_refresh' => true
        );

        $control_options = array(
            'width'  => 450
        );

        // Create the widget.
        parent::__construct(
            'srpw_widget',                                         // $this->id_base
            __('Smart Recent Posts', 'smart-recent-posts-widget'), // $this->name
            $widget_options,                                       // $this->widget_options
            $control_options                                       // $this->control_options
        );

        $this->alt_option_name = 'widget_smart_recent_entries';
    }

    /**
     * Outputs the widget based on the arguments input through the widget controls.
     */
    public function widget($args, $instance) {
        if (!isset($args['widget_id'])) {
            $args['widget_id'] = $this->id;
        }

        // Get the recent posts
        $recent = srpw_get_recent_posts($instance);

        if ($recent) {

            // Output the theme's $before_widget wrapper.
            echo $args['before_widget'];

            // If both title and title url is not empty, display it.
            if (!empty($instance['title_url']) && !empty($instance['title'])) {
                echo $args['before_title'] . '<a href="' . esc_url($instance['title_url']) . '" title="' . esc_attr($instance['title']) . '">' . apply_filters('widget_title',  $instance['title'], $instance, $this->id_base) . '</a>' . $args['after_title'];

                // If the title not empty, display it.
            } elseif (!empty($instance['title'])) {
                echo $args['before_title'] . apply_filters('widget_title',  $instance['title'], $instance, $this->id_base) . $args['after_title'];
            }

            // Get the recent posts query.
            echo $recent;

            // Close the theme's widget wrapper.
            echo $args['after_widget'];
        }
    }

    /**
     * Updates the widget control options for the particular instance of the widget.
     */
    public function update($new_instance, $old_instance) {

        // Validate post_type submissions
        $name = get_post_types(array('public' => true), 'names');
        $types = array();
        foreach ($new_instance['post_type'] as $type) {
            if (in_array($type, $name)) {
                $types[] = $type;
            }
        }
        if (empty($types)) {
            $types[] = 'post';
        }

        $instance                     = $old_instance;

        // General tab
        $instance['title']            = sanitize_text_field($new_instance['title']);
        $instance['title_url']        = esc_url_raw($new_instance['title_url']);
        if (current_user_can('unfiltered_html')) {
            $instance['before'] = $new_instance['before'];
        } else {
            $instance['before'] = wp_kses_post($new_instance['before']);
        }
        if (current_user_can('unfiltered_html')) {
            $instance['after'] = $new_instance['after'];
        } else {
            $instance['after'] = wp_kses_post($new_instance['after']);
        }
        $instance['css_class']        = sanitize_html_class($new_instance['css_class']);

        // Posts tab
        $instance['ignore_sticky']    = isset($new_instance['ignore_sticky']) ? (bool) $new_instance['ignore_sticky'] : false;
        $instance['exclude_current']  = isset($new_instance['exclude_current']) ? (bool) $new_instance['exclude_current'] : false;
        $instance['limit']            = intval($new_instance['limit']);
        $instance['offset']           = intval($new_instance['offset']);
        $instance['order']            = esc_attr($new_instance['order']);
        $instance['orderby']          = esc_attr($new_instance['orderby']);
        $instance['post_type']        = $types;
        $instance['post_status']      = esc_attr($new_instance['post_status']);

        // Taxonomy tab
        $instance['cat']              = $new_instance['cat'];
        $instance['tag']              = $new_instance['tag'];
        $instance['cat_exclude']      = $new_instance['cat_exclude'];
        $instance['tag_exclude']      = $new_instance['tag_exclude'];

        // Thumbnail tab
        $instance['thumbnail']        = isset($new_instance['thumbnail']) ? (bool) $new_instance['thumbnail'] : false;
        $instance['thumbnail_size']   = esc_attr($new_instance['thumbnail_size']);
        $instance['thumbnail_default'] = esc_url_raw($new_instance['thumbnail_default']);
        $instance['thumbnail_align']  = esc_attr($new_instance['thumbnail_align']);

        // Excerpt tab
        $instance['excerpt']          = isset($new_instance['excerpt']) ? (bool) $new_instance['excerpt'] : false;
        $instance['length']           = intval($new_instance['length']);
        $instance['readmore']         = isset($new_instance['readmore']) ? (bool) $new_instance['readmore'] : false;
        $instance['readmore_text']    = sanitize_text_field($new_instance['readmore_text']);

        // Display tab
        $instance['post_title']       = isset($new_instance['post_title']) ? (bool) $new_instance['post_title'] : false;
        $instance['date']             = isset($new_instance['date']) ? (bool) $new_instance['date'] : false;
        $instance['date_relative']    = isset($new_instance['date_relative']) ? (bool) $new_instance['date_relative'] : false;
        $instance['date_modified']    = isset($new_instance['date_modified']) ? (bool) $new_instance['date_modified'] : false;
        $instance['comment_count']    = isset($new_instance['comment_count']) ? (bool) $new_instance['comment_count'] : false;
        $instance['author']           = isset($new_instance['author']) ? (bool) $new_instance['author'] : false;

        // Appearance tab
        $instance['style']            = esc_attr($new_instance['style']);
        $instance['new_tab']          = isset($new_instance['new_tab']) ? (bool) $new_instance['new_tab'] : false;
        $instance['css']              = $new_instance['css'];

        return $instance;
    }

    /**
     * Displays the widget control options in the Widgets admin screen.
     */
    public function form($instance) {

        // Merge the user-selected arguments with the defaults.
        $instance = wp_parse_args((array) $instance, srpw_get_default_args());

        // Extract the array to allow easy use of variables.
        extract($instance); ?>

        <script>
            jQuery(document).ready(function($) {

                // Cache selector in a variable
                // to improve speed.
                var $tabs = $('.srpw-form-tabs'),
                    $hor = $('.horizontal-tabs');

                // Initialize the jQuery UI tabs
                $tabs.tabs({
                    active: $.cookie('activetab'),
                    activate: function(event, ui) {
                        $.cookie('activetab', ui.newTab.index(), {
                            expires: 10
                        });
                    }
                }).addClass('ui-tabs-vertical');

                // Add custom class
                $tabs.closest('.widget-inside').addClass('srpw-bg');

                // Initialize the jQuery UI tabs
                $hor.tabs().addClass('ui-tabs-horizontal');

            });
        </script>

        <div class="srpw-form-tabs">

            <ul class="srpw-tabs">
                <li><a href="#tab-1"><?php esc_html_e('General', 'smart-recent-posts-widget'); ?></a></li>
                <li><a href="#tab-2"><?php esc_html_e('Posts', 'smart-recent-posts-widget'); ?></a></li>
                <li><a href="#tab-3"><?php esc_html_e('Taxonomy', 'smart-recent-posts-widget'); ?></a></li>
                <li><a href="#tab-4"><?php esc_html_e('Thumbnail', 'smart-recent-posts-widget'); ?></a></li>
                <li><a href="#tab-5"><?php esc_html_e('Excerpt', 'smart-recent-posts-widget'); ?></a></li>
                <li><a href="#tab-6"><?php esc_html_e('Display', 'smart-recent-posts-widget'); ?></a></li>
                <li><a href="#tab-7"><?php esc_html_e('Appearance', 'smart-recent-posts-widget'); ?></a></li>
                <li><a href="#tab-8"><?php esc_html_e('Support', 'smart-recent-posts-widget'); ?></a></li>
            </ul>

            <div class="srpw-tabs-content">

                <div id="tab-1" class="srpw-tab-content">

                    <p>
                        <label for="<?php echo $this->get_field_id('title'); ?>">
                            <?php esc_html_e('Title', 'smart-recent-posts-widget'); ?>
                        </label>
                        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
                    </p>

                    <p>
                        <label for="<?php echo $this->get_field_id('title_url'); ?>">
                            <?php esc_html_e('Title URL', 'smart-recent-posts-widget'); ?>
                        </label>
                        <input class="widefat" id="<?php echo $this->get_field_id('title_url'); ?>" name="<?php echo $this->get_field_name('title_url'); ?>" type="url" value="<?php echo esc_url($instance['title_url']); ?>" />
                    </p>

                    <p>
                        <label for="<?php echo $this->get_field_id('css_class'); ?>">
                            <?php esc_html_e('CSS Class', 'smart-recent-posts-widget'); ?>
                        </label>
                        <input class="widefat" id="<?php echo $this->get_field_id('css_class'); ?>" name="<?php echo $this->get_field_name('css_class'); ?>" type="text" value="<?php echo sanitize_html_class($instance['css_class']); ?>" />
                    </p>

                    <p>
                        <label for="<?php echo $this->get_field_id('before'); ?>">
                            <?php esc_html_e('HTML or text before the recent posts', 'smart-recent-posts-widget'); ?>
                        </label>
                        <textarea class="widefat" id="<?php echo $this->get_field_id('before'); ?>" name="<?php echo $this->get_field_name('before'); ?>" rows="5"><?php echo $instance['before']; ?></textarea>
                    </p>

                    <p>
                        <label for="<?php echo $this->get_field_id('after'); ?>">
                            <?php esc_html_e('HTML or text after the recent posts', 'smart-recent-posts-widget'); ?>
                        </label>
                        <textarea class="widefat" id="<?php echo $this->get_field_id('after'); ?>" name="<?php echo $this->get_field_name('after'); ?>" rows="5"><?php echo $instance['after']; ?></textarea>
                    </p>

                </div><!-- #tab-1 -->

                <div id="tab-2" class="srpw-tab-content">

                    <p>
                        <input class="checkbox" type="checkbox" <?php checked($instance['ignore_sticky'], 1); ?> id="<?php echo $this->get_field_id('ignore_sticky'); ?>" name="<?php echo $this->get_field_name('ignore_sticky'); ?>" />
                        <label for="<?php echo $this->get_field_id('ignore_sticky'); ?>">
                            <?php esc_html_e('Ignore sticky posts', 'smart-recent-posts-widget'); ?>
                        </label>
                    </p>

                    <p>
                        <input class="checkbox" type="checkbox" <?php checked($instance['exclude_current'], 1); ?> id="<?php echo $this->get_field_id('exclude_current'); ?>" name="<?php echo $this->get_field_name('exclude_current'); ?>" />
                        <label for="<?php echo $this->get_field_id('exclude_current'); ?>">
                            <?php esc_html_e('Exclude current post', 'smart-recent-posts-widget'); ?>
                        </label>
                    </p>

                    <p>
                        <label for="<?php echo $this->get_field_id('limit'); ?>">
                            <?php esc_html_e('Number of posts to show', 'smart-recent-posts-widget'); ?>
                        </label>
                        <input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="number" step="1" min="-1" value="<?php echo (int)($instance['limit']); ?>" />
                    </p>

                    <p>
                        <label for="<?php echo $this->get_field_id('offset'); ?>">
                            <?php esc_html_e('Offset', 'smart-recent-posts-widget'); ?>
                        </label>
                        <input class="widefat" id="<?php echo $this->get_field_id('offset'); ?>" name="<?php echo $this->get_field_name('offset'); ?>" type="number" step="1" min="0" value="<?php echo (int)($instance['offset']); ?>" />
                        <small><?php esc_html_e('The number of posts to skip', 'smart-recent-posts-widget'); ?></small>
                    </p>

                    <div class="srpw-multiple-check-form">
                        <label>
                            <?php esc_html_e('Post Types', 'smart-recent-posts-widget'); ?>
                        </label>
                        <ul>
                            <?php foreach (get_post_types(array('public' => true), 'objects') as $type) : ?>
                                <li>
                                    <input type="checkbox" value="<?php echo esc_attr($type->name); ?>" id="<?php echo $this->get_field_id('post_type') . '-' . $type->name; ?>" name="<?php echo $this->get_field_name('post_type'); ?>[]" <?php checked(is_array($instance['post_type']) && in_array($type->name, $instance['post_type'])); ?> />
                                    <label for="<?php echo $this->get_field_id('post_type') . '-' . $type->name; ?>">
                                        <?php echo esc_html($type->labels->name); ?>
                                    </label>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <small><?php esc_html_e('Please note, Media post type is not supported by this plugin yet.', 'smart-recent-posts-widget') ?></small>
                    </div>

                    <p>
                        <label for="<?php echo $this->get_field_id('post_status'); ?>">
                            <?php esc_html_e('Post Status', 'smart-recent-posts-widget'); ?>
                        </label>
                        <select class="widefat" id="<?php echo $this->get_field_id('post_status'); ?>" name="<?php echo $this->get_field_name('post_status'); ?>" style="width:100%;">
                            <?php foreach (get_available_post_statuses() as $status_value => $status_label) { ?>
                                <option value="<?php echo esc_attr($status_label); ?>" <?php selected($instance['post_status'], $status_label); ?>><?php echo esc_html(ucfirst($status_label)); ?></option>
                            <?php } ?>
                        </select>
                    </p>

                    <p>
                        <label for="<?php echo $this->get_field_id('order'); ?>">
                            <?php esc_html_e('Order', 'smart-recent-posts-widget'); ?>
                        </label>
                        <select class="widefat" id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>" style="width:100%;">
                            <option value="DESC" <?php selected($instance['order'], 'DESC'); ?>><?php esc_html_e('Descending', 'srpw') ?></option>
                            <option value="ASC" <?php selected($instance['order'], 'ASC'); ?>><?php esc_html_e('Ascending', 'srpw') ?></option>
                        </select>
                    </p>

                    <p>
                        <label for="<?php echo $this->get_field_id('orderby'); ?>">
                            <?php esc_html_e('Orderby', 'smart-recent-posts-widget'); ?>
                        </label>
                        <select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" style="width:100%;">
                            <option value="ID" <?php selected($instance['orderby'], 'ID'); ?>><?php esc_html_e('ID', 'srpw') ?></option>
                            <option value="author" <?php selected($instance['orderby'], 'author'); ?>><?php esc_html_e('Author', 'srpw') ?></option>
                            <option value="title" <?php selected($instance['orderby'], 'title'); ?>><?php esc_html_e('Title', 'srpw') ?></option>
                            <option value="date" <?php selected($instance['orderby'], 'date'); ?>><?php esc_html_e('Date', 'srpw') ?></option>
                            <option value="modified" <?php selected($instance['orderby'], 'modified'); ?>><?php esc_html_e('Modified', 'srpw') ?></option>
                            <option value="rand" <?php selected($instance['orderby'], 'rand'); ?>><?php esc_html_e('Random', 'srpw') ?></option>
                            <option value="comment_count" <?php selected($instance['orderby'], 'comment_count'); ?>><?php esc_html_e('Comment Count', 'srpw') ?></option>
                            <option value="menu_order" <?php selected($instance['orderby'], 'menu_order'); ?>><?php esc_html_e('Menu Order', 'srpw') ?></option>
                        </select>
                    </p>

                </div><!-- #tab-2 -->

                <div id="tab-3" class="srpw-tab-content">

                    <div class="horizontal-tabs">

                        <ul class="tax-tab">
                            <li><a href="#include"><?php esc_html_e('Limit', 'smart-recent-posts-widget') ?></a></li>
                            <li><a href="#exclude"><?php esc_html_e('Exclude', 'smart-recent-posts-widget') ?></a></li>
                        </ul>

                        <div id="include" class="tax-tab-content">

                            <div class="srpw-multiple-check-form">
                                <label>
                                    <?php esc_html_e('Limit to categories', 'smart-recent-posts-widget'); ?>
                                </label>
                                <ul>
                                    <?php foreach (srpw_taxonomy_list('category') as $category) : ?>
                                        <li>
                                            <input type="checkbox" value="<?php echo (int) $category->term_id; ?>" id="<?php echo $this->get_field_id('cat') . '-' . (int) $category->term_id; ?>" name="<?php echo $this->get_field_name('cat'); ?>[]" <?php checked(is_array($instance['cat']) && in_array($category->term_id, $instance['cat'])); ?> />
                                            <label for="<?php echo $this->get_field_id('cat') . '-' . (int) $category->term_id; ?>">
                                                <?php echo esc_html($category->name); ?>
                                            </label>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>

                            <div class="srpw-multiple-check-form">
                                <label>
                                    <?php esc_html_e('Limit to tags', 'smart-recent-posts-widget'); ?>
                                </label>
                                <ul>
                                    <?php foreach (srpw_taxonomy_list() as $post_tag) : ?>
                                        <li>
                                            <input type="checkbox" value="<?php echo (int) $post_tag->term_id; ?>" id="<?php echo $this->get_field_id('tag') . '-' . (int) $post_tag->term_id; ?>" name="<?php echo $this->get_field_name('tag'); ?>[]" <?php checked(is_array($instance['tag']) && in_array($post_tag->term_id, $instance['tag'])); ?> />
                                            <label for="<?php echo $this->get_field_id('tag') . '-' . (int) $post_tag->term_id; ?>">
                                                <?php echo esc_html($post_tag->name); ?>
                                            </label>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>

                        </div><!-- #include -->

                        <div id="exclude" class="tax-tab-content">

                            <div class="srpw-multiple-check-form">
                                <label>
                                    <?php esc_html_e('Exclude categories', 'smart-recent-posts-widget'); ?>
                                </label>
                                <ul>
                                    <?php foreach (srpw_taxonomy_list('category') as $category) : ?>
                                        <li>
                                            <input type="checkbox" value="<?php echo (int) $category->term_id; ?>" id="<?php echo $this->get_field_id('cat_exclude') . '-' . (int) $category->term_id; ?>" name="<?php echo $this->get_field_name('cat_exclude'); ?>[]" <?php checked(is_array($instance['cat_exclude']) && in_array($category->term_id, $instance['cat_exclude'])); ?> />
                                            <label for="<?php echo $this->get_field_id('cat_exclude') . '-' . (int) $category->term_id; ?>">
                                                <?php echo esc_html($category->name); ?>
                                            </label>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>

                            <div class="srpw-multiple-check-form">
                                <label>
                                    <?php esc_html_e('Exclude tags', 'smart-recent-posts-widget'); ?>
                                </label>
                                <ul>
                                    <?php foreach (srpw_taxonomy_list() as $post_tag) : ?>
                                        <li>
                                            <input type="checkbox" value="<?php echo (int) $post_tag->term_id; ?>" id="<?php echo $this->get_field_id('tag_exclude') . '-' . (int) $post_tag->term_id; ?>" name="<?php echo $this->get_field_name('tag_exclude'); ?>[]" <?php checked(is_array($instance['tag_exclude']) && in_array($post_tag->term_id, $instance['tag_exclude'])); ?> />
                                            <label for="<?php echo $this->get_field_id('tag_exclude') . '-' . (int) $post_tag->term_id; ?>">
                                                <?php echo esc_html($post_tag->name); ?>
                                            </label>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>

                        </div><!-- #exclude -->

                    </div>

                </div><!-- #tab-3 -->

                <div id="tab-4" class="srpw-tab-content">

                    <?php if (current_theme_supports('post-thumbnails')) { ?>

                        <p>
                            <input id="<?php echo $this->get_field_id('thumbnail'); ?>" name="<?php echo $this->get_field_name('thumbnail'); ?>" type="checkbox" <?php checked($instance['thumbnail']); ?> />
                            <label for="<?php echo $this->get_field_id('thumbnail'); ?>">
                                <?php esc_html_e('Display Thumbnail', 'smart-recent-posts-widget'); ?>
                            </label>
                        </p>

                        <p>
                            <label for="<?php echo $this->get_field_id('thumbnail_size'); ?>">
                                <?php esc_html_e('Thumbnail Size ', 'smart-recent-posts-widget'); ?>
                            </label>
                            <select class="widefat" id="<?php echo $this->get_field_id('thumbnail_size'); ?>" name="<?php echo $this->get_field_name('thumbnail_size'); ?>" style="width:100%;">
                                <?php foreach (get_intermediate_image_sizes() as $size) { ?>
                                    <option value="<?php echo esc_attr($size); ?>" <?php selected($instance['thumbnail_size'], $size); ?>><?php echo esc_html($size); ?></option>
                                <?php }    ?>
                            </select>
                        </p>

                        <p>
                            <label class="srpw-block" for="<?php echo $this->get_field_id('thumbnail_align'); ?>">
                                <?php esc_html_e('Thumbnail Alignment', 'smart-recent-posts-widget'); ?>
                            </label>
                            <select class="widefat" id="<?php echo $this->get_field_id('thumbnail_align'); ?>" name="<?php echo $this->get_field_name('thumbnail_align'); ?>">
                                <option value="srpw-alignleft" <?php selected($instance['thumbnail_align'], 'srpw-alignleft'); ?>><?php esc_html_e('Left', 'smart-recent-posts-widget') ?></option>
                                <option value="srpw-alignright" <?php selected($instance['thumbnail_align'], 'srpw-alignright'); ?>><?php esc_html_e('Right', 'smart-recent-posts-widget') ?></option>
                                <option value="srpw-aligncenter" <?php selected($instance['thumbnail_align'], 'srpw-aligncenter'); ?>><?php esc_html_e('Center', 'smart-recent-posts-widget') ?></option>
                            </select>
                        </p>

                        <p>
                            <label for="<?php echo $this->get_field_id('thumbnail_default'); ?>">
                                <?php esc_html_e('Default Thumbnail', 'smart-recent-posts-widget'); ?>
                            </label>
                            <input class="widefat" id="<?php echo $this->get_field_id('thumbnail_default'); ?>" name="<?php echo $this->get_field_name('thumbnail_default'); ?>" type="text" value="<?php echo $instance['thumbnail_default']; ?>" />
                            <small><?php esc_html_e('Leave it blank to disable.', 'smart-recent-posts-widget'); ?></small>
                        </p>

                    <?php } ?>

                </div><!-- #tab-4 -->

                <div id="tab-5" class="srpw-tab-content">

                    <p>
                        <input id="<?php echo $this->get_field_id('excerpt'); ?>" name="<?php echo $this->get_field_name('excerpt'); ?>" type="checkbox" <?php checked($instance['excerpt']); ?> />
                        <label for="<?php echo $this->get_field_id('excerpt'); ?>">
                            <?php esc_html_e('Display Excerpt', 'smart-recent-posts-widget'); ?>
                        </label>
                    </p>

                    <p>
                        <label for="<?php echo $this->get_field_id('length'); ?>">
                            <?php esc_html_e('Excerpt Length', 'smart-recent-posts-widget'); ?>
                        </label>
                        <input class="widefat" id="<?php echo $this->get_field_id('length'); ?>" name="<?php echo $this->get_field_name('length'); ?>" type="number" step="1" min="0" value="<?php echo (int)($instance['length']); ?>" />
                    </p>

                    <p>
                        <input id="<?php echo $this->get_field_id('readmore'); ?>" name="<?php echo $this->get_field_name('readmore'); ?>" type="checkbox" <?php checked($instance['readmore']); ?> />
                        <label for="<?php echo $this->get_field_id('readmore'); ?>">
                            <?php esc_html_e('Display Readmore', 'smart-recent-posts-widget'); ?>
                        </label>
                    </p>

                    <p>
                        <label for="<?php echo $this->get_field_id('readmore_text'); ?>">
                            <?php esc_html_e('Readmore Text', 'smart-recent-posts-widget'); ?>
                        </label>
                        <input class="widefat" id="<?php echo $this->get_field_id('readmore_text'); ?>" name="<?php echo $this->get_field_name('readmore_text'); ?>" type="text" value="<?php echo strip_tags($instance['readmore_text']); ?>" />
                    </p>

                </div><!-- #tab-5 -->

                <div id="tab-6" class="srpw-tab-content">

                    <p>
                        <input id="<?php echo $this->get_field_id('post_title'); ?>" name="<?php echo $this->get_field_name('post_title'); ?>" type="checkbox" <?php checked($instance['post_title']); ?> />
                        <label for="<?php echo $this->get_field_id('post_title'); ?>">
                            <?php esc_html_e('Display Title', 'smart-recent-posts-widget'); ?>
                        </label>
                    </p>

                    <p>
                        <input id="<?php echo $this->get_field_id('date'); ?>" name="<?php echo $this->get_field_name('date'); ?>" type="checkbox" <?php checked($instance['date']); ?> />
                        <label for="<?php echo $this->get_field_id('date'); ?>">
                            <?php esc_html_e('Display Date', 'smart-recent-posts-widget'); ?>
                        </label>
                    </p>

                    <p>
                        <input id="<?php echo $this->get_field_id('comment_count'); ?>" name="<?php echo $this->get_field_name('comment_count'); ?>" type="checkbox" <?php checked($instance['comment_count']); ?> />
                        <label for="<?php echo $this->get_field_id('comment_count'); ?>">
                            <?php esc_html_e('Display Comment Count', 'smart-recent-posts-widget'); ?>
                        </label>
                    </p>

                    <p>
                        <input id="<?php echo $this->get_field_id('author'); ?>" name="<?php echo $this->get_field_name('author'); ?>" type="checkbox" <?php checked($instance['author']); ?> />
                        <label for="<?php echo $this->get_field_id('author'); ?>">
                            <?php esc_html_e('Display Author', 'smart-recent-posts-widget'); ?>
                        </label>
                    </p>

                    <p>
                        <input id="<?php echo $this->get_field_id('date_modified'); ?>" name="<?php echo $this->get_field_name('date_modified'); ?>" type="checkbox" <?php checked($instance['date_modified']); ?> />
                        <label for="<?php echo $this->get_field_id('date_modified'); ?>">
                            <?php esc_html_e('Display Modification Date', 'smart-recent-posts-widget'); ?>
                        </label>
                    </p>

                    <p>
                        <input id="<?php echo $this->get_field_id('date_relative'); ?>" name="<?php echo $this->get_field_name('date_relative'); ?>" type="checkbox" <?php checked($instance['date_relative']); ?> />
                        <label for="<?php echo $this->get_field_id('date_relative'); ?>">
                            <?php esc_html_e('Use Relative Date. eg: 5 days ago', 'smart-recent-posts-widget'); ?>
                        </label>
                    </p>

                </div><!-- #tab-6 -->

                <div id="tab-7" class="srpw-tab-content">

                    <p>
                        <label for="<?php echo $this->get_field_id('style'); ?>">
                            <?php esc_html_e('Style', 'smart-recent-posts-widget'); ?>
                        </label>
                        <select class="widefat" id="<?php echo $this->get_field_id('style'); ?>" name="<?php echo $this->get_field_name('style'); ?>" style="width:100%;">
                            <option value="default" <?php selected($instance['style'], 'default'); ?>><?php esc_html_e('Default', 'srpw') ?></option>
                            <option value="classic" <?php selected($instance['style'], 'classic'); ?>><?php esc_html_e('Classic', 'srpw') ?></option>
                            <option value="modern" <?php selected($instance['style'], 'modern'); ?>><?php esc_html_e('Modern', 'srpw') ?></option>
                        </select>
                        <small><?php printf(esc_html__('Please follow %1$sthis guidelines%2$s to get the best result.', 'smart-recent-posts-widget'), '<a href="https://wordpress.org/plugins/smart-recent-posts-widget" target="_blank">', '</a>'); ?></small>
                    </p>

                    <p>
                        <input id="<?php echo $this->get_field_id('new_tab'); ?>" name="<?php echo $this->get_field_name('new_tab'); ?>" type="checkbox" <?php checked($instance['new_tab']); ?> />
                        <label for="<?php echo $this->get_field_id('new_tab'); ?>">
                            <?php esc_html_e('Open link in new tab', 'smart-recent-posts-widget'); ?>
                        </label>
                    </p>

                    <p>
                        <label for="<?php echo $this->get_field_id('css'); ?>">
                            <?php esc_html_e('Custom CSS', 'smart-recent-posts-widget'); ?>
                        </label>
                        <textarea class="widefat" id="<?php echo $this->get_field_id('css'); ?>" name="<?php echo $this->get_field_name('css'); ?>" style="height:180px;"><?php echo $instance['css']; ?></textarea>
                    </p>

                </div><!-- #tab-7 -->

                <div id="tab-8" class="srpw-tab-content">

                    <p>
                    <p>Thank you for using this plugin, I hope you enjoy it and don't forget to <a href="https://wordpress.org/support/plugin/smart-recent-posts-widget/reviews/" target="_blank" rel="noopener noreferrer">leave a 5 star review</a>.</p>
                    <p>If you want to support me, please donate through <a href="https://paypal.me/satrya" target="_blank" rel="noopener noreferrer">this page</a>.</p>

                </div><!-- #tab-8 -->

            </div><!-- .srpw-tabs-content -->

        </div><!-- .srpw-form-tabs -->

<?php
    }
}
