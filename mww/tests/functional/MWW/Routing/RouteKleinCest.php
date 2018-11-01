<?php

class RouteKleinCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * Test routing with Klein works
     *
     * @param FunctionalTester $I
     * @throws \Codeception\Exception\ModuleException
     */
    public function test_routing(FunctionalTester $I)
    {
        $add_filter = <<<PHP
add_filter('wp-routes/register_routes', function() {
    klein_with('', function() {
        klein_respond('GET', '/testing-routing-with-klein', function() {
            echo 'Klein is fully working!';
        });
    });
});
PHP;
        $I->haveMuPlugin('klein-routes.php', $add_filter);

        $I->amOnPage('/testing-routing-with-klein');

        $I->see('Klein is fully working!');
    }
}
