<?php
/**
 * Displays the post content
 *
 * @since 0.1
 **/
function wl_the_content( $more_text, $length ) {
	global $id;
	$length = intval( $length );
	
	$raw_more_text = $more_text;
	$more_text = '<a href="' . get_permalink() . '" class="more-link">'. $raw_more_text .'</a>';
	
	if ( 0 < $length ) {
		$content = get_the_content('');
		$content = strip_shortcodes( $content );
		$content = apply_filters( 'the_content', $content );
		$content = str_replace( ']]>', ']]&gt;', $content );
		$content = strip_tags( $content );
		
		$excerpt_length = apply_filters( 'excerpt_length', $length );
		$excerpt_text = apply_filters( 'excerpt_more', '... ' . $more_text );
		
		$words = explode( ' ', $content, $excerpt_length + 1 );
		if ( count($words) > $excerpt_length ) {
			array_pop( $words );
			$content = implode( ' ', $words );
			$content = $content . $excerpt_text;
		}
		return "<p>$content</p>";
		
	} elseif ( -1 == $length ) {
		$content = get_the_content( $more_text );
		$content = apply_filters( 'the_content', $content );
		$content = str_replace( ']]>', ']]&gt;', $content );
		return $content;
		
	} elseif ( 0 == $length ) {
		return '';
	} else {
		return false;
	}
}

/**
 * Displays the pagination links
 *
 * @since 0.1
 **/
function wl_pagniation( $next = '&laquo; Older Entries', $prev = 'Newer Entries &raquo;' ) { ?>
	<!--BEGIN .navigation-links-->
	<div class="navigation-links">
		<div class="nav-next"><?php next_posts_link( __( $next , 'wordpress-loop' ) ); ?></div>
		<div class="nav-previous"><?php previous_posts_link( __( $prev , 'wordpress-loop' ) ); ?></div>
	<!--END .navigation-links-->
	</div>
	<?php
}

/**
 * Process any shortcodes applied to $content
 *
 * @since 0.1
 **/
function wl_postmeta( $content ) {
	$content = preg_replace( '/\[(.+?)\]/', '[entry_$1]', $content );	
	return apply_filters( 'wl_postmeta', do_shortcode( $content ) );
}

/**
 * Displays the entry title
 *
 * @since 0.1
 **/
function wl_entry_title( $atts = array() ) {
	$defaults = array( 'before' => '', 'after' => '', 'tag' => 'h2' );
	$args = shortcode_atts( $defaults, $atts );
	extract( $args, EXTR_SKIP );
	
	$output  = "<$tag class=\"entry-title\">";
	$output .= '<a href="'. get_permalink() .'" rel="bookmark" title="'. the_title_attribute( 'echo=0' ) .'">';
	$output .= get_the_title();
	$output .= '</a>';
	$output .= "</$tag>";
	
	return apply_filters( 'wl_entry_title', $before . $output . $after );
}

/**
 * Displays the post author
 *
 * @since 0.1
 */
function wl_entry_author( $atts = array() ) {
	$defaults = array( 'before' => '', 'after' => '' );
	$args = shortcode_atts( $defaults, $atts );
	extract( $args, EXTR_SKIP );
	
	$output  = '<span class="entry-author vcard author"><a href="'. get_author_posts_url( get_the_author_ID() ) . '" class="url fn" title="';
	$output .= sprintf( __( 'View all posts by %s', 'wordpress-loop'), esc_attr( get_the_author() ) );
	$output .= '">';
	$output .= get_the_author();
	$output .= '</a></span>';
	
	return apply_filters( 'wl_entry_author', $before . $output . $after );
}

/**
 * Displays the current post date, if time since is installed, it will use that instead.
 * Formatted for hAtom microformat.
 *
 * @since 0.1
 */
function wl_entry_date( $atts = array() ) {
	$defaults = array( 'before' => '', 'after' => '' );
	$args = shortcode_atts( $defaults, $atts );
	extract( $args, EXTR_SKIP );
	
	$output  = '<span class="entry-date"><abbr class="published" title="' . get_the_time( 'Y-m-d\TH:i:sO' ) . '">';
	$output .= get_the_time( get_option('date_format') );
	$output .= '</abbr></span>';
	
	return apply_filters( 'wl_entry_date', $before . $output . $after );
}

/**
 * Displays the current post time
 *
 * @since 0.1
 */
function wl_entry_time( $atts = array() ) {
	$defaults = array( 'before' => '', 'after' => '' );
	$args = shortcode_atts( $defaults, $atts );
	extract( $args, EXTR_SKIP );
	
	$output = '<span class="entry-time">' . get_the_time( get_option('time_format') ) . '</span>';
	
	return apply_filters( 'wl_entry_time', $before . $output . $after );
}

/**
 * Displays the current post date, if time since is installed, it will use that instead.
 * Formatted for hAtom microformat.
 *
 * @since 0.2
 */
