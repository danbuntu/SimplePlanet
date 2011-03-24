<?php
include('config.php');

require("simplepie/simplepie.inc");


// build array of feeds
$query = "SELECT feedsurl FROM feeds";
$results = mysql_query($query);

$feeds = array();
while ($row = mysql_fetch_array($results)) {
    $feeds[] = $row['0'];
}

//print_r($feeds);



$feed = new SimplePie();


$feed->set_feed_url($feeds);


// We'll use favicon caching here (Optional)
$feed->set_favicon_handler('handler_image.php');


//
// Run SimplePie.
$feed->init();

// This makes sure that the content is sent to the browser as text/html and the UTF-8 character set (since we didn't change it).
$feed->handle_content_type();

$feed->set_cache_duration(600); // Set the cache time
//turn on debug
if ($debug == '1') {
    if ($feed->error()) {
        echo $feed->error();
    }
}


// Get one of each feed for the list of blogs
// This array will hold the items we'll be grabbing.
$first_items = array();

// Let's go through the array, feed by feed, and store the items we want.
foreach ($feeds as $url) {
    // Use the long syntax
    $feed2 = new SimplePie();
    $feed2->set_feed_url($url);
    $feed2->init();

    // How many items per feed should we try to grab?
    $items_per_feed2 = 1;

    // As long as we're not trying to grab more items than the feed has, go through them one by one and add them to the array.
    for ($x = 0; $x < $feed2->get_item_quantity($items_per_feed2); $x++) {
        $first_items[] = $feed2->get_item($x);
    }

    // We're done with this feed, so let's release some memory.
    unset($feed2);
}


// function to shorten the text

function shorten($string, $length)
{
    // By default, an ellipsis will be appended to the end of the text.
    $suffix = '&hellip;';

    // Convert 'smart' punctuation to 'dumb' punctuation, strip the HTML tags,
    // and convert all tabs and line-break characters to single spaces.
    $short_desc = trim(str_replace(array("\r","\n", "\t"), ' ', strip_tags($string)));

    // Cut the string to the requested length, and strip any extraneous spaces
    // from the beginning and end.
    $desc = trim(substr($short_desc, 0, $length));

    // Find out what the last displayed character is in the shortened string
    $lastchar = substr($desc, -1, 1);

    // If the last character is a period, an exclamation point, or a question
    // mark, clear out the appended text.
    if ($lastchar == '.' || $lastchar == '!' || $lastchar == '?') $suffix='';

    // Append the text.
    $desc .= $suffix;

    // Send the new description back to the page.
    return $desc;
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <!-- make the rss icon avalaible -->
        <link rel="alternate" type="application/rss+xml"  href="<?php $siteUrl ?>feed.php" title="Your title">

            <link rel="stylesheet" href="style.css" type="text/css" media="screen" charset="utf-8" />

            <style type="text/css">
                h4.title {
                    /* We're going to add some space next to the titles so we can fit the 16x16 favicon image. */
                    background-color:transparent;
                    background-repeat:no-repeat;
                    background-position:0 1px;
                    padding-left:20px;
                }
            </style>
    </head>
    <body>

        <div id="background">
            <div id="wrapper">
                <div id="site">
                    <title><?php echo $planetTitle ?></title>
<?php if ($feed->error): ?>
                        <p><?php echo $feed->error; ?></p>
<?php endif; ?>

                        <h1><?php echo $planetTitle ?></h1>

<?php
    $itemlimit = 0;
    foreach ($feed->get_items() as $item) {
        if ($itemlimit == $max_items) {
            break;
        }
        if ($itemlimit <= $total_items) {
            $itemlimit = $itemlimit + 1;
        } else {
?>

                    <div class="chunk">

                    <?php /* Here, we'll use the $item->get_feed() method to gain access to the parent feed-level data for the specified item. */ ?>
                        <h4 class="title" style="background-image:url(<?php $feed = $item->get_feed();
                    echo $feed->get_favicon(); ?>);"><a href="<?php echo $item->get_permalink(); ?>"><?php echo $item->get_title(); ?></a></h4>

                        <?php echo shorten($item->get_content(), 350); ?>

                        <p class="footnote">Source: <a href="<?php $feed = $item->get_feed();
                        echo $feed->get_permalink(); ?>"><?php $feed = $item->get_feed();
                        echo $feed->get_title(); ?></a> | <?php echo $item->get_date('j M Y | g:i a T'); ?></p>

                    </div>


<?php $itemlimit = $itemlimit + 1;
                    }  } ?>

                        <div id="more">
<a href="index.php">newer items <</a>
                        </div>
                </div>
                <div id="feeds">

                    <div id="feed">
                        <a href="<?php echo $siteUrl ?>/feed2.php"><img src="./images/rss_icon.png"></a>
                    </div>
<?php include('rightmenu.php'); ?>

                </div>
            </div>
        </div>
        <div id="footer">
            <?php include('footer.php'); ?>
        </div>
    </body>
</html>
