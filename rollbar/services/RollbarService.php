<?php

/**
 * Rollbar plugin for Craft CMS.
 *
 * Rollbar_Rollbar Service
 *
 * @author    Joshua Baker
 * @copyright Copyright (c) 2016 Joshua Baker
 *
 * @link      https://joshuabaker.com/
 * @since     0.1.0
 */
namespace Craft;

use Level;
use Rollbar;
use Exception;

class RollbarService extends BaseApplicationComponent
{
    /**
     * A key/value map of LogLevel and Rollbar Level constants.
     */
    protected $levels = [
        LogLevel::Error => Level::ERROR,
        LogLevel::Info => Level::INFO,
        LogLevel::Profile => Level::DEBUG,
        LogLevel::Trace => Level::INFO,
        LogLevel::Warning => Level::WARNING,
    ];

    /**
     * Converts Craft’s LogLevel into Rollbar Level.
     *
     * @param string $level
     *
     * @return string
     */
    public function getLogLevel($level)
    {
        if (isset($this->levels[$level])) {
            return $this->levels[$level];
        }

        return $level;
    }

    /**
     * @see https://github.com/rollbar/rollbar-php#basic-usage
     */
    public function reportException(Exception $exception, $data = null, $payload = null)
    {
        return Rollbar::report_exception($exception, $data, $payload);
    }

    /**
     * @see https://github.com/rollbar/rollbar-php#basic-usage
     */
    public function reportMessage($message, $level = LogLevel::Error, $data = null, $payload = null)
    {
        $level = $this->getLogLevel($level);

        return Rollbar::report_message($message, $level, $data, $payload);
    }

    /**
     * Pass a Craft log to Rollbar.
     *
     * @see CLogger::getLogs();
     */
    public function log($log)
    {
        $includeLogLevels = craft()->config->get('includeLogLevels', 'rollbar');

        if (in_array($log[1], $includeLogLevels)) {
            $data = [
                'category' => $log[2],
                'timestamp' => $log[3],
                'plugin' => $log[5],
            ];

            return $this->reportMessage($log[0], $log[1], $data);
        }
    }
}
