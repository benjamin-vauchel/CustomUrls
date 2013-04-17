<?php
class CustomUrls {

    public $modx;
    public $config = array();

    public $redirector = null;

    function __construct(modX &$modx,array $config = array()) {
        $this->modx =& $modx;

        // We load CustomUrls package
        $basePath = $this->modx->getOption('customurls.core_path',$config,$this->modx->getOption('core_path').'components/customurls/');
        $assetsUrl = $this->modx->getOption('customurls.assets_url',$config,$this->modx->getOption('assets_url').'components/customurls/');
        $this->config = array_merge(array(
            'basePath' => $basePath,
            'corePath' => $basePath,
            'modelPath' => $basePath.'model/',
            'processorsPath' => $basePath.'processors/',
            'templatesPath' => $basePath.'templates/',
            'chunksPath' => $basePath.'elements/chunks/',
            'jsUrl' => $assetsUrl.'js/',
            'cssUrl' => $assetsUrl.'css/',
            'assetsUrl' => $assetsUrl,
            'connectorUrl' => $assetsUrl.'connector.php',
        ),$config);
        $this->modx->addPackage('customurls',$this->config['modelPath']);


        // We load Redirector service if it exists
        $redirector = $this->modx->getObject('modPlugin',array('name'=>'Redirector'));
        if ($redirector)
        {
            $corePath =  $this->modx->getOption('core_path').'components/redirector/';
            $redirector = $this->modx->getService('redirector','Redirector',$corePath.'model/redirector/');
            $this->redirector = ($redirector instanceof Redirector) ? $redirector : null;
        }
        else
        {
            $this->redirector = null;
        }
    }

    function getCustomUrl($resource)
    {
        $resourceCriterias = array(
            array(
                'criteria_key' => '',
            )
        );
        foreach($resource->toArray() as $key => $value)
        {
            $resourceCriterias[] = array(
                'OR:criteria_key:=' => $key,
                'AND:criteria_value:=' => $value
            );
        }

        $c = $this->modx->newQuery('CustomUrl');
        $c->innerJoin('modUserGroupMember', 'modUserGroupMember', '`member` = '.$this->modx->user->get('id').' AND (usergroup = 0 OR usergroup IS NULL OR usergroup = user_group)');
        $c->select('CustomUrl.*');
        $c->distinct();
        $c->where(array(
            'active'    => 1,
            $resourceCriterias,
            )
        );
        $c->sortby('criteria_key', 'desc');
        $c->limit(1);
        //$c->prepare();

        //$this->modx->log(xPDO::LOG_LEVEL_ERROR, $c->toSQL());

        return $this->modx->getObject('CustomUrl', $c);
    }

