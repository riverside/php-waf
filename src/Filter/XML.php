<?php
declare(strict_types=1);

namespace Riverside\Waf\Filter;

use Riverside\Waf\AbstractFilter;

/**
 * Class Xml
 *
 * @package Riverside\Waf\Filter
 */
class Xml extends AbstractFilter
{
    /**
     * Payload filename
     *
     * @var string
     */
    protected $payloads_file = "xml.txt";

    /**
     * Check given string
     *
     * @param string $value
     * @return bool
     */
    public function safe(string $value): bool
    {
        // TODO: Implement safe() method.
    }
}
