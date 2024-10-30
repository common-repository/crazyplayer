<?php
/*
Plugin Name: Crazy Player
Plugin URI: http://crazycafe.net/plugins
Description: This plugin will mp3 player in your wordpress site. 
Author: Crazy Cafe
Author URI: http://crazycafe.net
Version: 1.0
*/

// Including Files
function crazyplayer_files(){
    wp_enqueue_script( 'crazyplayer-main-js', plugins_url( '/assets/js/crazyplayer.min.js' , __FILE__ ), array() );
    wp_enqueue_style( 'crazyplayer-main', plugins_url( '/assets/css/crazyplayer.min.css' , __FILE__ ), array(), '1.0', 'all' );
}
add_action('wp_enqueue_scripts', 'crazyplayer_files');

// Creating custom post
function crazyplayer_custom_post() {
    register_post_type( 'crazycafe-player',
        array(
            'labels' => array(
                'name' => __( 'Players' ),
                'singular_name' => __( 'Player' )
            ),
            'supports' => array('title', 'editor', 'thumbnail', 'page-attributes'),
            'public' => true,
            'menu_icon' => 'dashicons-format-audio'
        )
    );
}
add_action( 'init', 'crazyplayer_custom_post' );

// Including CMB2 Metabox
if ( file_exists(  __DIR__ . '/inc/cmb2/init.php' ) ) {
  require_once  __DIR__ . '/inc/cmb2/init.php';
} elseif ( file_exists(  __DIR__ . '/inc/CMB2/init.php' ) ) {
  require_once  __DIR__ . '/inc/CMB2/init.php';
}

// Registering Metaboxes
function crazyplayer_metaboxes() {

    $prefix = '_crazyplayer_';

    $crazyplayer_metabox = new_cmb2_box( array(
        'id'            => 'crazyplayer_metabox_id',
        'title'         => __( 'Build playlist', 'cmb2' ),
        'object_types'  => array( 'crazycafe-player', ), // Post type
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true,
    ) );

    
    $crazyplayer_metabox_playlist = $crazyplayer_metabox->add_field( array(
        'id'         => $prefix . 'playlist',
        'type'       => 'group'
    ) );
    
    
    $crazyplayer_metabox->add_group_field( $crazyplayer_metabox_playlist, array(
        'name' => 'Music title',
        'desc'   => 'Type music title here',
        'id'   => 'title',
        'type' => 'text',
    ) );
    
    $crazyplayer_metabox->add_group_field( $crazyplayer_metabox_playlist, array(
        'name' => 'Music author',
        'desc'   => 'Type music author name here',
        'id'   => 'author',
        'type' => 'text',
    ) );
    
    $crazyplayer_metabox->add_group_field( $crazyplayer_metabox_playlist, array(
        'name' => 'Upload or add mp3 URL',
        'desc'   => 'You can upload or add mp3 URL here.',
        'id'   => 'mp3',
        'type' => 'file',
    ) );
    
    $crazyplayer_metabox->add_group_field( $crazyplayer_metabox_playlist, array(
        'name' => 'Upload or add mp3 thumbnail',
        'desc'   => 'You can upload or add mp3 thumbnail here.',
        'id'   => 'thumb',
        'type' => 'file',
    ) );
    
    $crazyplayer_metabox->add_group_field( $crazyplayer_metabox_playlist, array(
        'name' => 'Song Lyric',
        'desc'   => 'Add lyric here. It must be in lyric format. See documentation <a target="_blank" href="#">here.</a>',
        'id'   => 'lyric',
        'type' => 'textarea_code',
    ) );

}
add_action( 'cmb2_admin_init', 'crazyplayer_metaboxes' );

