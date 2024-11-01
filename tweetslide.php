<?php
/*
Plugin Name: Tweeter Feed Slider
Plugin URI: http://themewar.com/plugins/tweetslide
Description: Twitter Feed Slider. Simple twitter feed slider. Easy to customize and installation. Shortcode is available.
Version: 1.0
Author: Themewar
Author URI: http://themewar.com
License: 
Text Domain: themewar
*/

/*============================
 *  Define Directories
 =============================*/

define('TWEETSLIDE_ROOT', dirname( __FILE__ ));
define('TWEETSLIDE_ASSETS', plugin_dir_url( __FILE__ ).'/assets');
define('TWEETSLIDE_ASSETS_CSS', plugin_dir_url( __FILE__ ).'/assets/css');
define('TWEETSLIDE_ASSETS_IMG', plugin_dir_url( __FILE__ ).'/assets/images');
define('TWEETSLIDE_ASSETS_JS', plugin_dir_url( __FILE__ ).'/assets/js');


/*============================
 *  Enqueue Frontend css and js
 =============================*/

function theme_name_scripts() {
	wp_enqueue_style( 'tweet_slide_bx_css', plugin_dir_url( __FILE__ ).'assets/css/jquery.bxslider.css' );
	wp_enqueue_style( 'tweet_slide_style_css', plugin_dir_url( __FILE__ ).'assets/css/tweet_slide_style.css' );
	wp_enqueue_script( 'tweet_slide_bx_js', plugin_dir_url( __FILE__ ). 'assets/js/jquery.bxslider.min.js', array('jquery'), '', true );
        $effect = (get_option('_slide_effect', FALSE) != '') ? get_option('_slide_effect', FALSE) : 1;
        if($effect == 1)
        {
            wp_enqueue_script( 'tweet_slide_custome_js', plugin_dir_url( __FILE__ ). 'assets/js/tweet_slide.js', '', '', true );
        }
        else
        {
            wp_enqueue_script( 'tweet_slide_custome_js', plugin_dir_url( __FILE__ ). 'assets/js/tweet_fade.js', '', '', true );
        }
}

add_action( 'wp_enqueue_scripts', 'theme_name_scripts' );

/*============================
 *  Enqueue admin css and js
 =============================*/
function tweet_slide_enqueue_admin_style_and_js() {
    if(function_exists( 'wp_enqueue_media' ))
    {
        wp_enqueue_media();
    }
    else
    {
        wp_enqueue_style('thickbox');
        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');
    }
    wp_enqueue_style( 'tweetslide_admin_style', plugin_dir_url( __FILE__ ).'assets/css/tweet_slide_admin_style.css');
    wp_enqueue_script( 'tweetslide_admin_js', plugin_dir_url( __FILE__ ).'assets/js/twee_tslide_admin_script.js');
}
add_action( 'admin_enqueue_scripts', 'tweet_slide_enqueue_admin_style_and_js' );



/*============================
 *  Include Tweet Slide Class
 =============================*/
require_once (TWEETSLIDE_ROOT.'/class_tweet_slide.php');


//======================================
// Register Shortcode Buttons In TinyMce
//======================================

function tweetslide_register_button( $buttons ) {
    $shortcode = 'tweetslide';
    array_push( $buttons, "|", $shortcode );
    return $buttons;
}

function tweetslide_add_plugin( $plugin_array ) {
    $shortcode = 'tweetslide';
    $plugin_array[$shortcode] = plugin_dir_url( __FILE__ ).'assets/js/shortcode.js';
    return $plugin_array;
}

function tweetslide_recent_posts_button() {

   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
      return;
   }

   if ( get_user_option('rich_editing') == 'true' ) {
      add_filter( 'mce_external_plugins', 'tweetslide_add_plugin' );
      add_filter( 'mce_buttons', 'tweetslide_register_button' );
   }

}
add_action('init', 'tweetslide_recent_posts_button');



//======================================
// Create Settings Page
//======================================

add_action('admin_menu', 'tweetslide_setting_page_activate');

if(!function_exists('tweetslide_setting_page_activate'))
{
    function tweetslide_setting_page_activate() {
        add_options_page( 'Tweet Slide Settings', 'Tweet Slide Settings', 'manage_options', 'tweet_slide', 'tweet_slide_settings_page' );
    }
}

