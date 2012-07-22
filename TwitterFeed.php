<?php
/*
 * TwitterFeed.php - Display lignes of the chosen public Twitter into a page.
 * @author Baptiste 'BapNesS' Carlier
 * @version 1.1 18th Sep 2009
 * @copyright Copyright (C) 2009 Baptiste 'BapNesS' Carlier
 * @license The MIT License - http://www.opensource.org/licenses/mit-license.php 
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
    'name' => 'TwitterFeed',
    'author' => array('Baptiste \'BapNesS\' Carlier', 'Lewis Cawte'),
    'url' => 'https://www.mediawiki.org/wiki/Extenion:TwitterFeed',
    'descriptionmsg' => 'twitterfeed-desc',
    'version' => '1.2'
);

# Register Extension initializer
$wgExtensionFunctions[] = "wfTwitterFeedExtension";

// Internationalization file and autoloadable classes
$dir = dirname( __FILE__ ) . '/';
$wgExtensionMessagesFiles['TwitterFeed'] = $dir . 'TwitterFeed.i18n.php';
$wgAutoloadClasses['Tweet'] = $dir . 'TwitterFeed.class.php';