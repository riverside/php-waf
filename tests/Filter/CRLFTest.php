<?php
declare(strict_types=1);

namespace Riverside\Waf\Tests\Filter;

use PHPUnit\Framework\TestCase;
use Riverside\Waf\Filter\Crlf;

class CrlfTest extends TestCase
{
    public function testAttributes()
    {
        $attributes = array(
            'payloads_file',
        );

        foreach ($attributes as $attribute)
        {
            $this->assertClassHasAttribute($attribute, Crlf::class);
        }
    }

    public function testIsSafe()
    {
        $crlf = new Crlf();
        $this->assertTrue($crlf->safe('abc'));
    }

    public function testIsNotSafe()
    {
        $crlf = new Crlf();
        $this->assertFalse($crlf->safe('%0D%0ASet-Cookie:mycookie=myvalue'));
    }
}