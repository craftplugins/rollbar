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
        // Enabled reporting to ensure that the exception is logged
        $reportingLevel = error_reporting(E_ALL);

        $return = Rollbar::report_exception($exception, $data, $payload);

        // Revert to the original reporting level
        error_reporting($reportingLevel);

        return $return;
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
        $level = $log[1];
        $category = $log[2];

        $includeCraftLogs = craft()->config->get('includeCraftLogs', 'rollbar');
        $includePluginLogs = craft()->config->get('includePluginLogs', 'rollbar');
        $includeLogLevels = craft()->config->get('includeLogLevels', 'rollbar');

        if ($category == 'application' && !$includeCraftLogs) {
            // Don’t log Craft
            return;
        }

        if ($category == 'plugin' && !$includePluginLogs) {
            // Don’t log plugins
            return;
        }

        if (is_array($includePluginLogs) && !in_array($log[5], $includePluginLogs)) {
            // Don’t log this plugin
            return;
        }

        if (!in_array($level, $includeLogLevels)) {
            // Don’t log this level
            return;
        }

        $data = [
            'category' => $category,
            'timestamp' => $log[3],
            'plugin' => $log[5],
        ];

        return $this->reportMessage($log[0], $level, $data);
    }
}
