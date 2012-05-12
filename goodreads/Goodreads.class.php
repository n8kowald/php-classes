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
    private static $cache_dir;
    private static $cache_file;
    private static $cache_life_secs;
	
    /**
    * init()
    * Initialises settings
    *
    * @staticvar integer $num_books        Number of books to display 
    * @staticvar integer $goodreads_id     Goodreads user id: User IDs found in profile URLs e.g. http://goodreads.com/user/show/[4609321]-nathan
    * @staticvar string $shelf             The shelf to get books from e.g. currently-reading, read, to-read, favorites
    * @staticvar string $cache_dir         Set a directory to save cache files to e.g. /home/user/public_html/cache/
    *                                      If left blank it will save cache files to the current working directory
    * @staticvar integer $cache_life_secs  Time (in seconds) that cache files should last for
    * @staticvar string $cache_file        Builds the cache_file string. Format: 4609321-currently-reading.cache
    */
    private function init() {
        self::$goodreads_id = 4609321;
        self::$shelf = 'currently-reading'; // currently-reading, read, to-read, favorites
        self::$num_books = 5;
        /* Cache settings */
        self::$cache_dir = '';
        self::$cache_life_secs = 604800; // 1 week
        self::$cache_file = self::$cache_dir . self::$goodreads_id . '-' . self::$shelf .'.cache'; 
    }

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
    * cachefileExists()
    * Checks that a given cache_file exists on the server and is less than 1 week old
    *
    * @param string $cache_file Path and name of cache_file to look for
    * @return bool True if cache_file exists: False if not
    */
    private function cachefileExists($cache_file) {
		if (file_exists($cache_file) && (filemtime($cache_file) > (time() - self::$cache_life_secs))) {
			return true;
		} else {
			return false;
		}
    }
	
    /**
    * getBookData()
    * Takes a SimpleXMLElement built from self::getGoodreadsFeed()
    * Iterates over feed items, gets book details, adds found books details to an array
    *
    * @param SimpleXMLElement $simpleXML Books contained inside simpleXMLElement
    * @return array Returns an array of book details
    */
    private function getBookData(SimpleXMLElement $simpleXML) {
        $book_data = array();
        $c = 0;
        // NOTE: to see all the details available, view source of the URL contained in self::getGoodreadsFeed()
        foreach ($simpleXML as $element) {
            if ($c == self::$num_books) break;
            $book_data[$c]['title'] = (string) $element->title;
            $book_data[$c]['author'] = (string) $element->author_name;
            $book_data[$c]['book_id'] = (string) $element->book['id'];
            $c++;
        }
        return $book_data;
    }
	
    /**
    * formatBookData()
    * Gets book details and formats them e.g. %title% - <span class="author">%author%</span> %link%
    * I wanted a link to The Book Depository to follow each book. Link includes an affiliate ID: &a_aid=lowest-price.
    *
    * @param array $book_data Array of book details
    * @return array Returns a formatted array of books
    */
    private function formatBookData(Array $book_data) {
        $books = array();
        foreach ($book_data as $value) {
            $book_search = $value['title'] . " " . $value['author'];
            $search_qry = str_replace(',', '', str_replace(' ', '+', $book_search));
            $link = '[<a href="http://www.bookdepository.co.uk/search?searchTerm='.$search_qry.'&amp;search=search&amp;a_aid=lowest-price" target="_blank">view</a>]';
            $books[] = $value['title'] . " &mdash; <span class=\"author\">" . $value['author'] . "</span> &nbsp;" . $link;
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

        if (self::cachefileExists(self::$cache_file)) {
		
            $books_data = file_get_contents(self::$cache_file);
            return unserialize($books_data);
			
        } else {

            $xml = simplexml_load_file(self::getGoodreadsFeed(), 'SimpleXMLElement', LIBXML_NOCDATA); // Removes CDATA from XML
            if (!$xml) {
                return array("Error: Can't access this URL: " . self::getGoodreadsFeed());
            }
            $book_data = self::getBookData($xml->channel->item);
			
            if (!is_array($book_data)) { 
                return array("Error: No '".self::$shelf."' books found for Goodreads user id: ".self::$goodreads_id);
            }
			
            $books = self::formatBookData($book_data);
            // cache book data as serialised array
            $books_data = serialize($books);
            file_put_contents(self::$cache_file, $books_data);
            return $books;
			
        }
	
    }

    /**
    * getBooks()
    * Gets found books for Goodread's user and shelf, returns them to client code.
    * self::init() initializes static properties for settings 
    *
    * @return array Returns an array of formatted books to client code
    */
    public static function getBooks() {
        self::init();
        return self::getBooksFromShelf();
    }
	
}

?>
