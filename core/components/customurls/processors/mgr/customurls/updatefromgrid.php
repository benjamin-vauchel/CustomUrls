<?php
/**
 * @package customurls
 * @subpackage processors
 */
/* parse JSON */
if (empty($scriptProperties['data'])) return $modx->error->failure('Invalid data.');
$_DATA = $modx->fromJSON($scriptProperties['data']);
if (!is_array($_DATA)) return $modx->error->failure('Invalid data.');

/* get obj */
if (empty($_DATA['id'])) return $modx->error->failure($modx->lexicon('customurls.redirect_err_ns'));
$customurl = $modx->getObject('CustomUrl',$_DATA['id']);
if (empty($customurl)) return $modx->error->failure($modx->lexicon('customurls.redirect_err_nf'));

$customurl->fromArray($_DATA);

/* save */
if ($customurl->save() == false) {
    return $modx->error->failure($modx->lexicon('customurls.redirect_err_save'));
}


return $modx->error->success('',$customurl);