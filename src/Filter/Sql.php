<?php
declare(strict_types=1);

namespace Riverside\Waf\Filter;

use Riverside\Waf\AbstractFilter;

/**
 * Class Sql
 *
 * @package Riverside\Waf\Filter
 */
class Sql extends AbstractFilter
{
    /**
     * Payload filename
     *
     * @var string
     */
    protected $payloads_file = "sql.txt";

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

            if (empty($payload) || strpos($payload, '#') === 0)
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
