<?php
/*************************************************************************
* Cache
*
* Caches data, gets cached data
*
* @author Nathan Kowald
* @version 1.0
*
************************************************************************/
class Cache {

    private static $cache_dir;
    private static $cache_file;
    private static $cache_life_secs;
    
    /**
    * init()
    * Initialises settings
    *
    * @staticvar string $cache_dir         Set a directory to save cache files to e.g. /home/user/public_html/cache/
    *                                      If left blank it will save cache files to the calling directory (messier)
    *
    * @staticvar integer $cache_life_secs  Time (in seconds) that cache files should last for
    * @staticvar string $cache_file        Builds the cache_file string.
    */
    public static function init($cache_filename='', $cache_life='') {
        self::$cache_dir = ''; // e.g. /home/user/public_html/cache/
        if (self::$cache_dir == '') {
            $path = pathinfo(getcwd());
            self::$cache_dir = $path['dirname'] . '/' . $path['basename'] . '/';
        }
        self::$cache_life_secs = $cache_life;
        self::$cache_file = self::$cache_dir . $cache_filename;
    }
    
    /**
    * cacheFileExists()
    * Checks that the initialised cache_file exists on the server and is less than $cache_life_secs old
    *
    * @return bool True if cache_file exists: False if not
    */
    public static function cacheFileExists() {
        if (file_exists(self::$cache_file) && (filemtime(self::$cache_file) > (time() - self::$cache_life_secs))) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
    * getCache()
    * Gets the contents of the initialised cache file.
    *
    * @return mixed Returns the data stored in the cache file
    */
    public static function getCache() {
        $data = file_get_contents(self::$cache_file);
        return unserialize($data);
    }
    
    /**
    * setCache()
    * Creates the cache file, stores data in it.
    *
    * @param mixed $data Data you want to store in a cache file
    */
    public static function setCache($data='') {
        $cache_data = serialize($data);
        file_put_contents(self::$cache_file, $cache_data);
    }
}

?>
