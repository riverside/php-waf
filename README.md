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
        "riverside/php-waf" : "^2.0"
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
    $waf = new \Riverside\Waf\Firewall();
    $waf->run();
    ```

### Available filters
| Filter             | Description           |
| ------------------ | --------------------- |
| Sql                | SQL Injection         |
| Crlf               | CRLF Injection        |
| Xss                | Cross-site Scripting  |
| Xml                | XML Attacks           |

### Migration Guide to Version 2.0.0
##### What's changed
In version 2.0.0, I have made the following updates to improve consistency and adherence to PHP best practices:
1. Namespace renamed
    - Old namespace: `PhpWaf`
    - New namespace: `Riverside\Waf`
2. Class names renamed
    - Old names:
      - `src/Filter/CRLF.php` (Class `CRLF`)
      - `src/Filter/SQL.php` (Class `SQL`)
      - `src/Filter/XML.php` (Class `XML`)
      - `src/Filter/XSS.php` (Class `XSS`)
      - `src/BaseFilter.php` (Class `BaseFilter`)
    - New names:
      - `src/Filter/Crlf.php` (Class `Crlf`)
      - `src/Filter/Sql.php` (Class `Sql`)
      - `src/Filter/Xml.php` (Class `Xml`)
      - `src/Filter/Xss.php` (Class `Xss`)
      - `src/AbstractFilter.php` (Class `AbstractFilter`)

##### How to update your codebase
1. Update class imports:
    - Old way:
      - `use PhpWaf\Firewall;`
      - `use PhpWaf\Filter\CRLF;`
      - `use PhpWaf\Filter\SQL;`
      - `use PhpWaf\Filter\XML;`
      - `use PhpWaf\Filter\XSS;`
    - New way:
      - `use Riverside\Waf\Firewall;`
      - `use Riverside\Waf\Filter\Crlf;`
      - `use Riverside\Waf\Filter\Sql;`
      - `use Riverside\Waf\Filter\Xml;`
      - `use Riverside\Waf\Filter\Xss;`

[x1]: https://github.com/riverside/php-waf/actions/workflows/test.yml/badge.svg
[y1]: https://github.com/riverside/php-waf/actions/workflows/test.yml
[x2]: https://poser.pugx.org/riverside/php-waf/v/stable
[y2]: https://packagist.org/packages/riverside/php-waf
[x3]: https://poser.pugx.org/riverside/php-waf/license
[y3]: https://packagist.org/packages/riverside/php-waf