<?php
/**
 * Configuration file generated by ZFTool
 * The previous configuration file is stored in application.config.old
 *
 * @see https://github.com/zendframework/ZFTool
 */
$env = strtolower(getenv('APP_ENV')) ?: 'production';

return array(
    'modules' => array(
        'EdpSuperluminal',
        'ScnSocialAuth',
        'ZfcBase',
        'ZfcUser',
        'BjyAuthorize',
        'Mailchimp',
        'Google',
        'BingAds',
        'Eway',
        'JimmyBase',
        'Application',
        'Chat'
    ),
    'module_listener_options' => array(
        'config_glob_paths' => array(
             sprintf('config/autoload/{,*.}{local,global,%s}.php', $env),
             sprintf('config/autoload/private/%s/{,*.}{local,global,%s}.php', $env,$env)
  ),
        'module_paths' => array(
            './module/',
            './vendor/'
        )
    )
);