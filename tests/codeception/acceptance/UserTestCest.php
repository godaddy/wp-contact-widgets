<?php

class AdminTestCest {

	/**
	 * Helping function to login without checking cookie since not compatible
	 * with browserstack ie8-9
	 *
	 * @param AcceptanceTester $I
	 */
	protected function login( AcceptanceTester $I ) {

		//Check if cookie first
		static $cookie = null;

		if ( ! is_null( $cookie ) ) {

			$I->setCookie( AUTH_COOKIE, $cookie );

			return;

		}

		$I->wantTo( 'Log into WordPress admin' );

		// Let's start on the login page
		$I->amOnUrl( wp_login_url() );

		$I->wait( 3 );

		// Populate the login form's user id field
		$I->fillField( [ 'id' => 'user_login' ], 'admin' );

		// Populate the login form's password field
		$I->fillField( [ 'id' => 'user_pass' ], 'password' );

		// Submit the login form
		$I->click( [ 'name' => 'wp-submit' ] );

		// Wait for page to load [Hack for Safari and IE]
		$I->waitForElementVisible( [ 'css' => 'body.index-php' ] );

		$cookie = $I->grabCookie( AUTH_COOKIE );

	}

	/**
	 * Validate that we can see and fill the contact widget
	 *
	 * @before login
	 * @param \AcceptanceTester $I
	 */
	public function validateWidgetContactForm( AcceptanceTester $I ) {

		$I->wantTo( 'Validate contact widget form in widgets.php' );

		$I->amOnUrl( admin_url( 'widgets.php' ) );

		$I->see( 'Contact Details', [ 'css' => '.widget h3' ] );

		$selector = '#widget-list div[id$=wpcw_contact-__i__]';

		$I->click( [ 'css' => "$selector .widget-title" ] );

		$I->waitForElementVisible( [ 'css' => "$selector .widgets-chooser-actions .button-primary" ] );

		$I->click( [ 'css' => "$selector .widgets-chooser-actions .button-primary" ] );

		$selector = '#sidebar-1 div[id*=wpcw_contact]';

		$I->waitForElementVisible( [ 'css' => "{$selector} .widget-inside" ] );

		/**
		 * Fill all fields
		 */
		$I->fillField( [ 'css' => "{$selector} form .title input" ], 'Acceptance tests contact' );
		$I->fillField( [ 'css' => "{$selector} form .email input" ], 'info@local.dev' );
		$I->fillField( [ 'css' => "{$selector} form .phone input" ], '555-555-5555' );
		$I->fillField( [ 'css' => "{$selector} form .fax input" ], '555-555-5556' );
		$I->fillField( [ 'css' => "{$selector} form .address textarea" ], '1234 Santa Monica Blvd<br>Beverly Hills, CA 90210' );

		/**
		 * Submit widget form
		 */
		$I->click( [ 'css' => "{$selector} form input.button-primary" ] );

		// Wait for all ajax request to finish
		$I->waitForJS( 'return jQuery.active == 0;' );

    }

	/**
	 * Validate presence & widget output on front-end
	 *
	 * @param \AcceptanceTester $I
	 */
	public function validateWidgetContactOutput( AcceptanceTester $I ) {

		$I->wantTo( 'Validate contact front-end output' );

		$I->amOnUrl( home_url() );

		$I->waitForElementVisible( [ 'class' => 'wpcw-widget-contact' ] );

		// Let's validate what we submitted earlier
		$I->see( 'Acceptance tests contact', [ 'css' => '.wpcw-widget-contact .widget-title' ] );
		$I->see( 'info@local.dev', [ 'css' => '.wpcw-widget-contact ul li' ] );
		$I->see( '555-555-5555', [ 'css' => '.wpcw-widget-contact ul li' ] );
		$I->see( '555-555-5556', [ 'css' => '.wpcw-widget-contact ul li' ] );
		$I->see( '1234 Santa Monica Blvd', [ 'css' => '.wpcw-widget-contact ul li' ] );

		$I->waitForElementVisible( [ 'css' => '.wpcw-widget-contact ul li.has-map' ] );
		$I->waitForElementVisible( [ 'css' => '.wpcw-widget-contact ul li.has-map iframe[src="https://www.google.com/maps?q=1234%20Santa%20Monica%20BlvdBeverly%20Hills%2C%20CA%2090210&output=embed&hl=en&z=14"]' ] );

	}

