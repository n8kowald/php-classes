PHP Classes
===========

## Goodreads.class.php
Class to get books from a Goodreads user and shelf.  
Created for on the homepage of my website: www.nathankowald.com  

### Usage
Include Goodreads.class.php in your page.  
Modify the settings inside init().  
Call the static method, <pre>Goodreads::getBooks();</pre> from the page you want to use it on. 

### Settings
Settings inside init()  
    $goodreads_id = 4609321; <-- Goodreads user id.
    $shelf = 'currently-reading'; <-- Shelf you want to display.
    $num_books = 5; <-- Number of books to display.
    
    $cache_dir = ''; <-- Directory to save cache files in. Create with appropriate permissions.
    $cachelife_secs = 604800; // <-- How long should cache last? Defaults to 1 week.
    
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

