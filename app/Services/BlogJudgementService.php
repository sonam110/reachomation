<?php
 namespace App\Services;

class BlogJudgementService  {

public function BlogJudgement($site){
    
    $connect = new \mysqli("reachomation.cluster-cwyvyaxhb6rz.ap-south-1.rds.amazonaws.com","reachomation_115","BLTgWkyGNdQcpBMjoUS7","blogjudgement");

    // Check connection
    if ($connect->connect_errno) {
      echo "Failed to connect to MySQL: " . $connect->connect_error;
      exit();
    }

	$specialsymbols = array(',', '.', '$', '&', '!', '#', '@', '%', '*', '`', '~', '<', '>', '?', ';', ':', '+', '*', '/', '\\', '|', '\'', '"');

	$varname=array();
	// anchormatchhref is keyword which will be checked in href
	$varname['anchormatchhref']=array("category"=>array("category"=>5,"categories"=>5),"tag"=>array("tag"=>5,"tags"=>5),"portfolio"=>array("portfolio"=>-6,"portfolios"=>-6,"folio"=>-6,"project"=>-4,"projects"=>-4,"work"=>-4,"our-work"=>-4,"our_work"=>-4,"ourwork"=>-4),"casestudy"=>array("case-studies"=>-4,"case-study"=>-4,"case_study"=>-4,"casestudy"=>-4),"price"=>array("price"=>-4,"prices"=>-4,"pricing"=>-4,"package"=>-4,"packages"=>-4),"studio"=>array("studio"=>-4,"studios"=>-4),"hireus"=>array("hire-us"=>-4,"hireus"=>-4),"partner"=>array("partner"=>-4,"partners"=>-4),"service"=>array("service"=>-6,"services"=>-8),"bonus"=>array("bonus"=>-4),"login"=>array("login"=>-4,"log-in"=>-4,"log_in"=>-4),"join"=>array("join"=>-4,"joinus"=>-4,"join-us"=>-4,"join_us"=>-4,"register"=>-4,"my account"=>-4),"product"=>array("product"=>-4,"products"=>-4,"courses"=>-4),"solution"=>array("solution"=>-4,"solutions"=>-4),"career"=>array("career"=>-4,"careers"=>-4,"we-are-hiring"=>-4,"we_are_hiring"=>-4,"wearehiring"=>-4),"support"=>array("support"=>-4),"client"=>array("client"=>-4,"clients"=>-4,"testimonial"=>-4,"testimonials"=>-4),"press"=>array("press"=>-4),"getaquote"=>array("get-a-quote"=>-8,"get_a_quote"=>-8,"getaquote"=>-8),"playonline"=>array("play-online"=>-4,"play_online"=>-4,"playonline"=>-4),"callus"=>array("call-us"=>-4,"call_us"=>-4,"callus"=>-4),"helpcenter"=>array("help-center"=>-4,"help_center"=>-4,"helpcenter"=>-4),"whychooseus"=>array("why-choose-us"=>-4,"why_choose_us"=>-4,"whychooseus"=>-4,"what-we-do"=>-4,"what_we_do"=>-4,"whatwedo"=>-4),"company"=>array("company"=>-6,"agency"=>-6));

	// anchormatch_nodetitle array is for keyword to be matched in nodevalue and anchor title
	$varname['anchormatch_nodetitle']=array("advertis"=>array("advertis"=>8,"mediakit"=>8,"media kit"=>8),"interviews"=>array("interviews"=>6),"podcast"=>array("podcast"=>4),"price"=>array("price"=>-4,"prices"=>-4,"pricing"=>-4,"package"=>-4,"packages"=>-4),"studio"=>array("studio"=>-4,"studios"=>-4),"hireus"=>array("hireus"=>-4,"hire us"=>-4),"partner"=>array("partner"=>-4,"partners"=>-4),"service"=>array("service"=>-6,"services"=>-6),"bonus"=>array("bonus"=>-4),"login"=>array("login"=>-4,"log in"=>-4,"log_in"=>-4),"join"=>array("join"=>-4,"joinus"=>-4,"join us"=>-4,"join_us"=>-4,"Create an account"=>-4,"register"=>-4,"my account"=>-4),"product"=>array("product"=>-4,"products"=>-4,"courses"=>-4),"solution"=>array("solution"=>-4,"solutions"=>-4),"career"=>array("career"=>-4,"careers"=>-4,"we-are-hiring"=>-4,"we are hiring"=>-4,"wearehiring"=>-4),"support"=>array("support"=>-4),"portfolio"=>array("portfolio"=>-4,"portfolios"=>-4,"folio"=>-4,"our-work"=>-4,"our work"=>-4,"ourwork"=>-4),"client"=>array("client"=>-4,"clients"=>-4,"testimonial"=>-4,"testimonials"=>-4),"purchase"=>array("purchase"=>-4),"press"=>array("press"=>-4),"getaquote"=>array("get-a-quote"=>-8,"getaquote"=>-8,"get a quote"=>-8),"playonline"=>array("play-online"=>-4,"play online"=>-4,"playonline"=>-4),"callus"=>array("call-us"=>-4,"call us"=>-4,"callus"=>-4),"helpcenter"=>array("help-center"=>-4,"help center"=>-4,"helpcenter"=>-4),"whychooseus"=>array("why-choose-us"=>-4,"why choose us"=>-4,"whychooseus"=>-4,"what-we-do"=>-4,"what we do"=>-4,"whatwedo"=>-4),"company"=>array("company"=>-6,"agency"=>-6));

	// anchormatch array is for keyword which should be matched in nodevalue
	$varname['anchormatch']=array("contributer"=>array("contribut"=>8,"guestpost"=>8,"guest post"=>8,"writeforus"=>8,"write for us"=>8),"guide"=>array("guide"=>2),"review"=>array("review"=>2,"opinion"=>2),"archive"=>array("archive"=>6),"comment"=>array("comment"=>2),"editorial"=>array("editorial"=>2),"recentpost"=>array("recentpost"=>6,"recent post"=>6,"featuredstories"=>6,"featuredstory"=>6,"featured stories"=>6,"featured story"=>6 ,"latest post"=>6,"latestpost"=>6),"toppost"=>array("toppost"=>2,"top post"=>2,"popularpost"=>2,"popular post"=>2),"howto"=>array("howto"=>2,"how to"=>2,"how-to"=>2),"readmore"=>array("readmore"=>6,"read more"=>6,"continuereading"=>6,"continue reading"=>6,"keepreading"=>6,"keep reading"=>6),"morepost"=>array("morepost"=>8,"more post"=>8,"olderpost"=>8,"older post"=>8));

	// anchormatchexact array is for keyword which should be matched exactly in nodevalue
	$varname['anchormatchexact']=array("location"=>array("location"=>-4,"locations"=>-4),"vision"=>array("vision and values"=>-4,"vision and mission"=>-4,"mission and vision"=>-4,"boardofdirectors"=>-4,"awards & recognitions"=>-4,"awards and recognitions"=>-4,"annual report"=>-4),"warranty"=>array("warranty"=>-4),"checkout"=>array("checkout"=>-4),"gift"=>array("corporate gifts"=>-4,"corporate gift"=>-4),"faq"=>array("faq"=>-4,"faq's"=>-4,"faqs"=>-4,"frequently asked questions"=>-4),"research"=>array("research"=>-2),"signin"=>array("signin"=>-2,"sign in"=>-2,"apply"=>-2),"speaker"=>array("speaker"=>-4,"exhibitor"=>-4),"whyus"=>array("whyus"=>-2,"why us"=>-2),"shop"=>array("shop"=>-2),"affiliates"=>array("affiliates"=>-2,"become our affiliate"=>-2,"become an affiliate"=>-2,"join our affiliate program"=>-2),"cart"=>array("cart"=>-4,"shoppingcart"=>-4,"shopping cart"=>-4,"shipping and returns"=>-4,"order status"=>-4,"returns"=>-4,"mycart"=>-4,"order"=>-4,"shipping"=>-4,"returns policy"=>-4,"return policy"=>-4));

	// tdmatch is for keyword which should match in title or description 
	$varname['tdmatch']=array("articles"=>array("articles"=>8,"stories"=>8),"insight"=>array("insight"=>6),"magazine"=>array("magazine"=>6),"review"=>array("review"=>6,"opinion"=>6),"advice"=>array("advice"=>6),"analysis"=>array("analysis"=>4),"tutorial"=>array("tutorial"=>4,"guide"=>4),"interviews"=>array("interviews"=>4),"update"=>array("update"=>2),"coverage"=>array("coverage"=>2),"knowledge"=>array("knowledge"=>2),"service"=>array("service"=>-6),"company"=>array("company"=>-6,"agency"=>-6),"consultant"=>array("consultant"=>-4),"council"=>array("council"=>-4),"broker"=>array("broker"=>-4),"awardwinning"=>array("award-winning"=>-4),"tools"=>array("tools"=>-4),"howto"=>array("howto"=>2,"how to"=>2,"how-to"=>2),"playonline"=>array("playonline"=>-4,"play online"=>-4));

	// $tdexactmatch is for keyword which exactly matches in title or description
	$varname['tdexactmatch']=array("blog"=>array("blog"=>6,"blogs"=>6),"tips"=>array("tips"=>8),"hacks"=>array("hacks"=>4),"news"=>array("news"=>8),"firm"=>array("firm"=>-4));

	// Make sure if key in text match also belong to anchormatch then its key is same
	$varname['textmatch']=array("recentpost"=>array("recentpost"=>6,"recent post"=>6,"featuredstories"=>6,"featuredstory"=>6,"featured stories"=>6,"featured story"=>6 ,"latest post"=>6,"latestpost"=>6),"toppost"=>array("toppost"=>2,"top post"=>2,"popularpost"=>2,"popular post"=>2),"morepost"=>array("morepost"=>8,"more post"=>8,"olderpost"=>8,"older post"=>8));

	$varname['special']=array("blog"=>array("blog"=>-10),"store"=>array("store"=>-6),"subscribe"=>array("subscribe"=>4));
	$varname['others']=array("adsense"=>array("adsense"=>8),"wp"=>array("wp"=>2),"feed"=>array("feed"=>5),"tdblank"=>array("tdblank"=>-100));

	// --------Scraping work start----------
	$statsarray = array();
    $score = 0;
    $webdata = array();
//File
    $curl = curl_init($site);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.115 Safari/537.36");
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    $data = curl_exec($curl);
    if(!empty($data)){
        
        $tdk_doc = new \DOMDocument();
        libxml_use_internal_errors(TRUE);
        $tdk_doc->loadHTML($data);
        $data = NULL;
        libxml_clear_errors();
        $tdk_xpath = new \DOMXPath($tdk_doc);
        $score = 0;
        $anchorarray = array();
        $title = '';
        $textstr = '';
        $description = '';

    // Xpath to get number of a
        $tdk_a = $tdk_xpath->query("//a[contains(href,'')]");

    // Get all link
        foreach ($tdk_a AS $entry) {
            $aval = $entry->nodeValue;
            $aval = strip_tags($aval);
            // Subscribe check(In internal or external link)
            if ((strlen($aval) > 2) && (strlen($aval) < 28)) {
                if ((stripos($aval, 'subscribe') !== false) && (!isset($statsarray['positiveanchor']['subscribe']))) {
                    $statsarray['positiveanchor']['subscribe'] = 4;
                }
            }

            $anchtitle = $entry->getAttribute('title');
            $anchhremain = $entry->getAttribute('href');
            $anchhremain = trim($anchhremain, '/-#');
            $anchhremainarr = explode('#', $anchhremain);
            $anchhremain = $anchhremainarr[0];
            $anchhre = $anchhremain;

            // Check for blog
            if ((stripos($anchhremain, $site) !== false) || (((stripos($anchhremain, 'http') === false)) && (stripos($anchhremain, 'www') === false))) {
                $anchval = strtolower($aval);
                $anchval = trim($anchval, ' -#');
                $anchval = strip_tags($anchval);
                if ((stripos($anchhremain, 'blog') !== false)) {

                    $anchhre = str_ireplace(array('https://www.', 'http://www.', 'https://', 'http://'), '', $anchhremain);
                    if (substr($anchhre, 0, 4) == 'www.') {
                        $anchhre = substr($anchhre, 4);
                    }
                    $domain_urlar = explode("/", $anchhre);
                    $domain_url = $domain_urlar[0];
                    if ($anchval == 'blog') {
                        // Check if domain url is not in link
                        if ((($anchhre == '/blog/') || ($anchhre == '/blog') || ($anchhre == 'blog')) && (!isset($statsarray['negativeanchor']['blog']))) {
                            $statsarray['negativeanchor']['blog'] = -10;
                            $score = $score - 10;
                        } else if ((($anchhre == $domain_url . '/blog/') || ($anchhre == $domain_url . '/blog')) && (!isset($statsarray['negativeanchor']['blog']))) {
                            $statsarray['negativeanchor']['blog'] = -10;
                            $score = $score - 10;
                        }
                    }
                }
            }
            // End of check for blog


            if ((stripos($anchhremain, $site) !== false) || (((stripos($anchhremain, 'http') === false)) && (stripos($anchhremain, 'www') === false))) {

                // Check for store
                if ((stripos($anchhremain, 'store') !== false) || (stripos($anchhremain, 'buy') !== false) || (stripos($anchhremain, 'product') !== false)) {
                    $anchhre = str_ireplace(array('https://www.', 'http://www.', 'https://', 'http://'), '', $anchhremain);
                    if (substr($anchhre, 0, 4) == 'www.') {
                        $anchhre = substr($anchhre, 4);
                    }
                    // $domain_urlar=explode("/", $anchhre);
                    // $domain_url=$domain_urlar[0];
                    if ($anchval == 'store' || $anchval == 'stores' || $anchval == 'storelocator' || $anchval == 'store locator' || $anchval == 'locatestore' || $anchval == 'locate store' || $anchval == 'find a store' || $anchval == 'online store') {
                        // Check if domain url is not in link
                        if ((stripos($anchhre, '/tag/') == false) && (stripos($anchhre, '/tags/') == false) && (stripos($anchhre, '/category/') == false) && (stripos($anchhre, '/categories/') == false)) {


                            if ((stripos($anchhre, '/store') !== false || stripos($anchhre, '/buy') !== false || stripos($anchhre, '/product') !== false || $anchhre == 'store') && (!isset($statsarray['negativeanchor']['store']))) {
                                $statsarray['negativeanchor']['store'] = -6;
                                $score = $score - 6;
                            }
                        }
                    }
                }
                // End of check for store
                // Check for tel
                if ((stripos($anchhremain, 'tel:') !== false) && (!isset($statsarray['negativeanchor']['callus']))) {
                    $statsarray['negativeanchor']['callus'] = -4;
                    $score = $score - 2;
                }
                // End of check for tel
            }
            // End of check for store
            // Check for anchor href
            if ((stripos($anchhremain, $site) !== false) || (((stripos($anchhremain, 'http') === false)) && (stripos($anchhremain, 'www') === false))) {
                $cathre = $anchhremain;
                foreach ($varname['anchormatchhref'] AS $arrayhref => $arrayhrefval) {
                    foreach ($arrayhrefval AS $anchhkey => $anchhval) {
                        if ($anchhval > 0) {
                            if (!isset($statsarray['positiveanchor'][$arrayhref])) {
                                $arhrpos = positivehrefmatch($arrayhref, $anchhkey, $anchhval, $statsarray, $cathre);
                                if ($arhrpos) {
                                    $statsarray['positiveanchor'][$arrayhref] = $anchhval;
                                    $score = $score + $anchhval;
                                }
                            }
                        } else if ($anchhval < 0) {
                            if ((stripos($cathre, '/tag/') == false) && (stripos($cathre, '/tags/') == false) && (stripos($cathre, '/category/') == false) && (stripos($cathre, '/categories/') == false) && (!isset($statsarray['negativeanchor'][$arrayhref]))) {
                                $arhrpos = negativehrefmatch($arrayhref, $anchhkey, $anchhval, $statsarray, $cathre);
                                if ($arhrpos) {
                                    $statsarray['negativeanchor'][$arrayhref] = $anchhval;
                                    $score = $score + $anchhval;
                                }
                            }
                        }
                    }
                }
            }
            // End of check anchor href
            // Check for anchor nodevalue/ title
            if ((stripos($anchhremain, $site) !== false) || (((stripos($anchhremain, 'http') === false)) && (stripos($anchhremain, 'www') === false))) {
                $anchval = trim($aval);
                $anchval = strtolower($anchval);
                $anchval = strip_tags($anchval);
                if ((strlen($anchval) > 2) && (strlen($anchval) < 28) && (strlen($anchtitle) < 28)) {
                    $anchhre = $anchhremain;
                    $anchnodetitle = $anchval . " " . $anchtitle;
                    $tag_cat = 0;
                    $neg_match = 1;
                    if ((stripos($anchhre, '/tag/') !== false) || (stripos($anchhre, '/tags/') !== false) || (stripos($anchhre, '/category/') !== false) || (stripos($anchhre, '/categories/') !== false)) {
                        $tag_cat = 1;
                    }

                    // Anchor match in nodevalue
                    foreach ($varname['anchormatch'] AS $anchornode => $anchornodeval) {
                        foreach ($anchornodeval AS $anchorkey => $anchorval) {
                            if ($anchorval > 0) {
                                if (!isset($statsarray['positiveanchor'][$anchornode])) {
                                    // Function for positive anchor match(returns positive anchor match array and score)
                                    $arrpos = positiveanchormatch($anchornode, $anchorkey, $anchorval, $statsarray, $anchval);
                                    if ($arrpos) {
                                        $statsarray['positiveanchor'][$anchornode] = $anchorval;
                                        $score = $score + $anchorval;
                                    }
                                }
                            } else {
                                // If href does not contains category and tag
                                if (($tag_cat == 0) && (!isset($statsarray['negativeanchor'][$anchornode]))) {
                                    // Function for negative anchor match(returns negative anchor match array and score)
                                    $arrneg = negativeanchormatch($anchornode, $anchorkey, $anchorval, $statsarray, $anchval);
                                    if ($arrneg) {
                                        $statsarray['negativeanchor'][$anchornode] = $anchorval;
                                        $score = $score + $anchorval;
                                    }
                                }
                            }
                        }
                    }
                    // End of anchor match in nodevalue
                    // Exact Anchor match in nodevalue
                    foreach ($varname['anchormatchexact'] AS $anchornode => $anchornodeval) {
                        foreach ($anchornodeval AS $anchorkey => $anchorval) {
                            if ($anchorval > 0) {
                                if (!isset($statsarray['positiveanchor'][$anchornode])) {
                                    // Function for positive anchor match(returns positive anchor match array and score)
                                    $arrpos = exactanchormatch($anchorkey, $anchval);
                                    if ($arrpos) {
                                        $statsarray['positiveanchor'][$anchornode] = $anchorval;
                                        $score = $score + $anchorval;
                                    }
                                }
                            } else {
                                // If href does not contains category and tag
                                if (($tag_cat == 0) && (!isset($statsarray['negativeanchor'][$anchornode]))) {
                                    // Function for negative anchor match(returns negative anchor match array and score)
                                    $arrneg = exactanchormatch($anchorkey, $anchval);
                                    if ($arrneg) {
                                        $statsarray['negativeanchor'][$anchornode] = $anchorval;
                                        $score = $score + $anchorval;
                                    }
                                }
                            }
                        }
                    }
                    // End of anchor match in exact nodevalue
                    // Anchor match in nodevalue title
                    foreach ($varname['anchormatch_nodetitle'] AS $arraynt => $arrayntval) {
                        foreach ($arrayntval AS $anchorndkey => $anchorndval) {
                            if ($anchorndval > 0) {
                                if (!isset($statsarray['positiveanchor'][$arraynt])) {
                                    // Function for positive anchor match(returns positive anchor match array and score)
                                    $arrpos = positiveanchormatch($arraynt, $anchorndkey, $anchorndval, $statsarray, $anchnodetitle);
                                    if ($arrpos) {
                                        $statsarray['positiveanchor'][$arraynt] = $anchorndval;
                                        $score = $score + $anchorndval;
                                    }
                                }
                            } else {
                                // If href does not contains category and tag
                                if (($tag_cat == 0) && (!isset($statsarray['negativeanchor'][$arraynt]))) {
                                    // Function for negative anchor match(returns negative anchor match array and score)
                                    $arrneg = negativeanchormatch($arraynt, $anchorndkey, $anchorndval, $statsarray, $anchnodetitle);
                                    if ($arrneg) {
                                        $statsarray['negativeanchor'][$arraynt] = $anchorndval;
                                        $score = $score + $anchorndval;
                                    }
                                }
                            }
                        }
                    }

                    // End of Anchor match in nodevalue and title
                    if (!preg_match("/^[a-zA-Z0-9\-_]*$/", $anchval)) {
                        $anchval = preg_replace('/[^A-Za-z0-9\-_]/', '', $anchval);
                    } else {
                        
                    }
                    if ((is_numeric($anchval)) || (strlen($anchval) < 4)) {
                        
                    } else if ((!in_array($anchval, $anchorarray)) && (preg_match("/[a-zA-Z]+/", $anchval))) {
                        $anchorarray[] = $anchval;
                    }
                }
            }
            // End of check anchor nodevalue/ title
        }
        if (!(isset($statsarray['others']['adsense']))) {
            $tdk_en = $tdk_xpath->query("//script[contains(@src,'adsbygoogle') or contains(@src,'doubleclick.net')]");
            if ($tdk_en->length > 0) {
                $statsarray['others']['adsense'] = 8;
                $score = $score + 8;
            }
        }

        // Xpath to check wordpress
        $entrieswp = $tdk_xpath->query("//link[contains(@href,'wp-content/themes') or contains(@href,'wp-content/plugins') or contains(@href,'wp-includes/css')] | //script[contains(@src,'wp-content/themes') or contains(@src,'wp-content/plugins') or contains(@src,'wp-includes/js')]");
        foreach ($entrieswp as $entry) {
            $statsarray['others']['wp'] = 2;
            $score = $score + 2;
            break;
        }
        // Xpath to get title
        $tdk_row1 = $tdk_xpath->query('//title');
        if ($tdk_row1->length > 0) {
            foreach ($tdk_row1 as $rowscr) {
                $title = $rowscr->nodeValue;
                $title = trim(strip_tags($title));
                if ($title != '') {
                    break;
                }
            }
        }
        // Xpath to get description
        $tdk_row1 = $tdk_xpath->query('//meta[@name=translate("DESCRIPTION","ABCDEFGHIJKLMNOPQRSTUVWXYZ","abcdefghijklmnopqrstuvwxyz")]');
        if ($tdk_row1->length > 0) {
            foreach ($tdk_row1 as $rowscr) {
                $description = $rowscr->getAttribute('content');
                $description = trim(strip_tags($description));
                // If description is not empty then break
                if (!empty($description)) {
                    break;
                }
            }
        }
        if (strlen($description) < 150) {
            $tdk_row1 = $tdk_xpath->query('//meta[@property=translate("OG:DESCRIPTION","ABCDEFGHIJKLMNOPQRSTUVWXYZ","abcdefghijklmnopqrstuvwxyz")]');
            if ($tdk_row1->length > 0) {
                foreach ($tdk_row1 as $rowscr) {
                    $description2 = $rowscr->getAttribute('content');
                    $description2 = trim(strip_tags($description2));
                    // If description2 is not empty then break
                    if (!empty($description2)) {
                        $description = $description . " " . $description2;
                        break;
                    }
                }
            }
        }
        if (strlen($description) < 150) {
            $tdk_row1 = $tdk_xpath->query('//meta[@property=translate("DESCRIPTION","ABCDEFGHIJKLMNOPQRSTUVWXYZ","abcdefghijklmnopqrstuvwxyz")]');
            if ($tdk_row1->length > 0) {
                foreach ($tdk_row1 as $rowscr) {
                    $description3 = $rowscr->getAttribute('content');
                    $description3 = trim(strip_tags($description3));
                    // If description3 is not empty then break
                    if (!empty($description3)) {
                        $description = $description . " " . $description3;
                        break;
                    }
                }
            }
        }

        // Get text of all element except a
        $tdk_text = $tdk_xpath->query('//*[not(self::a)][string-length(normalize-space(text())) <28][string-length(text()) >2]/text()[normalize-space()]');
        if ($tdk_text->length > 0) {
            foreach ($tdk_text as $rowtext) {
                $textval = $rowtext->nodeValue;
                $textstr .= " " . $textval;
            }
        }
        // Check in text array textmatch which we cant find in anchor keyword 
        foreach ($varname['textmatch'] AS $textk => $textv) {
            foreach ($textv AS $textkey => $textval) {
                if ($textval > 0) {
                    $texttype = "positivetext";
                    $anctype = "positiveanchor";
                } else {
                    $texttype = "negativetext";
                    $anctype = "negativeanchor";
                }
                if ((stripos($textstr, $textkey) !== false) && (!isset($statsarray[$anctype][$textk]))) {
                    $statsarray[$anctype][$textk] = $textval;
                    $score = $score + $textval;
                    break;
                }
            }
        }

        $title = trim(addslashes(mysqli_real_escape_string($connect, $title)));
        $description = trim(mysqli_real_escape_string($connect, $description));
        $anchorarray = array_unique(array_filter($anchorarray));
        $anchor = implode(',', $anchorarray);
        $anchor = trim(addslashes(mysqli_real_escape_string($connect, $anchor)));

        // Craete title keyword which will have the necessary data of title
        $title = str_ireplace(',', ' ', $title);
        $titlenew = str_ireplace($specialsymbols, '', $title);
        $titlearr = explode(" ", $titlenew);
        // $titlearr2 to check duplicacy
        $titlearr2 = array();
        foreach ($titlearr AS $arrtitle => $artitleval) {
            if (in_array($artitleval, $titlearr2)) {
                unset($titlearr[$arrtitle]);
            } else if (is_numeric($artitleval)) {
                unset($titlearr[$arrtitle]);
            } else if (strlen($artitleval) < 4) {
                unset($titlearr[$arrtitle]);
            } else if (!preg_match("/^[a-zA-Z0-9\-_]*$/", $artitleval)) {
                $artitleval = preg_replace('/[^A-Za-z0-9\-_]/', '', $artitleval);
            }
            // Now check if title is empty or have no alphabet
            if ((isset($titlearr[$arrtitle])) && (empty($titlearr[$arrtitle]))) {
                unset($titlearr[$arrtitle]);
            } else if ((isset($titlearr[$arrtitle])) && (!preg_match("/[a-zA-Z]+/", $artitleval))) {
                unset($titlearr[$arrtitle]);
            } else if (isset($titlearr[$arrtitle])) {
                $titlearr2[] = $artitleval;
            }
        }
        // Create description keyword which will have the necessary data of description
        $description = str_ireplace(',', ' ', $description);
        $descriptionnew = str_ireplace($specialsymbols, '', $description);
        $descriptionarr = explode(" ", $descriptionnew);
        // $desarr2 to check duplicacy
        $desarr2 = array();
        foreach ($descriptionarr AS $arrdescription => $ardescriptionval) {
            if (in_array($ardescriptionval, $desarr2)) {
                unset($descriptionarr[$arrdescription]);
            } else if (is_numeric($ardescriptionval)) {
                unset($descriptionarr[$arrdescription]);
            } else if (strlen($ardescriptionval) < 4) {
                unset($descriptionarr[$arrdescription]);
            } else if (!preg_match("/^[a-zA-Z0-9\-_]*$/", $ardescriptionval)) {
                $ardescriptionval = preg_replace('/[^A-Za-z0-9\-_]/', '', $ardescriptionval);
            }
            // Now check if description is empty or have no alphabet
            if ((isset($descriptionarr[$arrdescription])) && (empty($descriptionarr[$arrdescription]))) {
                unset($descriptionarr[$arrdescription]);
            } else if ((isset($descriptionarr[$arrdescription])) && (!preg_match("/[a-zA-Z]+/", $descriptionarr[$arrdescription]))) {
                unset($descriptionarr[$arrdescription]);
            } else if (isset($descriptionarr[$arrdescription])) {
                $desarr2[] = $ardescriptionval;
            }
        }

        // Check for title description contains keyword 
        foreach ($varname['tdmatch'] AS $tdarrk => $tdarrv) {
            foreach ($tdarrv AS $tdkey => $tdval) {
                if ($tdval > 0) {
                    $titletype = "positivetd";
                    if ((stripos($title, $tdkey) !== false) && (!isset($statsarray[$titletype][$tdarrk]))) {
                        $statsarray[$titletype][$tdarrk] = $tdval;
                        $score = $score + $tdval;
                        break;
                    } else if ((stripos($description, $tdkey) !== false) && (!isset($statsarray[$titletype][$tdarrk]))) {
                        $statsarray[$titletype][$tdarrk] = $tdval;
                        $score = $score + $tdval;
                        break;
                    }
                } else {
                    $titletype = "negativetd";
                    if ((stripos($title, $tdkey) !== false) && (!isset($statsarray[$titletype][$tdarrk]))) {
                        $statsarray[$titletype][$tdarrk] = $tdval;
                        $score = $score + $tdval;
                        break;
                    } else if ((stripos($description, $tdkey) !== false) && (!isset($statsarray[$titletype][$tdarrk]))) {
                        $statsarray[$titletype][$tdarrk] = $tdval;
                        $score = $score + $tdval;
                        break;
                    }
                }
            }
        }

        // Check for title description exactly contains keyword 
        foreach ($varname['tdexactmatch'] AS $tdearrk => $tdearrv) {
            foreach ($tdearrv AS $tdkey => $tdeval) {
                if ($tdeval > 0) {
                    $titletype = 'positivetd';
                    if ((preg_match("/\b$tdkey\b/i", $title)) && (!isset($statsarray[$titletype][$tdearrk]))) {
                        $statsarray[$titletype][$tdearrk] = $tdeval;
                        $score = $score + $tdeval;
                        break;
                    } else if ((preg_match("/\b$tdkey\b/i", $description)) && (!isset($statsarray[$titletype][$tdearrk]))) {
                        $statsarray[$titletype][$tdearrk] = $tdeval;
                        $score = $score + $tdeval;
                        break;
                    }
                } else {
                    $titletype = "negativetd";
                    if ((preg_match("/\b$tdkey\b/i", $title)) && (!isset($statsarray[$titletype][$tdearrk]))) {
                        $statsarray[$titletype][$tdearrk] = $tdval;
                        $score = $score + $tdval;
                        break;
                    } else if ((preg_match("/\b$tdkey\b/i", $description)) && (!isset($statsarray[$titletype][$tdearrk]))) {
                        $statsarray[$titletype][$tdearrk] = $tdval;
                        $score = $score + $tdval;
                        break;
                    }
                }
            }
        }

        if ((empty($title)) && (empty($description))) {
            $statsarray['others']['tdblank'] = -5;
            $score = $score - 5;
        }

        $myscorearr = array();
        foreach ($statsarray AS $array => $arrval) {
            foreach ($statsarray[$array] AS $key => $val) {
                if ($val > 0) {
                    $statsarray[$array][$key] = '+' . $val;
                } else {
                    
                }
            }
        }
        $statsstr = '';
        if (count($statsarray) > 0) {
            $statsstr = json_encode($statsarray);
            //$statsstr = mysqli_real_escape_string($connect, $statsstr);
        } else {
            $score = -100;
        }
    	// ----------Scraping work end-----------


    	// --------Algo work start---------------
    	$dfg=mysqli_query($connect, "SELECT MAX(cons/pros)  as bll FROM weightage WHERE 1");
    	$mcaprow=mysqli_fetch_assoc($dfg);
    	$maxcap=$mcaprow['bll'];
    	$res1=mysqli_query($connect, "SELECT * FROM weightage WHERE `impact` = 1 group by keyword");
    	$pos_array=array();
    	$pdep_array=array();
    	$posar=array();
    	$neg_array=array();
    	$negar=array();
    	while($arrf=mysqli_fetch_assoc($res1)){
    		if($arrf['cons']==0){
    			$arrf['cons']=1;
    		}
    		$star=round($arrf['pros']/$arrf['cons'],2);
    		if($star>$maxcap){
    			$star=$maxcap;
    		}
    		$posar[$arrf['keyword']]=$star;
    		$pos_array[]=$arrf['keyword'];
    		if($arrf['dependence']==1){
    			$pdep_array[]=$arrf['keyword'];
    		}
    	}

    	$res2=mysqli_query($connect,"SELECT * FROM weightage WHERE `impact` = 0 group by keyword");
    	while($arrf2=mysqli_fetch_assoc($res2)){
    		if($arrf2['pros']==0){
    			$arrf2['pros']=1;
    		}
    		$star=round($arrf2['cons']/$arrf2['pros'],2);
    		if($star>$maxcap){
    			$star=$maxcap;
    		}
    		$negar[$arrf2['keyword']]=$star;
    		$neg_array[]=$arrf2['keyword'];
    	}
    	$totalpos=array_sum($posar);
    	$totalneg=array_sum($negar);




    	$arraycheckp=array();
    	$arraycheckn=array();
    	$isit_blog=2;
    	$posnum=0;
    	$negnum=0;
    	$poscounter=0;
    	$negcounter=0;
    	$independence=0;
    	$blogstatus=2;
    	if($statsstr==''){
    		return false;
    	}
    	$keyword=json_decode($statsstr, 1);
    	foreach ($keyword as $key => $abla) {
    		foreach ($abla as $key => $value) {
    			if($value>0){
    				if(in_array($key, $pos_array) && !(in_array($key, $arraycheckp))){
    					$posnum+=$posar[$key];
    					$arraycheckp[]=$key;
    					$poscounter++;
    					if(!(in_array($key,$pdep_array))){
    						$independence=1;
    					}
    				}
    			}
    			if($value<0){
    				if(in_array($key, $neg_array) && !(in_array($key, $arraycheckn))){
    					$negnum+=$negar[$key];
    					$arraycheckn[]=$key;
    					$negcounter++;
    				}
    			}
    		}
    	}

    	$a=$posnum/$totalpos;
    	$b=$negnum/$totalneg;
    	$blogvalue=$a-$b;
    	if($a>$b && $independence==1){
    		$blogstatus=1;
    	}
    	if($a>$b && $independence==0){
    		$blogstatus=3;
    	}
    	if($blogstatus==1 && $score>0 && $poscounter>1){
    		return true;
    	}else{
    		return false;
    	}
    }

	// ---------Algo work end----------------
}


}

 

