# php-waf
[![CI](https://github.com/riverside/php-waf/actions/workflows/test.yml/badge.svg)](https://github.com/riverside/php-waf/actions/workflows/test.yml)
[![License](https://poser.pugx.org/riverside/php-waf/license)](https://packagist.org/packages/riverside/php-waf)

PHP Web Application Firewall

### Requirements
- PHP >= 7.0

### Installation
If Composer is not installed on your system yet, you may go ahead and install it using this command line:
```
$ curl -sS https://getcomposer.org/installer | php
```
Next, add the following require entry to the <code>composer.json</code> file in the root of your project.
```json
{
    "require" : {
        "riverside/php-waf" : "*"
    }
}
```
Finally, use Composer to install php-waf and its dependencies:
```
$ php composer.phar install 
```
### How to use
1. Configure your web server
    - Apache
    ```apacheconfig
    php_value auto_prepend_file "/path/to/waf.php"
    ```
    - Nginx
    ```
    fastcgi_param PHP_VALUE "auto_prepend_file=/path/to/waf.php";
    ```
2. Create an Firewall instance 
    - waf.php
    ```php
    <?php
    $waf = new \PhpWaf\Firewall();
    $waf->run();
    ```

### Available filters
| Filter             | Description          |
| ------------------ | -------------------- |
| SQL                | Prevent SQL Injections |
| XSS                | XSS Attacks          |
| XML                | Stops XML Attacks          |
