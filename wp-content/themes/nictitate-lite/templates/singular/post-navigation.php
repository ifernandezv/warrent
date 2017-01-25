<?php 
$prev_post = get_previous_post(); 
if ( ! empty( $prev_post ) ) :
?>
    <p class="prev-post">
        <a href="<?php echo get_permalink( $prev_post->ID ); ?>">&laquo;&nbsp;<?php _e('Previous Article', 'nictitate-lite'); ?></a>
        <a class="article-title" href="<?php echo get_permalink( $prev_post->ID ); ?>"><?php echo wp_kses_post( $prev_post->post_title ); ?></a>
        <span class="entry-date"><?php echo get_post_time( get_option( 'date_format' ), false, $prev_post->ID, true ); ?></span>
    </p>
<?php endif; ?>

<?php
$next_post = get_next_post();
if ( ! empty( $next_post ) ) : 
?>
    <p class="next-post">
        <a href="<?php echo get_permalink( $next_post->ID ); ?>"><?php _e('Next Article', 'nictitate-lite'); ?>&nbsp;&raquo;</a>
        <a class="article-title" href="<?php echo get_permalink( $next_post->ID ); ?>"><?php echo wp_kses_post( $next_post->post_title ); ?></a>
        <span class="entry-date"><?php echo get_post_time( get_option( 'date_format' ), false, $next_post->ID, true ); ?></span>                                
    </p>
<?php endif; ?>