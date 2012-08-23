<?php
class CustomUrlRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'CustomUrl';
    public $languageTopics = array('customurls:default');
    public $objectType = 'customurls.customurl';
}
return 'CustomUrlRemoveProcessor';