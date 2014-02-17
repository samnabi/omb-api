<?php
// Include config file
include_once('lib/config.php');

// Fetch requested cases and push them to an array 
$stmt = 'SELECT * FROM cases WHERE';
$first = true;
foreach ($_GET['munis'] as $muni){
    if($first){
        $stmt .= ' (`muni` = "'.urldecode($muni).'"';
        $first = false;
    }
    else $stmt .= ' OR `muni` = "'.urldecode($muni).'"';
}
$stmt .= ')';
$first = true;
foreach ($_GET['status'] as $status){
    if($first){
        $stmt .= ' AND (`status` = "'.urldecode($status).'"';
        $first = false;
    }
    else $stmt .= ' OR `status` = "'.urldecode($status).'"';
}
$stmt .= ')';
if(!empty($_GET['keywords'])){
    $first = true;
    $words = explode(' ',preg_replace("/[^A-Za-z0-9 ]/", '', $_GET['keywords']));
    foreach ($words as $word){
        if($first){
            $stmt .= ' AND (`address` LIKE "%'.$word.'%" OR `description` LIKE "%'.$word.'%" OR `id` LIKE "%'.$word.'%"';
            $first = false;
        }
        else $stmt .= ' OR `address` LIKE "%'.$word.'%" OR `description` LIKE "%'.$word.'%" OR `id` LIKE "%'.$word.'%"';
    }
    $stmt .= ')';
}
$db = new PDO('sqlite:db/'.$db_filename);
$cases = $db->query($stmt);
$cases = $cases->fetchall(PDO::FETCH_CLASS);

if($_GET['format'] == 'json'){
    echo json_encode($cases);
}
elseif ($_GET['format'] == 'rss') {
    echo 'This should return an RSS feed. I\'m working on it.';
}
else { ?>
    <?php include_once('header.php') ?>
        <main>
            <p><a href="index.php">&larr; Back to search</a></p>
            <p>Found <strong><?php echo count($cases) ?></strong> OMB cases matching the following criteria:</p>
            <ul class="resultsOverview">
                <li>Municipalities: <?php foreach ($_GET['munis'] as $muni) echo '<span>'.urldecode($muni).'</span>' ?></li>
                <li>Status: <?php foreach ($_GET['status'] as $status) echo '<span>'.urldecode($status).'</span>' ?></li>
                <?php if (isset($words)){ ?>
                    <li>Keywords: <?php foreach ($words as $word) echo '<span>'.urldecode($word).'</span>' ?></li>
                <?php } ?>
            </ul>
            <ul class="searchResults">
                <?php foreach ($cases as $case) { ?>
                    <li class="clearfix">
                        <header>
                            <p>Case No. <a href="https://www.omb.gov.on.ca/ecs/CaseDetail.aspx?n=<?php echo $case->id ?>"><?php echo $case->id ?></a></p>
                            <p>Status: <?php echo $case->status ?></p>
                            <p><strong><?php echo $case->address == '' ? '' : $case->address.', ' ?><?php echo $case->muni ?></strong></p>
                        </header>
                        <main>
                            <p><?php echo $case->description ?></p>
                        </main>
                    </li>
                <?php } ?>
            </ul>
        </main>
    <?php include_once('footer.php') ?>
<?php } ?>