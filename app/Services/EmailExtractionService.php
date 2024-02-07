<?php
 namespace App\Services;


class EmailExtractionService {

public function containsDescenders($text) {
    $descenders = array("example", "info@gmail.com", "admin@gmail.com", "contact@gmail.com", "webmaster@gmail.com", "me@gmail.com", "hello@gmail.com", "support@gmail.com", "editor@gmail.com", "@address.", "@sentry.io", "@domain.", "@company.", "@yourdomain.", "@mydomain.", "@email.", "@your-domain.", "@whateverdomain.", "@disqus.", "@envato.", "@domainname.", ".plusgoogle.com", "myemail@gmail.com", "@website.com", "privacy@", "j.doe@inbox.com", "@yoursite.com", "@yourwebsite.com", "youremail@gmail.com", "example@gmail.com", "john@doe.com", "xxxxx", "xyz", "you@emaildomain.com", "you@freewebmail.com", "@yours.com", "@youremail.com", "yourname@", "@yourcompanyname.com", "@mail.com", "@medium.com", "@yourcompany.com", "abuse", "test@", "firstname.surname@", "abc@", "sales@", "cs@", "customersupport@", "comments@", "help@", "@sample.com", "yourfriends@", "youremail@", "your-email@", "sample@", "orders", "subscription", "access@", "accounts@", "account@", "accountservices", "billing@", "your.address@gmail.com", "account-name_support@middleeasy.com", "john@gmail.com", "info@companyname.com");
    foreach ($descenders as $letter) {
        if (stripos($text, $letter) !== false) {
            return true;
            break;
        }
    }
    return false;
}


public function extractEmail($domain){
 	$arr = array("(@)", "{@}", "[@]", "[at]", "{at}", "(at)");
	$arr_dot = array("(dot)", "{dot}", "[dot]");
	$emaildata = '';
	$paths = array("/contact", "/about/", "/about-us", "/about-me", "/write-for", "/advertis");
	$anchtextarray = array("contact", "contact me", "contactme", "contact us", "contactus", "about", "about me", "aboutme", "about us", "aboutus", "advertise", "advertising", "advertise with us", "advertisewithus", "contribute", "workwithus", "work with us", "writeforus", "write for us", "meet the team", "meettheteam", "ourteam", "our team", "mediakit", "media kit", "guestpost", "guest post", "submitguestpost", "submit guest post", "getintouch", "get in touch", "who we are");
	$pattern = '/[A-Za-z][A-Za-z0-9_\-\.]*[A-Za-z0-9_\-\.]+(\@|\(@\)|\{@\}|\[@\]|\[at\]|\{at\}|\(at\))[A-Za-z0-9_\-\.]+(\.|\(dot\)|\{dot\}|\[dot\])[A-Za-z]{2,4}/is';

	$domain_l = strlen($domain);
    $needed_url = array($domain);
    $unique_url = array();
    $emails = array();
    $curl = curl_init($domain);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.115 Safari/537.36");
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    $html = curl_exec($curl);
    $httpcodeMAin = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $errorsData = date("y-m-d-H-i-s") . "--" . $domain . "--CURL End Home page.\r\n ";
    $errorsData = date("y-m-d-H-i-s") . "--" . $domain . "--CURL HTTP Code(" . $httpcodeMAin . ").\r\n ";
    curl_close($curl);
   
    if (!empty($html)) {
	        $dom = new \DOMDocument();
	        libxml_use_internal_errors(TRUE);
	        @$dom->loadHTML($html);
	        libxml_clear_errors();
	        $fburl = self::getfacebookurl($html);
	        $links = $dom->getElementsByTagName('a');
	        foreach ($links as $link) {
	        	
	            $allurl = $link->getAttribute('href');
	            $allanchtext = $link->nodeValue;
	        	$anchhre = str_ireplace(array('https://www.', 'http://www.', 'https://', 'http://'), '', $allurl);
	            foreach ($paths as $key => $path) {
	            	if (stripos($anchhre, $path) !== false || in_array($allanchtext, $anchtextarray)) {
	                    if (substr($anchhre, 0, $domain_l) == $domain) {
	                        $needed_url[] = strtolower($anchhre);
	                    } elseif (!(stripos($anchhre, "/.") !== false)) {
	                        $needed_url[] = $domain . "/" . strtolower(ltrim($allurl, '/'));
	                    }
	                }
	            }
	        }
	        unset($html);

	        if (count($needed_url) > 0) {
	            $unique_url = array_unique(array_filter($needed_url));
	            foreach ($unique_url as $exact) {
	                $curl = curl_init($exact);
	                curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
	                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
	                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	                curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.115 Safari/537.36");
	                curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
	                curl_setopt($curl, CURLOPT_TIMEOUT, 30);
	                $raw = curl_exec($curl);
	                $httpcodePages = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	                curl_close($curl);
	                $re = "~data:image/[a-zA-Z]*;base64,[a-zA-Z0-9+/\\=]*=~";
	                $subst = "#";
	                $raw = preg_replace($re, $subst, $raw);
	                $matches = array();
	                preg_match_all($pattern, $raw, $matches);
	                $matches[0] = array_unique(array_filter($matches[0]));
	                if (count($matches[0]) > 0) {
	                    foreach ($matches[0] as $key => $email) {
	                        $email = strtolower($email);
	                        $email = ltrim($email, "-");
	                        $email = ltrim($email, "_");
	                        $email = ltrim($email, ".");
	                        $email = preg_replace('/^(u003e)/', '', $email);
	                        $email = preg_replace('/^(u002F)/', '', $email);
	                        $email = preg_replace('/^(u00a0)/', '', $email);
	                        $ext = explode(".", $email);
	                        $validformat = array("com", "net", "org", "in", "au", "co");
	                        if (in_array(end($ext), $validformat) && ($this->containsDescenders($email) == false) && preg_match_all("/[0-9]/", $email) < 7) {
	                            foreach ($arr as $ar) {
	                                $email = str_replace($ar, "@", $email);
	                            }
	                            foreach ($arr_dot as $ar) {
	                                $email = str_replace($ar, ".", $email);
	                            }
	                            $emails[] = $email;
	                        }
	                    }
	                } else {
	                    $exact = str_ireplace(array('https://www.', 'http://www.', 'https://', 'http://'), '', $exact);
	                    $exact = "https://www." . $exact;

	                }
	                $emails = array_unique(array_filter($emails));
	                if (count($emails) < 3) {
	                    continue;
	                } else {
	                    break;
	                }
	                //die;
	            }
	            if (!empty($emails)) {
	   
	            	//$emails = array_multisort($emails)
	                $emails = array_values($emails);
	                $ecount = count($emails);
	                $upto = 3;
	                if ($ecount < $upto) {
	                    $upto = $ecount;
	                }
	               
	                for ($i = 0; $i < $upto; $i++) {
	                    $emaildata = $emails[0];
	                
	            	}
	        	}
        	
	        }
	       return  $emaildata;
           
        
    }

}

