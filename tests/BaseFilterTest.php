<?php
declare(strict_types=1);

namespace PhpWaf\Tests;

use PHPUnit\Framework\TestCase;
use PhpWaf\BaseFilter;
use PhpWaf\Firewall;

class BaseFilterTest extends TestCase
{
    public function testAttributes()
    {
        $attributes = array(
            'payloads',
            'payloads_file',
        );

        foreach ($attributes as $attribute)
        {
            $this->assertClassHasAttribute($attribute, BaseFilter::class);
        }
    }

    /**
     * @throws \PhpWaf\Exception
     */
    public function testFilterAttributes()
    {
        $firewall = new Firewall();

        foreach (array_keys($firewall->getFilters()) as $filter)
        {
            $this->assertClassHasAttribute('payloads_file', "PhpWaf\\Filter\\$filter");
        }
    }

    /**
     * @throws \PhpWaf\Exception
     */
    public function testFilterMethods()
    {
        $firewall = new Firewall();

        foreach ($firewall->getFilters() as $filter => $enabled)
        {
            if (!$enabled)
            {
                continue;
            }
            $className = "PhpWaf\\Filter\\$filter";
            $object = new $className;
            $this->assertTrue(method_exists($object, "safe"));
        }
    }
}