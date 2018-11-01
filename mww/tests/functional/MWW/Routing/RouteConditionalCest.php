<?php

class RouteConditionalCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function _after(FunctionalTester $I)
    {
    }

    // tests
    public function test_routing_by_array(FunctionalTester $I)
    {
        $code = <<<PHP
class Foo {
    public function bar()
    {
        echo 'Is bar!';
    }
}
add_filter('mww_conditional_routes', function(\$routes) {
    \$routes['is_front_page'] = ['Foo', 'Bar'];
    return \$routes;
});
PHP;
        $I->haveMuPlugin('my-routes.php', $code);
        $I->amOnPage('/');

        $I->see('Is bar!');
    }
}
