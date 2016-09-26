<?php

use Craft\LogLevel;

return [

    /**
     * The server side access token supplied by Rollbar.
     *
     * @param string
     */
    'accessToken' => '',

    /**
     * Whether or not to include Craft logs.
     *
     * Be careful if you use this with `devMode` on. Craft’s logging is verbose!
     *
     * @param bool
     */
    'includeCraftLogs' => false,

    /**
     * Whether or not to include plugin logs.
     *
     * You can supply an array of plugins to include.
     *
     * @param bool|array
     */
    'includePluginLogs' => true,

    /**
     * Log levels to push to Rollbar from Craft and plugins.
     *
     * @param array
     */
    'includeLogLevels' => [
        LogLevel::Error,
        LogLevel::Info,
        LogLevel::Profile,
        LogLevel::Trace,
        LogLevel::Warning,
    ],

    /**
     * Status codes to include when logging {@link \Craft\HttpException}.
     *
     * @param bool|array
     */
    'includeHttpStatusCodes' => [
        416,
        500,
        503,
    ],

];
