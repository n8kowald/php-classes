## Goodreads.class.php
Class to easily display your Goodreads books on your website.  
**Demo:** http://www.nathankowald.com <-- under 'Books I'm Reading'

### Usage
-Include Goodreads.class.php.  
-Update the cache settings inside `Goodreads::init()`. If setting a cache directory, must have write permissions.  
-Set values for: $goodreads_id, $shelf and $num_books  
-Call the static method, `Goodreads::getBooks($goodreads_id, $shelf, $num_books)` from your page. 

### Cache Settings  
    private function init() {
        self::$cache_dir = ''; <-- Directory for cache files. Left blank, cache files created in calling directory. 
        self::$cachelife_secs = 604800; <-- How long should cache last? Defaults to 1 week.  
    }

### Example
    <?php
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

