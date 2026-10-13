<?php
// MODX 2.3+/3 load this controller directly, without index.class.php (which defines the base class)
require_once dirname(__DIR__) . '/index.class.php';
class CustomUrlsHomeManagerController extends CustomUrlsManagerController {
    public function process(array $scriptProperties = array()) {
 
    }
    public function getPageTitle() { return $this->modx->lexicon('customurls'); }
    public function loadCustomCssJs() {
        $this->addJavascript($this->customurls->config['jsUrl'].'mgr/widgets/customurls.grid.js');
        $this->addJavascript($this->customurls->config['jsUrl'].'mgr/widgets/home.panel.js');
        $this->addLastJavascript($this->customurls->config['jsUrl'].'mgr/sections/index.js');
    }
    public function getTemplateFile() { return $this->customurls->config['templatesPath'].'home.tpl'; }
}
