<?php
namespace PhpWaf;

/**
 * Class BaseFilter
 *
 * @package PhpWaf
 */
abstract class BaseFilter
{
    /**
     * @var array
     */
    protected $payloads = array();

    /**
     * @var string
     */
    protected $payloads_file = "";

    /**
     * BaseFilter constructor.
     */
    public function __construct()
    {
        $filename = __DIR__ . '/payloads/' . $this->payloads_file;
        if (is_file($filename) && ($payloads = file($filename)) !== false)
        {
            $this->payloads = $payloads;
        }
    }

    /**
     * Check given string
     *
     * @param string $value
     * @return bool
     */
    abstract public function safe(string $value): bool;
}
