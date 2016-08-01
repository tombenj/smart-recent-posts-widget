<?php
/**
 * Widget forms.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
?>

<script>
	jQuery( document ).ready( function( $ ) {

		// Cache selector in a variable
		// to improve speed.
		var $tabs = $( '.srpw-form-tabs' ),
		    $hor  = $( '.horizontal-tabs' );

		// Initialize the jQuery UI tabs
		$tabs.tabs({
			active   : $.cookie( 'activetab' ),
			activate : function( event, ui ){
				$.cookie( 'activetab', ui.newTab.index(),{
					expires : 10
				});
			}
		}).addClass( 'ui-tabs-vertical' );

		// Add custom class
		$tabs.closest( '.widget-inside' ).addClass( 'srpw-bg' );

		// Initialize the jQuery UI tabs
		$hor.tabs().addClass( 'ui-tabs-horizontal' );

	});
</script>

<div class="srpw-form-tabs">

	<ul class="srpw-tabs">
		<li><a href="#tab-1"><?php _e( 'General', 'smart-recent-posts-widget' ); ?></a></li>
		<li><a href="#tab-2"><?php _e( 'Posts', 'smart-recent-posts-widget' ); ?></a></li>
		<li><a href="#tab-3"><?php _e( 'Taxonomy', 'smart-recent-posts-widget' ); ?></a></li>
		<li><a href="#tab-4"><?php _e( 'Thumbnail', 'smart-recent-posts-widget' ); ?></a></li>
		<li><a href="#tab-5"><?php _e( 'Excerpt', 'smart-recent-posts-widget' ); ?></a></li>
		<li><a href="#tab-6"><?php _e( 'Meta', 'smart-recent-posts-widget' ); ?></a></li>
		<li><a href="#tab-7"><?php _e( 'Custom CSS', 'smart-recent-posts-widget' ); ?></a></li>
	</ul>

	<div class="srpw-tabs-content">

		<div id="tab-1" class="srpw-tab-content">

			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>">
					<?php _e( 'Title', 'smart-recent-posts-widget' ); ?>
				</label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'title_url' ); ?>">
					<?php _e( 'Title URL', 'smart-recent-posts-widget' ); ?>
				</label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title_url' ); ?>" name="<?php echo $this->get_field_name( 'title_url' ); ?>" type="url" value="<?php echo esc_url( $instance['title_url'] ); ?>" />
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'css_class' ); ?>">
					<?php _e( 'CSS Class', 'smart-recent-posts-widget' ); ?>
				</label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'css_class' ); ?>" name="<?php echo $this->get_field_name( 'css_class' ); ?>" type="text" value="<?php echo sanitize_html_class( $instance['css_class'] ); ?>"/>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'before' ); ?>">
					<?php _e( 'HTML or text before the recent posts', 'smart-recent-posts-widget' );?>
				</label>
				<textarea class="widefat" id="<?php echo $this->get_field_id( 'before' ); ?>" name="<?php echo $this->get_field_name( 'before' ); ?>" rows="5"><?php echo $instance['before']; ?></textarea>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'after' ); ?>">
					<?php _e( 'HTML or text after the recent posts', 'smart-recent-posts-widget' );?>
				</label>
				<textarea class="widefat" id="<?php echo $this->get_field_id( 'after' ); ?>" name="<?php echo $this->get_field_name( 'after' ); ?>" rows="5"><?php echo $instance['after']; ?></textarea>
			</p>

		</div><!-- #tab-1 -->

		<div id="tab-2" class="srpw-tab-content">

			<p>
				<input class="checkbox" type="checkbox" <?php checked( $instance['ignore_sticky'], 1 ); ?> id="<?php echo $this->get_field_id( 'ignore_sticky' ); ?>" name="<?php echo $this->get_field_name( 'ignore_sticky' ); ?>" />
				<label for="<?php echo $this->get_field_id( 'ignore_sticky' ); ?>">
					<?php _e( 'Ignore sticky posts', 'smart-recent-posts-widget' ); ?>
				</label>
			</p>

			<p>
				<input class="checkbox" type="checkbox" <?php checked( $instance['exclude_current'], 1 ); ?> id="<?php echo $this->get_field_id( 'exclude_current' ); ?>" name="<?php echo $this->get_field_name( 'exclude_current' ); ?>" />
				<label for="<?php echo $this->get_field_id( 'exclude_current' ); ?>">
					<?php _e( 'Exclude current post', 'smart-recent-posts-widget' ); ?>
				</label>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'limit' ); ?>">
					<?php _e( 'Number of posts to show', 'smart-recent-posts-widget' ); ?>
				</label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>" type="number" step="1" min="-1" value="<?php echo (int)( $instance['limit'] ); ?>" />
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'offset' ); ?>">
					<?php _e( 'Offset', 'smart-recent-posts-widget' ); ?>
				</label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'offset' ); ?>" name="<?php echo $this->get_field_name( 'offset' ); ?>" type="number" step="1" min="0" value="<?php echo (int)( $instance['offset'] ); ?>" />
				<small><?php _e( 'The number of posts to skip', 'smart-recent-posts-widget' ); ?></small>
			</p>

			<div class="srpw-multiple-check-form">
				<label>
					<?php _e( 'Post Types', 'smart-recent-posts-widget' ); ?>
				</label>
				<ul>
					<?php foreach ( get_post_types( array( 'public' => true ), 'objects' ) as $type ) : ?>
						<li>
							<input type="checkbox" value="<?php echo esc_attr( $type->name ); ?>" id="<?php echo $this->get_field_id( 'post_type' ) . '-' . $type->name; ?>" name="<?php echo $this->get_field_name( 'post_type' ); ?>[]" <?php checked( is_array( $instance['post_type'] ) && in_array( $type->name, $instance['post_type'] ) ); ?> />
							<label for="<?php echo $this->get_field_id( 'post_type' ) . '-' . $type->name; ?>">
								<?php echo esc_html( $type->labels->name ); ?>
							</label>
						</li>
					<?php endforeach; ?>
				</ul>
				<small><?php _e( 'Please note, Media post type is not supported by this plugin yet.', 'smart-recent-posts-widget' ) ?></small>
			</div>

			<p>
				<label for="<?php echo $this->get_field_id( 'post_status' ); ?>">
					<?php _e( 'Post Status', 'smart-recent-posts-widget' ); ?>
				</label>
				<select class="widefat" id="<?php echo $this->get_field_id( 'post_status' ); ?>" name="<?php echo $this->get_field_name( 'post_status' ); ?>" style="width:100%;">
					<?php foreach ( get_available_post_statuses() as $status_value => $status_label ) { ?>
						<option value="<?php echo esc_attr( $status_label ); ?>" <?php selected( $instance['post_status'], $status_label ); ?>><?php echo esc_html( ucfirst( $status_label ) ); ?></option>
					<?php } ?>
				</select>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'order' ); ?>">
					<?php _e( 'Order', 'smart-recent-posts-widget' ); ?>
				</label>
				<select class="widefat" id="<?php echo $this->get_field_id( 'order' ); ?>" name="<?php echo $this->get_field_name( 'order' ); ?>" style="width:100%;">
					<option value="DESC" <?php selected( $instance['order'], 'DESC' ); ?>><?php _e( 'Descending', 'srpw' ) ?></option>
					<option value="ASC" <?php selected( $instance['order'], 'ASC' ); ?>><?php _e( 'Ascending', 'srpw' ) ?></option>
				</select>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'orderby' ); ?>">
					<?php _e( 'Orderby', 'smart-recent-posts-widget' ); ?>
				</label>
				<select class="widefat" id="<?php echo $this->get_field_id( 'orderby' ); ?>" name="<?php echo $this->get_field_name( 'orderby' ); ?>" style="width:100%;">
					<option value="ID" <?php selected( $instance['orderby'], 'ID' ); ?>><?php _e( 'ID', 'srpw' ) ?></option>
					<option value="author" <?php selected( $instance['orderby'], 'author' ); ?>><?php _e( 'Author', 'srpw' ) ?></option>
					<option value="title" <?php selected( $instance['orderby'], 'title' ); ?>><?php _e( 'Title', 'srpw' ) ?></option>
					<option value="date" <?php selected( $instance['orderby'], 'date' ); ?>><?php _e( 'Date', 'srpw' ) ?></option>
					<option value="modified" <?php selected( $instance['orderby'], 'modified' ); ?>><?php _e( 'Modified', 'srpw' ) ?></option>
					<option value="rand" <?php selected( $instance['orderby'], 'rand' ); ?>><?php _e( 'Random', 'srpw' ) ?></option>
					<option value="comment_count" <?php selected( $instance['orderby'], 'comment_count' ); ?>><?php _e( 'Comment Count', 'srpw' ) ?></option>
					<option value="menu_order" <?php selected( $instance['orderby'], 'menu_order' ); ?>><?php _e( 'Menu Order', 'srpw' ) ?></option>
				</select>
			</p>

		</div><!-- #tab-2 -->

		<div id="tab-3" class="srpw-tab-content">

			<div class="horizontal-tabs">

				<ul class="tax-tab">
					<li><a href="#include"><?php _e( 'Limit', 'smart-recent-posts-widget' ) ?></a></li>
					<li><a href="#exclude"><?php _e( 'Exclude', 'smart-recent-posts-widget' ) ?></a></li>
				</ul>

				<div id="include" class="tax-tab-content">

					<div class="srpw-multiple-check-form">
						<label>
							<?php _e( 'Limit to categories', 'smart-recent-posts-widget' ); ?>
						</label>
						<ul>
							<?php foreach ( srpw_taxonomy_list( 'category' ) as $category ) : ?>
								<li>
									<input type="checkbox" value="<?php echo (int) $category->term_id; ?>" id="<?php echo $this->get_field_id( 'cat' ) . '-' . (int) $category->term_id; ?>" name="<?php echo $this->get_field_name( 'cat' ); ?>[]" <?php checked( is_array( $instance['cat'] ) && in_array( $category->term_id, $instance['cat'] ) ); ?> />
									<label for="<?php echo $this->get_field_id( 'cat' ) . '-' . (int) $category->term_id; ?>">
										<?php echo esc_html( $category->name ); ?>
									</label>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>

					<div class="srpw-multiple-check-form">
						<label>
							<?php _e( 'Limit to tags', 'smart-recent-posts-widget' ); ?>
						</label>
						<ul>
							<?php foreach ( srpw_taxonomy_list() as $post_tag ) : ?>
								<li>
									<input type="checkbox" value="<?php echo (int) $post_tag->term_id; ?>" id="<?php echo $this->get_field_id( 'tag' ) . '-' . (int) $post_tag->term_id; ?>" name="<?php echo $this->get_field_name( 'tag' ); ?>[]" <?php checked( is_array( $instance['tag'] ) && in_array( $post_tag->term_id, $instance['tag'] ) ); ?> />
									<label for="<?php echo $this->get_field_id( 'tag' ) . '-' . (int) $post_tag->term_id; ?>">
										<?php echo esc_html( $post_tag->name ); ?>
									</label>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>

				</div><!-- #include -->

				<div id="exclude" class="tax-tab-content">

					<div class="srpw-multiple-check-form">
						<label>
							<?php _e( 'Exclude categories', 'smart-recent-posts-widget' ); ?>
						</label>
						<ul>
							<?php foreach ( srpw_taxonomy_list( 'category' ) as $category ) : ?>
								<li>
									<input type="checkbox" value="<?php echo (int) $category->term_id; ?>" id="<?php echo $this->get_field_id( 'cat_exclude' ) . '-' . (int) $category->term_id; ?>" name="<?php echo $this->get_field_name( 'cat_exclude' ); ?>[]" <?php checked( is_array( $instance['cat_exclude'] ) && in_array( $category->term_id, $instance['cat_exclude'] ) ); ?> />
									<label for="<?php echo $this->get_field_id( 'cat_exclude' ) . '-' . (int) $category->term_id; ?>">
										<?php echo esc_html( $category->name ); ?>
									</label>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>

					<div class="srpw-multiple-check-form">
						<label>
							<?php _e( 'Exclude tags', 'smart-recent-posts-widget' ); ?>
						</label>
						<ul>
							<?php foreach ( srpw_taxonomy_list() as $post_tag ) : ?>
								<li>
									<input type="checkbox" value="<?php echo (int) $post_tag->term_id; ?>" id="<?php echo $this->get_field_id( 'tag_exclude' ) . '-' . (int) $post_tag->term_id; ?>" name="<?php echo $this->get_field_name( 'tag_exclude' ); ?>[]" <?php checked( is_array( $instance['tag_exclude'] ) && in_array( $post_tag->term_id, $instance['tag_exclude'] ) ); ?> />
									<label for="<?php echo $this->get_field_id( 'tag_exclude' ) . '-' . (int) $post_tag->term_id; ?>">
										<?php echo esc_html( $post_tag->name ); ?>
									</label>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>

				</div><!-- #exclude -->

			</div>

		</div><!-- #tab-3 -->

		<div id="tab-4" class="srpw-tab-content">

			<?php if ( current_theme_supports( 'post-thumbnails' ) ) { ?>

				<p>
					<input id="<?php echo $this->get_field_id( 'thumbnail' ); ?>" name="<?php echo $this->get_field_name( 'thumbnail' ); ?>" type="checkbox" <?php checked( $instance['thumbnail'] ); ?> />
					<label for="<?php echo $this->get_field_id( 'thumbnail' ); ?>">
						<?php _e( 'Display Thumbnail', 'smart-recent-posts-widget' ); ?>
					</label>
				</p>

				<p>
					<label for="<?php echo $this->get_field_id( 'thumbnail_size' ); ?>">
						<?php _e( 'Thumbnail Size ', 'smart-recent-posts-widget' ); ?>
					</label>
					<select class="widefat" id="<?php echo $this->get_field_id( 'thumbnail_size' ); ?>" name="<?php echo $this->get_field_name( 'thumbnail_size' ); ?>" style="width:100%;">
						<?php foreach ( get_intermediate_image_sizes() as $size ) { ?>
							<option value="<?php echo esc_attr( $size ); ?>" <?php selected( $instance['thumbnail_size'], $size ); ?>><?php echo esc_html( $size ); ?></option>
						<?php }	?>
					</select>
				</p>

				<p>
					<label class="srpw-block" for="<?php echo $this->get_field_id( 'thumbnail_align' ); ?>">
						<?php _e( 'Thumbnail Alignment', 'smart-recent-posts-widget' ); ?>
					</label>
					<select class="widefat" id="<?php echo $this->get_field_id( 'thumbnail_align' ); ?>" name="<?php echo $this->get_field_name( 'thumbnail_align' ); ?>">
						<option value="srpw-alignleft" <?php selected( $instance['thumbnail_align'], 'srpw-alignleft' ); ?>><?php _e( 'Left', 'smart-recent-posts-widget' ) ?></option>
						<option value="srpw-alignright" <?php selected( $instance['thumbnail_align'], 'srpw-alignright' ); ?>><?php _e( 'Right', 'smart-recent-posts-widget' ) ?></option>
						<option value="srpw-aligncenter" <?php selected( $instance['thumbnail_align'], 'srpw-aligncenter' ); ?>><?php _e( 'Center', 'smart-recent-posts-widget' ) ?></option>
					</select>
				</p>

				<p>
					<label for="<?php echo $this->get_field_id( 'thumbnail_default' ); ?>">
						<?php _e( 'Default Thumbnail', 'smart-recent-posts-widget' ); ?>
					</label>
					<input class="widefat" id="<?php echo $this->get_field_id( 'thumbnail_default' ); ?>" name="<?php echo $this->get_field_name( 'thumbnail_default' ); ?>" type="text" value="<?php echo $instance['thumbnail_default']; ?>"/>
					<small><?php _e( 'Leave it blank to disable.', 'smart-recent-posts-widget' ); ?></small>
				</p>

			<?php } ?>

		</div><!-- #tab-4 -->

		<div id="tab-5" class="srpw-tab-content">

			<p>
				<input id="<?php echo $this->get_field_id( 'excerpt' ); ?>" name="<?php echo $this->get_field_name( 'excerpt' ); ?>" type="checkbox" <?php checked( $instance['excerpt'] ); ?> />
				<label for="<?php echo $this->get_field_id( 'excerpt' ); ?>">
					<?php _e( 'Display Excerpt', 'smart-recent-posts-widget' ); ?>
				</label>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'length' ); ?>">
					<?php _e( 'Excerpt Length', 'smart-recent-posts-widget' ); ?>
				</label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'length' ); ?>" name="<?php echo $this->get_field_name( 'length' ); ?>" type="number" step="1" min="0" value="<?php echo (int)( $instance['length'] ); ?>" />
			</p>

			<p>
				<input id="<?php echo $this->get_field_id( 'readmore' ); ?>" name="<?php echo $this->get_field_name( 'readmore' ); ?>" type="checkbox" <?php checked( $instance['readmore'] ); ?> />
				<label for="<?php echo $this->get_field_id( 'readmore' ); ?>">
					<?php _e( 'Display Readmore', 'smart-recent-posts-widget' ); ?>
				</label>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'readmore_text' ); ?>">
					<?php _e( 'Readmore Text', 'smart-recent-posts-widget' ); ?>
				</label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'readmore_text' ); ?>" name="<?php echo $this->get_field_name( 'readmore_text' ); ?>" type="text" value="<?php echo strip_tags( $instance['readmore_text'] ); ?>" />
			</p>

		</div><!-- #tab-5 -->

		<div id="tab-6" class="srpw-tab-content">

			<p>
				<input id="<?php echo $this->get_field_id( 'date' ); ?>" name="<?php echo $this->get_field_name( 'date' ); ?>" type="checkbox" <?php checked( $instance['date'] ); ?> />
				<label for="<?php echo $this->get_field_id( 'date' ); ?>">
					<?php _e( 'Display Date', 'smart-recent-posts-widget' ); ?>
				</label>
			</p>

			<p>
				<input id="<?php echo $this->get_field_id( 'comment_count' ); ?>" name="<?php echo $this->get_field_name( 'comment_count' ); ?>" type="checkbox" <?php checked( $instance['comment_count'] ); ?> />
				<label for="<?php echo $this->get_field_id( 'comment_count' ); ?>">
					<?php _e( 'Display Comment Count', 'smart-recent-posts-widget' ); ?>
				</label>
			</p>

			<p>
				<input id="<?php echo $this->get_field_id( 'author' ); ?>" name="<?php echo $this->get_field_name( 'author' ); ?>" type="checkbox" <?php checked( $instance['author'] ); ?> />
				<label for="<?php echo $this->get_field_id( 'author' ); ?>">
					<?php _e( 'Display Author', 'smart-recent-posts-widget' ); ?>
				</label>
			</p>

			<p>
				<input id="<?php echo $this->get_field_id( 'date_modified' ); ?>" name="<?php echo $this->get_field_name( 'date_modified' ); ?>" type="checkbox" <?php checked( $instance['date_modified'] ); ?> />
				<label for="<?php echo $this->get_field_id( 'date_modified' ); ?>">
					<?php _e( 'Display Modification Date', 'smart-recent-posts-widget' ); ?>
				</label>
			</p>

			<p>
				<input id="<?php echo $this->get_field_id( 'date_relative' ); ?>" name="<?php echo $this->get_field_name( 'date_relative' ); ?>" type="checkbox" <?php checked( $instance['date_relative'] ); ?> />
				<label for="<?php echo $this->get_field_id( 'date_relative' ); ?>">
					<?php _e( 'Use Relative Date. eg: 5 days ago', 'smart-recent-posts-widget' ); ?>
				</label>
			</p>

		</div><!-- #tab-6 -->

		<div id="tab-7" class="srpw-tab-content">

			<p>
				<label for="<?php echo $this->get_field_id( 'css' ); ?>">
					<?php _e( 'Custom CSS', 'smart-recent-posts-widget' ); ?>
				</label>
				<textarea class="widefat" id="<?php echo $this->get_field_id( 'css' ); ?>" name="<?php echo $this->get_field_name( 'css' ); ?>" style="height:180px;"><?php echo $instance['css']; ?></textarea>
			</p>

		</div><!-- #tab-7 -->

	</div><!-- .srpw-tabs-content -->

</div><!-- .srpw-form-tabs -->
