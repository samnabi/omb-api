<!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]> <html class="lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]> <html class="lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>Task List</title>
  <link rel="stylesheet" href="style.css">
  <!--[if lt IE 9]><script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
</head>
<body>

<!-- Includes and functions -->
<?php

include_once('simple_html_dom.php');

function getMunis() {
        
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
            $munis[] =  array(
                            'name' => $muni->plaintext,
                            'href' => 'https://www.omb.gov.on.ca'.$muni->href
                        );
        }
    }
    
    $html->clear(); // Fix memleak
    $html2->clear(); // Fix memleak
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

<!-- Municipality picker-->
<?php if(!isset($_POST['munis'])){ ?>
    <form action="" method="POST">
        <input type="hidden" name="munis" value="true" />
        <section class="tasks">
          <header class="tasks-header">
            <h2 class="tasks-title">Municipalities</h2>
          </header>
          <fieldset class="tasks-list">
            <?php foreach(getMunis() as $muni) { ?>
                <label class="tasks-list-item">
                    <input type="checkbox" name="<?php echo urlencode($muni['name']) ?>" value="<?php echo $muni['href'] ?>" class="tasks-list-cb">
                    <span class="tasks-list-mark"></span>
                    <span class="tasks-list-desc"><?php echo $muni['name'] ?></span>
                </label>
            <?php } ?>
          </fieldset>
        </section>
        <input class="btn" type="submit" value="Submit" />
    </form>
<?php } ?>

<!-- Case table -->
<?php if(isset($_POST['munis'])) {
    array_shift($_POST); ?>
    <table class="cases">
        <thead>
            <tr>
                <th>Municipality</th>
                <th>Case No.</th>
                <th>Status</th>
                <th>Description</th>
                <th>Address</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($_POST as $muni => $url) $urls[] = urldecode($url);
            foreach (getCases($urls) as $case) { ?>
                <tr>
                    <td><?php echo $case['muni'] ?></td>
                    <td><a href="https://www.omb.gov.on.ca/ecs/CaseDetail.aspx?n=<?php echo $case['id'] ?>"><?php echo $case['id'] ?></a></td>
                    <td><?php echo $case['status'] ?></td>
                    <td><?php echo $case['description'] ?></td>
                    <td><?php echo $case['address'] ?></td>
                </tr>
            <?php } ?>
        </tbody>   
    </table>
<?php } ?>

</body>
</html>