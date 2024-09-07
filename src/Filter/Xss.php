<?php
declare(strict_types=1);

namespace Riverside\Waf\Filter;

use Riverside\Waf\AbstractFilter;

/**
 * Class Xss
 *
 * @package Riverside\Waf\Filter
 */
class Xss extends AbstractFilter
{
    /**
     * Payload filename
     *
     * @var string
     */
    protected $payloads_file = "xss.txt";

    /**
     * Check given string
     *
     * @param string $value
     * @return bool
     */
    public function safe(string $value): bool
    {
        foreach ($this->payloads as $payload)
        {
            $payload = trim($payload);

            if (empty($payload))
            {
                continue;
            }

            if ($payload == $value || stripos($value, $payload) !== false)
            {
                return false;
            }
        }

        return true;
    }
}
