<?php

require __DIR__ . '/../env.php';

/**
 * SAML 2.0 remote SP metadata for SimpleSAMLphp.
 *
 * See: https://simplesamlphp.org/docs/stable/simplesamlphp-reference-sp-remote
 */

$f_duplicatePerUser = function ($hosts) use ($devs)
{
    $mappedHosts = array();
    foreach ($hosts as $host) {
        if (preg_match('/(\{LOGIN\}).(\{ID_LAPTOP\})/', $host)) {
            foreach ($devs as $login => $id_laptops) {
                if (is_array($id_laptops)) {
                    foreach ($id_laptops as $id_laptop) { 
                        $mappedHosts[] = preg_replace('/(\{LOGIN\}\.\{ID_LAPTOP\})/', $login . '.' . $id_laptop, $host);
                    }
                } else {
                        $mappedHosts[] = preg_replace('/(\{LOGIN\}\.\{ID_LAPTOP\})/', $login . '.' . $id_laptops, $host);
                }
            }
        } else {
            $mappedHosts[] = $host;
        }
    }
    return $mappedHosts;
};

$f_duplicatePerBU = function ($hosts) use ($bus)
{
    $mappedHosts = array();
    foreach ($hosts as $host) {
        if (preg_match('/(\{BU\})/', $host)) {
            foreach ($bus as $bu) {
                $mappedHosts[] = preg_replace('/(\{BU\})/', $bu, $host);
            }
        } else {
            $mappedHosts[] = $host;
        }
    }
    return $mappedHosts;
};

$f_duplicatePerEnv = function ($hosts) use ($recettesCount)
{
    $mappedHosts = array();
    foreach ($hosts as $host) {
        if (preg_match('/(\{RECETTE_NUM\})/', $host)) {
            for ($i = 1; $i <= $recettesCount; $i++) {
                $mappedHosts[] = preg_replace('/(\{RECETTE_NUM\})/', $i, $host);
            }
        } else {
            $mappedHosts[] = $host;
        }
    }
    return $mappedHosts;
};

function fillURI($hostGroups)
{
    $groups = array();
    foreach ($hostGroups as $appGroup => $hosts) {
        $groups[$appGroup] = array();
        $mappedHosts = [];
        foreach ($hosts as $host) {
            $mappedHosts[] = $host . '/simplesaml/module.php/saml/sp/saml2-acs.php/' . $appGroup;
        }
        $groups[$appGroup] = $mappedHosts;
    }
    return $groups;
}

$groups = array();
$hostGroups = fillURI($hostGroups);
$groups = array_map($f_duplicatePerBU, $hostGroups);
if (isset($recettesCount)) {
    $groups = array_map($f_duplicatePerEnv, $groups);
}
if (isset($devs)) {
    $groups = array_map($f_duplicatePerUser, $groups);
}
$metadata['adp-test'] = array (
	'AssertionConsumerService' => 'https://ehctest1.fr.adp.com/',
	'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',
	'simplesaml.nameidattribute' => 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/name',
	'simplesaml.attributes' => true,
	'authproc' => array(
		1 => array(
			'class' => 'saml:AttributeNameID',
			'attribute' => 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/name',
			'Format' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent'
		),
		10 => array(
			'class' => 'core:AttributeLimit',
			'default' => TRUE,
		),
		20 => array(
			'class' => 'core:AttributeAdd',
			'ApplicationID' => array('test'),
			'CompanyID' => array('FR201406275838986')
		)
	),
	'bypassRequesterID' => true
);

$metadata['adp-prod'] = array (
	'AssertionConsumerService' => 'https://sso.ehc.adp.com/samlsp',
	'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',
	'simplesaml.nameidattribute' => 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/name',
	'simplesaml.attributes' => true,
	'authproc' => array(
		1 => array(
			'class' => 'saml:AttributeNameID',
			'attribute' => 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/name',
			'Format' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent'
		),
		10 => array(
			'class' => 'core:AttributeLimit',
			'default' => TRUE,
		),
		20 => array(
			'class' => 'core:AttributeAdd',
			'ApplicationID' => array('hrservices'),
			'CompanyID' => array('FR201406275838986')
		)
	),
	'bypassRequesterID' => true
);

$metadata['smartrecruiters'] = array (
        'AssertionConsumerService' => 'https://www.smartrecruiters.com/web-sso/saml/VoyagePriv/callback',
        'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',
        'simplesaml.nameidattribute' => 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/name',
        'simplesaml.attributes' => true,
        'bypassRequesterID' => true
);

$metadata['periscope'] = array (
        'AssertionConsumerService' => 'https://www.periscopedata.com/auth/saml/callback',
        'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',
        'simplesaml.nameidattribute' => 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/name',
        'simplesaml.attributes' => true,
        'bypassRequesterID' => true
);

$metadata['https://d-voyageprivecom.elmg.net/'] = array (
    'AssertionConsumerService' => 'https://d-voyageprivecom.elmg.net/lib/auth/simplesamlphp/www/module.php/saml/sp/saml2-acs.php/sp1',
    'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:transient',
    'simplesaml.nameidattribute' => 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/name',
    'simplesaml.attributes' => true,
    'bypassRequesterID' => true,
    'authproc' => array(
        1 => array(
            'class' => 'saml:AttributeNameID',
            'attribute' => 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/name',
            'Format' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:transient'
        )
    )
);

