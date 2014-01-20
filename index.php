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

include_once('config.php');
include_once('functions.php');

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