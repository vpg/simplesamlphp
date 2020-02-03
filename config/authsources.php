<?php

$config = array(

    // This is a authentication source which handles admin authentication.
    'admin' => array(
        // The default is to use core:AdminPassword, but it can be replaced with
        // any authentication source.

        'core:AdminPassword',
    ),
    'azure-auth' => array(
        'saml:SP',
        'entityID' => NULL,
        'idp' => 'https://sts.windows.net/3f367aee-4bbd-4fd3-9146-81f55630b6fc/',
        'discoURL' => null
    )
);
