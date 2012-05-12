PHP Classes
===========

## Goodreads.class.php
Class to easily display your Goodreads books on your website.

### Usage
-Include Goodreads.class.php in your page.  
-Modify the settings inside init().  
-Call the static method, Goodreads::getBooks(); from the page you want to use it on. 

### Settings  
    $goodreads_id = 4609321; <-- Goodreads user id.  
    $shelf = 'currently-reading'; <-- Shelf to display.  
    $num_books = 5; <-- Number of books to display.  
     
    $cache_dir = ''; <-- Directory for cache files. Create with appropriate permissions.  
    $cachelife_secs = 604800; // <-- How long should book cache last? Defaults to 1 week.  
    
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

