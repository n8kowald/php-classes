## Goodreads.class.php
Class to easily display your Goodreads books on your website.
**Demo:** http://www.nathankowald.com <-- under 'Books I'm Reading'

### Usage
-Include Goodreads.class.php in your page.  
-Update the settings inside Goodreads::init().  
-Call the static method, Goodreads::getBooks() from the page you want to use it on. 

### Settings  
    private function init() {
    
    self::$goodreads_id = 4609321; <-- Goodreads user id.  
    self::$shelf = 'currently-reading'; <-- Shelf to display.  
    self::$num_books = 5; <-- Number of books to display.  
    
    self::$cache_dir = ''; <-- Directory for cache files. Create with appropriate permissions.  
    self::$cachelife_secs = 604800; <-- How long should book cache last? Defaults to 1 week.  

    }

### Example
    <?php
    include('Goodreads.class.php');
    $books = Goodreads::getBooks();
    
    $html = '<h3>Currently Reading</h3>';
    $html .= '<ul>';
    foreach ($books as $book) {
       $html .= '<li>' . $book . '</li>';
    }
    $html .= '</ul>';
    echo $html;
    ?>

