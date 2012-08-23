<?php
class CustomUrlGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'CustomUrl';
    public $languageTopics = array('customurls:default');
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'DESC';
    public $objectType = 'customurls.customurl';

    /*public function prepareQueryBeforeCount(xPDOQuery $c) 
    {
    	$c->innerJoin('modUserGroup', 'modUserGroup', 'modUserGroup.id = CustomUrl.usergroup');
	    return $c;
	}*/
/*

	public function prepareRow(xPDOObject $object) 
	{
        $customurl = $object->toArray('', false, true);
        //$customurl['downloaded_on'] = ($customurl['downloaded_on'] > 0) ? date('d-m-Y à H:i:s',strtotime($customurl['downloaded_on'])) : '';
        //$customurl['usergroup'] = $object->getOne('modUserGroup')->get('name'). ' ('.$customurl['usergroup'].')';
        return $customurl;
    }*/
}
return 'CustomUrlGetListProcessor';