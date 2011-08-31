<?php
/*
 Original Schema Validation code created by Alistair Kearney
 Taken from http://sandbox.pointybeard.com/validate-xml/ and http://sandbox.pointybeard.com/validate-xml/index.phps
 Modified to be used in the Symphony Schema DevKit extension by Remie Bolte
 */

	Class SchemaValidator {
		private $_errors;
		
		public function __construct() {
			$this->_errors = array();
		}

		public function getErrors() {
			return $this->_errors;
		}

		public function validate($xml,$schema) {
			// Enable user error handling
			libxml_use_internal_errors(true);

			try {
				if(empty($xml)) {
					throw new Exception("You provided an empty XML string");
				}
				
				$doc = DOMDocument::loadXML($xml);
				if(!($doc instanceof DOMDocument)){
					$this->_errors = libxml_get_errors();
				}
	
				if(!@$doc->schemaValidate($schema)){
			        $this->_errors = libxml_get_errors();
				}
			} catch (Exception $e) {
				$this->_errors = array(0 => array('message'=>$e->getMessage()));
			}

			// Disable user error handling & Error Cleanup
			libxml_use_internal_errors(false);
			libxml_clear_errors();

			// If there are no errors, assume that it is all OK!
			return empty($this->_errors);
    	}
    }
?>