function wl_entry_last_modified( $atts = array() ) {
	$defaults = array( 'before' => '', 'after' => '' );
	$args = shortcode_atts( $defaults, $atts );
	extract( $args, EXTR_SKIP );
	
	$output .= '<span class="entry-date-last-modified"><abbr class="last-modified" title="' . get_the_time( 'Y-m-d\TH:i:sO' ) . '">';
	$output .= get_the_modified_date();
	$output .= '</abbr></span>';
	
	return apply_filters( 'wl_entry_last_modified', $before . $output . $after );
}

/**
 * Displays the number of comments in current post enclosed in a link.
 *
 * @since 0.1
 */
function wl_entry_comments( $atts = array() ) {
	$defaults = array( 'before' => '', 'after' => '', 'zero' => __( '0 Comments', 'wordpress-loop' ), 'one' => __( '% Comment', 'wordpress-loop' ), 'more' => __( '% Comments', 'wordpress-loop' ), 'none' => __( 'Comments Closed', 'wordpress-loop' ) );
	$args = shortcode_atts( $defaults, $atts );
	extract( $args, EXTR_SKIP );
	
	ob_start();
	comments_popup_link( $zero, $one, $more, '', $none );
	$output = '<span class="entry-comments">' . ob_get_clean() . '</span>';
	
	return apply_filters( 'wl_entry_comments', $before . $output . $after );
}

/**
 * Displays a list of comma seperated cats
 *
 * @since 0.1
 **/
function wl_entry_cats( $atts = array() ) {
	$defaults = array( 'before' => '', 'after' => '', 'sep' => ', ' );
	$args = shortcode_atts( $defaults, $atts );
	extract( $args, EXTR_SKIP );
	
	$output = '<span class="entry-tax-cats">' . get_the_category_list( $sep, '', false ) . '</span>';
	
	return apply_filters( 'wl_entry_cats', $before . $output . $after );
}

/**
 * Displays a list of comma seperated tags
 *
 * @since 0.1
 **/
function wl_entry_tags( $atts = array() ) {
	$defaults = array( 'before' => '', 'after' => '', 'sep' => ', ' );
	$args = shortcode_atts( $defaults, $atts );
	extract( $args, EXTR_SKIP );

	$output = '<span class="entry-tax-tags">' . get_the_tag_list( null, $sep, '' ) . '</span>';
	
	return apply_filters( 'wl_entry_tags', $before . $output . $after );
}

/**
 * Displays one or more comma seperated list of taxonomies associated with the current post
 *
 * @since 0.1
 **/
function wl_entry_tax( $atts = array() ) {
	$defaults = array( 'before' => '', 'after' => '' );
	$args = shortcode_atts( $defaults, $atts );
	extract( $args, EXTR_SKIP );
	
	$_tax = get_the_taxonomies();
	foreach ( $_tax as $key => $value ) {
		preg_match( '/(.+?): /i', $value, $matches );
		$tax[] = '<span class="entry-tax-'. $key .'">' . str_replace( $matches[0], '<span class="entry-tax-meta">'. $matches[1] .': </span>', $value ) . '</span>';
	}
	$output = join( ' ', $tax );
	
	return apply_filters( 'wl_entry_tax', $before . $output . $after );
}

/**
 * Entry Edit link
 *
 * @since 0.1
 */
function wl_entry_edit( $atts = array() ) {
	$defaults = array( 'before' => '', 'after' => '', 'label' => __( 'Edit', 'wordpress-loop' ) );
	$args = shortcode_atts( $defaults, $atts );
	extract( $args, EXTR_SKIP );
	
	global $post;

	if ( !current_user_can( 'edit_' . $post->post_type, $post->ID ) )
		return false;

	$link = '<a class="entry-edit-link" href="' . get_edit_post_link( $post->ID ) . '" title="' . esc_attr( "$label " . $post->post_type ) . '">'. $label .'</a>';
	$output = '<span class="entry-edit">' . apply_filters( 'edit_post_link', $link, $post->ID ) . '</span>';
	
	return apply_filters( 'wl_entry_edit', $before . $output . $after );
}

// Shortcodes
add_shortcode( 'entry_title', 'wl_entry_title' );
add_shortcode( 'entry_author', 'wl_entry_author' );
add_shortcode( 'entry_date', 'wl_entry_date' );
add_shortcode( 'entry_time', 'wl_entry_time' );
add_shortcode( 'entry_last_modified', 'wl_entry_last_modified' );
add_shortcode( 'entry_comments', 'wl_entry_comments' );
add_shortcode( 'entry_cats', 'wl_entry_cats' );
add_shortcode( 'entry_tags', 'wl_entry_tags' );
add_shortcode( 'entry_tax', 'wl_entry_tax' );
add_shortcode( 'entry_edit', 'wl_entry_edit' );
?>