    public function getfacebookurl($html){
		$fburl='';
		$dom = new \DOMDocument();
	    libxml_use_internal_errors(TRUE);
	    @$dom->loadHTML($html);
	    libxml_clear_errors();
	    $tdk_xpath = new \DOMXPath($dom);
	    $entries = $tdk_xpath->query("//*//a[contains(@href,'facebook.com')][not(contains(@href,'facebook.com/?'))][not(contains(@href,'/post/'))][not(contains(@href,'/posts/'))][not(contains(@href,'.php'))][not(contains(@href,'facebook.com/pages'))][not(contains(@href,'facebook.com/groups'))][not(contains(@href,'facebook.com/pg'))][not(contains(@href,'facebook.com/#'))][not(contains(@href,'facebook.com/tagdiv'))][not(contains(@href,'facebook.com/events'))][not(contains(@href,'facebook.com/wordpress'))][not(contains(@href,'facebook.com/jegtheme'))][not(contains(@href,'facebook.com/PenciDesign'))][not(contains(@href,'facebook.com/facebook.com'))][not(contains(@href,'facebook.com/@'))][not(contains(@href,'facebook.com/yourpage'))][not(contains(@href,'facebook.com/yourpageurl'))][not(contains(@href,'facebook.com/your-page-url'))][not(contains(@href,'facebook.com/yourprofile'))][not(contains(@href,'facebook.com/yourusername'))][not(contains(@href,'facebook.com/your-username'))]");
	    foreach ($entries as $entry){
	        $soc_link=$entry->getAttribute('href');
	        if(stripos($soc_link, ' ')!==false){
	         	continue;
	        }
	        $soc_link=trim($soc_link);
	        $soc_link=trim($soc_link, '/');
	        $soc_link=str_ireplace(array('https://www.','http://www.','https://','http://'), '', $soc_link);
	        if(substr($soc_link,0,4)=='www.'){
	             $soc_link=substr($soc_link, 4);
	        }
	        $soc_link=trim($soc_link, '/ ');
	        if(!(in_array(strtolower($soc_link), array('facebook.com', 'facebook.com/', 'facebook.com/sharer'))) && (stripos($soc_link, "@")===false)){
	            if(strlen($soc_link)<250 && $soc_link!=''){
	                if(stripos($soc_link, 'facebook.com')!==false && stripos($soc_link, 'facebook.com')==0 && (strlen($soc_link) == mb_strlen($soc_link, 'utf-8'))){
	                     $soc_linkdf=explode('/', $soc_link);
	                    if(count($soc_linkdf)>1){
	                        $soc_link=$soc_linkdf[0]."/".$soc_linkdf[1];
	                        if(empty($fburl)){
	                            $fburl=$soc_link;
	                        } 
	                    }
	                }
	            }
	        }
	    }
	    return $fburl ;
	}

}

 

