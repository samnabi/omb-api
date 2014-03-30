<?php include_once('header.php') ?>
    <main>
        <h3>Find OMB cases</h3>

        <form action="results.php" method="GET">

            <fieldset>
                <legend>Municipality</legend>
                <?php
                    $db = new PDO('sqlite:db/'.$db_filename);
                    $munis = $db->query('SELECT DISTINCT `muni` FROM `cases` ORDER BY `muni` ASC');
                    $munis = $munis->fetchall(PDO::FETCH_COLUMN);
                ?>
                <select class="munisList" name="munis[]" multiple data-placeholder="Type one or more municipality names...">
                    <?php foreach($munis as $muni) { ?>
                        <option value="<?php echo urlencode($muni) ?>"><?php echo $muni ?></option>
                    <?php } ?>
                </select>
            </fieldset>

            <fieldset>
                <legend>Keywords <span>Optional</span></legend>
                <input name="keywords" type="text" placeholder="Search descriptions and addresses" />
            </fieldset>

            <fieldset>
                <legend>Status</legend>
                <label><input type="checkbox" name="status[]" value="Open" checked /> Show <strong>open</strong> cases</label>
                <label><input type="checkbox" name="status[]" value="Closed" /> Show <strong>closed</strong> cases</label>
            </fieldset>

            <fieldset>
                <legend>Format</legend>
                <label><input type="radio" name="format" value="web" checked /> Human-readable search results</label>
                <label><input type="radio" name="format" value="json" /> JSON object</label>
                <label><input type="radio" name="format" value="rss" /> RSS feed</label>
            </fieldset>

            <fieldset class="center">
                <input class="button" type="submit" value="Search" />
            </fieldset>
        </form>
    </main>
<?php include_once('footer.php') ?>