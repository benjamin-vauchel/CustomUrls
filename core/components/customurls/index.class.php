<?php
require_once dirname(__FILE__) . '/model/customurls/customurls.class.php';
abstract class CustomUrlsManagerController extends modExtraManagerController {
    public $customurls;
    public function initialize() {
        $this->customurls = new CustomUrls($this->modx);
 
        //$this->addCss($this->customurls->config['cssUrl'].'mgr.css');
        $this->addJavascript($this->customurls->config['jsUrl'].'mgr/customurls.js');
        $this->addHtml('<script type="text/javascript">
        Ext.onReady(function() {
            CustomUrls.config = '.$this->modx->toJSON($this->customurls->config).';
        });
        </script>');
        return parent::initialize();
    }
    public function getLanguageTopics() {
        return array('customurls:default');
    }
    public function checkPermissions() { return true;}
}
class IndexManagerController extends CustomUrlsManagerController {
    public static function getDefaultController() { return 'home'; }
}
