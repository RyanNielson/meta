#Meta

A package that makes it easy to add meta tags to your views.

This package will work in any PHP application, and Facades and Service Providers are provided to make it easy to integrate with Laravel 4.

## Installation

#### General

Run the following Composer command in your terminal, or simply add `'ryannielson/meta': '1.1.*'` to your composer.json file:

    composer require ryannielson/meta:'1.1.*'

Then update Composer from the terminal:

    composer update

#### Laravel Specific

This package also includes Laravel facades and service providers to make integration with Laravel easy.

Once complete, you now have to add the the service provider to the providers array in `app/config/app.php`: 

    'RyanNielson\Meta\MetaServiceProvider'
    
Finally, add the following entry entry to the aliases array in `app/config/app.php`:

    'Meta' => 'RyanNielson\Meta\Facades\Meta'


That's it!


## Usage

#### General

To set meta tag values, you will use the `set(array())` method on the Meta instance. Just pass this Meta object around to persist the set values. 

    $meta = new \RyanNielson\Meta\Meta;

    // Example #1 - Basic setting of values
    $meta->set(array('title' => 'Page Title', 'description' => 'Page Description', 'keywords' => array('great', 'site')));

    // Example #2 - Setting nested values. This will render tags with names like og:title and og:description
    $meta->set(array('title' => 'Page Title', 'og' => array('title' => 'OG Title', 'description' => 'OG Description')));


To display your meta tags using the set values, you will use the `display(array())` function on your Meta object.:

    $meta->display();

    // Displaying Example #1 from above
    <meta name="title" content="Page Title"/>
    <meta name="description" content="Page Description"/>
    <meta name="keywords" content="great, site"/>

    // Displaying Example #2 from above
    <meta name="title" content="Page Title"/>
    <meta name="og:title" content="OG Title"/>
    <meta name="og:description" content="OG Description"/>


#### Laravel Specific

To set meta tag values, you will use the `Meta::set(array())` function. Any set values will persist through the entire request of the application:

    // Example #1 - Basic setting of values
    Meta::set(array('title' => 'Page Title', 'description' => 'Page Description', 'keywords' => array('great', 'site')));

    // Example #2 - Setting nested values. This will render tags with names like og:title and og:description
    Meta::set(array('title' => 'Page Title', 'og' => array('title' => 'OG Title', 'description' => 'OG Description')));


To display your meta tags using the set values, you will use the `Meta::display(array())` function. This will normally be done in your layout or in other views:

    Meta::display();

    // Displaying Example #1 from above
    <meta name="title" content="Page Title"/>
    <meta name="description" content="Page Description"/>
    <meta name="keywords" content="great, site"/>

    // Displaying Example #2 from above
    <meta name="title" content="Page Title"/>
    <meta name="og:title" content="OG Title"/>
    <meta name="og:description" content="OG Description"/>


The display function also accepts an array of default values. These will be used when displaying your meta tags if a value is not set already using `set()`.
