<?php

error_reporting(E_ALL);
require_once('rollingCurl.php');

function getMunis(){

  // Hard-coded list of URLs for all the subpages of the OMB municipalities directory.
  // We could have scraped this from the homepage, but they're not likely to change.
  $urls = array(
    'https://www.omb.gov.on.ca/ecs/MuniList.aspx?n=A',
    'https://www.omb.gov.on.ca/ecs/MuniList.aspx?n=B',
    'https://www.omb.gov.on.ca/ecs/MuniList.aspx?n=C',
    'https://www.omb.gov.on.ca/ecs/MuniList.aspx?n=D',
    'https://www.omb.gov.on.ca/ecs/MuniList.aspx?n=E',
    'https://www.omb.gov.on.ca/ecs/MuniList.aspx?n=F',
    'https://www.omb.gov.on.ca/ecs/MuniList.aspx?n=G',
    'https://www.omb.gov.on.ca/ecs/MuniList.aspx?n=H',
    'https://www.omb.gov.on.ca/ecs/MuniList.aspx?n=I',
    'https://www.omb.gov.on.ca/ecs/MuniList.aspx?n=J',
    'https://www.omb.gov.on.ca/ecs/MuniList.aspx?n=K',
    'https://www.omb.gov.on.ca/ecs/MuniList.aspx?n=L',
    'https://www.omb.gov.on.ca/ecs/MuniList.aspx?n=M',
    'https://www.omb.gov.on.ca/ecs/MuniList.aspx?n=N',
    'https://www.omb.gov.on.ca/ecs/MuniList.aspx?n=O',
    'https://www.omb.gov.on.ca/ecs/MuniList.aspx?n=P',
    'https://www.omb.gov.on.ca/ecs/MuniList.aspx?n=Q',
    'https://www.omb.gov.on.ca/ecs/MuniList.aspx?n=R',
    'https://www.omb.gov.on.ca/ecs/MuniList.aspx?n=S',
    'https://www.omb.gov.on.ca/ecs/MuniList.aspx?n=T',
    'https://www.omb.gov.on.ca/ecs/MuniList.aspx?n=U',
    'https://www.omb.gov.on.ca/ecs/MuniList.aspx?n=V',
    'https://www.omb.gov.on.ca/ecs/MuniList.aspx?n=W',
    'https://www.omb.gov.on.ca/ecs/MuniList.aspx?n=X',
    'https://www.omb.gov.on.ca/ecs/MuniList.aspx?n=Y',
    'https://www.omb.gov.on.ca/ecs/MuniList.aspx?n=Z'
  );

  // Initialize the array of municipalities.
  $munis = array();

  // This function scrapes the pages and populates $munis with the relevant info.
  // We'll execute it below.
  function pages_callback($response, $info, $request){

    preg_match_all('/(\/ecs\/MuniCaseList\.aspx.+?)>(.+?)</', $response, $matches);

    foreach ($matches[0] as $muni){
      // Declare the variable as global so we can access it from outside the function.
      global $munis;
      global $urlsonly;
      // Push each municipality's name and URL to the array.
      $matches = explode('>',substr($muni,0,-1));
      $munis[] = array(
        'name' => $matches[1],
        'href' => 'https://www.omb.gov.on.ca'.$matches[0]
      );
    }
  }

  // Populate our URL list and execute the callback function.
  $rc = new RollingCurl('pages_callback');
  $rc->window_size = 20;
  foreach ($urls as $url) $rc->request($url);
  $rc->execute();

  // Return the array of municipalities. Again, declare the global variable.
  global $munis;
  return $munis;
}


function getCases($urls = array()){

  // Initialize the array of cases.
  $cases = array();

  // This function scrapes the pages and populates $cases with the relevant info.
  // We'll execute it below.
  function cases_callback($response, $info, $request){

    preg_match_all('/<td.+?<\/td><td.+?<\/td><td.+?<\/td><td.+?<\/td><td.+?<\/td>/', $response, $matches);

    // Grab the municipality name from the URL
    parse_str(parse_url($info['url'],PHP_URL_QUERY),$query);

    foreach ($matches[0] as $row){

      preg_match('/<td.*?>(.*?)<\/td><td.*?>.*?<\/td><td.*?>(.*?)<\/td><td.*?>(.*?)<\/td><td.*?><a.+>(.*?)<\/a><\/td>/', $row, $matches);

      // Declare the variable as global so we can access it from outside the function.
      global $cases;

      // Ensure that there is a case number...
      if ($matches[4] != '') {
        // Push details to the array.
        $cases[] = array(
          'muni' => urldecode($query['mn']),
          'id' => $matches[4],
          'status' => $matches[3],
          'description' => $matches[2],
          'address' => $matches[1]
        );
      }

      // Output to show progress
      echo count($cases)."\n";
    }
  }

  // Populate our URL list and execute the callback function.
  $rc = new RollingCurl('cases_callback');
  $rc->window_size = 20;
  foreach ($urls as $url) $rc->request($url);
  $rc->execute();

  // Return the array of municipalities. Again, declare the global variable.
  global $cases;
  return $cases;
}

?>