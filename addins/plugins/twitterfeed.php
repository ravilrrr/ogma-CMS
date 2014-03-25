<?php 

// Twitter Feed plugin for OGMA CMS

Plugins::registerPlugin( 
		'twitterfeed',
        'Twitter Feed',
        'Twitter Feed plugin for OGMA',
        '0.0.1',
        'Mike Swan',
        'http://www.digimute.com/'
        );

class TwitterFeed {

    public function __construct() {
 
    }

    public static function init(){
        Actions::addAction('admin-add-sidebar','Menu::addSidebarMenu',1,array("Twitter",'','twitterfeed&action=edit','fa fa-fw fa-twitter'));
        Actions::addAction('admin-add-to-dashboard','Menu::addDashboardItem',1,array("twitter",'','twitterfeed&action=edit','fa fa-fw fa-twitter'));
        $language = Core::$site['language'];
        Lang::mergeLanguage(Core::$settings['pluginpath'].'twitterfeed'.DS.'lang'.DS.$language.'.lang.php');
       

    }

    public static function initFrontend(){
        // Frontend stuff
    }
    
    public static function initShortcodes(){
        // initialize shortcodes 
        Shortcodes::addShortcode('twitterfeed','Twitterfeed::getTweets', '[twitterfeed /]');
    }

    public static function buildBaseString($baseURI, $method, $params) {
        $r = array();
        ksort($params);
        foreach($params as $key=>$value){
            $r[] = "$key=" . rawurlencode($value);
        }
        return $method."&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r));
    }

    public static function buildAuthorizationHeader($oauth) {
        $r = 'Authorization: OAuth ';
        $values = array();
        foreach($oauth as $key=>$value)
            $values[] = "$key=\"" . rawurlencode($value) . "\"";
        $r .= implode(', ', $values);
        return $r;
    }

    public static function admin(){
        $action = Core::getAction();            // get URI action
        $id = Core::getID();                    // get page ID
        
        if ($action=="update"){
            $settings = Xml::xml2array(ROOT . 'addins/plugins/twitterfeed/data/settings.xml');
            $savesettings = $settings;
            foreach ($settings as $item=>$val){
                if (isset($_POST['post-'.$item])) {
                    $savesettings[$item]=$_POST['post-'.$item];
                }
            }
            $ret=file_put_contents(ROOT . 'addins/plugins/twitterfeed/data/settings.xml', Xml::arrayToXml($savesettings));
            if ($ret){
                 Core::addAlert( Form::showAlert('success', __("UPDATED",array(":record"=>"",":type"=>__("SETTINGS"))) ));
            } else {
                Core::addAlert( Form::showAlert('error', __("UPDATEDFAIL",array(":record"=>"",":type"=>__("SETTINGS"))) ));
            }
            $action='edit'; 
            $_GET['action']='edit';  
        }

        $settings = Xml::xml2array(ROOT . 'addins/plugins/twitterfeed/data/settings.xml');

        if ($action=="edit"){

            Core::getAlerts();
            
            echo '<div class="col-md-12">';

            $ogmaForm = new Form();
            
            $ogmaForm->addHeader(__("TWITTER_SETTINGS"));
           
            $ogmaForm->startTabHeaders();

            $ogmaForm->createTabHeader(array('main'=>'Main'),true);
            
            Actions::executeAction('twitterfeed-tab-header');

            $ogmaForm->endTabHeaders();

            $ogmaForm->createForm('load.php?tbl=twitterfeed&action=update');

            $ogmaForm->startTabs();
            $ogmaForm->createTabPane('main',true);
            $ogmaForm->displayField('post-screenname', __("TWITTER_SCREENNAME") ,  'text', '',$settings['screenname']);
            $ogmaForm->displayField('post-count', __("TWITTER_COUNT") ,  'text', '',$settings['count']);
            $ogmaForm->displayField('post-authtoken',__("TWITTER_AUTHTOKEN"),  'text', '',$settings['authtoken']);
            $ogmaForm->displayField('post-authtokensecret',__("TWITTER_AUTHTOKENSECRET"),  'text', '',$settings['authtokensecret']);
            $ogmaForm->displayField('post-consumerkey',__("TWITTER_CONSUMER"), 'text', '',$settings['consumerkey']);
            $ogmaForm->displayField('post-consumersecret',__("TWITTER_CONSUMERSECRET"),  'text', '',$settings['consumersecret']);  
         
            Actions::executeAction('twitterfeed-tab-new');

            $ogmaForm->endTabs();
            
            $ogmaForm->formButtons();
            $ogmaForm->endForm();

            $ogmaForm->show();

           echo '</div>';
        }
    }


