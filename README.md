
<p align="center"><img src="https://www.lucasbustamante.com.br/wp-content/uploads/2018/10/mww-logo.svg"></p>

## About Modern WordPress Website

Modern WordPress Website (MWW) is a modern way of building WordPress websites.<br/>
Simple, unopiniated and powerful, it's a great skeleton to bootstrap a new WordPress project.

- Simple route engine with native WordPress functions.
- Fully featured MVC.
- Modern, yet simple PHP.
- PSR-4 Autoloading.
- Only 20 files and 15kb in size (Approximately).
- Installs as *mu-plugin*

Modern WordPress Website (MWW) is great for experienced PHP developers using WordPress, and for intermediate developers who want to take their skills to the next level.

## Building a Small Project

[![Click to watch on YouTube](https://img.youtube.com/vi/-_PmRfG83Oc/0.jpg)](https://www.youtube.com/watch?v=-_PmRfG83Oc)

[(Click to watch on YouTube)](https://www.youtube.com/watch?v=-_PmRfG83Oc)

## Building a Small Project (Português PT-BR)

[![Click to watch on YouTube](https://img.youtube.com/vi/NGQ2siW5DwI/0.jpg)](https://www.youtube.com/watch?v=NGQ2siW5DwI)

[(Click to watch on YouTube)](https://www.youtube.com/watch?v=NGQ2siW5DwI)

## Installation

Despite being a Theme in practical terms, *Modern WordPress Website* is installed as a mu-plugin. This way we intercept WordPress requests at an earlier stage and have more control over it.

To get started, simply follow these steps in a clean WordPress installation:

- Create wp-content/**mu-plugins** folder.
- Run `git clone https://github.com/Luc45/ModernWordPressWebsite .` inside **mu-plugins** folder.
- Run `composer update` inside the recently created **mu-plugins/mww/** folder to generate the autoload files.
- (Recommended) You will not need your theme anymore, you can create an empty theme with just index.php, style.css and functions.php. [Download empty theme](https://github.com/Luc45/EmptyTheme/archive/master.zip).

Now it's up to you to create awesome stuff!

## How it works

MWW is installed as a mu-plugin. With it, you don't need a theme. It is used to help you start a new WordPress project using a MVC structure, with all the potential of WordPress. Even though it's powerful, it's also very simple. The src folder, which contains the logic of MWW, contains only 8 files.

The heart of MWW is the Route class, it works like this:

```php
/**
 * Routes the request to the appropriate Controller
 */
public function routeRequest()
{
    add_filter('template_include', function () {
        ob_start();

        if (is_front_page()) {
            $page = new HomeController;
        } else {
            $page = new NotFoundController;
        }

        $page->output();

        echo ob_get_clean();
        return false;
    });
}
```

We perform checks using native WordPress functions such as is_front_page() to load the appropriate controller, then we call the method output() on it. Let's take a look at HomeController output method:

```php
// HomeController
public function output()
{
    $this->template->include('partials.header');
    $this->template->include('pages.home');
    $this->template->include('partials.footer');
}
```

We are loading a header, the home page content and the footer.

What if we want to show Posts on our Home page?

Well, we can do just this:
```php
// HomeController
public function output()
{
    $posts = get_posts();

    $this->template->include('partials.header');
    $this->template->include('pages.home', ['posts' => $posts);
    $this->template->include('partials.footer');
}
```
Then, we have a variable in our pages/home view with all posts. Let's use it:
```php
// views/pages/home.php
foreach ($posts as $post) {
    echo $post->post_title;
}
```

You see? We could easily separate the logic, we don't need to use get_posts() in our view, we can do it in the Controller (or better yet, create a Model for it) and pass it digested to the view. This way, it is easier for our application to grow organized.

## Contributing

To contribute to Modern WordPress Website, you can open an issue with your suggestion and if approved, do a pull-request. Please follow PSR-2 code-styling standards and remind about the unopiniated and simple philosophy of Modern WordPress Theme.

## License

The Modern WordPress Website is licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Hire me

![Hire Me!](https://www.lucasbustamante.com.br/wp-content/uploads/2018/10/lucas-small.jpg)<br/>
*My name is Lucas Bustamante, creator of Modern WordPress Website.<br/>
Hire me at lucasfbustamante@gmail.com*
