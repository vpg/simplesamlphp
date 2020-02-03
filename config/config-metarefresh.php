<?php

$config = array(
    'sets' => array(
        'azure' => array(
            'cron' => array('daily', 'hourly', 'frequent'),
            'sources' => array(
                array(
                    'src' => 'https://login.microsoftonline.com/3f367aee-4bbd-4fd3-9146-81f55630b6fc/federationmetadata/2007-06/federationmetadata.xml',
                    'types' => array('saml20-idp-remote'),
                ),
	    ),
            'outputDir' => 'metadata',
            'outputFormat' => 'flatfile'
        ),
    ),
);
