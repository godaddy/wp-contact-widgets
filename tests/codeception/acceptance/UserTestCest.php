<?php
/**
 * AdminTestCest class for Codeception.
 *
 * @package ContactWidgets
 */

/**
 * Contact Widgets WPCW_Admin_Test_Cest class.
 *
 * @package ContactWidgets
 */
class WPCW_AdminTestCest {

	/**
	 * Helping function to login without checking cookie since not compatible
	 * with browserstack ie8-9
	 *
	 * @param AcceptanceTester $i Represents a single testing instance.
	 */
	protected function login( AcceptanceTester $i ) {

		// Check if cookie first.
		static $cookie = null;

		if ( ! is_null( $cookie ) ) {

			$i->setCookie( AUTH_COOKIE, $cookie );

			return;

		}

		$i->wantTo( 'Log into WordPress admin' );

		// Let's start on the login page.
		$i->amOnUrl( wp_login_url() );

		$i->wait( 3 );

		// Populate the login form's user id field.
		$i->fillField( array( 'id' => 'user_login' ), 'admin' );

		// Populate the login form's password field.
		$i->fillField( array( 'id' => 'user_pass' ), 'password' );

		// Submit the login form.
		$i->click( array( 'name' => 'wp-submit' ) );

		// Wait for page to load [Hack for Safari and IE].
		$i->waitForElementVisible( array( 'css' => 'body.index-php' ) );

		$cookie = $i->grabCookie( AUTH_COOKIE );

	}

	/**
	 * Validate that we can see and fill the contact widget.
	 *
	 * @before login
	 * @param \AcceptanceTester $i Represents a single testing instance.
	 */
	public function validate_widget_contact_form( AcceptanceTester $i ) {

		$i->wantTo( 'Validate contact widget form in widgets.php' );

		$i->amOnUrl( admin_url( 'widgets.php' ) );

		$i->see( 'Contact Details', array( 'css' => '.widget h3' ) );

		$selector = '#widget-list div[id$=wpcw_contact-__i__]';

		$i->click( array( 'css' => "$selector .widget-title" ) );

		$i->waitForElementVisible( array( 'css' => "$selector .widgets-chooser-actions .button-primary" ) );

		$i->click( array( 'css' => "$selector .widgets-chooser-actions .button-primary" ) );

		$selector = '#sidebar-1 div[id*=wpcw_contact]';

		$i->waitForElementVisible( array( 'css' => "{$selector} .widget-inside" ) );

		/**
		 * Fill all fields.
		 */
		$i->fillField( array( 'css' => "{$selector} form .title input" ), 'Acceptance tests contact' );
		$i->fillField( array( 'css' => "{$selector} form .email input" ), 'info@local.dev' );
		$i->fillField( array( 'css' => "{$selector} form .phone input" ), '555-555-5555' );
		$i->fillField( array( 'css' => "{$selector} form .fax input" ), '555-555-5556' );
		$i->fillField( array( 'css' => "{$selector} form .address textarea" ), '1234 Santa Monica Blvd<br>Beverly Hills, CA 90210' );

		/**
		 * Submit widget form.
		 */
		$i->click( array( 'css' => "{$selector} form input.button-primary" ) );

		// Wait for all ajax request to finish.
		$i->waitForJS( 'return jQuery.active == 0;' );

	}

	/**
	 * Validate presence & widget output on front-end.
	 *
	 * @param \AcceptanceTester $i Represents a single testing instance.
	 */
	public function validate_widget_contact_output( AcceptanceTester $i ) {

		$i->wantTo( 'Validate contact front-end output' );

		$i->amOnUrl( home_url() );

		$i->waitForElementVisible( array( 'class' => 'wpcw-widget-contact' ) );

		// Let's validate what we submitted earlier.
		$i->see( 'Acceptance tests contact', array( 'css' => '.wpcw-widget-contact .widget-title' ) );
		$i->see( 'info@local.dev', array( 'css' => '.wpcw-widget-contact ul li' ) );
		$i->see( '555-555-5555', array( 'css' => '.wpcw-widget-contact ul li' ) );
		$i->see( '555-555-5556', array( 'css' => '.wpcw-widget-contact ul li' ) );
		$i->see( '1234 Santa Monica Blvd', array( 'css' => '.wpcw-widget-contact ul li' ) );

		$i->waitForElementVisible( array( 'css' => '.wpcw-widget-contact ul li.has-map' ) );
		$i->waitForElementVisible( array( 'css' => '.wpcw-widget-contact ul li.has-map iframe[src="https://www.google.com/maps?q=1234%20Santa%20Monica%20BlvdBeverly%20Hills%2C%20CA%2090210&output=embed&hl=en&z=14"]' ) );

	}

