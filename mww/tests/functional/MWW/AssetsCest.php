<?php

class AssetsCest
{
    /** @var heredoc original assets file content */
    protected $original_assets;

    /**
     * Saves a copy of the original assets file
     *
     * @param FunctionalTester $I
     */
    public function _before(FunctionalTester $I)
    {
        if ($this->original_assets === null) {
            $this->original_assets = file_get_contents($I->getWpRootFolder() . '/wp-content/mu-plugins/mww/app/Support/assets.php');
        }
    }

    /**
     * Restores original assets file
     *
     * @param FunctionalTester $I
     */
    public function _after(FunctionalTester $I)
    {
        $I->writeToMuPluginFile('mww/app/Support/assets.php', $this->original_assets);
    }

    /**
     * Asserts that enqueueing a local style and a local javascript actually outputs them
     *
     * @param FunctionalTester $I
     * @throws \Codeception\Exception\ModuleException
     */
    public function it_should_enqueue_style_and_javascript(FunctionalTester $I)
    {
        $I->writeToMuPluginFile('mww/public/css/test_it_should_enqueue_style.css', '');
        $I->writeToMuPluginFile('mww/public/js/test_it_should_enqueue_js.js', '');

        $assets = <<<PHP
add_action('wp_enqueue_scripts', function() use (\$assets)
{
    \$assets->enqueueStyle('test_it_should_enqueue_style.css');
    \$assets->enqueueJavascript('test_it_should_enqueue_js.js');
});
PHP;

        $I->writeToMuPluginFile('mww/app/Support/assets.php', $assets);

        $add_route = <<<PHP
add_filter('wp-routes/register_routes', function() {
    klein_with('', function() {
        klein_respond('GET', '/it_should_enqueue_style_and_javascript', function() {
            wp_head();
        });
    });
});
PHP;
        $I->haveMuPlugin('a.php', $add_route);

        $I->amOnPage('/it_should_enqueue_style_and_javascript');

        //$I->see(\Codeception\Util\Locator::contains('link', 'test_it_should_enqueue_style.css'));
        //$I->see(\Codeception\Util\Locator::contains('script', 'test_it_should_enqueue_js.css'));

        $I->seeInSource('test_it_should_enqueue_style.css');
        $I->seeInSource('test_it_should_enqueue_js.js');

        $I->deleteMuPluginFile('mww/public/css/test_it_should_enqueue_style.css');
        $I->deleteMuPluginFile('mww/public/js/test_it_should_enqueue_js.js');
    }

    /**
     * Asserts that enqueuing a remote style and a remote javascript actually outputs them
     *
     * @param FunctionalTester $I
     * @throws \Codeception\Exception\ModuleException
     */
    public function it_should_enqueue_remote_style_and_javascript(FunctionalTester $I)
    {
        $assets = <<<PHP
add_action('wp_enqueue_scripts', function() use (\$assets)
{
    \$assets->enqueueRemoteStyle('foo', 'http://foo.com/style.css');
    \$assets->enqueueRemoteJavascript('bar', 'http://foo.com/script.js');
});
PHP;

        $I->writeToMuPluginFile('mww/app/Support/assets.php', $assets);

        $add_route = <<<PHP
add_filter('wp-routes/register_routes', function() {
    klein_with('', function() {
        klein_respond('GET', '/it_should_enqueue_style_and_javascript', function() {
            wp_head();
        });
    });
});
PHP;
        $I->haveMuPlugin('a.php', $add_route);

        $I->amOnPage('/it_should_enqueue_style_and_javascript');

        $I->seeInSource('http://foo.com/style.css');
        $I->seeInSource('http://foo.com/script.js');
    }
}
