<?php
class CustomUrlCreateProcessor extends modObjectCreateProcessor {
    public $classKey = 'CustomUrl';
    public $languageTopics = array('customurls:default');
    public $objectType = 'customurls.customurl';
 
    public function beforeSave() 
    {
        $this->setProperty('usergroup',intval($this->getProperty('usergroup',0)));

        $this->setProperty('uri', ($this->getProperty('uri',false) == 'true' ? true : false));
        $this->setProperty('override', ($this->getProperty('override',false) == 'true' ? true : false));
        $this->setProperty('active', ($this->getProperty('active',false) == 'true' ? true : false));
        
        return parent::beforeSave();
    }
}
return 'CustomUrlCreateProcessor';