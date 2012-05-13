PHP Classes
===========

## Cache.class.php
A simple caching class. Faster loading data for dynamic content.  

### Cache Settings
Set a cache directory inside `Cache::init()`. This directory must have write permissions.   
If left blank, cache files are saved in the calling directory.   

Cache filename and time is set in `Cache::init('filename.cache', 604800)` <-- Cache valid for 1 week  

## Feeder.class.php
A simple class to get data from an RSS feed.

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
    
    $goodreads_id = 4609321; // <-- Goodreads user id.  
    $shelf = 'currently-reading'; // <-- Shelf to display: currently-reading, read, to-read, favorites
    $num_books = 5; // <-- Number of books to display
    
    $books = Goodreads::getBooks($goodreads_id, $shelf, $num_books);
    
    $html = '<h3>Currently Reading</h3>';
    $html .= '<ul>';
    foreach ($books as $book) {
       $html .= '<li>' . $book . '</li>';
    }
    $html .= '</ul>';
    echo $html;
    ?>

## last.fm/Lastfm.class.php
Easily display your 'loved songs' on your website
**Demo:** http://www.nathankowald.com/favourites

### Usage
-Include Cache.class.php, Feeder.class.php and Goodreads.class.php.  
-Set: $username and $num_books  
-Call the static method, `Lastfm::getSongs($username, $num_songs)` from your page. 

### Example
    <?php
    include('Cache.class.php');
    include('Feeder.class.php');
    include('Goodreads.class.php');
    
    $songs = Lastfm::getSongs($username='n8kowald', $num_songs='20');
    $songs_html = '<ul>';
    foreach ($songs as $song) {
        $songs_html .= '<li>' . $song . '</li>';
    }
    $songs_html .= '</ul>';
    echo $songs_html;
    ?>