    public static function returnTweet($num = null){
        
        $settings = Xml::xml2array(ROOT . 'addins/plugins/twitterfeed/data/settings.xml');

        if ($num) $settings['count'] = $num;

        $oauth_access_token         = $settings['authtoken'];
        $oauth_access_token_secret  = $settings['authtokensecret'];
        $consumer_key               = $settings['consumerkey'];
        $consumer_secret            = $settings['consumersecret'];

        $twitter_timeline           = "user_timeline";  //  mentions_timeline / user_timeline / home_timeline / retweets_of_me

        //  create request
            $request = array(
                'screen_name'       => $settings['screenname'],
                'count'             => $settings['count']
            );

        $oauth = array(
            'oauth_consumer_key'        => $consumer_key,
            'oauth_nonce'               => time(),
            'oauth_signature_method'    => 'HMAC-SHA1',
            'oauth_token'               => $oauth_access_token,
            'oauth_timestamp'           => time(),
            'oauth_version'             => '1.0'
        );

        //  merge request and oauth to one array
            $oauth = array_merge($oauth, $request);

        //  do some magic
            $base_info              = Twitterfeed::buildBaseString("https://api.twitter.com/1.1/statuses/$twitter_timeline.json", 'GET', $oauth);
            $composite_key          = rawurlencode($consumer_secret) . '&' . rawurlencode($oauth_access_token_secret);
            $oauth_signature            = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
            $oauth['oauth_signature']   = $oauth_signature;

        //  make request
            $header = array(Twitterfeed::buildAuthorizationHeader($oauth), 'Expect:');
            $options = array( CURLOPT_HTTPHEADER => $header,
                              CURLOPT_HEADER => false,
                              CURLOPT_URL => "https://api.twitter.com/1.1/statuses/$twitter_timeline.json?". http_build_query($request),
                              CURLOPT_RETURNTRANSFER => true,
                              CURLOPT_SSL_VERIFYPEER => false,
                              CURLOPT_CONNECTTIMEOUT_MS => 1000,
                              CURLOPT_TIMEOUT_MS => 1000,
                              );

            $feed = curl_init();
            curl_setopt_array($feed, $options);
            $json = curl_exec($feed);
            curl_close($feed);
        return json_decode($json, true);
    }

    public static function process_links($text) {


        // NEW Link Creation from clickable items in the text
        $text = preg_replace('/((http)+(s)?:\/\/[^<>\s]+)/i', '<a href="$0" target="_blank" rel="nofollow">$0</a>', $text );
        // Clickable Twitter names
        $text = preg_replace('/[@]+([A-Za-z0-9-_]+)/', '<a href="http://twitter.com/$1" target="_blank" rel="nofollow">@$1</a>', $text );
        // Clickable Twitter hash tags
        $text = preg_replace('/[#]+([A-Za-z0-9-_]+)/', '<a href="http://twitter.com/search?q=%23$1" target="_blank" rel="nofollow">$0</a>', $text );
        // END TWEET CONTENT REGEX
        return $text;

    }

    public static function displayTweets($tweets){
        //
        $ret = '';
        if (count($tweets)>0){
            foreach($tweets as $t){
                $ret .=  '<ul class="media-list">';
                $ret .=  '  <li class="media">';
                $ret .= '    <a class="pull-left" href="#">';
                $ret .= '      <img class="media-object" src="'.$t['user']['profile_image_url'].'" alt="">';
                $ret .= '    </a>';
                $ret .= '    <div class="media-body">';
                $ret .= Twitterfeed::process_links($t['text']);
                $ret .= '    </div>';
                $ret .= '  </li>';
                $ret .= '</ul>';

            }
        } else {
            return __("TWITTER_ERROR");
        }
        return $ret;
    }

    public static function getTweets($atts, $content = null){
        extract(Shortcodes::shortcodeAtts(array(
            "num" => null
          ), $atts));
        $tweets = Twitterfeed::returnTweet($num);
        return Twitterfeed::displayTweets($tweets);
    }

}

?>