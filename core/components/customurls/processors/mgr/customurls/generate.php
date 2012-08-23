<?php

if (!$modx->hasPermission('save_document')) return $modx->error->failure($modx->lexicon('access_denied'));

// Load CustomUrls service
$corePath =  $modx->getOption('customurls.core_path',$scriptProperties,$modx->getOption('core_path').'components/customurls/');
$customUrls = $modx->getService('customurls','CustomUrls',$corePath.'model/customurls/',$scriptProperties);
if (!($customUrls instanceof CustomUrls)) return '';

function array_keys_multi(array $array)
{
    $keys = array();
 
    foreach ($array as $key => $value) {
        $keys[] = $key;
 
        if (is_array($array[$key])) {
            $keys = array_merge($keys, array_keys_multi($array[$key]));
        }
    }
 
    return $keys;
}

$resourceIds = array_keys_multi($modx->getTree($modx->getChildIds(0,1,array('context' => 'web')),10,array('context' => 'web')));
$resources = $modx->getCollection('modResource', array('id:IN' => $resourceIds));

foreach($resources as &$resource)
{
    // Select the proper URL pattern of the current resource
    $customUrl = $customUrls->getCustomUrl($resource);

    if(!empty($customUrl))
    {
        $customUrl->set('override', true);
        $customUrls->generateCustomUrl($resource, $customUrl);
    }
}

return $modx->error->success();