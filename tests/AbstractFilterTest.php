<?php
declare(strict_types=1);

namespace Riverside\Waf\Tests;

use PHPUnit\Framework\TestCase;
use Riverside\Waf\AbstractFilter;
use Riverside\Waf\Firewall;

class AbstractFilterTest extends TestCase
{
    public function testAttributes()
    {
        $attributes = array(
            'payloads',
            'payloads_file',
        );

        foreach ($attributes as $attribute)
        {
            $this->assertClassHasAttribute($attribute, AbstractFilter::class);
        }
    }

    /**
     * @throws \Riverside\Waf\Exception
     */
    public function testFilterAttributes()
    {
        $firewall = new Firewall();

        foreach (array_keys($firewall->getFilters()) as $filter)
        {
            $this->assertClassHasAttribute('payloads_file', "Riverside\\Waf\\Filter\\$filter");
        }
    }

    /**
     * @throws \Riverside\Waf\Exception
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
            $className = "Riverside\\Waf\\Filter\\$filter";
            $object = new $className;
            $this->assertTrue(method_exists($object, "safe"));
        }
    }
}