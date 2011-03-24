<?php
include('config.php');
include("./feedcreator/feedcreator.class.php");
include_once('./simplepie/simplepie.inc'); // Include SimplePie

// build array of feeds
$query = "SELECT feedsurl FROM feeds";
$results = mysql_query($query);

$feeds = array();
while ($row = mysql_fetch_array($results) ) {
$feeds[] = $row['0'];
}

$feed = new SimplePie(); // Create a new instance of SimplePie
// Load the feeds
$feed->set_feed_url($feeds);

$feed->set_cache_duration (600); // Set the cache time
$feed->enable_xml_dump(isset($_GET['xmldump']) ? true : false);
$success = $feed->init(); // Initialize SimplePie
$feed->handle_content_type(); // Take care of the character encoding


$rss = new UniversalFeedCreator();
$rss->useCached();
$rss->title = $planetTitle;
$rss->description = $description;
$rss->link = $siteUrl;
$rss->syndicationURL = $siteUrl;

$image = new FeedImage();
$image->title = $planetTitle . " logo";
$image->url = "./images/logo.gif";
$image->link = $siteUrl;
$image->description = $description;
$rss->image = $image;


foreach($feed->get_items() as $item2) {

    $item = new FeedItem();
    $item->title = $item2->get_title();
    $item->link = $item2->get_permalink();
    $item->description = $item2->get_description();
    $item->date = $item2->get_date('j M Y | g:i a T');
    $item->source = $item2->get_feed();
    $item->author = $item2->get_author();

    $rss->addItem($item);
}

$rss->saveFeed("RSS1.0", "feed.xml");
?>
