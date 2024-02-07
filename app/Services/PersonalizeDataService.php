<?php
 namespace App\Services;
ini_set('display_errors', 0);
ignore_user_abort(true);
set_time_limit(0);
ini_set('memory_limit',-1);
class PersonalizeDataService  {

    
    function gethtml($url){
     $curl = curl_init($url);
       curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
       curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
       curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
       curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.115 Safari/537.36");
       curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 25);
       curl_setopt($curl, CURLOPT_TIMEOUT, 30);
      $html = curl_exec($curl);
      @mb_convert_encoding($html, 'HTML-ENTITIES', 'utf-8');
      return $html;
      curl_close($curl);
    }

    function striposa($haystack, $needles) {
      if(count($needles) > 0) {
            foreach($needles as $needle) {
                $res = stripos($haystack, $needle);
                if($res !== false){
                    return false;
                }
            }
            return true;
      }
    }

    function get_feedurl($domain){
      $html = self::gethtml($domain);
      if(!empty($html)){
        $tdk_doc = new \DOMDocument();
        libxml_use_internal_errors(true);
        @$tdk_doc->loadHTML($html);
        libxml_use_internal_errors(false);

        $tdk_xpath = new \DOMXPath($tdk_doc);
        $tdk_h = $tdk_xpath->query('//link[@type="application/rss+xml"]');
        $count = 1;
        foreach($tdk_h AS $head){
          if($count>1){
              break;
          }else{
            $articlelink = $head->getAttribute('href');
              if(!strstr($articlelink, 'http')){
                   $articlelink = 'http://www.'.$domain.$articlelink;
                  }
            $count++;
          }
        }
        if(isset($articlelink)){
            return $articlelink;
        }else{
            return false;
        }
      }else{
          return false;
      }
    }

    public function personalizeData($domain) {

        $mysqlid = new \mysqli("reachomation.cluster-cwyvyaxhb6rz.ap-south-1.rds.amazonaws.com","reachomation_115","BLTgWkyGNdQcpBMjoUS7","blogsearchprosearcher");

    // Check connection
    if ($mysqlid->connect_errno) {
      echo "Failed to connect to MySQL: " . $mysqlid->connect_error;
      exit();
    }
       $avoidednames = array("contributor", "university", "news", "guest", "protected",  "team", "ncoblogs3", "subscribed", "view", "school");

        $avoidedphrases = array("editor", "admin" , "staff", "customer", "support", "services", "press", "advert");

        $website = "http://".$domain;
        $feedurl='';
        $blogtitle='';
        $articlelink='';
        $author='';
        $author_arr = array();
        $activitydate='';
        $websitehtml = self::gethtml($website);
        if(!empty($websitehtml)) {
          $dom = new \DOMDocument();
          libxml_use_internal_errors(true);
          $dom->loadHTML($websitehtml);
          libxml_use_internal_errors(false);

          $xpath = new \DOMXPath($dom);
          $contributors = $xpath->query('//a[contains(@href, "/author/")]');
          $contributors1 = $xpath->query('//a[contains(@href, "/authors/")]');
          $contributors2 = $xpath->query('//a[@rel="author"]');
          if(!empty($contributors)){
              foreach ($contributors as $contributor){
                 $author = $contributor->nodeValue;
                
              }
          }else if(!empty($contributors1)){
              foreach ($contributors1 as $contributor){
                 $author = $contributor->nodeValue;
                
              }
          }else if(!empty($contributors2)){
            foreach ($contributors2 as $contributor){
                 $author = $contributor->nodeValue;
                
              }
          }

        if($author==''){

          $feedurl = self::get_feedurl($website);
          if($feedurl==''){
            $feedurl = $website."/feed";
          }
          $feedhtml = self::gethtml($feedurl);
          if($feedhtml!=''){
            libxml_use_internal_errors(true);
            $xml = simplexml_load_string($feedhtml);
            libxml_use_internal_errors(false);
            $json = json_encode($xml);
            $array = json_decode($json,TRUE);
            if(!empty($array)){
              //print_r($array);
             // die;
              $blogtitle = '';
              $articlelink = '';
              if(isset($array['channel']['item'])) {
                $blogtitle = $array['channel']['item']['0']['title'];
                $articlelink = $array['channel']['item']['0']['link'];
                $blogtitle = \mysqli_real_escape_string($mysqlid, $blogtitle);
              }
              if(isset($array['channel']['title'])) {
                $blogtitle = $array['channel']['title'];
                //$blogtitle = \mysqli_real_escape_string($mysqlid, $blogtitle);
              }
              if(isset($array['channel']['link'])) {
                $articlelink = $array['channel']['link'];
              }

              
            }
            $authorregex = '/<dc:creator><\!\[CDATA\[([a-zA-Z0-9-_ ]*)\]\]><\/dc:creator>/';
            preg_match($authorregex, $feedhtml, $match);
            if(!empty($match)){
               $author = $match[1];
           
            }
            if($author==''){
              $posthtml = self::gethtml($articlelink);

              $dom = new \DOMDocument();
              libxml_use_internal_errors(true);

              @$dom->loadHTML($posthtml);
              libxml_use_internal_errors(false);

              $xpath = new \DOMXPath($dom);
              $contributors = $xpath->query('//a[contains(@href, "/author/")]');
              $contributors1 = $xpath->query('//a[contains(@href, "/authors/")]');
              $contributors2 = $xpath->query('//a[@rel="author"]');
              if(!empty($contributors)){
                  foreach ($contributors as $contributor){
                     $author = $contributor->nodeValue;
                    
                  }
              }else if(!empty($contributors1)){
                  foreach ($contributors1 as $contributor){
                     $author = $contributor->nodeValue;
                    
                  }
              }else if(!empty($contributors2)){
                  foreach ($contributors2 as $contributor){
                     $author = $contributor->nodeValue;
                    
                  }
              }
            }
          }
        }
        if(stripos(str_replace(" ", '', strtolower($author)), substr($domain, 0, strpos($domain, "."))) !== false){
          $author="";
        }
        if (stripos($author, "@") !== false){
          $author="";
        }
        $author = trim(str_replace(['dr.','the ','mrs.', 'mr.'], '', strtolower($author)));
        $author_arr = explode(" ", $author);
        if(isset($author_arr[0])){
          $author = ucfirst($author_arr[0]);
        }else{
          $author="";
        }
        if(strlen($author)<4 || in_array(strtolower($author), $avoidednames)){
          $author="";
        }
        if(stripos($author, ".")!==false || preg_match('~[0-9]+~', $author)){
          $author="";
        }
        if(!$this->striposa(strtolower($author), $avoidedphrases)) {
          $author="";
        }

        if(stripos($articlelink, $domain) === false || is_array($articlelink)){
          $articlelink="";
          $blogtitle="";
        }
        $blogtitle = preg_replace('/[^0-9a-zA-Z -_\/|,\.\"\']/', '', $blogtitle);

       /* echo "<br><br>feedurl: ".$feedurl;
        echo "<br>articlelink: ".$articlelink;
        echo "<br>author: ".$author;
        echo "<br>title: ".$blogtitle;
        echo "<br>activitydate: ".$activitydate;*/
        $author = preg_replace('/[^\00-\255]+/u', '', $author);
        return $resultArr =[
          "author" => $author,
          "title" => $blogtitle,
        ];
      }
         
    }

}




