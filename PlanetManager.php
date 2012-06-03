<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * @file
 * @ingroup Extensions
 * @version 0.1
 * @author Lewis Cawte <lewis@lewiscawte.info>
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 * @link https://www.mediawiki.org/wiki/Extension:PlanetManager
 */

if { !defined( 'MEDIAWIKI' ) ) {
	die( "This is not a valid entry point.\n" );
}

$wgExtensionCredits['specialpage'][] = array(
	'path' => __FILE__,
	'name' => 'Planet Manager',
	'author' => 'Lewis Cawte',
	'version' => '0.1',
	'url' => 'https://www.mediawiki.org/wiki/Extension:PlanetManager',
	'descriptionmsg' => 'Planet blog aggretor manager',
);

$wgResourceModules['ext.planetmanager'] = array(
	'styles' => 'PlanetManager.css',
	'localBasePath' => dirname( __FILE__ ),
	'remoteExtPath' => 'PlanetManager',
	),
);

// Set up the special page
$dir = dirname( __FILE__ ) . '/';
$wgExtensionMessagesFiles['PlanetManager'] = $dir . 'PlanetManager.i18n.php';
$wgAutoloadClasses['SpecialPlanetManager'] = $dir . 'PlanetManager.body.php';
$wgSpecialPages['PlanetManager'] = 'SpecialPlanetManager';
