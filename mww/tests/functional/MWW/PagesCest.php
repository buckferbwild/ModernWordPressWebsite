<?php

class PagesCest
{

    public function _before(FunctionalTester $I)
    {
    }

    public function _after(FunctionalTester $I)
    {

    }

    // tests
    public function it_should_render_page(FunctionalTester $I)
    {
        $page = <<<PHP
<?php
namespace App\Pages;
use MWW\Pages\Page;
class FunctionalTestPage extends Page {
    public function index() {
        echo 'I am a functional testing page!';
    }
}
PHP;

        $I->writeToMuPluginFile('mww/app/Pages/FunctionalTestPage.php', $page);

        $add_route = <<<PHP
add_filter('wp-routes/register_routes', function() {
    klein_with('', function() {
        klein_respond('GET', '/test_it_should_render_page', function() {
            \$page = new App\Pages\FunctionalTestPage;
            \$page->index();
        });
    });
});
PHP;
        $I->haveMuPlugin('a.php', $add_route);

        $I->amOnPage('/test_it_should_render_page');
        $I->see('I am a functional testing page!');
        $I->deleteMuPluginFile('mww/app/Pages/FunctionalTestPage.php');
    }

    // tests
    public function it_should_render_page_with_template(FunctionalTester $I)
    {
        $template = <<<EOF
I am a page with a template!
EOF;

        $I->writeToMuPluginFile('mww/views/functional-testing-page.php', $template);

        $page = <<<PHP
<?php
namespace App\Pages;
use MWW\Pages\Page;
class FunctionalTestPageWithTemplate extends Page {
    public function index() {
        \$this->template->include('functional-testing-page');
    }
}
PHP;

        $I->writeToMuPluginFile('mww/app/Pages/FunctionalTestPageWithTemplate.php', $page);

        $add_route = <<<PHP
add_filter('wp-routes/register_routes', function() {
    klein_with('', function() {
        klein_respond('GET', '/it_should_render_page_with_template', function() {
            \$page = new App\Pages\FunctionalTestPageWithTemplate;
            \$page->index();
        });
    });
});
PHP;
        $I->haveMuPlugin('a.php', $add_route);

        $I->amOnPage('/it_should_render_page_with_template');
        $I->see('I am a page with a template!');
        $I->deleteMuPluginFile('mww/app/Pages/FunctionalTestPageWithTemplate.php');
    }
}
