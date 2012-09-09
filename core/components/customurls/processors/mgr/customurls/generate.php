<?php

if (!$modx->hasPermission('save_document')) return $modx->error->failure($modx->lexicon('access_denied'));

// Load CustomUrls service
$corePath =  $modx->getOption('customurls.core_path',$scriptProperties,$modx->getOption('core_path').'components/customurls/');
$customUrls = $modx->getService('customurls','CustomUrls',$corePath.'model/customurls/',$scriptProperties);
if (!($customUrls instanceof CustomUrls)) return '';

// Get resources id
$resourceIds = $modx->getChildIds(0,10,array('context' => 'web'));

foreach($resourceIds as $resourceId)
{
    // Select resource
    $resource = $modx->getObject('modResource', array('id' => $resourceId));

    if(is_object($resource))
    {
        // Select the proper URL pattern of the current resource
        $customUrl = $customUrls->getCustomUrl($resource);

        //$this->modx->log(modX::LOG_LEVEL_ERROR, 'Generate URL for '.$resource->get('pagetitle').' - '.$resource->get('id'));

        // Generate resource URL
        if(!empty($customUrl))
        {
            $customUrl->set('override', true);
            $customUrls->generateCustomUrl($resource, $customUrl);
        }
    }
}

return $modx->error->success();