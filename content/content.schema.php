<?php

	require_once(TOOLKIT . '/class.devkit.php');
	require_once(EXTENSIONS . '/debugdevkit/content/content.debug.php');
	
	class Content_SchemaDevKit_List extends Content_DebugDevKit_Debug {
		public function __construct(){
			parent::__construct();
			$this->_title = __('Schema Validation');
			$this->_query_string = parent::__buildQueryString(array('symphony-page', 'validation'));

			if (!empty($this->_query_string)) {
				$this->_query_string = '&amp;' . General::sanitize($this->_query_string);
			}
		}

		protected function buildJump($wrapper) {
			$schemas = $this->__buildSchemaList($this->__findSchemas());
			if (is_object($schemas)) {
				$wrapper->appendChild($schemas);
			}
		}

		public function buildContent($wrapper) {
			$this->_view = 'result';
			$this->addScriptToHead(URL . '/extensions/schemadevkit/assets/devkit.js', 9126345);
			$this->addStylesheetToHead(URL . '/extensions/schemadevkit/assets/devkit.css', 'screen', 9126346);
			parent::buildContent($wrapper);
		}

		protected function __buildSchemaList($schemas) {
			$list = new XMLElement('ul');
			
			// Always include basic XHTML 1.0 Strict schema as an example
			$filename = $this->__relativePath(EXTENSIONS . '/schemadevkit/assets/XHTML-1.0-Strict.xsd');
			$item = $this->buildJumpItem(basename($filename),"?validate={$filename}",false);
			$list->appendChild($item);

			foreach ($schemas as $u) {
				$filename = $this->__relativePath($u);
				$item = $this->buildJumpItem(basename($filename),"?validate={$filename}",false);
				$list->appendChild($item);
			}
			
			if(empty($schemas)) {
				$item = new XMLElement('li');
				$item->appendChild(new XMLElement('span','No schema definitions found in /workspace/xsd/', array('style'=>'display:inline-block;margin-left: -240px;padding: 5px 10px 5px 260px;')));
				$list->appendChild($item);
			}
			
			return $list;
		}
		
		protected function __findSchemas() {
			$schemas = array();
			if($handle = opendir(WORKSPACE . '/xsd/')) {
			    while (false !== ($file = readdir($handle))) {
					if (preg_match("/.xsd/i", $file)) {
						$schemas[] = WORKSPACE . '/xsd/' . $file;
					}
			    }
			    closedir($handle);
			}
			sort($schemas);
			return $schemas;
		}
		
		private function __relativePath($filename) {
			// remove path to DOCROOT from absolute path. the realpath mess is necessary to cope with Windows paths (realpath always returns C:\Programs\... instead of /Programs/...)
			return str_replace('\\','/',str_replace(realpath(DOCROOT),'',realpath($filename)));
		}
	}

?>
