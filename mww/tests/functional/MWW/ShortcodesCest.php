<?php

class ShortcodesCest {
	/**
	 * Creates test shortcode file
	 *
	 * @param FunctionalTester $I
	 */
	public function _before( FunctionalTester $I ) {
		$shortcode = <<<PHP
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
		$I->writeToMuPluginFile( 'mww/app/Shortcodes/TestCanAddShortcode.php', $shortcode );
	}

	/**
	 * Delete test shortcode file
	 *
	 * @param FunctionalTester $I
	 */
	public function _after( FunctionalTester $I ) {
		$I->deleteMuPluginFile( 'mww/app/Shortcodes/TestCanAddShortcode.php' );
	}

	/**
	 * Asserts that calling a generated shortcode outputs it's contents
	 *
	 * @param FunctionalTester $I
	 *
	 * @throws \Codeception\Exception\ModuleException
	 */
	public function it_should_render_simple_shortcode( FunctionalTester $I ) {
		$add_route = <<<PHP
add_filter('wp-routes/register_routes', function() {
    klein_respond('GET', '/test_it_should_render_simple_shortcode', function() {
        echo do_shortcode('[test_shortcode_124]');
    });
});
PHP;
		$I->haveMuPlugin( 'a.php', $add_route );

		$I->amOnPage( '/test_it_should_render_simple_shortcode' );
		$I->see( 'Hi World' );
	}

	/**
	 * Asserts that calling a generated output with parameters outputs it's contents
	 *
	 * @param FunctionalTester $I
	 *
	 * @throws \Codeception\Exception\ModuleException
	 */
	public function it_should_render_shortcode_atts( FunctionalTester $I ) {
		$add_route = <<<PHP
add_filter('wp-routes/register_routes', function() {
    klein_respond('GET', '/test_it_should_render_shortcode_atts', function() {
        echo do_shortcode('[test_shortcode_124 name="Lucas"]');
    });
});
PHP;
		$I->haveMuPlugin( 'a.php', $add_route );

		$I->amOnPage( '/test_it_should_render_shortcode_atts' );
		$I->see( 'Hi Lucas' );
	}
}
