<?php

class SpecialPlanetManager extends SpecialPage {
	function __construct() {
		parent::__construct( 'PlanetManager' );
	}

	function execute( $par ) {
		global $wgRequest, $wgOut;

		$this->setHeaders();

                # Get request data from, e.g.
                $param = $wgRequest->getText('param');
 
                # Do stuff
                $ini_array = parse_ini_file( $mailmanIniFile, true);
                $wgOut->addWikiText( $ini_array );
        }
}
