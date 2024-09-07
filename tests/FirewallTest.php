<?php
declare(strict_types=1);

namespace Riverside\Waf\Tests;

use PHPUnit\Framework\TestCase;
use Riverside\Waf\Exception;
use Riverside\Waf\Filter\Crlf;
use Riverside\Waf\Filter\Sql;
use Riverside\Waf\Filter\Xml;
use Riverside\Waf\Filter\Xss;
use Riverside\Waf\Firewall;

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

    /**
     * @return Firewall
     * @throws Exception
     */
    public function testFilters()
    {
        $firewall = new Firewall();
        $this->assertIsArray($firewall->getFilters());

        return $firewall;
    }

    /**
     * @depends testFilters
     * @param Firewall $firewall
     * @return Firewall
     * @throws Exception
     */
    public function testEnable(Firewall $firewall)
    {
        $this->assertInstanceOf(Firewall::class, $firewall->enable('Xml'));
        $filters = $firewall->getFilters();
        $this->assertIsArray($filters);
        $this->assertTrue($filters['Xml']);

        return $firewall;
    }

    /**
     * @depends testEnable
     * @param Firewall $firewall
     * @return Firewall
     * @throws Exception
     */
    public function testDisable(Firewall $firewall)
    {
        $this->assertInstanceOf(Firewall::class, $firewall->disable('Sql'));
        $filters = $firewall->getFilters();
        $this->assertIsArray($filters);
        $this->assertFalse($filters['Sql']);

        return $firewall;
    }

    /**
     * @depends testDisable
     * @param Firewall $firewall
     * @return Firewall
     * @throws Exception
     */
    public function testSetters(Firewall $firewall)
    {
        $this->assertInstanceOf(Firewall::class, $firewall->setLogFile(''));
        $this->assertInstanceOf(Firewall::class, $firewall->setLogFormat(''));
        $this->assertInstanceOf(Firewall::class, $firewall->setMode(Firewall::MODE_LOG));

        return $firewall;
    }

    /**
     * @depends testFilters
     * @param Firewall $firewall
     * @throws Exception
     */
    public function testGetFilterInstance(Firewall $firewall)
    {
        $this->assertInstanceOf(Crlf::class, $firewall->getFilterInstance('Crlf'));
        $this->assertInstanceOf(Sql::class, $firewall->getFilterInstance('Sql'));
        $this->assertInstanceOf(Xml::class, $firewall->getFilterInstance('Xml'));
        $this->assertInstanceOf(Xss::class, $firewall->getFilterInstance('Xss'));
    }

    /**
     * @depends testFilters
     * @param Firewall $firewall
     * @return Firewall
     * @throws Exception
     */
    public function testRun(Firewall $firewall)
    {
        $firewall
            ->setMode(Firewall::MODE_LOG)
            ->setLogFile('php://temp')
            ->setLogFormat(Firewall::COMMON_LOG_FORMAT)
            ->enable('Xss')
            ->disable('Xml')
            ->enable('Crlf')
            ->enable('Sql')
            ->run();

        $this->assertInstanceOf(Firewall::class, $firewall);

        return $firewall;
    }

    /**
     * @depends testFilters
     * @param Firewall $firewall
     * @throws Exception
     */
    public function testRunFilter(Firewall $firewall)
    {
        $temp_file = tempnam(sys_get_temp_dir(), 'Tux');

        $firewall
            ->setMode(Firewall::MODE_LOG)
            ->setLogFile($temp_file);
        $inst = $firewall->runFilter('Sql');
        $this->assertInstanceOf(Firewall::class, $inst);

        $contents = file_get_contents($temp_file);
        $this->assertStringContainsString('[Sql]', $contents);
    }

    /**
     * @depends testFilters
     * @param Firewall $firewall
     * @throws Exception
     */
    public function testGetFilter(Firewall $firewall)
    {
        $filter = 'Sql';

        $firewall->enable($filter);
        $this->assertTrue($firewall->getFilter($filter));

        $firewall->disable($filter);
        $this->assertFalse($firewall->getFilter($filter));
    }

    /**
     * @depends testFilters
     * @param Firewall $firewall
     * @throws Exception
     */
    public function testExceptionMode(Firewall $firewall)
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(Exception::ERROR_UNKNOWN_MODE);
        $this->expectExceptionCode(Exception::ERROR_UNKNOWN_MODE_CODE);
        $firewall->setMode(123);
    }

    /**
     * @depends testFilters
     * @param Firewall $firewall
     * @throws Exception
     */
    public function testExceptionLogFormat(Firewall $firewall)
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(Exception::ERROR_EMPTY_LOG_FORMAT);
        $this->expectExceptionCode(Exception::ERROR_EMPTY_LOG_FORMAT_CODE);
        $firewall->setLogFile('php://temp');
        $firewall->setLogFormat('');
        $firewall->log('test', 'Sql');
    }

    /**
     * @depends testFilters
     * @param Firewall $firewall
     * @throws Exception
     */
    public function testExceptionLogFile(Firewall $firewall)
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(Exception::ERROR_EMPTY_LOG_FILE);
        $this->expectExceptionCode(Exception::ERROR_EMPTY_LOG_FILE_CODE);
        $firewall->setLogFile('');
        $firewall->log('test', 'Sql');
    }

    /**
     * @depends testFilters
     * @param Firewall $firewall
     * @throws Exception
     */
    public function testExceptionFilter(Firewall $firewall)
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(Exception::ERROR_UNKNOWN_FILTER);
        $this->expectExceptionCode(Exception::ERROR_UNKNOWN_FILTER_CODE);
        $firewall->enable('unknown');
    }
}