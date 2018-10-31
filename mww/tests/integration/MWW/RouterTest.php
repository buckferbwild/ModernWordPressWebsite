<?php

use MWW\Router;
use Codeception\Stub;
use Codeception\Stub\Expected;

class RouterTest extends \Codeception\TestCase\WPTestCase
{

    public function setUp()
    {
        // before
        parent::setUp();

        // your set up methods here
    }

    public function tearDown()
    {
        // your tear down methods here

        // then
        parent::tearDown();
    }

    // tests
    public function test_closure_route()
    {
        $routes = Stub::make($class, [
            'add' => Expected::once(),
            'processConditionalByClosure' => Expected::once()
        ]);

        $routes->add('is_front_page', function() {
            echo 'Testing acceptance closure!';
        });
    }

}