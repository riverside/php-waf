<?php
namespace PhpWaf;

use PHPUnit\Framework\TestCase;

class FirewallTest extends TestCase
{
    public function testAttributes()
    {
        $attributes = array(
            'filters',
            'mode',
            'log_file',
            'log_format',
        );

        foreach ($attributes as $attribute)
        {
            $this->assertClassHasAttribute($attribute, Firewall::class);
        }
    }

}