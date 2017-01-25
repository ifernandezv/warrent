<?php
/*
Plugin Name: Webbstj&auml;rnan
Plugin URI: http://webbstjarnan.se
Description: Webbstj&auml;rnans till&auml;gg hj&auml;lper dig med Webbstj&auml;rnans logga och leder dig r&auml;tt i arbetet med Internet i skolan.
Version: 0.1
Author: <a href="http://jacksoncage.se/">Love Nyberg</a> & <a href="http://formrikt.se">Therese Tjernstr&ouml;m</a>
Author URI: 
License: BSD
*/

/* Add funtion to dashboard */
function webbstjarnan_dashboard_widgets() {
 global $wp_meta_boxes;
   wp_add_dashboard_widget('custom_help_widget', 'Webbstj&auml;rnan', 'webbstjarnan_dashboard_help');
}
add_action('wp_dashboard_setup', 'webbstjarnan_dashboard_widgets');


// Webbstjarnan Dashboard Help
function webbstjarnan_dashboard_help() {
 echo '
 
<div style="float: left;width: 48%;display: block; padding: 3px;">

	<p style="color:#8F8F8F;font-family: sans-serif;font-size: 14px;font-weight: normal;margin-left: 3px;">Vad &auml;r Webbstj&auml;rnan?</p>

 	<p>Webbstj&auml;rnan &auml;r en m&ouml;jlighet f&ouml;r dig som g&aring;r i skolan att l&auml;ra dig mer om Internet och utveckla din digitala kompetens.<br>
	
	<br>
	
	<p style="color:#8F8F8F;font-family: sans-serif;font-size: 14px;font-weight: normal;margin-left: 3px;">Vi hj&auml;lper dig g&auml;rna!</p>

	Mejl: <a href="mailto:support@webbstjarnan.se">support@webbstjarnan.se</a><br>

	Telefon: 08-452 35 40<br><br>

 <p>Mer information och hj&auml;lp finns p&aring; <a href="http://webbstjarnan.se">Webbstj&auml;rnan.se &raquo;</a></p>
	</div>

 

 <div style="float: left;width: 48%; padding: 3px;">

 	<p style="color:#8F8F8F;font-family: sans-serif;font-size: 14px;font-weight: normal;margin-left: 3px;">Krav</p>

 	Du m&aring;ste uppfylla Webbstj&auml;rnans krav med en <a href"http://www.webbstjarnan.se/tavlingen/det-har-maste-du-gora/en-egen-om-sida/">om-sida</a> och <a href="http://www.webbstjarnan.se/tavlingen/det-har-maste-du-gora/webbstjarnans-logga/">logga</a>. Se v&aring;ra <a href="http://webbstjarnan.se/video">videoguider</a> f&ouml;r att se hur du klarar kraven. </p>

<p style="color:#8F8F8F;font-family: sans-serif;font-size: 11px;font-weight: normal;">F&ouml;r att f&aring; in loggan p&aring; din webbplats kan du &auml;ven g&aring; in p&aring; <a href="' . home_url() . '/wp-admin/widgets.php">Utseende > Widgets</a> och dra in widgeten som heter Webbstj&auml;rnan.</p>


 </div>	

 
 <div style="width: 100%;display: inline-block;">
 </div>

 

 ';
}

/* Widget part is based on Justin Tadlock (http://justintadlock.com) plugin Example Widget */
/**
 * Add function to widgets_init that'll load our widget.
 * @since 0.1
 */
add_action( 'widgets_init', 'ws_widgets' );

/**
 * Register our widget.
 * 'Webbstjarnan_Widget' is the widget class used below.
 *
 * @since 0.1
 */
function ws_widgets() {
	register_widget( 'Webbstjarnan_Widget' );
}

/**
 * Webbstjarnan Widget class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 * @since 0.1
 */
class Webbstjarnan_Widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function Webbstjarnan_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'webbstjarnan', 'description' => __('Webbstj&auml;rnans widget f&ouml;r att visa logan.', 'webbstjarnan') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'webbstjarnan-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'webbstjarnan-widget', __('Webbstj&auml;rnan', 'webbstjarnan'), $widget_ops, $control_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$logga = $instance['logga'];

		/* Before widget (defined by themes). */
		echo $before_widget;
		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;
		/* If show logga was selected, display the user's logga. */
		if ( $logga )
		{
			printf( '<a href="http://webbstjarnan.se" target="_blank"><img title="webbstjarnan" src="' . home_url() . '/wp-content/plugins/webbstjarnan/images/'. __('%1$s', 'webbstjarnan.') .'.png" alt="" width="206px" /></a>', $logga );
		}
		
		/* After widget (defined by themes). */
		echo $after_widget;
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );

		/* No need to strip tags for logga and show_logga. */
		$instance['logga'] = $new_instance['logga'];

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('Vi &auml;r med i Webbstj&auml;rnan', 'webbstjarnan'), 'logga' => 'text' );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Titel:', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		
		<!-- Logga: Select Box -->
		<p>
			<label for="<?php echo $this->get_field_id( 'logga' ); ?>"><?php _e('V&auml;lj logga:', 'webbstjarnan'); ?></label> 
			<select id="<?php echo $this->get_field_id( 'logga' ); ?>" name="<?php echo $this->get_field_name( 'logga' ); ?>" class="widefat" style="width:100%;">

				<option <?php if ( 'text' == $instance['format'] ) echo 'selected="selected"'; ?>>text</option>
				<option <?php if ( 'text-element' == $instance['format'] ) echo 'selected="selected"'; ?>>text-element</option>
				<option <?php if ( 'element' == $instance['format'] ) echo 'selected="selected"'; ?>>element</option>
				<option <?php if ( 'badge' == $instance['format'] ) echo 'selected="selected"'; ?>>badge</option>
			</select>
		</p>		

	<?php
	}
}
?>