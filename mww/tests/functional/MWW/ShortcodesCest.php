<?php

class ShortcodesCest
{
    /** @heredoc shortcode */
    protected $shortcode;

    public function _before(FunctionalTester $I)
    {
        $this->shortcode = <<<PHP
<?php
namespace App\Shortcodes;
use MWW\Shortcodes\Shortcode;
class TestCanAddShortcode extends Shortcode {
    public \$shortcode = 'test_shortcode_124';
    public function register_shortcode(\$atts, \$content = null) {
        ob_start();
        \$name = empty(\$atts['name']) ? 'World' : \$atts['name'];
        echo 'Hi ' . \$name;
        return ob_get_clean();
    }
}
PHP;
    }

    public function _after(FunctionalTester $I)
    {
        $I->deleteMuPluginFile('mww/app/Shortcodes/TestCanAddShortcode.php');
    }

    // tests
    public function it_should_render_simple_shortcode(FunctionalTester $I)
    {
        $I->writeToMuPluginFile('mww/app/Shortcodes/TestCanAddShortcode.php', $this->shortcode);

        $add_route = <<<PHP
add_filter('wp-routes/register_routes', function() {
    klein_with('', function() {
        klein_respond('GET', '/test_it_should_render_simple_shortcode', function() {
            echo do_shortcode('[test_shortcode_124]');
        });
    });
});
PHP;
        $I->haveMuPlugin('a.php', $add_route);

        $I->amOnPage('/test_it_should_render_simple_shortcode');
        $I->see('Hi World');
    }

    // tests
    public function it_should_render_shortcode_atts(FunctionalTester $I)
    {
        $I->writeToMuPluginFile('mww/app/Shortcodes/TestCanAddShortcode.php', $this->shortcode);

        $add_route = <<<PHP
add_filter('wp-routes/register_routes', function() {
    klein_with('', function() {
        klein_respond('GET', '/test_it_should_render_shortcode_atts', function() {
            echo do_shortcode('[test_shortcode_124 name="Lucas"]');
        });
    });
});
PHP;
        $I->haveMuPlugin('a.php', $add_route);

        $I->amOnPage('/test_it_should_render_shortcode_atts');
        $I->see('Hi Lucas');
    }
}
