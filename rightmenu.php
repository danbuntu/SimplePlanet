<?php
// Get one of each feed and display the title

echo '<h3>Follow Our Bloggers</h3>';

foreach ($first_items as $item) :
    $feed = $item->get_feed();
?>
    <a href="<?php $feed = $item->get_feed();
    echo $feed->get_permalink(); ?>"><?php $feed = $item->get_feed();
    echo $feed->get_title(); ?></a>
<?php
    echo '<br/>';
endforeach
?>
