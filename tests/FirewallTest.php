<?php
namespace PhpWaf\Tests;

use PHPUnit\Framework\TestCase;
use PhpWaf\Firewall;

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

    public function testFilters()
    {
        try {
            $firewall = new Firewall();
            $this->assertIsArray($firewall->getFilters());
        } catch (\Exception $e) {

        }

        return $firewall;
    }

    /**
     * @depends testFilters
     * @param Firewall $firewall
     * @return Firewall
     * @throws \Exception
     */
    public function testEnable(Firewall $firewall)
    {
        $this->assertInstanceOf(Firewall::class, $firewall->enable('XML'));
        $filters = $firewall->getFilters();
        $this->assertIsArray($filters);
        $this->assertTrue($filters['XML']);

        return $firewall;
    }

    /**
     * @depends testEnable
     * @param Firewall $firewall
     * @return Firewall
     * @throws \Exception
     */
    public function testDisable(Firewall $firewall)
    {
        $this->assertInstanceOf(Firewall::class, $firewall->disable('SQL'));
        $filters = $firewall->getFilters();
        $this->assertIsArray($filters);
        $this->assertFalse($filters['SQL']);

        return $firewall;
    }

    /**
     * @depends testDisable
     * @param Firewall $firewall
     * @return Firewall
     * @throws \Exception
     */
    public function testSetters(Firewall $firewall)
    {
        $this->assertInstanceOf(Firewall::class, $firewall->setLogFile(''));
        $this->assertInstanceOf(Firewall::class, $firewall->setLogFormat(''));
        $this->assertInstanceOf(Firewall::class, $firewall->setMode(3));

        return $firewall;
    }

    /**
     * @depends testSetters
     * @param Firewall $firewall
     * @return Firewall
     * @throws \Exception
     */
    public function testRun(Firewall $firewall)
    {
        $firewall
            ->setMode(3)
            ->setLogFormat('')
            ->setLogFile('')
            ->disable('XSS')
            ->disable('XML')
            ->disable('CRLF')
            ->enable('SQL')
            ->run();

        $this->assertInstanceOf(Firewall::class, $firewall);

        return $firewall;
    }

    /**
     * @depends testRun
     * @param Firewall $firewall
     * @throws \Exception
     */
    public function testException(Firewall $firewall)
    {
        $this->expectException(\Exception::class);
        $firewall->setMode(100);
    }
}