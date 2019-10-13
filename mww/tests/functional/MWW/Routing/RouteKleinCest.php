<?php

class RouteKleinCest {
	/**
	 * Test routing with Klein works
	 *
	 * @param FunctionalTester $I
	 *
	 * @throws \Codeception\Exception\ModuleException
	 */
	public function it_should_route_a_request_with_klein( FunctionalTester $I ) {
		$add_filter = <<<PHP
add_filter('wp-routes/register_routes', function() {
    klein_respond('GET', '/testing-routing-with-klein', function() {
        echo 'Klein is fully working!';
    });
});
PHP;
		$I->haveMuPlugin( 'a.php', $add_filter );

		$I->amOnPage( '/testing-routing-with-klein' );
		$I->see( 'Klein is fully working!' );
	}
}
