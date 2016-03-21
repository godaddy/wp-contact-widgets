<?php
use Codeception\Util\Debug;

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
		$I->amOnPage( wp_login_url() );

		// Populate the login form's user id field
		$I->fillField( [ 'id' => 'user_login' ], 'admin' );

		// Populate the login form's password field
		$I->fillField( [ 'id' => 'user_pass' ], 'password' );

		// Submit the login form
		$I->click( [ 'name' => 'wp-submit' ] );

		// Wait for page to load [Hack for Safari and IE]
		$I->waitForElementVisible( [ 'css' => 'body.index-php' ], 5 );

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

	    $I->amOnPage( admin_url( 'widgets.php' ) );

	    $I->canSee( 'Contact', [ 'css' => '.widget h3' ] );

	    $selector = '#widget-list div[id$=wpcw_contact-__i__]';

	    $I->click( [ 'css' => "$selector .widget-title" ] );

	    $I->waitForElementVisible( [ 'css' => "$selector .widgets-chooser-actions .button-primary" ] );

	    $I->click( [ 'css' => "$selector .widgets-chooser-actions .button-primary" ] );

	    $selector = '#sidebar-1 div[id*=wpcw_contact]';

	    $I->waitForElementVisible( [ 'css' => "{$selector} .widget-inside" ], 3 );

	    /**
	     * Fill all fields
	     */
	    $I->fillField( [ 'css' => "{$selector} form .title input" ], 'Acceptance tests contact' );
	    $I->fillField( [ 'css' => "{$selector} form .email input" ], 'info@local.dev' );
	    $I->fillField( [ 'css' => "{$selector} form .phone input" ], '555-555-5555' );
	    $I->fillField( [ 'css' => "{$selector} form .fax input" ], '555-555-5556' );
	    $I->fillField( [ 'css' => "{$selector} form .address textarea" ], '123 santa monica blvd<br> Los Angeles' );

	    /**
	     * Submit widget form
	     */
	    $I->click( [ 'css' => "{$selector} form input.button-primary" ] );

	    // Wait for all ajax request to finish
	    $I->waitForJS( 'return jQuery.active == 0;', 5 );

    }

	/**
	 * Validate presence & widget output on front-end
	 *
	 * @param \AcceptanceTester $I
	 */
	public function validateWidgetContactOutput( AcceptanceTester $I ) {

		$I->wantTo( 'Validate contact front-end output' );

		$I->amOnPage( home_url() );

		$I->canSeeElementInDOM( [ 'class' => 'wpcw-widget-contact' ] );

		// Let's validate what we submitted earlier
		$I->canSee( 'Acceptance tests contact', [ 'css' => '.wpcw-widget-contact .widget-title' ] );
		$I->canSee( 'info@local.dev', [ 'css' => '.wpcw-widget-contact ul li' ] );
		$I->canSee( '555-555-5555', [ 'css' => '.wpcw-widget-contact ul li' ] );
		$I->canSee( '555-555-5556', [ 'css' => '.wpcw-widget-contact ul li' ] );
		$I->canSee( '123 santa monica blvd', [ 'css' => '.wpcw-widget-contact ul li' ] );

		$I->canSeeElementInDOM( [ 'css' => '.wpcw-widget-contact ul li.has-map' ] );
		$I->canSeeElementInDOM( [ 'css' => '.wpcw-widget-contact ul li.has-map iframe[src="//www.google.com/maps?q=123+santa+monica+blvd+Los+Angeles&output=embed&hl=en"]' ] );

	}

	/**
	 * Validate that we can see and fill the social widget
	 *
	 * @before login
	 * @param \AcceptanceTester $I
	 */
	public function validateWidgetSocialForm( AcceptanceTester $I ) {

		$I->wantTo( 'Validate social widget form in widgets.php' );

		$I->amOnPage( admin_url( 'widgets.php' ) );

		$I->canSee( 'Social Profiles', [ 'css' => '.widget h3' ] );

		$selector = '#widget-list div[id$=wpcw_social-__i__]';

		$I->click( [ 'css' => "$selector .widget-title" ] );

		$I->waitForElementVisible( [ 'css' => "$selector .widgets-chooser-actions .button-primary" ] );

		$I->click( [ 'css' => "$selector .widgets-chooser-actions .button-primary" ] );

		$selector = '#sidebar-1 div[id*=wpcw_social]';

		$I->waitForElementVisible( [ 'css' => "{$selector} .widget-inside" ], 3 );

		/**
		 * Fill all fields
		 */
		$I->fillField( [ 'css' => "{$selector} form .title input" ], 'Acceptance tests social' );

		$this->selectSocialIcon( $I, 'facebook', $selector );
		$this->selectSocialIcon( $I, 'twitter', $selector );

		// Let's test reordering so facebook should be first
		$I->dragAndDrop( [ 'css' => "{$selector} form p.facebook .wpcw-widget-sortable-handle" ], [ 'css' => "{$selector} .wpcw-widget-social .icons" ] );

		/**
		 * Submit widget form
		 */
		$I->click( [ 'css' => "{$selector} form input.button-primary" ] );

		// Wait for all ajax request to finish
		$I->waitForJS( 'return jQuery.active == 0;', 5 );

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

		$I->amOnPage( home_url() );

		$I->canSeeElementInDOM( [ 'css' => '.wpcw-widget-social' ] );

		$I->canSee( 'Acceptance tests social', [ 'css' => '.wpcw-widget-social .widget-title' ] );

		// Check that facebook is indeed the first element return in the list
		$I->canSeeElementInDOM( [ 'css' => '.wpcw-widget-social ul li:first-child span[class*="facebook"]' ] );
		$I->canSeeElementInDOM( [ 'css' => '.wpcw-widget-social ul li:last-child span[class*="twitter"]' ] );

	}

}
