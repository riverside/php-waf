# php-waf
PHP Web Application Firewall

| Build | Stable | License |
| --- | --- | --- |
| [![CI][x1]][y1] | [![Latest Stable Version][x2]][y2] | [![License][x3]][y3] |

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
| Filter             | Description           |
| ------------------ | --------------------- |
| SQL                | SQL Injection         |
| CRLF               | CRLF Injection        |
| XSS                | Cross-site Scripting  |
| XML                | XML Attacks           |

[x1]: https://github.com/riverside/php-waf/actions/workflows/test.yml/badge.svg
[y1]: https://github.com/riverside/php-waf/actions/workflows/test.yml
[x2]: https://poser.pugx.org/riverside/php-waf/v/stable
[y2]: https://packagist.org/packages/riverside/php-waf
[x3]: https://poser.pugx.org/riverside/php-waf/license
[y3]: https://packagist.org/packages/riverside/php-waf