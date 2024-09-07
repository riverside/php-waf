<?php
declare(strict_types=1);

namespace Riverside\Waf\Tests\Filter;

use PHPUnit\Framework\TestCase;
use Riverside\Waf\Filter\Xml;

class XmlTest extends TestCase
{
    public function testAttributes()
    {
        $attributes = array(
            'payloads_file',
        );

        foreach ($attributes as $attribute)
        {
            $this->assertClassHasAttribute($attribute, Xml::class);
        }
    }
}