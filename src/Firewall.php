<?php
declare(strict_types=1);

namespace Riverside\Waf;

/**
 * Class Firewall
 *
 * @package Riverside\Waf
 */
class Firewall
{
    /**
     * @var int
     */
    const MODE_LOG_AND_BLOCK = 1;

    /**
     * @var int
     */
    const MODE_BLOCK = 2;

    /**
     * @var int
     */
    const MODE_LOG = 3;

    /**
     * @var string
     */
    const COMMON_LOG_FORMAT = '%m (%i) [%f] - %v';

    /**
     * Available filters and their default status
     *
     * @var array
     */
    protected $filters = [
        'Sql' => true,
        'Xml' => false,
        'Xss' => true,
        'Crlf' => true,
    ];

    /**
     * The mode in that firewall is running
     *
     * @var int Valid modes are: 1 - block & log; 2 - block only; 3 - log only
     */
    protected $mode = self::MODE_LOG_AND_BLOCK;

    /**
     * The log file path
     *
     * @var string
     */
    protected $log_file = 'waf.log';

    /**
     * Log format
     *
     * %f - filter
     * %v - value
     * %i - ip address
     * %d - date
     * %t - time
     * %m - date & time
     * %u - unix time
     *
     * @var string
     */
    protected $log_format = self::COMMON_LOG_FORMAT;

    /**
     * Firewall constructor.
     *
     * @param int $mode
     * @throws Exception
     */
    public function __construct(int $mode=self::MODE_LOG_AND_BLOCK)
    {
        $this->setMode($mode);
    }

    /**
     * Send HTTP status code 400 to client
     */
    public static function block()
    {
        http_response_code(400);
        exit;
    }

    /**
     * Write the detected to a log file
     *
     * @param string $value
     * @param string $filter
     * @return Firewall
     * @throws Exception
     */
    public function log(string $value, string $filter): Firewall
    {
        if (empty($this->log_file))
        {
            throw new Exception(Exception::ERROR_EMPTY_LOG_FILE, Exception::ERROR_EMPTY_LOG_FILE_CODE);
        }

        if (empty($this->log_format))
        {
            throw new Exception(Exception::ERROR_EMPTY_LOG_FORMAT, Exception::ERROR_EMPTY_LOG_FORMAT_CODE);
        }

        $data = str_replace(
            ['%f', '%v', '%i', '%d', '%t', '%m', '%u'],
            [$filter, $value, $_SERVER['REMOTE_ADDR'], date('Y-m-d'), date('H:i:s'),
                date('Y-m-d H:i:s'), time()],
            $this->log_format);

        file_put_contents($this->log_file, "\nwarn $data", FILE_APPEND);

        return $this;
    }

    /**
     * Set log file path
     *
     * @param string $value
     * @return Firewall
     */
    public function setLogFile(string $value): Firewall
    {
        $this->log_file = $value;

        return $this;
    }

    /**
     * Set log format
     *
     * @param string $value
     * @return Firewall
     */
    public function setLogFormat(string $value): Firewall
    {
        $this->log_format = $value;

        return $this;
    }

    /**
     * Set mode
     *
     * @param int $value
     * @return Firewall
     * @throws Exception
     */
    public function setMode(int $value): Firewall
    {
        if (!in_array($value, [self::MODE_LOG_AND_BLOCK, self::MODE_BLOCK, self::MODE_LOG]))
        {
            throw new Exception(Exception::ERROR_UNKNOWN_MODE, Exception::ERROR_UNKNOWN_MODE_CODE);
        }
        $this->mode = $value;

        return $this;
    }

    /**
     * Enable a given filter
     *
     * @param string $filter
     * @return Firewall
     * @throws Exception
     */
    public function enable(string $filter): Firewall
    {
        if (!array_key_exists($filter, $this->filters))
        {
            throw new Exception(Exception::ERROR_UNKNOWN_FILTER, Exception::ERROR_UNKNOWN_FILTER_CODE);
        }
        $this->filters[$filter] = true;

        return $this;
    }

    /**
     * Disable a given filter
     *
     * @param string $filter
     * @return Firewall
     * @throws Exception
     */
    public function disable(string $filter): Firewall
    {
        if (!array_key_exists($filter, $this->filters))
        {
            throw new Exception(Exception::ERROR_UNKNOWN_FILTER_CODE, Exception::ERROR_UNKNOWN_FILTER_CODE);
        }
        $this->filters[$filter] = false;

        return $this;
    }

    /**
     * Handles a detected attack accordingly current mode
     *
     * @param string $value
     * @param string $filter
     * @return Firewall
     * @throws Exception
     */
    public function handle(string $value, string $filter): Firewall
    {
        if ($this->mode == self::MODE_LOG_AND_BLOCK)
        {
            // log & block
            $this->log($value, $filter);
            self::block();
        }

        if ($this->mode == self::MODE_BLOCK)
        {
            // block only
            self::block();
        }

        if ($this->mode == self::MODE_LOG)
        {
            // log only
            $this->log($value, $filter);
        }

        return $this;
    }

    /**
     * Get filter's instance
     *
     * @param string $filter
     * @return AbstractFilter
     * @throws Exception
     */
    public function getFilterInstance(string $filter): AbstractFilter
    {
        if (!array_key_exists($filter, $this->getFilters()))
        {
            throw new Exception(Exception::ERROR_UNKNOWN_FILTER_CODE, Exception::ERROR_UNKNOWN_FILTER_CODE);
        }

        $class = "Riverside\\Waf\\Filter\\$filter";

        return new $class;
    }

    /**
     * Runs a given filter
     *
     * @param string $filter
     * @return Firewall
     * @throws Exception
     */
    public function runFilter(string $filter): Firewall
    {
        $instance = $this->getFilterInstance($filter);

        foreach ($_GET as $key => $val)
        {
            if (!$instance->safe($val))
            {
                $this->handle($val, $filter);
            }
        }

        foreach ($_POST as $key => $val)
        {
            if (!$instance->safe($val))
            {
                $this->handle($val, $filter);
            }
        }

        return $this;
    }

    /**
     * Runs all the enabled filters
     *
     * @return Firewall
     * @throws Exception
     */
    public function run(): Firewall
    {
        foreach ($this->getFilters() as $filter => $enabled)
        {
            if (!$enabled)
            {
                continue;
            }
            $this->runFilter($filter);
        }

        return $this;
    }

    /**
     * Get list with filters
     *
     * @return array
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * Get filter's current state
     *
     * @param string $filter
     * @return bool
     * @throws Exception
     */
    public function getFilter(string $filter): bool
    {
        if (!array_key_exists($filter, $this->filters))
        {
            throw new Exception(Exception::ERROR_UNKNOWN_FILTER_CODE, Exception::ERROR_UNKNOWN_FILTER_CODE);
        }

        return $this->filters[$filter];
    }
}