if(!function_exists('tweet_slide_settings_page'))
{
    function tweet_slide_settings_page()
    {
        if(isset($_POST['tweetslidesubmit']) && $_POST['tweetslidesubmit'] == 'Save')
        {
             update_option('_twit_screen_name', $_POST['_twit_screen_name']);
             update_option('_consumer_key', $_POST['_consumer_key']);
             update_option('_consumer_secret', $_POST['_consumer_secret']);
             update_option('_access_token', $_POST['_access_token']);
             update_option('_access_token_secret', $_POST['_access_token_secret']);
             update_option('_font_size', $_POST['_font_size']);
             update_option('_font_color', $_POST['_font_color']);
             update_option('_slide_effect', $_POST['_slide_effect']);
             update_option('_slide_nav', $_POST['_slide_nav']);
             update_option('_tweet_icon_or_image', $_POST['_tweet_icon_or_image']);
             update_option('_tweet_time', $_POST['_tweet_time']);
             update_option('_tweet_profile_name', $_POST['_tweet_profile_name']);
             update_option('_slide_view_style', $_POST['_slide_view_style']);
             update_option('_slide_bg_img', $_POST['_slide_bg_img']);
             update_option('_slide_bg_color', $_POST['_slide_bg_color']);
             update_option('_slide_padding_top', $_POST['_slide_padding_top']);
             update_option('_slide_padding_bot', $_POST['_slide_padding_bot']);
             update_option('_slide_item_number', $_POST['_slide_item_number']);
             
             
             
             $urls = admin_url().'options-general.php?page=tweet_slide';
             echo '<script>';
                echo 'window.location.href='.$urls;
             echo '</script>';
             
        }
        
        $_twit_screen_name = get_option('_twit_screen_name', FALSE);
        $_consumer_key = get_option('_consumer_key', FALSE);
        $_consumer_secret = get_option('_consumer_secret', false);
        $_access_token = get_option('_access_token', FALSE);
        $_access_token_secret = get_option('_access_token_secret', FALSE);
        $_font_size = get_option('_font_size', FALSE);
        $_font_color = get_option('_font_color', FALSE);
        $_slide_effect = get_option('_slide_effect', FALSE);
        $_slide_nav = get_option('_slide_nav', FALSE);
        $_tweet_icon_or_image = get_option('_tweet_icon_or_image', FALSE);
        $_tweet_time = get_option('_tweet_time', FALSE);
        $_tweet_profile_name = get_option('_tweet_profile_name', FALSE);
        $_slide_view_style = get_option('_slide_view_style', FALSE);
        $_slide_bg_img = get_option('_slide_bg_img', FALSE);
        $_slide_bg_color = get_option('_slide_bg_color', FALSE);
        $_slide_padding_top = get_option('_slide_padding_top', FALSE);
        $_slide_padding_bot = get_option('_slide_padding_bot', FALSE);
        $_slide_item_number = get_option('_slide_item_number', FALSE);
        ?>
        <div class="wrap">
            <h2>Tweet Slider Settings</h2>
            <form method="post" action="#">
                <table class="wp-list-table widefat fixed">
                    <thead>
                        <tr>
                            <th class="labelTH">
                                <strong>API Settings</strong>
                            </th>
                            <th class="fieldTH">
                                <strong>Shortcode: [tweetslide]</strong>
                            </th>
                        </tr>
                        <tr>
                            <td class="labelTD">
                                Twitter Screen Name
                            </td>
                            <td class="fieldTD">
                                <input required="required" type="text" id="_twit_screen_name" name="_twit_screen_name" value="<?php echo $_twit_screen_name; ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="labelTD">
                                Consumer Key
                            </td>
                            <td class="fieldTD">
                                <input required="required" type="text" id="_consumer_key" name="_consumer_key" value="<?php echo $_consumer_key; ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="labelTD">
                                Consumer Secret
                            </td>
                            <td class="fieldTD">
                                <input required="required" type="text" id="_consumer_secret" name="_consumer_secret" value="<?php echo $_consumer_secret; ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="labelTD">
                                Access Token
                            </td>
                            <td class="fieldTD">
                                <input required="required" type="text" id="_access_token" name="_access_token" value="<?php echo $_access_token; ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="labelTD">
                                Access Token Secret
                            </td>
                            <td class="fieldTD">
                                <input required="required" type="text" id="_access_token_secret" name="_access_token_secret" value="<?php echo $_access_token_secret; ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <th class="labelTH">
                                <strong>Appearance Settings</strong>
                            </th>
                            <th class="fieldTH">
                                <strong>Shortcode: [tweetslide]</strong>
                            </th>
                        </tr>
                        <tr>
                            <td class="labelTD">
                                View Style
                            </td>
                            <td class="fieldTD">
                                <select name="_slide_view_style" id="_slide_view_style">
                                    <option value="">Select View Style</option>
                                    <option value="1" <?php if($_slide_view_style == 1) { echo 'selected="selected"';} ?>>Parallax</option>
                                    <option value="2" <?php if($_slide_view_style == 2) { echo 'selected="selected"';} ?>>Non Parallax</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="labelTD">
                                Parallax BG Image
                            </td>
                            <td class="fieldTD">
                                <input class="tweetslide_url" id="_slide_bg_img" type="text" name="_slide_bg_img" value="<?php echo $_slide_bg_img ?>">
                                <a href="#" class="tweetslide_upload button">Upload</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="labelTD">
                                Non Parallax BG Color
                            </td>
                            <td class="fieldTD">
                                <input placeholder="#F1F1F1" id="_slide_bg_color" type="text" name="_slide_bg_color" value="<?php echo $_slide_bg_color; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td class="labelTD">
                                Section Padding Top
                            </td>
                            <td class="fieldTD">
                                <input placeholder="100px" id="_slide_padding_top" type="text" name="_slide_padding_top" value="<?php echo $_slide_padding_top; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td class="labelTD">
                                Section Padding Bottom
                            </td>
                            <td class="fieldTD">
                                <input placeholder="100px" id="_slide_padding_bot" type="text" name="_slide_padding_bot" value="<?php echo $_slide_padding_bot; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td class="labelTD">
                                Slide Effect
                            </td>
                            <td class="fieldTD">
                                <select name="_slide_effect" id="_slide_effect">
                                    <option value="">Select Slide Effect</option>
                                    <option value="1" <?php if($_slide_effect == 1) { echo 'selected="selected"';} ?>>Slide</option>
                                    <option value="2" <?php if($_slide_effect == 2) { echo 'selected="selected"';} ?>>Fade</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="labelTD">
                                Navigation Type
                            </td>
                            <td class="fieldTD">
                                <select name="_slide_nav" id="_slide_nav">
                                    <option value="">Select Slide Nav</option>
                                    <option value="1" <?php if($_slide_nav == 1) { echo 'selected="selected"';} ?>>Arrow</option>
                                    <option value="2" <?php if($_slide_nav == 2) { echo 'selected="selected"';} ?>>Bullet</option>
                                    <option value="3" <?php if($_slide_nav == 3) { echo 'selected="selected"';} ?>>Both</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="labelTD">
                                Icon or Profile Pic
                            </td>
                            <td class="fieldTD">
                                <select name="_tweet_icon_or_image" id="_tweet_icon_or_image">
                                    <option value="">Icon Or Image</option>
                                    <option value="1" <?php if($_tweet_icon_or_image == 1) { echo 'selected="selected"';} ?>>Icon</option>
                                    <option value="2" <?php if($_tweet_icon_or_image == 2) { echo 'selected="selected"';} ?>>Profile Picture</option>
                                    <option value="3" <?php if($_tweet_icon_or_image == 3) { echo 'selected="selected"';} ?>>None</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="labelTD">
                                Display Tweet Time
                            </td>
                            <td class="fieldTD">
                                <select name="_tweet_time" id="_tweet_time">
                                    <option value="">Wanna Show Time?</option>
                                    <option value="1" <?php if($_tweet_time == 1) { echo 'selected="selected"';} ?>>Show</option>
                                    <option value="2" <?php if($_tweet_time == 2) { echo 'selected="selected"';} ?>>Hide</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="labelTD">
                                Display Profile Name
                            </td>
                            <td class="fieldTD">
                                <select name="_tweet_profile_name" id="_tweet_profile_name">
                                    <option value="">Wanna Show Profile Name?</option>
                                    <option value="1" <?php if($_tweet_profile_name == 1) { echo 'selected="selected"';} ?>>Show</option>
                                    <option value="2" <?php if($_tweet_profile_name == 2) { echo 'selected="selected"';} ?>>Hide</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="labelTD">
                                Font Size
                            </td>
                            <td class="fieldTD">
                                <input placeholder="24px"  type="text" id="_font_size" name="_font_size" value="<?php echo $_font_size; ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="labelTD">
                                Font Color
                            </td>
                            <td class="fieldTD">
                                <input placeholder="#FFFFFF"  type="text" id="_font_color" name="_font_color" value="<?php echo $_font_color; ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="labelTD">
                                Number of Slider Item
                            </td>
                            <td class="fieldTD">
                                <input id="_slide_item_number" type="text" name="_slide_item_number" value="<?php echo $_slide_item_number; ?>">
                            </td>
                        </tr>
                        
                        <tr>
                            <td class="labelTD">
                                &nbsp;
                            </td>
                            <td class="fieldTD">
                                <input type="submit" name="tweetslidesubmit" value="Save" class="button button-primary button-large"/>
                            </td>
                        </tr>
                    </thead>


                </table>
            </form>
        </div>
        <?php
    }
}

