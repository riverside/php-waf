<?php
declare(strict_types=1);

namespace PhpWaf\Tests\Filter;

use PHPUnit\Framework\TestCase;
use PhpWaf\Filter\XSS;

class XSSTest extends TestCase
{
    public function testAttributes()
    {
        $attributes = array(
            'payloads_file',
        );

        foreach ($attributes as $attribute)
        {
            $this->assertClassHasAttribute($attribute, XSS::class);
        }
    }

    public function testIsSafe()
    {
        $crlf = new XSS();
        $this->assertTrue($crlf->safe('abc'));
    }

    public function testIsNotSafe()
    {
        $crlf = new XSS();
        $this->assertFalse($crlf->safe('<img src=1 href=1 onerror="javascript:alert(1)"></img>'));
    }
}