<?php  

/*
getFeeds('https://habr.com/ru/rss/best/daily/');

function getFeeds($url) {

  $content = file_get_contents($url);
  $items = new SimpleXmlElement($content);

  print "<ul>";

   foreach($items->channel->item as $item) {

   		print_r($item->link);
   		print_r($item->title);
   		print_r($item->description );
   }

  print "</ul>";

  }
*/
$url = "https://habr.com/ru/rss/best/daily/";

feed($url);

 function feed($feedURL) {
    $i = 0; // initiate counter to limit the amount of articles to return
    $url = $feedURL; // url to parse

	$temp = mb_convert_encoding( file_get_contents($url), 'UTF-8' );
	$rss = simplexml_load_string($temp);

	   // $rss = simplexml_load_file($url); // the XML parser
        // RSS items loop
        foreach($rss->channel->item as $item) {  //loop through each item
            $link = $item->link;  //extract the link
            $title = $item->title;  //extract the title           
            $description = strip_tags($item->description);  //extract description and strip HTML
                if (strlen($description) > 200) {
                    // truncate string if greater than 200 characters
                    $stringCut = substr($description, 0, 200);
                    // make sure it ends in a complete word and add ... at the end
                    $description = substr($stringCut, 0, strrpos($stringCut, ' ')).'...'; 
                }
                if ($i < 5) { // parse only 4 items
                    echo '
                        <a href="'.$link.'" target="_blank"><h5>'.$title.'</h5></a>
                        <p>'.$description.'</p>';
                }
            $i++;
        }
}