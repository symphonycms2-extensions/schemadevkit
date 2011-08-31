<?php
	require_once(TOOLKIT . '/class.devkit.php');
	require_once(EXTENSIONS . '/schemadevkit/lib/SchemaValidator.php');
	
	class Content_SchemaDevKit_Validate extends DevKit {
		protected $_view = '';

		public function __construct(){
			parent::__construct();
		}

		public function build() {
			if(strlen(trim($_GET['validate'])) > 0) { $this->_view = $_GET['validate']; }
			
			try {
				$validator = new SchemaValidator();
				if($validator->validate($this->_output,(DOCROOT . $this->_view))) {
					$result = json_encode(array('result'=>'success', 'errors' => array()));
				} else {
					$result = json_encode(array('result'=>'failed', 'errors' => $validator->getErrors()));
				}
			} catch(Exception $e) {
				return "b";
			}
			
			return $result;
		}
	}

?>
