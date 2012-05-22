<?php
/*************************************************************************
* Feeder
*
* Gets data from feeds
*
* @author Nathan Kowald
* @version 1.0
*
************************************************************************/
class Feeder {

    private static $feed_url;
    private static $num_items;
    private static $values;

    /**
    * isPrefix()
    * Checks if an xml element is a prefix
    *
    * @param string $value A single value. Taken from the given $values array
    * @return boolean True if prefix. False if not.
    */
    private static function isPrefix($value) {
        if (strpos($value, ':') === false) {
            return false;
        } else {
            return true;
        }
    }
    
    /**
    * getPrefixValue()
    * Gets the value of a prefixed element
    *
    * @param SimpleXMLElement $element A simpleXML element object
    * @param string $value A single value. Taken from the given $values array
    * @return string Value of the prefix
    */
    private static function getPrefixValue(SimpleXMLElement $element, $value) {
        $parts = explode(':', $value);
        return (string) $element->children($parts[0], true)->$parts[1];
    }
    
    /**
    * getData()
    * Builds an array containing the feed values set in the $values array.
    *
    * @param SimpleXMLElement $simpleXML A simpleXML object created in Feeder::loadFeed()
    * @return mixed Feed data if found or boolean false if no items found
    */
    private static function getData(SimpleXMLElement $simpleXML) {
        $data = array();
        $c = 0;
        foreach ($simpleXML as $element) {
            if ($c == self::$num_items) break;
            foreach (self::$values as $val) {
                $data[$c][$val] = (self::isPrefix($val)) ? self::getPrefixValue($element, $val) : (string) $element->$val;
            }
            $c++;
        }
        return (count($data) > 0) ? $data : false;
    }
    
    /**
    * loadFeed()
    * Loads a given feed.
    *
    * @return mixed Returns an xml object from a given feed, or false on failure
    */
    private static function loadFeed() {
        $xml = @simplexml_load_file(self::$feed_url, 'SimpleXMLElement', LIBXML_NOCDATA); // Removes CDATA from XML
        if (!$xml) {
            // Feed can't be loaded
        	return false;
        }
        return $xml;
    }

	/**
    * getEntries()
    * Detects feed format: atom or rss. Returns entries for that format
    *
    * @return SimpleXmlElement object containing feed items/entries
    */
    private static function getEntries() {
        if (!$xml = self::loadFeed()) return false;
        $root_element = $xml->getName();
        return ($root_element == 'rss') ? $xml->channel->item : $xml->entry;
    }
	
    /**
    * getItems()
    * Sets up required properties then gets $value data from the given feed
    *
    * @param string $feed_url  URL of the feed you want to get data from
    * @param int $num_items    Number of feed items you want to get
    * @param array $values     Array of values to get from the feed. E.g. array('title', 'description', link')
    * @return mixed array of feed data or false if no data found
    */
    public static function getItems($feed_url='', $num_items=10, $values=array()) {
        self::$feed_url = $feed_url;
        self::$num_items = $num_items;
        self::$values = $values;
        
        if (!$entries = self::getEntries()) return false;
        return self::getData($entries);
    }
	
}

?>
