<?php

class RouterCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    public function test_closure_route(AcceptanceTester $I)
    {
        $routes = new MWW\Router;
        $routes->add('is_front_page', function() {
            echo 'Testing acceptance closure!';
        });

        $I->amOnPage('/');
        $I->see('Testing acceptance closure!');
    }
}
