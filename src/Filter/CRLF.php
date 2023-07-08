<?php
namespace PhpWaf\Filter;

use PhpWaf\BaseFilter;

/**
 * Class CRLF
 *
 * @package PhpWaf\Filter
 */
class CRLF extends BaseFilter
{
    /**
     * Payload filename
     *
     * @var string
     */
    protected $payloads_file = "crlf.txt";

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
