<?php

/**
 * Rollbar plugin for Craft CMS.
 *
 * Craft integration with error monitoring service Rollbar.
 *
 * @author    Joshua Baker
 * @copyright Copyright (c) 2016 Joshua Baker
 *
 * @link      https://joshuabaker.com/
 * @since     0.1.0
 */
namespace Craft;

use Rollbar;

class RollbarPlugin extends BasePlugin
{
    /**
     * @return mixed
     */
    public function init()
    {
        require_once __DIR__.'/vendor/autoload.php';

        Rollbar::init([
            'access_token' => craft()->config->get('accessToken', 'rollbar'),
            'environment' => CRAFT_ENVIRONMENT,
        ], false, false);

        craft()->onException->add(function ($event) {
            craft()->rollbar->reportException($event->exception);
        });

        craft()->onError->add(function ($event) {
            craft()->rollbar->reportMessage($event->message);
        });

        $logger = Craft::getLogger();
        $logger->attachEventHandler('onFlush', function($event) use ($logger) {
            foreach ($logger->getLogs($level) as $log) {
                craft()->rollbar->log($log);
            }
        });
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return Craft::t('Rollbar');
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return Craft::t('Craft integration with error monitoring service Rollbar.');
    }

    /**
     * @return string
     */
    public function getDocumentationUrl()
    {
        return 'https://github.com/craft-rollbar/rollbar/blob/master/README.md';
    }

    /**
     * @return string
     */
    public function getReleaseFeedUrl()
    {
        return 'https://raw.githubusercontent.com/craft-rollbar/rollbar/master/releases.json';
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return '0.1.0';
    }

    /**
     * @return string
     */
    public function getDeveloper()
    {
        return 'Joshua Baker';
    }

    /**
     * @return string
     */
    public function getDeveloperUrl()
    {
        return 'https://joshuabaker.com/';
    }
}
