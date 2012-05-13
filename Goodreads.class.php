<?php
/*************************************************************************
* Goodreads
*
* Class to easily display your Goodreads books on your website.
*
* @author Nathan Kowald
* @version 1.0
*
************************************************************************/
class Goodreads {
	
    private static $goodreads_id;
    private static $shelf;
    private static $num_books;
    private static $error;

    /**
    * getGoodreadsFeed()
    * Builds feed URL for given user and shelf
    *
    * @return string $url Goodreads feed to grab books from
    */
    private function getGoodreadsFeed() {
        $url = sprintf("http://www.goodreads.com/review/list_rss/%d?shelf=%s", 
            self::$goodreads_id,
            self::$shelf
        );
        return $url;
    }

    /**
    * formatBookData()
    * Gets book details and formats them e.g. %title% - <span class="author">%author%</span> %link%
    * I wanted a link to The Book Depository to follow each book. Link includes an affiliate ID: &a_aid=lowest-price.
    *
    * @param array $book_data Array containing book details
    * @return array Returns a formatted array of books
    */
    private function formatBookData(Array $book_data) {
        $books = array();
        foreach ($book_data as $value) {
            $book_search = $value['title'] . " " . $value['author_name'];
            $search_qry = str_replace(',', '', str_replace(' ', '+', $book_search));
            $link = '[<a href="http://www.bookdepository.co.uk/search?searchTerm='.$search_qry.'&amp;search=search&amp;a_aid=lowest-price" target="_blank">view</a>]';
            $books[] = $value['title'] . " &mdash; <span class=\"author\">" . $value['author_name'] . "</span> &nbsp;" . $link;
        }
        return $books;
    }
	
    /**
    * getBooksFromShelf()
    * Gets the array of books from a cache_file if found (and less than a week old).
    * If no cache_file is found it creates this array of books and adds them to the cache_file - for fast loading next time
    *
    * @return array Returns a nicely formatted array of books
    */
    private function getBooksFromShelf() {
        $cache_filename = self::$goodreads_id .'-'. self::$shelf .'.cache';
        $cache_life = 604800; // 1 week
        Cache::init($cache_filename, $cache_life);
        if (Cache::cacheFileExists()) {
            return Cache::getCache();
        }
        $book_data = Feeder::getItems(self::getGoodreadsFeed(), self::$num_books, array('title', 'author_name'));
        if (!is_array($book_data)) {
            self::$error = "Goodreads feed does not exist. Check user: ".self::$goodreads_id." and shelf: '".self::$shelf."' exist";
            return array(self::$error); 
        }
        $books = self::formatBookData($book_data);
        Cache::setCache($books);
        return $books;
    }
    
    /**
    * isValidGoodreadsID()
    * Superficial validity check. Checks that ID is not blank and a number
    *
    * @param integer $goodreads_id  Given ID to check 
    *
    * @return boolean Returns true/false and sets an error message in self::$error if false
    */

    private static function isValidGoodreadsID($goodreads_id) {
    	if ($goodreads_id == '') {
    		self::$error = 'Goodreads user ID cannot be blank';
    		return false;
    	} else if (!is_numeric($goodreads_id)) {
    		self::$error = 'Goodreads user ID needs to be a number';
    		return false;
    	}
    	return true;
    }

    /**
    * getBooks()
    * Gets found books for Goodreads user and shelf, returns them to client code.
    * self::init() initializes cache settings from param values 
    *
    * @param integer $gid        Goodreads user id: User IDs found in profile URLs e.g. http://goodreads.com/user/show/[4609321]-nathan
    * @param string $shelf       The shelf to get books from e.g. currently-reading, read, to-read, favorites
    * @param integer $num_books  Number of books to display 
    *
    * @return array Returns an array of formatted books to client code or an error.
    */
    public static function getBooks($gid='', $shelf='currently-reading', $num_books = 5) {
    
    	if (!self::isValidGoodreadsID($gid)) return array(self::$error);

    	self::$goodreads_id = $gid;
        self::$shelf = $shelf;
        self::$num_books = $num_books;

        return self::getBooksFromShelf();
    }
	
}

?>
