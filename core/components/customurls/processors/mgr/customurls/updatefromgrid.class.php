<?php
/**
 * Class-based version of updatefromgrid.php: MODX 3 only runs class-based processors.
 * Same logic as the procedural file, which is kept for older installations.
 *
 * @package customurls
 * @subpackage processors
 */
class CustomUrlUpdateFromGridProcessor extends \MODX\Revolution\Processors\Processor {
    public function getLanguageTopics() {
        return array('customurls:default');
    }

    public function process() {
        $data = $this->getProperty('data');
        if (empty($data)) return $this->failure('Invalid data.');
        $data = $this->modx->fromJSON($data);
        if (!is_array($data)) return $this->failure('Invalid data.');

        if (empty($data['id'])) return $this->failure($this->modx->lexicon('customurls.redirect_err_ns'));
        $customurl = $this->modx->getObject('CustomUrl', $data['id']);
        if (empty($customurl)) return $this->failure($this->modx->lexicon('customurls.redirect_err_nf'));

        $customurl->fromArray($data);
        if ($customurl->save() == false) {
            return $this->failure($this->modx->lexicon('customurls.redirect_err_save'));
        }
        return $this->success('', $customurl);
    }
}
return 'CustomUrlUpdateFromGridProcessor';
