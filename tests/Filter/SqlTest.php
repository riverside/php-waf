<?php
declare(strict_types=1);

namespace Riverside\Waf\Tests\Filter;

use PHPUnit\Framework\TestCase;
use Riverside\Waf\Filter\Sql;

class SqlTest extends TestCase
{
    public function testAttributes()
    {
        $attributes = array(
            'payloads_file',
        );

        foreach ($attributes as $attribute)
        {
            $this->assertClassHasAttribute($attribute, Sql::class);
        }
    }

    public function testIsSafe()
    {
        $sql = new Sql();
        $this->assertTrue($sql->safe('abc'));
    }

    public function testIsNotSafe()
    {
        $sql = new Sql();
        $this->assertFalse($sql->safe('TRUE DIV(SELECT ORD(LEFT'));
    }
}