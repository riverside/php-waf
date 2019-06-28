<?php
namespace PhpWaf;

class Firewall
{
    /**
     * Available filters and their default status
     *
     * @var array
     */
    protected $filters = array(
        'SQL' => true,
        'XML' => false,
        'XSS' => true,
    );
    /**
     * The mode in that firewall is running
     *
     * @var int Valid modes are: 1 - block & log; 2 - block only; 3 - log only
     */
    protected $mode = 1;
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
    protected $log_format = '%m (%i) [%f] - %v';

    /**
     * Firewall constructor.
     * @param int $mode
     */
    public function __construct(int $mode=1)
    {
        $this->setMode($mode);
    }

    /**
     * Send HTTP status code 400 to client
     */
    public static function block()
    {
        header("HTTP/1.1 400 Bad Request");
        exit;
    }

    /**
     * Write the detected  to a log file
     *
     * @param string $value
     * @param string $filter
     * @return Firewall
     */
    public function log(string $value, string $filter): self
    {
        if (!empty($this->log_file) && !empty($this->log_format))
        {
            $data = str_replace(
                array('%f', '%v', '%i', '%d', '%t', '%m', '%u'),
                array($filter, $value, $_SERVER['REMOTE_ADDR'], date('Y-m-d'), date('H:i:s'),
                    date('Y-m-d H:i:s'), time()),
                $this->log_format);

            file_put_contents($this->log_file, "\nwarn $data", FILE_APPEND);
        }

        return $this;
    }

    /**
     * Set log file path
     * @param string $value
     * @return Firewall
     */
    public function setLogFile(string $value): self
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
    public function setLogFormat(string $value): self
    {
        $this->log_format = $value;

        return $this;
    }

    /**
     * Set mode
     *
     * @param int $value
     * @return Firewall
     */
    public function setMode(int $value): self
    {
        if (in_array($value, range(1,3)))
        {
            $this->mode = $value;
        }

        return $this;
    }

    /**
     * Enable a given filter
     *
     * @param string $filter
     * @return Firewall
     */
    public function enable(string $filter): self
    {
        if (array_key_exists($filter, $this->filters))
        {
            $this->filters[$filter] = true;
        }

        return $this;
    }

    /**
     * Disable a given filter
     *
     * @param string $filter
     * @return Firewall
     */
    public function disable(string $filter): self
    {
        if (array_key_exists($filter, $this->filters))
        {
            $this->filters[$filter] = false;
        }

        return $this;
    }

    /**
     * Handles a detected attack accordingly current mode
     *
     * @param string $value
     * @param string $filter
     * @return Firewall
     */
    public function handle(string $value, string $filter): self
    {
        if ($this->mode == 1)
        {
            // log & block
            $this->log($value, $filter);
            self::block();
        }

        if ($this->mode == 2)
        {
            // block only
            self::block();
        }

        if ($this->mode == 3)
        {
            // log only
            $this->log($value, $filter);
        }

        return $this;
    }

    /**
     * Runs a given filter
     *
     * @param string $filter
     * @return Firewall
     */
    public function runFilter(string $filter): self
    {
        if (!$this->filters[$filter])
        {
            return $this;
        }

        $class = "PhpWaf\\Filter\\$filter";
        $instance = new $class;

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
     */
    public function run(): self
    {
        foreach (array_keys($this->filters) as $filter)
        {
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
}