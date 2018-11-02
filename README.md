<p align="center"><img src="https://www.lucasbustamante.com.br/wp-content/uploads/2018/10/mww-logo.svg"></p>

## About Modern WordPress Website

Modern WordPress Website (MWW) is a modern way of building WordPress websites.<br/>
Simple and powerful, it's a great skeleton to bootstrap a new WordPress project.

- MVC in WordPress.
- Simple route engine with native WordPress functions.
- Modern, yet simple PHP.
- PSR-4 Autoloading.
- Integrated with Acceptance, Functional, Integration and Unit tests (Thanks to Luca Tume, from wp-browser)
- Installs as a *mu-plugin*

Modern WordPress Website (MWW) is great for experienced PHP developers using WordPress, and for intermediate developers who want to take their skills to the next level.

## Building a Small Project

[![Click to watch on YouTube](https://img.youtube.com/vi/w2uH_Ae-lhc/0.jpg)](https://www.youtube.com/watch?v=w2uH_Ae-lhc)

[(Click to watch on YouTube)](https://www.youtube.com/watch?v=w2uH_Ae-lhc)

## Installation

*Modern WordPress Website* is installed as a mu-plugin. This way we intercept WordPress requests at an earlier stage and have more control over the application.

To get started, simply follow these steps in a clean WordPress installation:

- Run `git clone https://github.com/Luc45/ModernWordPressWebsite wp-content/mu-plugins` in the root folder of a clean WordPress installation
- Run `composer update` in **wp-content/mu-plugins/mww/**
- (Recommended) You will not need your theme anymore, you can create an empty theme with just index.php, style.css and functions.php. [Download empty theme](https://github.com/Luc45/EmptyTheme/archive/master.zip).
- (Recommended) Set up tests by editing .env.example and renaming it to .env - Run tests with `vendor/bin/codecept run`

Now it's up to you to create awesome stuff!

## How it works

Even though MWW is powerful, it's also very simple. It all starts with the routes:

```php
// routes/conditional.php
$router->add('is_front_page', ['App\Pages\Home', 'index']);
```

If is_front_page() is true, then call method index on App\Pages\Home:

```php
// app/pages/Home.php
class Home extends Page
{
    public function index()
    {
        $this->template->include('header');
        $this->template->include('pages.home');
        $this->template->include('footer');
    }
}
```

Here, we are including the header, the home page content and the footer.

That's all we need to get started!

Of course that modern applications uses a lot of dynamic data, not only static views. Here's how we can show Posts on the Home page:

```php
// app/pages/Home.php
class Home extends Page
{
    public function index()
    {
        $this->template->include('header');
        $this->template->include('pages.home', [
            'posts' => get_posts()
        ]);
        $this->template->include('footer');
    }
}
```
Then, we have a variable `$posts` in our home view with the content of `get_posts()`:
```php
// views/pages/home.php
foreach ($posts as $post) {
    echo $post->post_title;
}
```

You see? This is MVC. We could easily separate the logic - we don't need to use get_posts() in our view, we can do it in the Controller (or create a model for it) and pass it digested to the view. This way, it is easier for our application to grow organized.

## Contributing

To contribute to Modern WordPress Website, you can open an issue with your suggestion and if approved, do a pull-request. Please follow PSR-2 code-styling standards and remind about the unopiniated and simple philosophy of Modern WordPress Theme.

## License

The Modern WordPress Website is licensed under the [MIT license](https://opensource.org/licenses/MIT).
