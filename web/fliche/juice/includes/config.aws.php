<?php

return array(
    // Bootstrap the configuration file with AWS specific features
    'includes' => array('_aws'),
    'services' => array(
        // All AWS clients extend from 'default_settings'. Here we are
        // overriding 'default_settings' with our default credentials and
        // providing a default region setting.
        'default_settings' => array(
            'params' => array(
                'credentials' => array(
                    'key'    => 'AKIAJNQLLLZRPPFRPKRQ',
                    'secret' => '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',
                ),
                #'region' => 'us-west-1',
					      'region'  => 'ap-southeast-1',
					      'version' => 'latest'
            )
        )
    )
);