    function generateCustomUrl(&$resource, $customUrl)
    {
        // Set current resource and resourceMap for snippets processing (UltimateParent, getResourceField, ...)
        $activeResource = $this->modx->resource;
        $this->modx->resource = &$resource;
        $this->modx->resourceMap = $this->modx->contexts[$resource->get('context_key')]->resourceMap;
        $this->modx->resourceMap[$resource->get('parent')][] = $resource->get('id');

        // Create temporary chunk from custom url pattern
        $chunk = $this->modx->newObject('modChunk');
        $chunk->setCacheable(false);
        $chunk->setContent($customUrl->get('pattern'));

        // Get resource fields ...
        $resourceProperties = $resource->toArray();
        $resourceProperties['alias'] = $resource->cleanAlias($resource->get('pagetitle')); // We manually generate alias to avoid recurisivity

        // ... and TVs
        $tvs = array();
        $pattern = '#\[\[\+tv\.(.*)(:.*)?\]\]#iU';
        preg_match_all($pattern, $customUrl->get('pattern'), $matches);
        if(isset($matches[1]))
        {
            $tmp = array_flip($matches[1]);
            foreach($tmp as $key => $value)
            {
                $tvs['tv.'.$key] = $resource->getTVValue($key);
            }
        }
        $resourceProperties = array_merge($resourceProperties, $tvs);

        //$this->modx->log(modX::LOG_LEVEL_ERROR, print_r($resourceProperties, true));
        //$this->modx->log(modX::LOG_LEVEL_ERROR, print_r($this->modx->getParentIds($resource->get('id')), true));

        // Generate alias
        if(!$customUrl->get('uri'))
        {
            $oldAlias = isset($_REQUEST['alias']) ? $_REQUEST['alias'] : $resource->get('alias');
            $newAlias = $chunk->process($resourceProperties);

            //$this->modx->log(modX::LOG_LEVEL_ERROR, $oldAlias .' != '. $newAlias);

            if(empty($oldAlias) || ($customUrl->get('override') && $oldAlias != $newAlias))
            {
                // We create the redirection
                if(!is_null($this->redirector) && $oldAlias != $newAlias)
                {
                    //$this->modx->log(modX::LOG_LEVEL_ERROR, $oldAlias .' != '. $newAlias);
                    $this->createRedirect($resource);
                }
                // We save the updated resource
                $resource->set('alias', $newAlias);
                $resource->set('uri', '');
                $resource->set('uri_override', false);
                $resource->save();
            }
        }
        // or generate URI
        else
        {
            $parentResource = $this->modx->getObject('modResource', $resource->get('parent'));
            $cuProperties = array();
            if(!empty($parentResource))
            {
                $parentUri = preg_replace('/\.[^.]+$/','',rtrim($parentResource->get('uri'), '/'));
                $cuProperties = array(
                    'cu.parent_uri' => $parentUri,
                );
            }
            else
            {
                $cuProperties = array(
                    'cu.parent_uri' => '',
                );
            }

           //$this->modx->log(modX::LOG_LEVEL_ERROR, 'Parent URI ('.$resource->get('id').') : '.$parentUri);

            $properties = array_merge($cuProperties, $resourceProperties);
            //$this->modx->log(modX::LOG_LEVEL_ERROR, print_r($properties, true));

            $oldUri = isset($_REQUEST['uri']) ? $_REQUEST['uri'] : $resource->get('uri');
            $newUri = ltrim($chunk->process($properties), '/');

            //$this->modx->log(modX::LOG_LEVEL_ERROR, 'newUri : '.$newUri);

            // We add the extension or container suffix
            $isHtml = true;
            $extension = '';
            $workingContext = $this->modx->getContext($resource->get('context_key'));
            $containerSuffix = $workingContext->getOption('container_suffix', '');

            if ($contentType = $this->modx->getObject('modContentType', $resource->get('content_type'))) {
                $extension = $contentType->getExtension();
                $isHtml = (strpos($contentType->get('mime_type'), 'html') !== false);
            }
            if ($resource->get('is_folder') && $isHtml && !empty ($containerSuffix)) {
                $extension = $containerSuffix;
            }

            $newUri .= $extension;
            //$this->modx->log(modX::LOG_LEVEL_ERROR, 'Extension ('.$resource->get('id').') : '.$extension);


            if(empty($oldUri) || ($customUrl->get('override') && $oldUri != $newUri))
            {
                // We create the redirection
                if(!is_null($this->redirector) && $oldUri != $newUri)
                {
                    $this->createRedirect($resource);
                }

                // We save the updated resource
                $resource->set('uri', $newUri);
                $resource->set('uri_override', true);
                $resource->save();
                $this->modx->cacheManager->refresh(); // @TODO : use clearCache function instead
            }
        }

        $this->modx->resource = $activeResource;

        return isset($newAlias) ? $newAlias : $newUri;
    }

    function createRedirect($resource)
    {

        // We specifie context to make relative links with makeUrl
        $this->modx->switchContext($resource->get('context_key'));

        // We create the redirection for the resource ...
        $values = '("'.$this->modx->makeUrl($resource->get('id')).'","[[~'.$resource->get('id').']]", '.($resource->get('deleted') ? 0 : 1).')';

        //  ... and for its children
        $childrenIds = $this->modx->getChildIds($resource->get('id'), 10);
        foreach($childrenIds as $childId)
        {
            $childResource = $this->modx->getObject('modResource', $childId);

            if(is_object($childResource))
            {
                $values .= ',("'.$this->modx->makeUrl($childResource->get('id')).'","[[~'.$childResource->get('id').']]", '.($childResource->get('deleted') ? 0 : 1).')';
            }
        }

        // We use here a MySQL query and not a xPDO query to handle ON DUPLICATE KEY
        $tableName = $this->modx->getTableName('modRedirect');
        $result = $this->modx->query("INSERT INTO {$tableName} (pattern,target,active) VALUES {$values} ON DUPLICATE KEY UPDATE target=VALUES(target)");
        if($result == false)
        {
            $this->modx->log(modX::LOG_LEVEL_DEBUG, '[CustomUrls] Redirects for resource '.$resource->get('id').' and childs havent been generated');
        }
    }

    public function clearCache($resource) {
        $syncSite = $this->getProperty('syncsite',false);
        $clearCache = $this->getProperty('clearCache',false);
        if (!empty($syncSite) || !empty($clearCache)) {
            $contexts = array($this->object->get('context_key'));
            if (!empty($this->oldContext)) {
                $contexts[] = $this->oldContext->get('key');
            }
            $this->modx->cacheManager->refresh(array(
                'db' => array(),
                'auto_publish' => array('contexts' => $contexts),
                'context_settings' => array('contexts' => $contexts),
                'resource' => array('contexts' => $contexts),
            ));
        }
    }

}
