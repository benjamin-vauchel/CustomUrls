<?php

class CustomUrlsTests extends PHPUnit_Framework_TestCase{

	public $modx;
	public $customUrls;

	public function setUp()
	{
		require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/config.core.php';
		require_once MODX_CORE_PATH.'config/'.MODX_CONFIG_KEY.'.inc.php';
		require_once MODX_CORE_PATH.'model/modx/modx.class.php';
		global $modx;
		$this->modx = new modX();
		$this->modx->initialize('mgr');//web
		
		// Load CustomUrls service
		$corePath =  $this->modx->getOption('core_path').'components/customurls/';
		$this->customUrls = $this->modx->getService('customurls','CustomUrls',$corePath.'model/customurls/');

		// We load CustomUrls package
        $basePath = $this->modx->getOption('core_path').'components/customurls/';
        $this->modx->addPackage('customurls',$basePath.'model/');
	}

	public function testIsServiceLoaded()
	{
		$this->assertEquals(true, $this->customUrls instanceof CustomUrls);
	}

	public function testGeneratedAlias()
	{
		$timestamp = time();
		$resource = $this->modx->newObject('modResource', array(
			'id' => 1,
			'pagetitle' => 'My pagetitle',
			'longtitle' => 'My longtitle',
			'introtext' => 'My intro text',
			'publishedon' => $timestamp,
			'createdon' => $timestamp,
			'editedon' => $timestamp,
			)
		);

		// Test simple dummy text
		$customUrl = $this->modx->newObject('customUrl', array(
			'pattern' => 'my-dummy-text-alias'
			)
		);
		$this->assertEquals('my-dummy-text-alias', $this->customUrls->generateCustomUrl($resource, $customUrl));

		// Test resource placeholders
		$customUrl = $this->modx->newObject('customUrl', array(
			'pattern' => '[[+alias]]'
			)
		);
		$this->assertEquals('my-pagetitle', $this->customUrls->generateCustomUrl($resource, $customUrl));

		// Test TV placeholders
		/*$customUrl = $this->modx->newObject('customUrl', array(
			'pattern' => '[[+tv.my_tv]]'
			)
		);
		$this->assertEquals('my-pagetitle', $this->customUrls->generateCustomUrl($resource, $customUrl));*/

		// Test snippet
		$snippet = $this->modx->newObject('modSnippet', array(
			'name'		=> 'snippet',
			'content' 	=> '<?php return "My snippet return value";',
			)
		);
		$snippet->save();
		$customUrl = $this->modx->newObject('customUrl', array(
			'pattern' => '[[+snippet]]'
			)
		);
		$this->assertEquals('my-snippet-return-value', $this->customUrls->generateCustomUrl($resource, $customUrl));

		// Test output filter
		$customUrl = $this->modx->newObject('customUrl', array(
			'pattern' => '[[+publishedon:strtotime:date=`%Y-%m-%d`]]'
			)
		);
		$this->assertEquals(strftime($timestamp, '%Y-%m-%d'), $this->customUrls->generateCustomUrl($resource, $customUrl));
	}

	// Test translit

	// Test redirections

	// Test generation

	// Test hierarcical URL

	// Test plugin
}