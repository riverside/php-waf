<?php
declare(strict_types=1);

namespace PhpWaf;

/**
 * Class Exception
 *
 * @package PhpWaf
 */
class Exception extends \Exception
{
    const ERROR_EMPTY_LOG_FILE = 'Empty log_file';
    const ERROR_EMPTY_LOG_FILE_CODE = 1001;

    const ERROR_EMPTY_LOG_FORMAT = 'Empty log_format';
    const ERROR_EMPTY_LOG_FORMAT_CODE = 1002;

    const ERROR_UNKNOWN_MODE = 'Unknown mode';
    const ERROR_UNKNOWN_MODE_CODE = 1003;

    const ERROR_UNKNOWN_FILTER = 'Unknown filter';
    const ERROR_UNKNOWN_FILTER_CODE = 1004;
}