	/**
	 * Validate that we can see and fill the social widget
	 *
	 * @before login
	 * @param \AcceptanceTester $I
	 */
	public function validateWidgetSocialForm( AcceptanceTester $I ) {

		$I->wantTo( 'Validate social widget form in widgets.php' );

		$I->amOnUrl( admin_url( 'widgets.php' ) );

		$I->see( 'Social Profiles', [ 'css' => '.widget h3' ] );

		$selector = '#widget-list div[id$=wpcw_social-__i__]';

		$I->click( [ 'css' => "$selector .widget-title" ] );

		$I->waitForElementVisible( [ 'css' => "$selector .widgets-chooser-actions .button-primary" ] );

		$I->click( [ 'css' => "$selector .widgets-chooser-actions .button-primary" ] );

		$selector = '#sidebar-1 div[id*=wpcw_social]';

		$I->waitForElementVisible( [ 'css' => "{$selector} .widget-inside" ] );

		/**
		 * Fill all fields
		 */
		$I->fillField( [ 'css' => "{$selector} form .title input" ], 'Acceptance tests social' );

		$this->selectSocialIcon( $I, 'facebook', $selector );

		$I->wait( 1 );

		$this->selectSocialIcon( $I, 'twitter', $selector );

		$I->wait( 1 );

		// Let's test reordering so facebook should be first
		$I->dragAndDrop( [ 'css' => "{$selector} form p.facebook .wpcw-widget-sortable-handle" ], [ 'css' => "{$selector} .wpcw-widget-social .icons" ] );

		$I->wait( 1 );

		/**
		 * Submit widget form
		 */
		$I->click( [ 'css' => "{$selector} form input.button-primary" ] );

		// Wait for all ajax request to finish
		$I->waitForJS( 'return jQuery.active == 0;' );

	}

	/**
	 * Select a social icon
	 *
	 * @param $I
	 * @param $name
	 * @param $selector
	 */
	protected function selectSocialIcon( $I, $name, $selector ) {

		$I->click( [ 'css' => "{$selector} form .icons a[data-key={$name}]" ] );

		$I->waitForElementVisible( [ 'css' => "{$selector} form p.{$name} input" ] );

		$I->pressKey( [ 'css' => "{$selector} form p.{$name} input" ], 'test' );

	}

	/**
	 * Validate social media output
	 *
	 * @param \AcceptanceTester $I
	 */
	public function validateWidgetSocialOutput( AcceptanceTester $I ) {

		$I->wantTo( 'Validate social front-end output' );

		$I->amOnUrl( home_url() );

		$I->waitForElementVisible( [ 'css' => '.wpcw-widget-social' ] );

		$I->see( 'Acceptance tests social', [ 'css' => '.wpcw-widget-social .widget-title' ] );

		$I->executeJS('jQuery(".wpcw-widget-social ul li:first-child")[0].scrollIntoView();');

		// Facebook should be first after reordering
		$I->seeElementInDOM( [ 'css' => '.wpcw-widget-social ul li:first-child span[class*="facebook"]' ] );
		$I->seeElementInDOM( [ 'css' => '.wpcw-widget-social ul li:last-child span[class*="twitter"]' ] );

		$I->moveMouseOver( [ 'css' => '#wp-admin-bar-my-account' ] );

	}

	/**
	 * Validate that the edit link redirects to the customizer correctly
	 *
	 * @param AcceptanceTester $I
	 */
	public function validateEditLink( AcceptanceTester $I ) {

		$I->wantTo( 'Validate the edit link of our widget' );

		$I->amOnUrl( home_url() );

		$I->waitForElementVisible( [ 'css' => '.wpcw-widgets .post-edit-link' ] );

		$I->executeJS( 'jQuery(".wpcw-widgets .post-edit-link")[0].scrollIntoView();' );

		$I->click( [ 'css' => '.wpcw-widget-social .post-edit-link' ] );

		$I->seeInCurrentUrl( '/wp-admin/customize.php' );

		$I->wait( 3 ); // The animation takes a little bit of time

		$I->seeElement( [ 'class' => 'wpcw-widget-social' ] );

	}

}
