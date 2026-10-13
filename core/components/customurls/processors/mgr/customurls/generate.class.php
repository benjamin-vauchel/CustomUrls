<?php
/**
 * Class-based version of generate.php: MODX 3 only runs class-based processors.
 * Same logic as the procedural file, which is kept for older installations.
 *
 * @package customurls
 * @subpackage processors
 */
class CustomUrlsGenerateProcessor extends \MODX\Revolution\Processors\Processor {
    public function checkPermissions() {
        return $this->modx->hasPermission('save_document');
    }

    public function process() {
        // Load CustomUrls service
        $corePath = $this->modx->getOption('customurls.core_path', $this->properties, $this->modx->getOption('core_path') . 'components/customurls/');
        $customUrls = $this->modx->getService('customurls', 'CustomUrls', $corePath . 'model/customurls/', $this->properties);
        if (!($customUrls instanceof CustomUrls)) return $this->failure();

        // Get resources id
        $resourceIds = $this->modx->getChildIds(0, 10, array('context' => 'web'));

        foreach ($resourceIds as $resourceId) {
            $resource = $this->modx->getObject('modResource', array('id' => $resourceId));
            if (is_object($resource)) {
                // Select the proper URL pattern of the current resource
                $customUrl = $customUrls->getCustomUrl($resource);
                // Generate resource URL
                if (!empty($customUrl)) {
                    $customUrl->set('override', true);
                    $customUrls->generateCustomUrl($resource, $customUrl);
                }
            }
        }
        return $this->success();
    }
}
return 'CustomUrlsGenerateProcessor';
