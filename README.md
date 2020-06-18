# Blueberry Distribution Drupal 8

## What is Blueberry Dristrib D8
The Blueberry Distrib D8 provides a pre-installed website for Drupal 8 with the basic modules and an administration theme configured.

## Requirements

- Composer


## Installation

Get the code source of the profile Blueberry, you can run the command below (replace PROJECT_CODE by the name of your project) :
```
composer create-project margauxdupuy/blueberry-project --stability=dev PROJECT_CODE
```

Command line is not yet ready for the package selection.

Finally, complete the installation of the profile, assuming you can access a database on localhost with root:root.

Don't forget to export the configuration with :
```
drush cex -y
```


## Theme installation



## Update

- To update Drupal Core and its dependencies, run :
```
composer update drupal/core webflo/drupal-core-require-dev "symfony/*" --with-dependencies
```

- To update the profile, run : 
```
composer update druids/blueberry --with-dependencies
```

## Enable developer mode / disable cache

Source : https://www.liip.ch/en/blog/lets-debug-drupal-8

Uncomment these lines in `settings.php` :

```php
if (file_exists($app_root . '/' . $site_path . '/settings.local.php')) {
  include $app_root . '/' . $site_path . '/settings.local.php';
}
```

Create the file `settings.local.php` in web/sites/default` (you can duplicate example.settings.local.php`).

Uncomment/update some values in settings.local.php` :

- uncomment this line to enable the “null cache service”:
```php
$settings['container_yamls'][] = DRUPAL_ROOT . '/sites/development.services.yml';
```

- uncomment these lines to disable CSS/JS aggregation:
```php
$config['system.performance']['css']['preprocess'] = FALSE;
$config['system.performance']['js']['preprocess'] = FALSE;
```

- uncomment these lines to disable the render cache and the dynamic page cache:
```php
$settings['cache']['bins']['render'] = 'cache.backend.null';
$settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.null';
```

- you can allow test modules and themes to be installed if needed with:
```php
$settings['extension_discovery_scan_tests'] = TRUE;
```


Disable Twig caching in `development.services.yml` and add the following settings :

```php
parameters:
    twig.config:
        debug: true
        auto_reload: true
        cache: false
```

