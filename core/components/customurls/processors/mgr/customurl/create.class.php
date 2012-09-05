<?php
class CustomUrlCreateProcessor extends modObjectCreateProcessor {
    public $classKey = 'CustomUrl';
    public $languageTopics = array('customurls:default');
    public $objectType = 'customurls.customurl';

    public function beforeSet()
    {
        $this->setCheckbox('uri',false);
        $this->setCheckbox('override',false);
        $this->setCheckbox('active',false);
        return parent::beforeSet();
    }
}
return 'CustomUrlCreateProcessor';