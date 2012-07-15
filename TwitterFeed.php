<?php //{{MediaWikiExtension}}<source lang="php">
/*
 * TwitterFeed.php - Display lignes of the chosen public Twitter into a page.
 * @author Baptiste 'BapNesS' Carlier
 * @version 1.1 18th Sep 2009
 * @copyright Copyright (C) 2009 Baptiste 'BapNesS' Carlier
 * @license The MIT License - http://www.opensource.org/licenses/mit-license.php 
 * -----------------------------------------------------------------------
 * Description:
 *     This is a MediaWiki extension which adds an additional tag, <twitterfeed>, for displaying
 *     the last tweets into wiki articles.
 * Requirements:
 *     MediaWiki 1.6.x, 1.8.x, 1.9.x or higher
 *     PHP 5.x or higher
 * Installation:
 *     1. Drop this script (TwitterFeed.php) in $IP/extensions
 *         Note: $IP is your MediaWiki install dir.
 *     2. Enable the extension by adding this line to your LocalSettings.php:
 *            require_once('extensions/TwitterFeed.php');
 *     3. Make sure that these php5 variables are activated:
 *            allow_url_include = On
 *            allow_url_fopen = On
 * Usage:
 *     Once installed, you may utilize TwitterFeed by placing the <twitterfeed> tag in an
 *     article's text:
 *         <twitterfeed id="27030648"></twitterfeed>
 *     or
 *         <twitterfeed nb="3" id="27030648"></twitterfeed>
 * Where id is the session user id. To get it :
 *     1. Go to the Twitter page:
 *         Example:  http://twitter.com/winkinpark
 *     2. Copy the RSS Feed url:
 *         Example:  http://twitter.com/statuses/user_timeline/76944488.rss
 *     3. And finally get the ID :
 *         Example:  76944488
 * -----------------------------------------------------------------------
 * Copyright (c) 2009 Baptiste 'BapNesS' Carlier
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy 
 * of this software and associated documentation files (the "Software"), to deal 
 * in the Software without restriction, including without limitation the rights to 
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of 
 * the Software, and to permit persons to whom the Software is furnished to do 
 * so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all 
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, 
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES 
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND 
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT 
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, 
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING 
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR 
 * OTHER DEALINGS IN THE SOFTWARE. 
 * -----------------------------------------------------------------------
 */
 
# Confirm MW environment
if (defined('MEDIAWIKI')) {
 
# Credits
$wgExtensionCredits['parserhook'][] = array(
    'name'=>'TwitterFeed',
    'author'=>'Baptiste \'BapNesS\' Carlier (baptiste&lt;at&gt;nesscorp.fr)',
    'url'=>'http://twitter-feed.sourceforge.net',
    'description'=>'Display lignes of the chosen public Twitter into a page.',
    'version'=>'1.1'
);

# Register Extension initializer
$wgExtensionFunctions[] = "wfTwitterFeedExtension";
 
# Extension initializer
function wfTwitterFeedExtension() {
    global $wgParser, $wgMessageCache;
    $wgParser->setHook("twitterfeed", "renderTwitterFeed");
    $wgMessageCache->addMessage('twitterfeed-bad-id', 'Invalid or protected Twitter RSS supplied: [$1]');
}
 
/**
 * Tools
 */
class Tweet {
	var $user;
	var $user_link;
	var $content;
	var $date;
	var $content_link;
	
	function Tweet($user, $user_link, $content, $date, $content_link) {
		$this->user = $user;
		$this->user_link = $user_link;
		$this->content = $content;
		$this->date = $date;
		$this->content_link = $content_link;
	}
}

class Tweets {
	var $tab;
	var $nbTweets;
	function Tweets() {
		$this->tab = array();
		$this->nbTweets = 0;
	}
	function AddTweet($tweet) {
		array_push($this->tab, $tweet);
		$this->nbTweets++;
	}
	function Get($num) {
		return $this->tab[$num];
	}
}
/**
 * Callback function for display tweets.
 * @param String $input Text between open and close tags - should always be empty or null.
 * @param Array $params Array of tag attributes.
 * @param Parser $parser Instance of Parser performing the parse.
 */
function renderTwitterFeed( $input, $params, &$parser ) {

    # Check for parameters and ensure it has a valid value
    $id = htmlspecialchars($params['id']);
    $nb = htmlspecialchars($params['nb']);
    if ($id==null || preg_match('%[^A-Za-z0-9_:/.]%',$id)) {
        return '<div class="errorbox">'.wfMsgForContent('twitterfeed-bad-id', $id).'</div>';
    }

    # Build URL and output embedded flash object
	$xml = "http://twitter.com/statuses/user_timeline/".$id.".rss";
	$xml = simplexml_load_file($xml);
	
	$title = ucfirst(substr($xml->channel->title, 10));

	$nbTweets = sizeof($xml->channel->item);
	if (!preg_match('%[^0-9]%',$nb) && !($nb == null || $nb <= 0 || $nb > $nbTweets) ) {
		$nbTweets = $nb;
	}

	$my_tweets = new Tweets();

	for ( $i = 0 ; $i < $nbTweets ; $i++ ) {
		$cont = preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', '<a href="$1">$1</a>', $xml->channel->item[$i]->description);
		$my_tweets->AddTweet(new Tweet($title, $xml->channel->link, $cont, $xml->channel->item[$i]->pubDate, $xml->channel->item[$i]->link));
	}

	$toreturn = "";
	for ( $i = 0 ; $i < $nbTweets ; $i++){
		$user = $my_tweets->tab[$i]->user;
		$user_link = $my_tweets->tab[$i]->user_link;
		$content = $my_tweets->tab[$i]->content;
		$date = $my_tweets->tab[$i]->date;
		$content_link = $my_tweets->tab[$i]->content_link;
		
		// First loop
		if ( $i == 0 ) {
			$toreturn = $toreturn."<div><u><a href=\"".$user_link."\" targe=\"_blank\" title=\"".$user."\">".$user."</a></u><br/><ul>";
		}
		
		$toreturn = $toreturn."<li>[".substr($date, 0, strlen($date)-6)."] ".$content." (<a href=\"".$content_link."\" targe=\"_blank\" title=\"".$user."\">link</a>)</li>";
		
		// Last loop
		if ( $i+1 == $my_tweets->nbTweets ) {
			$toreturn = $toreturn."<ul></div>";
		}
	}
	
	return $toreturn;
}
 
} # Closing MW Environment wrapper
//</source>
?>