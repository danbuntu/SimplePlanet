<?php include('config.php'); ?>
<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rss version="2.0">
<channel>
<title>Aggregated Feed Demo</title>
<link><?php echo $siteUrl ?></link>
<description>
<?php echo $description; ?>
</description>
<language>en-us</language>

<?php



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
?>

<?php if ($success) {
$itemlimit=0;
foreach($feed->get_items() as $item) {

?>
<item>
<title><?php echo $item->get_title() . '-' . $item->get_title(); ?></title>
<link><?php echo $item->get_permalink(); ?></link>
<description>
<?php echo $item->get_content(); ?>
</description>
</item>
<?

}
}
?>
</channel>
</rss>