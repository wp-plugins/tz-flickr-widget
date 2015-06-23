<?php
/**
 * Widget - Pretty Flickr Widget
 *
 * @package Pretty Flickr Widget
 * @subpackage Classes
 * For another improvement, you can drop email to support@templaza.com or visit http://www.templaza.com
 *
 **/

class Pretty_Flickr_Widget extends WP_Widget {

    var $prefix;
    var $textdomain;

    function __construct(){
        // Set default variable for the widget instances
        $this->prefix = 'tzflickr';
        $this->textdomain = 'tz-flickr-widget';

        // Set up the widget control options
        $control_options = array(
            'width' => 300,
            'height' => 350,
            'id_base' => $this->prefix
        );
        $widget_options = array('classname' => 'widget_flickr', 'description' => __( 'Displays a Flickr photo stream from ID with Pretty photo', $this->textdomain ) );

        // Create the widget
        $this->WP_Widget($this->prefix, __('TZ Flickr Widget', $this->textdomain), $widget_options, $control_options );

        // Load additional scripts and styles file to the widget admin area
        add_action( 'load-widgets.php', array(&$this, 'widget_admin') );

        // Load the widget stylesheet for the widgets screen.
        if ( is_active_widget(false, false, $this->id_base, true) && !is_admin() ) {
            wp_enqueue_style( 'tz-flickr', TZ_FLICKR_WIDGET_URL . 'css/widget.css', false, 0.7, 'screen' );
            wp_enqueue_style( 'tz-flickr-pretty', TZ_FLICKR_WIDGET_URL . 'css/prettyPhoto.css', false, 0.7, 'screen' );
        }
    }

    /**
     * Push additional script and style files to the widget admin area
     * @since 1.2.1
     **/
    function widget_admin() {
        wp_enqueue_style( 'tz-flickr-admin', TZ_FLICKR_WIDGET_URL . 'css/widget.css' );
    }

    /**
     * Push the widget stylesheet widget.css into widget admin page
     * @since 1.2.1
     **/
    function widget( $args, $instance ) {
        extract( $args );
        wp_enqueue_script( 'Tz-flickr-admin', TZ_FLICKR_WIDGET_URL . 'js/jflickrfeed.min.js' );
        wp_enqueue_script( 'Tz-flickr-admin-pretty', TZ_FLICKR_WIDGET_URL . 'js/jquery.prettyPhoto.js' );

        // Set up the arguments for wp_list_categories().
        $cur_arg = array(
            'title'			=> $instance['title'],
            'flickr_id'		=> $instance['flickr_id'],
            'count'			=> (int) $instance['count'],
            'prettyphoto'	=> $instance['prettyphoto'],
            'size'			=> isset( $instance['size'] ) ? $instance['size'] : 's',
        );

        extract( $cur_arg );
        $id_pre = rand(2,200);

        $flickr_class = 'pretty_flickr'.$id_pre.'';
        // print the before widget
        echo $before_widget;

        if ( $title )
            echo $before_title . $title . $after_title;

        // Wrap the widget
        ?>

        <div class="tz-flickr">
          <ul class="flickr pretty_flickr <?php echo $flickr_class; ?>"></ul>
        </div>

        <?php
        // Print the after widget
        echo $after_widget;
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function(){
                jQuery('.<?php echo $flickr_class; ?>').jflickrfeed({
                    limit: <?php echo $count;?>,
                    qstrings: {
                        id: '<?php echo $flickr_id;?>'
                    },
                    itemTemplate: '<li>'+
                    '<a target="<?php if($prettyphoto !='checked'){ _e('_blank',$this->textdomain);}?>" rel="<?php if($prettyphoto =='checked'){ _e('prettyPhoto_flickr[pp_gal]',$this->textdomain);}?>"' +
                    'href="<?php if($prettyphoto =='checked'){ _e('/{image}}',$this->textdomain);}else{ _e('/{link}}',$this->textdomain);} ?>" title="/{title}}">' +
                    '<img src="/{image_<?php echo $size; ?>}}" alt="/{title}}" />' +
                    '</a>' +
                    '</li>'
                },function(data) {
                    jQuery(".flickr a[rel^='prettyPhoto']").prettyPhoto();
                });
            });
        </script>
        <?php
    }


    /**
     * Widget form function
     * @since 1.2.1
     **/
    function form( $instance ) {
        // Set default form values.
        $defaults = array(
            'title'			=> esc_attr__( 'Flickr Widget', $this->textdomain ),
            'flickr_id'		=> '', // 36587311@N08
            'count'			=> 9,
            'size'			=> 's',
            'prettyphoto'	=> 'checked',
        );
        $instance = wp_parse_args( (array) $instance, $defaults );

        $sizes = array(
            's' => esc_attr__( 'Standard', $this->textdomain ),
            't' => esc_attr__( 'Thumbnail', $this->textdomain ),
            'm' => esc_attr__( 'Medium', $this->textdomain )
        );
        ?>
        <ul class="tz-flickr-admin">
            <li>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Flickr Title', $this->textdomain); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
            </li>
            <li>
                <label for="<?php echo $this->get_field_id('flickr_id'); ?>"><?php _e('Flickr ID', $this->textdomain); ?></label>
                <input id="<?php echo $this->get_field_id('flickr_id'); ?>" name="<?php echo $this->get_field_name('flickr_id'); ?>" type="text" value="<?php echo esc_attr( $instance['flickr_id'] ); ?>" />
                <span class="controlDesc"><?php _e( 'Put the flickr ID here. Example: 36587311@N08', $this->textdomain ); ?></span>
            </li>
            <li class="prettyphoto">
                <label for="<?php echo $this->get_field_id('flickr_id'); ?>"><?php _e('Use Pretty Photo', $this->textdomain); ?></label>
                <input id="<?php echo $this->get_field_id('prettyphoto'); ?>" name="<?php echo $this->get_field_name('prettyphoto'); ?>" value="checked"<?php echo esc_attr($instance['prettyphoto']);?> type="checkbox"  />
                <span class="controlDesc"><?php _e( 'Checked to open photo with Pretty Photo', $this->textdomain ); ?></span>
            </li>
            <li class="number">
                <label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Number', $this->textdomain); ?></label>
                <span class="controlDesc"><?php _e( 'Number of images show on frontend', $this->textdomain ); ?></span>
                <input class="column-last" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo esc_attr( $instance['count'] ); ?>" size="3" />
            </li>
            <li>
                <label for="<?php echo $this->get_field_id('sizes'); ?>"><?php _e( 'Sizes', $this->textdomain ); ?></label>
                <span class="controlDesc"><?php _e( 'Represents the size of the image', $this->textdomain ); ?></span>
                <select id="<?php echo $this->get_field_id( 'size' ); ?>" name="<?php echo $this->get_field_name( 'size' ); ?>">
                    <?php foreach ( $sizes as $k => $v ) { ?>
                        <option value="<?php echo $k; ?>" <?php selected( $instance['size'], $k ); ?>><?php echo $v; ?></option>
                    <?php } ?>
                </select>
            </li>
        </ul>
        <?php

    }

    /**
     * Widget update function
     * @since 1.2.1
     **/
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['flickr_id'] 		= strip_tags($new_instance['flickr_id']);
        $instance['count'] 			= (int) $new_instance['count'];
        $instance['size']			= strip_tags($new_instance['size']);
        $instance['title']			= strip_tags($new_instance['title']);
        $instance['prettyphoto']	= strip_tags($new_instance['prettyphoto']);

        return $instance;
    }



}