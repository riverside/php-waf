<?php
declare(strict_types=1);

namespace Riverside\Waf\Tests\Filter;

use PHPUnit\Framework\TestCase;
use Riverside\Waf\Filter\Xss;

class XssTest extends TestCase
{
    public function testAttributes()
    {
        $attributes = array(
            'payloads_file',
        );

        foreach ($attributes as $attribute)
        {
            $this->assertClassHasAttribute($attribute, Xss::class);
        }
    }

    public function testIsSafe()
    {
        $xss = new Xss();
        $this->assertTrue($xss->safe('abc'));
    }

    public function testIsNotSafe()
    {
        $xss = new Xss();
        $this->assertFalse($xss->safe('<img src=1 href=1 onerror="javascript:alert(1)"></img>'));
    }
}