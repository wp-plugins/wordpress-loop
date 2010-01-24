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
		return $content;
		
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
	return do_shortcode( $content );
}

/**
 * Displays the entry title
 *
 * @since 0.1
 **/
function wl_entry_title( $args = array() ) {
	$defaults = array( 'tag' => 'h2' );
	$r = wp_parse_args( $args, $defaults );
	extract( $r, EXTR_SKIP );
	
	$output = "<{$r['tag']} class=\"entry-title\">";
	$output .= '<a href="'. get_permalink() .'" rel="bookmark" title="'. the_title_attribute( 'echo=0' ) .'">';
	$output .= get_the_title();
	$output .= '</a>';
	$output .= "</{$r['tag']}>\n";		
	echo $output; // apply_filters
}

/**
 * Displays the current post author.
 * Formatted for hAtom microformat.
 *
 * @since 0.1
 */
function wl_entry_author() {
	$output .= '<span class="entry-author vcard author"><a href="'. get_author_posts_url( get_the_author_ID() ) . '" class="url fn" title="';
	$output .= sprintf( __( 'View all posts by %s', 'wordpress-loop'), esc_attr( get_the_author() ) );
	$output .= '">';
	$output .= get_the_author();
	$output .= '</a></span>' . "\n";
	return $output;
}

/**
 * Displays the current post date, if time since is installed, it will use that instead.
 * Formatted for hAtom microformat.
 *
 * @since 0.1
 */
function wl_entry_date() {
	$output .= "\n" .'<span class="entry-date">'. "\n";
	$output .= '<abbr class="published" title="' . get_the_time( 'Y-m-d\TH:i:sO' ) . '">';
	$output .= get_the_time( get_option('date_format') );
	$output .= '</abbr>';
	$output .= "\n" .'</span>'. "\n";
	return $output;
}

/**
 * Displays the number of comments in current post enclosed in a link.
 *
 * @since 0.1
 */
function wl_entry_comments() {
	if (is_singular()) return;
	ob_start();

	comments_popup_link( __( 'Leave a comment', 'wordpress-loop' ), '<span class="comment-count">1</span> ' . __( 'Comment', 'wordpress-loop' ), '<span class="comment-count">%</span> '. __( 'Comments', 'wordpress-loop' ), 'commentslink', '<span class="comments-closed">'. __( 'Comments Closed', 'wordpress-loop' ) .'</span>' );

	return '<span class="entry-comments">' . ob_get_clean() . '</span>';
}

/**
 * Entry Edit link
 *
 * @since 0.1
 */
function wl_entry_edit() {
	global $post;

	if ( !current_user_can( 'edit_' . $post->post_type, $post->ID ) )
		return false;

	$link = '<a class="entry-edit-link" href="' . get_edit_post_link( $post->ID ) . '" title="' . esc_attr( 'Edit ' . $post->post_type ) . '">'. __('Edit', 'wordpress-loop') .'</a>';
	$edit = '<span class="entry-edit">' . apply_filters( 'edit_post_link', $link, $post->ID ) . '</span>';
	return $edit;
}

/**
 * Displays the current post time
 *
 * @since 0.1
 */
function wl_entry_time() {
	return '<span class="entry-time">' . get_the_time( get_option('time_format') ) . '</span>';
}

/**
 * Displays a list of comma seperated cats
 *
 * @since 0.1
 **/
function wl_entry_cats( $args = array() ) {
	$defaults = array( 'sep' => ',' );
	$r = wp_parse_args( $args, $defaults );
	
	return '<span class="entry-tax-cats">' . get_the_category_list( $r['sep'], '', false ) . '</span>';
}

/**
 * Displays a list of comma seperated tags
 *
 * @since 0.1
 **/
function wl_entry_tags( $args = array() ) {
	$defaults = array( 'sep' => ',' );
	$r = wp_parse_args( $args, $defaults );

	return '<span class="entry-tax-tags">' . get_the_tag_list( null, $r['sep'], '' ) . '</span>';
}

/**
 * Displays one or more comma seperated list of taxonomies associated with the current post
 *
 * @since 0.1
 **/
function wl_entry_tax( $args = array() ) {
	$defaults = array( 'sep' => ',', 'last' => 'and', 'end' => '.' );
	$r = wp_parse_args( $args, $defaults );
	
	$_tax = get_the_taxonomies();
	foreach ( $_tax as $key => $value ) {
		preg_match( '/(.+?): /i', $value, $matches );
		$tax[] = '<span class="entry-tax-'. $key .'">' . str_replace( $matches[0], '<span class="entry-tax-meta">'. $matches[1] .': </span>', $value ) . '</span>';
	}
	return join( ' ', $tax );
}

// Shortcodes
add_shortcode( 'entry_title', 'wl_entry_title' );
add_shortcode( 'entry_author', 'wl_entry_author' );
add_shortcode( 'entry_date', 'wl_entry_date' );
add_shortcode( 'entry_comments', 'wl_entry_comments' );
add_shortcode( 'entry_time', 'wl_entry_time' );
add_shortcode( 'entry_edit', 'wl_entry_edit' );
add_shortcode( 'entry_cats', 'wl_entry_cats' );
add_shortcode( 'entry_tags', 'wl_entry_tags' );
add_shortcode( 'entry_tax', 'wl_entry_tax' );
?>