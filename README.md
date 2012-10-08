PHP Classes
===========

## Cache.class.php
A simple caching class. Faster loading for dynamic content.  

### Cache Settings
Set a directory for cache files inside `Cache::init()`. This directory must have write permissions.   
If left blank, cache files are saved in the calling directory.   

Cache filename and cache time (seconds) are set in `Cache::init($cache_filename, $cache_life)`

### Example
    <?php
    include('Cache.class.php');
    Cache::init('currently-reading.cache', 86400); // <-- 1 day cache life
    if (Cache::cacheFileExists()) {
        // Get contents of a cache file
        echo Cache::getCache();
    } else {
        // Create a cache file
        $books = array('Flowers for Algernon - Daniel Keyes', 'The Stranger - Albert Camus');
        Cache::setCache($books);
    }
    ?>

## Feeder.class.php
A simple class to get data from an RSS feed.

### Example
    <?php
    include('Feeder.class.php');
    $items = Feeder::getItems('http://ws.audioscrobbler.com/2.0/user/n8kowald/lovedtracks.rss', 10, array('title', 'link'));
    $html = "<ul>";
    foreach ($items as $item) {
        $html .= '<a href="'.$item['link'].'">'.$item['title'].'</a>';
        $html .= "</ul>";
    }
    echo $html;
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
-Set: $username and $num_songs  
-Call Lastfm::getLovedSongs($username, $num_songs)` from your page. 

### Example
    <?php
    include('Cache.class.php');
    include('Feeder.class.php');
    include('Lastfm.class.php');
    
    $songs = Lastfm::getLovedSongs($username='n8kowald', $num_songs='20');
    $html = '<ul>';
    foreach ($songs as $song) {
        $html .= '<li>' . $song . '</li>';
    }
    $html .= '</ul>';
    echo $html;
    ?>



