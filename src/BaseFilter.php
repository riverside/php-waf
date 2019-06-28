<?php
namespace PhpWaf;

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
        $payloads = @file(__DIR__ . '/../payloads/' . $this->payloads_file);
        if ($payloads !== false)
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