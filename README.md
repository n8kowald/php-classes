PHP Classes
===========

## Goodreads.class.php
Class to get books from a Goodreads user and shelf.  
Created for on the homepage of my website: www.nathankowald.com  

### Usage
    // Include the class, modify the settings inside init() and formatBookData()
    // call getBooks() from your page
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

