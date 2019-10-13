<?php

namespace MWW\Templating;

use Illuminate\View\Factory;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Illuminate\View\FileViewFinder;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Compilers\BladeCompiler;

class ViewFactory
{
    public static function create()
    {
        // Storage paths for Blade files and raw PHP compiled templates
        $pathsToTemplates        = [MWW_PATH . '/views'];
        $pathToCompiledTemplates = MWW_PATH . '/views/compiled';

        // Dependencies
        $filesystem = new Filesystem;

        $eventDispatcher = new Dispatcher(new Container);

        // Create View Factory capable of rendering PHP and Blade templates
        $viewResolver = new EngineResolver;

        $bladeCompiler = new BladeCompiler($filesystem, $pathToCompiledTemplates);

        $viewResolver->register('blade', function () use ($bladeCompiler) {
            return new CompilerEngine($bladeCompiler);
        });

        $viewResolver->register('php', function () {
            return new PhpEngine;
        });

        // Register custom Blade Directives
        self::extendDirectives($bladeCompiler);

        $viewFinder = new FileViewFinder($filesystem, $pathsToTemplates);

        return new Factory($viewResolver, $viewFinder, $eventDispatcher);
    }

    /**
     * Extend Blade Directives
     *
     * @param BladeCompiler
     */
    private static function extendDirectives(BladeCompiler $compiler)
    {
        $compiler->directive('loop', function () {
            return '<?php if (have_posts()) { while (have_posts()) { the_post(); ?>';
        });

        $compiler->directive('endloop', function () {
            return '<?php }} ?>';
        });

        $compiler->directive('query', function ($expression) {
            return '<?php $_query = (is_array(' . $expression . ')) ? new \WP_Query(' . $expression . ') : ' . $expression . '; if ($_query->have_posts()) { while ($_query->have_posts()) { $_query->the_post(); ?>';
        });

        $compiler->directive('endquery', function () {
            return '<?php }} wp_reset_postdata(); ?>';
        });
    }
}
