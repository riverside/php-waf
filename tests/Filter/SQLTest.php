<?php
declare(strict_types=1);

namespace PhpWaf\Tests\Filter;

use PHPUnit\Framework\TestCase;
use PhpWaf\Filter\SQL;

class SQLTest extends TestCase
{
    public function testAttributes()
    {
        $attributes = array(
            'payloads_file',
        );

        foreach ($attributes as $attribute)
        {
            $this->assertClassHasAttribute($attribute, SQL::class);
        }
    }

    public function testIsSafe()
    {
        $crlf = new SQL();
        $this->assertTrue($crlf->safe('abc'));
    }

    public function testIsNotSafe()
    {
        $crlf = new SQL();
        $this->assertFalse($crlf->safe('TRUE DIV(SELECT ORD(LEFT'));
    }
}