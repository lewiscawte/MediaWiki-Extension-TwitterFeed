<?php

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
    $username = htmlspecialchars($params['id']);
    $nb = htmlspecialchars($params['nb']);
    if ($username==null || preg_match('%[^A-Za-z0-9_:/.]%',$ussername)) {
        return '<div class="errorbox">'.wfMsg('twitterfeed-badid').'</div>';
    }

    # Build URL and output embedded flash object
	$xml = "http://twitter.com/statuses/user_timeline/".$username.".rss";
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

