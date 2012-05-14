PHP Classes
===========

## Cache.class.php
A simple caching class. Faster loading data for dynamic content.  

### Cache Settings
Set a directory for cache files inside `Cache::init()`. This directory must have write permissions.   
If left blank, cache files are saved in the calling directory.   

Cache filename and cache time are set in `Cache::init($cache_filename, $cache_life)` <-- Cache lasts 1 week  

### Example
    <?php
    include('Cache.class.php');
    // Get contents of a cache file
    Cache::init('4609321-currently-reading.cache', 604800);
    if (Cache::cacheFileExists()) {
        return Cache::getCache();
    }
    // Create a cache file 
    $books = array('Flowers for Algernon - Daniel Keyes', 'The Stranger - Albert Camus');
    Cache::setCache($books);
    ?>

## Feeder.class.php
A simple class to get data from an RSS feed.

### Example
    <?php
    include('Feeder.class.php');
    $items = Feeder::getItems('http://feeds.delicious.com/v2/rss/n8kowald/shared', 10, array('title', 'link', 'description'));
    $article_html = "<ul class=\"lovedarticles\">\n";
    foreach ($items as $item) {
        $item_html = '<a href="'.$item['link'].'" target="_blank">'.$item['title'].'</a>';
        if ($item['description'] != '') $item_html .= "&ndash; " . $item['description'];
            $article_html .= "<li>$item_html</li>\n";
        }
    $article_html .= "</ul>\n";
    echo $article_html;
    ?>

## Goodreads.class.php
Easily display your Goodreads books on your website.  
**Demo:** http://www.nathankowald.com <-- under 'Books I'm Reading'

### Usage
-Include Cache.class.php, Feeder.class.php and Goodreads.class.php.  
-Set: $goodreads_id, $shelf and $num_books  
-Call the static method, `Goodreads::getBooks($goodreads_id, $shelf, $num_books)` from your page. 

### Example
    <?php
    include('Cache.class.php');
    include('Feeder.class.php');
    include('Goodreads.class.php');
    
    $books = Goodreads::getBooks($goodreads_id=4609321, $shelf='currently-reading', $num_books=5);
    
    $html = '<h3>Currently Reading</h3>';
    $html .= '<ul>';
    foreach ($books as $book) {
       $html .= '<li>' . $book . '</li>';
    }
    $html .= '</ul>';
    echo $html;
    ?>

## Lastfm.class.php
Easily display your last.fm 'loved songs' on your website.  
**Demo:** http://www.nathankowald.com/favourites

### Usage
-Include Cache.class.php, Feeder.class.php and Goodreads.class.php.  
-Set: $username and $num_books  
-Call the static method, `Lastfm::getLovedSongs($username, $num_songs)` from your page. 

### Example
    <?php
    include('Cache.class.php');
    include('Feeder.class.php');
    include('Goodreads.class.php');
    
    $songs = Lastfm::getLovedSongs($username='n8kowald', $num_songs='20');
    $songs_html = '<ul>';
    foreach ($songs as $song) {
        $songs_html .= '<li>' . $song . '</li>';
    }
    $songs_html .= '</ul>';
    echo $songs_html;
    ?>