// Registering shortcode
function crazyplayer_shortcode($atts){
    extract( shortcode_atts( array(
        'id' => '',
        'theme' => '#72e6cf',
        'autoplay' => 'false',
        'showlrc' => 'false',
        'style' => 'style1',
        'bgcolor' => '#1e2226',
    ), $atts) );
     
    $q = new WP_Query(
        array('posts_per_page' => 1, 'post_type' => 'crazycafe-player', 'p' =>$id)
        );      
         
    $list = '<div id="crazycafe-player-'.$id.'" class="aplayer">';
    
    if($showlrc == 'true') {
    while($q->have_posts()) : $q->the_post();
        $idd = get_the_ID();
        $crazycafe_musics = get_post_meta($idd, '_crazyplayer_playlist', true);
    
        foreach($crazycafe_musics as $crazycafe_music) {
            if($crazycafe_music['lyric']) {
            $list .= '
                <pre class="aplayer-lrc-content">'.$crazycafe_music['lyric'].'</pre>
            ';
            }
        }
    endwhile;
    }
    
    $list .='</div>
    <script>
        var ap4 = new APlayer({
            element: document.getElementById(\'crazycafe-player-'.$id.'\'),
            narrow: false,
            autoplay: '.$autoplay.',
            showlrc: '.$showlrc.',
            theme: \''.$theme.'\',
            style: \''.$style.'\',
            bgcolor: \''.$bgcolor.'\',
            music: [
    ';
    while($q->have_posts()) : $q->the_post();
        $idd = get_the_ID();
        $crazycafe_musics = get_post_meta($idd, '_crazyplayer_playlist', true);
    
        foreach($crazycafe_musics as $crazycafe_music) {
        $list .= '
            {
                title: \''.$crazycafe_music['title'].'\',
                author: \''.$crazycafe_music['author'].'\',
                url: \''.$crazycafe_music['mp3'].'\',
                pic: \''.$crazycafe_music['thumb'].'\'
            },
        ';
        }
    endwhile;
    $list.= '
            ]
        });
        ap4.init();    
    </script>    
    ';
    wp_reset_query();
    return $list;
}
add_shortcode('crazyplayer', 'crazyplayer_shortcode');  

function crazyplayer_narrow_shortcode( $atts, $content = null  ) {
 
    extract( shortcode_atts( array(
        'id' => 'narrowp',
        'title' => '',
        'artist' => '',
        'url' => '',
        'thumb' => '',
        'theme' => '#cf3c3f',
        'bgcolor' => '#1e2226',
        'autoplay' => 'true',
    ), $atts ) );
 
    return '
        <div id="crazyplayer-narrow-'.$id.'" class="aplayer"></div>
        <script>
        var ap1 = new APlayer({
            element: document.getElementById(\'crazyplayer-narrow-'.$id.'\'),
            narrow: true,
            autoplay: '.$autoplay.',
            showlrc: false,
            theme: \''.$theme.'\',
            bgcolor: \''.$bgcolor.'\',
            music: {
                title: \''.$title.'\',
                author: \''.$artist.'\',
                url: \''.$url.'\',
                pic: \''.$thumb.'\'
            }
        });
        ap1.init();        
        </script>
    ';
}   
add_shortcode('crazyplayer_narrow', 'crazyplayer_narrow_shortcode');

// Crazyplayer MCE Button
function crazyplayer_add_mce_button() {
	if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
		return;
	}
	if ( 'true' == get_user_option( 'rich_editing' ) ) {
		add_filter( 'mce_external_plugins', 'crazyplayer_add_tinymce_plugin' );
		add_filter( 'mce_buttons', 'crazyplayer_register_mce_button' );
	}
}
add_action('admin_head', 'crazyplayer_add_mce_button');

function crazyplayer_add_tinymce_plugin( $plugin_array ) {
	$plugin_array['crazyplayer_mce_button'] = plugin_dir_url( __FILE__ ) .'/assets/js/crazyplayer-mce-button.js';
	return $plugin_array;
}

function crazyplayer_register_mce_button( $buttons ) {
	array_push( $buttons, 'crazyplayer_mce_button' );
	return $buttons;
}