<?php
declare(strict_types=1);

namespace PhpWaf\Tests\Filter;

use PHPUnit\Framework\TestCase;
use PhpWaf\Filter\XML;

class XMLTest extends TestCase
{
    public function testAttributes()
    {
        $attributes = array(
            'payloads_file',
        );

        foreach ($attributes as $attribute)
        {
            $this->assertClassHasAttribute($attribute, XML::class);
        }
    }
}