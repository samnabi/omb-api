<?php

include_once('simple_html_dom.php'); // These functions rely on this DOM library

function getMunis($urlsonly = false) {
    $url = 'https://www.omb.gov.on.ca/ecs/default.aspx';
    $ch = curl_init($url);
    curl_setopt ($ch, CURLOPT_CAINFO, WWW_ROOT . DS ."cacert.pem");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    $html = str_get_html(curl_exec($ch));  
    curl_close($ch);

    foreach ($html->find('.estatuslinks > li > a') as $letter) {        
        $url2 = 'https://www.omb.gov.on.ca/ecs/'.$letter->href;
        $ch2 = curl_init($url2);
        curl_setopt ($ch2, CURLOPT_CAINFO, WWW_ROOT . DS ."cacert.pem");
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, FALSE);
        $html2 = str_get_html(curl_exec($ch2));  
        curl_close($ch2);

        foreach ($html2->find('.estatuslist td a') as $muni){
        	if($urlsonly){
        		$munis[] = 'https://www.omb.gov.on.ca'.$muni->href;
        	}
        	else{
            	$munis[] =  array(
                	            'name' => $muni->plaintext,
                    	        'href' => 'https://www.omb.gov.on.ca'.$muni->href
                        	);
            }
        }
    	$html2->clear(); // Fix memleak
    }
    $html->clear(); // Fix memleak
    return $munis;
}

function getCases($urls){
    foreach ($urls as $url) {
        $ch = curl_init($url);
        curl_setopt ($ch, CURLOPT_CAINFO, WWW_ROOT . DS ."cacert.pem");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $html = str_get_html(curl_exec($ch));  
        curl_close($ch);

        parse_str(parse_url($url,PHP_URL_QUERY),$query);

        $rows = $html->find('.estatuslist tr');
        array_shift($rows);
        foreach($rows as $row){
            $cases[] =  array(
                            'muni' => urldecode($query['mn']),
                            'id' => trim($row->find('a',0)->plaintext),
                            'status' => trim($row->find('td',3)->plaintext),
                            'description' => trim($row->find('td',2)->plaintext),
                            'address' => trim($row->find('td',0)->plaintext)
                        );
        }
        $html->clear(); // Fix memleak
    }
    return $cases;
}

?>