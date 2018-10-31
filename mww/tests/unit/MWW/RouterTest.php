<?php

use \Codeception\Stub\Expected as Expected;

use \AspectMock\Test as test;

use MWW\Router;

function conditionalRouteWithFunction() {
    echo 'Function being called.';
}

class RouterTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
        test::clean();
    }

    // tests
    public function testConditionalRouteWithFunction()
    {

        Router::singleton()->add('is_front_page', 'conditionalRouteWithFunction');
    }
}