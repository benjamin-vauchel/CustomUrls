<?php
class CustomUrlCreateProcessor extends modObjectCreateProcessor {
    public $classKey = 'CustomUrl';
    public $languageTopics = array('customurls:default');
    public $objectType = 'customurls.customurl';
 
    public function beforeSave() 
    {
        $this->object->set('usergroup',intval($this->getProperty('usergroup',0)));
        $this->object->set('uri', ($this->getProperty('uri',false) == 'true' ? true : false));
        $this->object->set('override', ($this->getProperty('override',false) == 'true' ? true : false));
        $this->object->set('active', ($this->getProperty('active',false) == 'true' ? true : false));

        return parent::beforeSave();
    }
}
return 'CustomUrlCreateProcessor';