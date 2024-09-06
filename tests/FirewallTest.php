<?php
declare(strict_types=1);

namespace PhpWaf\Tests;

use PHPUnit\Framework\TestCase;
use PhpWaf\Exception;
use PhpWaf\Filter\CRLF;
use PhpWaf\Filter\SQL;
use PhpWaf\Filter\XML;
use PhpWaf\Filter\XSS;
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
     * @throws Exception
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
        $this->assertInstanceOf(CRLF::class, $firewall->getFilterInstance('CRLF'));
        $this->assertInstanceOf(SQL::class, $firewall->getFilterInstance('SQL'));
        $this->assertInstanceOf(XML::class, $firewall->getFilterInstance('XML'));
        $this->assertInstanceOf(XSS::class, $firewall->getFilterInstance('XSS'));
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
            ->enable('XSS')
            ->disable('XML')
            ->enable('CRLF')
            ->enable('SQL')
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
        $inst = $firewall->runFilter('SQL');
        $this->assertInstanceOf(Firewall::class, $inst);

        $contents = file_get_contents($temp_file);
        $this->assertStringContainsString('[SQL]', $contents);
    }

    /**
     * @depends testFilters
     * @param Firewall $firewall
     * @throws Exception
     */
    public function testGetFilter(Firewall $firewall)
    {
        $filter = 'SQL';

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
        $firewall->log('test', 'SQL');
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
        $firewall->log('test', 'SQL');
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

    /**
     * @runInSeparateProcess
     */
    public function testBlock()
    {
        ob_start();

        $this->expectOutputString('');
        $this->expectException(\PHPUnit\Framework\Error\Warning::class);

        Firewall::block();

        $this->assertEquals(400, http_response_code());

        ob_end_clean();
    }
}