//======================================
// Time and Date Formating
//======================================
function tweet_slide_time_ago($time)
{
   $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
   $lengths = array("60","60","24","7","4.35","12","10");

   $now = time();

       $difference     = $now - $time;
       $tense         = "ago";

   for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
       $difference /= $lengths[$j];
   }

   $difference = round($difference);

   if($difference != 1) {
       $periods[$j].= "s";
   }

   return "$difference $periods[$j] ago";
}



//======================================
// Tweet slide Shortcode
//======================================
function tweet_slide_shortcode_generator($atts, $content = null)
{
    
    $tweet_return = '';
    
    $screenName = get_option('_twit_screen_name', FALSE);
    $consumer_key = get_option('_consumer_key', FALSE);
    $consumer_secret = get_option('_consumer_secret', FALSE);
    $access_token = get_option('_access_token', FALSE);
    $token_secret = get_option('_access_token_secret', FALSE);
    $_slide_item_number = get_option('_slide_item_number', FALSE);
    
    $_slide_view_style = (get_option('_slide_view_style', FALSE) != '') ? get_option('_slide_view_style', FALSE): 1;
    $_slide_bg_img = (get_option('_slide_bg_img', FALSE)) ? get_option('_slide_bg_img', FALSE) : '';
    $_slide_bg_color = (get_option('_slide_bg_color', FALSE) != '') ? get_option('_slide_bg_color', FALSE) : '';
    $_slide_padding_top = get_option('_slide_padding_top', FALSE);
    $_slide_padding_bot = get_option('_slide_padding_bot', FALSE);
    $tweets_to_display = ($_slide_item_number != '' && $_slide_item_number > 0) ? $_slide_item_number : 3;
    
    if($screenName == '' || $consumer_key == '' || $consumer_secret == '' || $access_token == '' || $token_secret == '')
    {
        $tweet_return .= '<p class="tweetSlideError">Please set up all information in <strong>Settings -> Tweet Slide Settings</strong>.</p>';
        return $tweet_return;
    }
    
    $options = array(
        'consumer_key'          => $consumer_key,
        'consumer_secret'       => $consumer_secret,
        'access_token'          => $access_token,
        'access_token_secret'   => $token_secret,
        'twitter_screen_name'   => $screenName,
        'tweets_to_display'    => $_slide_item_number
    );
    
    
    $tweetSlide = new tweetSlide($options);
    $tweets = $tweetSlide->get_tweet_array();
    $tc = count($tweets);
    
    $sectionStyle = '';
    $sectionClass = '';
    
    if($_slide_view_style == 2)
    {
        $sectionClass = 'tweetBrallax';
        if($_slide_bg_color != '')
        {
            $sectionStyle .= 'background: #'.$_slide_bg_color.'; ';
        }
    }
    else
    {
        $sectionClass = '';
        if($_slide_bg_img != ''):
        $sectionStyle .= 'background: url('.esc_url($_slide_bg_img).') repeat center center fixed; ';
        endif;
    }
    if($_slide_padding_top != '')
    {
        $sectionStyle .= 'padding-top: '.esc_html($_slide_padding_top).'; ';
    }
    
    if($_slide_padding_bot != '')
    {
        $sectionStyle .= 'padding-bottom: '.esc_html($_slide_padding_bot).'; ';
    }
    
    
    $_font_size = (get_option('_font_size', FALSE)) ? get_option('_font_size', FALSE) : '';
    $_font_color = (get_option('_font_color', FALSE) != '') ? get_option('_font_color', FALSE) : '';
    $_tweet_icon_or_image = (get_option('_tweet_icon_or_image', FALSE) != '') ? get_option('_tweet_icon_or_image', FALSE) : 1;
    $_slide_nav = (get_option('_slide_nav', FALSE) != '') ? get_option('_slide_nav', FALSE) : 3;
    $_tweet_time = (get_option('_tweet_time', FALSE) != '') ? get_option('_tweet_time', FALSE) : 1;
    $_tweet_profile_name = (get_option('_tweet_profile_name', FALSE) != '') ? get_option('_tweet_profile_name', FALSE) : 1;
    
    
    
    $slideClass = '';
    if($_slide_nav == 1)
    {
        $slideClass = 'withArrow';
    }
    elseif($_slide_nav == 2)
    {
        $slideClass = 'widthBullet';
    }
    else
    {
        $slideClass = 'bothNavigation';
    }
    
    $style = '';
    if($_font_size != '')
    {
        $style .= 'font-size: '.esc_html($_font_size).'; ';
    }
    if($_font_color != '')
    {
        $style .= 'color: '.esc_html($_font_color).'; ';
    }
    
    
    
    $tweet_return .= '<section class="tweetSlideSection '.$slideClass.' '.$sectionClass.'" id="tweetSlideSection" style="'.$sectionStyle.'">';
        $tweet_return .= '<div class="tweetSlideOverlay"></div>';
        $tweet_return .= '<div class="sliderContent">';
            $tweet_return .= '<div class="tweetsliderColumn">';
                if(is_array($tweets))
                {
                    $num = 1;
                    $tweet_return .= '<ul class="bxslider">';
                    foreach($tweets as $tweet)
                    {
                        
                        $tweet_return .= '<li>';
                            $tweet_return .= '<div class="singleTwitt">';
                            if($_tweet_icon_or_image == 1):
                                $tweet_return .= '<div class="twittIcon">';
                                $tweet_return .= '</div>';
                            elseif($_tweet_icon_or_image == 2):
                                $tweet_return .= '<div class="twittImage">';
                                    $tweet_return .= '<img src="'.$tweet['user']['profile_image_url'].'" alt=""/>';
                                $tweet_return .= '</div>';
                            endif;
                            
                            $tweet_return .= '<div class="twittDetails" style="'.$style.'">
                                '.__($tweet['text'], 'themewar').'
                            </div>';
                            
                            if($_tweet_profile_name != 2):
                            $tweet_return .= '<div class="twittMeta">
                                <a style="color: '.$_font_color.';" href="https://twitter.com/'.$tweet['user']['screen_name'].'">@'.$tweet['user']['screen_name'].'</a>
                            </div>';
                            endif;
                            if($_tweet_time != 2):
                                $time = strtotime($tweet['created_at']);
                                $date = tweet_slide_time_ago($time);
                            $tweet_return .= '<div class="twitTime">
                                '.__($date, 'themewar').'
                            </div>';
                            endif;
                            
                        $tweet_return .= '</div>';
                        $tweet_return .= '</li>';
                        
                        if($tweets_to_display == $num)
                        {
                            break;
                        }
                        $num++;
                        
                    }
                    $tweet_return .= '</ul>';
                }
                else
                {
                    
                }
                
            $tweet_return .= '</div>';
        $tweet_return .= '</div>';
    $tweet_return .= '</section>';
    
    
    
    return $tweet_return;
}

add_shortcode( 'tweetslide', 'tweet_slide_shortcode_generator'  );


//======================================
// Plugin Action Links
//======================================

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'add_tweet_slide_settings_link' );

if(!function_exists('add_tweet_slide_settings_link'))
{
    function add_tweet_slide_settings_link ( $links ) 
    {
        $mylinks = array(
           '<a href="' . admin_url( 'options-general.php?page=tweet_slide' ) . '">Settings</a>',
        );
        return array_merge( $links, $mylinks );
    }
}