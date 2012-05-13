<?php
/*************************************************************************
* Last.fm
*
* Class to easily display Last.fm information on your page
*
* @author Nathan Kowald
* @version 1.0
*
************************************************************************/
class Lastfm {
	
	private static $username;
	private static $num_songs;
    private static $error;

    /**
    * getLovedSongsFeed()
    * Builds feed URL for last.fm loved tracks
    *
    * @return string $url Last.fm feed to grab loved songs from
    */
    private function getLovedSongsFeed() {
        $url = sprintf("http://ws.audioscrobbler.com/2.0/user/%s/lovedtracks.rss", 
           self::$username
        );
        return $url;
    }
    
	/**
    * formatSongData()
    * Formats songs. Links to the song on last.fm
    *
    * @param array $song_data Song data to format
    * @return array $songs Returns formatted songs
    */
    private function formatSongData(Array $song_data) {
        $songs = array();
        foreach ($song_data as $song) {
            $songs[] = "<a href=\"".$song['link']."\">".$song['title']."</a>";
        }
        return $songs;
    }
    
	/**
    * getLovedSongs()
    * Gets user's loved songs. From cache file or from the feed.
    *
    * @return array $songs Returns formatted songs
    */
    private function getLovedSongs() {
        $cache_filename = self::$username . '-loved-tracks.cache';
        $cache_life = 86400; // 1 day
        Cache::init($cache_filename, $cache_life);
        
        if (Cache::cacheFileExists()) {
            return Cache::getCache();
        } else {
            $song_data = Feeder::getItems(self::getLovedSongsFeed(), self::$num_songs, array('title', 'link'));
            if (!is_array($song_data)) {
            	self::$error = "Last.fm loved tracks feed not found";
            	return array(self::$error); 
            }
            $songs = self::formatSongData($song_data);
            Cache::setCache($songs);
            return $songs;
        }
    }
    
    /**
    * isValidUsername()
    * Superficial check to see if given username is valid.
    *
    * @return boolean True if name is not blank, False if blank
    */
    private static function isValidUsername($username) {
    	if ($username == '') {
    		self::$error = 'Username cannot be blank';
    		return false;
    	}
    	return true;
    }
    
    /**
    * getSongs()
    * Public function to call in client code that gets a user's loved songs
    *
    * @param $username Last.fm username
    * @param $num_songs The number of loved songs to retrieve
    * @return boolean True if name is not blank, False if blank
    */
    public static function getSongs($username='', $num_songs = 30) {
    
    	if (!self::isValidUsername($username)) return array(self::$error);

    	self::$username = $username;
        self::$num_songs = $num_songs;
        
        return self::getLovedSongs();
    }
	
}

?>