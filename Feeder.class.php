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
    * getData()
    * Builds an array containing the feed values set in the $values array.
    *
    * @param SimpleXMLElement $simpleXML A simpleXML object created in Feeder::loadFeed()
    * @return mixed Feed data if found or boolean false if no items found
    */
    private function getData(SimpleXMLElement $simpleXML) {
        $data = array();
        $c = 0;
        foreach ($simpleXML as $element) {
            if ($c == self::$num_items) break;
            foreach (self::$values as $val) {
                $data[$c][$val] = (string) $element->$val;
            }
            $c++;
        }
        return (count($data) > 0) ? $data : false;
    }
	
	/**
    * loadFeed()
    * Loads a given feed. Sets up a SimpleXMLElement containing feed items.
    *
    * @return mixed Returns the result of Feeder::getData() or false if feed can't be loaded
    */
    private function loadFeed() {
        $xml = @simplexml_load_file(self::$feed_url, 'SimpleXMLElement', LIBXML_NOCDATA); // Removes CDATA from XML
        if (!$xml) {
            // Feed not found
        	return false;
        }
        return self::getData($xml->channel->item);
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
    public function getItems($feed_url='', $num_items=10, $values=array()) {
        self::$feed_url = $feed_url;
        self::$num_items = $num_items;
        self::$values = $values;
        
        return self::loadFeed();
    }
	
}

?>