$metadata['https://vpg.elmg.net/'] = array (
    'AssertionConsumerService' => 'https://vpg.elmg.net/lib/auth/simplesamlphp/www/module.php/saml/sp/saml2-acs.php/sp1',
    'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:transient',
    'simplesaml.nameidattribute' => 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/name',
    'simplesaml.attributes' => true,
    'bypassRequesterID' => true,
    'authproc' => array(
        1 => array(
            'class' => 'saml:AttributeNameID',
            'attribute' => 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/name',
            'Format' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:transient'
        )
    )
);

$metadata['https://d-voyageprivecom.elmg.net/'] = array (
	'AssertionConsumerService' => 'https://d-voyageprivecom.elmg.net/lib/auth/simplesamlphp/www/module.php/saml/sp/saml2-acs.php/sp1',
	'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',
	'simplesaml.nameidattribute' => 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/name',
	'simplesaml.attributes' => true,
	'bypassRequesterID' => true
);

$metadata['builder'] = array(
    'AssertionConsumerService' => $groups['builder'],
        'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',
        'simplesaml.nameidattribute' => 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/name',
        'simplesaml.attributes' => true,
        'bypassRequesterID' => true
);

$metadata['bong'] = array(
	'AssertionConsumerService' => $groups['bong'],
        'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',
        'simplesaml.nameidattribute' => 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/name',
        'simplesaml.attributes' => true,
        'bypassRequesterID' => true
);

$metadata['ozone'] = array(
	'AssertionConsumerService' => $groups['ozone'],
        'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',
        'simplesaml.nameidattribute' => 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/name',
        'simplesaml.attributes' => true,
        'bypassRequesterID' => true
);

$metadata['turbo'] = array(
    'AssertionConsumerService' => $groups['turbo'],
    'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',
    'simplesaml.nameidattribute' => 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/name',
    'simplesaml.attributes' => true,
    'bypassRequesterID' => true
);

$metadata['carambola'] = array(
    'AssertionConsumerService' => $groups['carambola'],
    'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',
    'simplesaml.nameidattribute' => 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/name',
    'simplesaml.attributes' => true,
    'bypassRequesterID' => true
);

$metadata['https://rct.easiware.fr/8.2/voyageprive/AuthSAML/AuthServices'] = array(
    'AssertionConsumerService' => 'https://rct.easiware.fr/8.2/voyageprive/AuthSAML/AuthServices/Acs',
    'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',
    'simplesaml.nameidattribute' => 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/name',
    'simplesaml.attributes' => true,
    'bypassRequesterID' => true,
);

$metadata['https://www5.easiware.fr/voyageprive/AuthSAML/AuthServices'] = array(
    'AssertionConsumerService' => 'https://www5.easiware.fr/voyageprive/AuthSAML/AuthServices/Acs',
    'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',
    'simplesaml.nameidattribute' => 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/name',
    'simplesaml.attributes' => true,
    'bypassRequesterID' => true,
);

$metadata['https://redmine.bovpg.net'] = array (
        'AssertionConsumerService' => 'https://redmine.bovpg.net/auth/saml/callback',
        'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',
        'simplesaml.nameidattribute' => 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/name',
        'simplesaml.attributes' => true,
        'bypassRequesterID' => true
);

$metadata['redmine'] = array (
        'AssertionConsumerService' => 'https://redmine.bovpg.net/auth/saml/callback',
        'SingleLogoutService' => 'https://redmine.bovpg.net/auth/saml/sls',
        'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',
        'simplesaml.nameidattribute' => 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/name',
        'simplesaml.attributes' => true,
        'bypassRequesterID' => true
);

$metadata['https://tableau.bovpg.net/samlservice/public/sp/metadata?alias=c6604f15-eb0a-4754-bed1-172f34b604c0'] = array (
        'AssertionConsumerService' => 'https://tableau.bovpg.net/samlservice/public/sp/SSO?alias=c6604f15-eb0a-4754-bed1-172f34b604c0',
        'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',
        'simplesaml.nameidattribute' => 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/name',
        'simplesaml.attributes' => true,
        'bypassRequesterID' => true
);

$metadata['sonarqube'] = array (
        'AssertionConsumerService' => 'https://sonarqube.bovpg.net:7443/oauth2/callback/saml',
        'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',
        'simplesaml.nameidattribute' => 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/name',
        'simplesaml.attributes' => true,
        'bypassRequesterID' => true
);

$metadata['https://membres.voyage-prive.com/voyageprive/wp/wp-content/plugins/miniorange-saml-20-single-sign-on/'] = array (
  'entityid' => 'https://membres.voyage-prive.com/voyageprive/wp/wp-content/plugins/miniorange-saml-20-single-sign-on/',
  'metadata-set' => 'saml20-sp-remote',
  'simplesaml.nameidattribute' => 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/name',
  'expire' => 1603929599,
  'AssertionConsumerService' => 
  array (
    0 => 
    array (
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
      'Location' => 'https://membres.voyage-prive.com/voyageprive/wp/',
      'index' => 1,
    ),
  ),
  'SingleLogoutService' => 
  array (
  ),
  'NameIDFormat' => 'urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress',
  'validate.authnrequest' => false,
  'saml20.sign.assertion' => true,
);
