<?php
declare(strict_types=1);

namespace Riverside\Waf;

/**
 * Class Exception
 *
 * @package Riverside\Waf
 */
class Exception extends \Exception
{
    /**
     * @var string
     */
    const ERROR_EMPTY_LOG_FILE = 'Empty log_file';

    /**
     * @var int
     */
    const ERROR_EMPTY_LOG_FILE_CODE = 1001;

    /**
     * @var string
     */
    const ERROR_EMPTY_LOG_FORMAT = 'Empty log_format';

    /**
     * @var int
     */
    const ERROR_EMPTY_LOG_FORMAT_CODE = 1002;

    /**
     * @var string
     */
    const ERROR_UNKNOWN_MODE = 'Unknown mode';

    /**
     * @var int
     */
    const ERROR_UNKNOWN_MODE_CODE = 1003;

    /**
     * @var string
     */
    const ERROR_UNKNOWN_FILTER = 'Unknown filter';

    /**
     * @var int
     */
    const ERROR_UNKNOWN_FILTER_CODE = 1004;
}