	/**
	 * Validate that we can see and fill the social widget.
	 *
	 * @before login
	 * @param \AcceptanceTester $i Represents a single testing instance.
	 */
	public function validate_widget_social_form( AcceptanceTester $i ) {

		$i->wantTo( 'Validate social widget form in widgets.php' );

		$i->amOnUrl( admin_url( 'widgets.php' ) );

		$i->see( 'Social Profiles', array( 'css' => '.widget h3' ) );

		$selector = '#widget-list div[id$=wpcw_social-__i__]';

		$i->click( array( 'css' => "$selector .widget-title" ) );

		$i->waitForElementVisible( array( 'css' => "$selector .widgets-chooser-actions .button-primary" ) );

		$i->click( array( 'css' => "$selector .widgets-chooser-actions .button-primary" ) );

		$selector = '#sidebar-1 div[id*=wpcw_social]';

		$i->waitForElementVisible( array( 'css' => "{$selector} .widget-inside" ) );

		/**
		 * Fill all fields.
		 */
		$i->fillField( array( 'css' => "{$selector} form .title input" ), 'Acceptance tests social' );

		$this->selectSocialIcon( $i, 'facebook', $selector );

		$i->wait( 1 );

		$this->selectSocialIcon( $i, 'twitter', $selector );

		$i->wait( 1 );

		// Let's test reordering so facebook should be first.
		$i->dragAndDrop( array( 'css' => "{$selector} form p.facebook .wpcw-widget-sortable-handle" ), array( 'css' => "{$selector} .wpcw-widget-social .icons" ) );

		$i->wait( 1 );

		/**
		 * Submit widget form.
		 */
		$i->click( array( 'css' => "{$selector} form input.button-primary" ) );

		// Wait for all ajax request to finish.
		$i->waitForJS( 'return jQuery.active == 0;' );

	}

	/**
	 * Select a social icon.
	 *
	 * @param object $i Represents a single testing instance.
	 * @param string $name String representing a form element.
	 * @param string $selector String representing a social icon.
	 */
	protected function select_social_icon( $i, $name, $selector ) {

		$i->click( array( 'css' => "{$selector} form .icons a[data-key={$name}]" ) );

		$i->waitForElementVisible( array( 'css' => "{$selector} form p.{$name} input" ) );

		$i->pressKey( array( 'css' => "{$selector} form p.{$name} input" ), 'test' );

	}

	/**
	 * Validate social media output.
	 *
	 * @param \AcceptanceTester $i Represents a single testing instance.
	 */
	public function validate_widget_social_output( AcceptanceTester $i ) {

		$i->wantTo( 'Validate social front-end output' );

		$i->amOnUrl( home_url() );

		$i->waitForElementVisible( array( 'css' => '.wpcw-widget-social' ) );

		$i->see( 'Acceptance tests social', array( 'css' => '.wpcw-widget-social .widget-title' ) );

		$i->executeJS( 'jQuery(".wpcw-widget-social ul li:first-child")[0].scrollIntoView();' );

		// Facebook should be first after reordering.
		$i->seeElementInDOM( array( 'css' => '.wpcw-widget-social ul li:first-child span[class*="facebook"]' ) );
		$i->seeElementInDOM( array( 'css' => '.wpcw-widget-social ul li:last-child span[class*="twitter"]' ) );

		$i->moveMouseOver( array( 'css' => '#wp-admin-bar-my-account' ) );

	}

	/**
	 * Validate that the edit link redirects to the customizer correctly.
	 *
	 * @param AcceptanceTester $i Represents a single testing instance.
	 */
	public function validate_edit_link( AcceptanceTester $i ) {

		$i->wantTo( 'Validate the edit link of our widget' );

		$i->amOnUrl( home_url() );

		$i->waitForElementVisible( array( 'css' => '.wpcw-widgets .post-edit-link' ) );

		$i->executeJS( 'jQuery(".wpcw-widgets .post-edit-link")[0].scrollIntoView();' );

		$i->click( array( 'css' => '.wpcw-widget-social .post-edit-link' ) );

		$i->seeInCurrentUrl( '/wp-admin/customize.php' );

		$i->wait( 3 ); // The animation takes a little bit of time.

		$i->seeElement( array( 'class' => 'wpcw-widget-social' ) );

	}

}
