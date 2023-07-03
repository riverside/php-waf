<?php
namespace PhpWaf\Filter;

use PhpWaf\BaseFilter;

/**
 * Class XSS
 *
 * @package PhpWaf\Filter
 */
class XSS extends BaseFilter
{
    /**
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
