<?php
declare(strict_types=1);

namespace PhpWaf\Tests\Filter;

use PHPUnit\Framework\TestCase;
use PhpWaf\Filter\CRLF;

class CRLFTest extends TestCase
{
    public function testAttributes()
    {
        $attributes = array(
            'payloads_file',
        );

        foreach ($attributes as $attribute)
        {
            $this->assertClassHasAttribute($attribute, CRLF::class);
        }
    }

    public function testIsSafe()
    {
        $crlf = new CRLF();
        $this->assertTrue($crlf->safe('abc'));
    }

    public function testIsNotSafe()
    {
        $crlf = new CRLF();
        $this->assertFalse($crlf->safe('%0D%0ASet-Cookie:mycookie=myvalue'));
    }
}