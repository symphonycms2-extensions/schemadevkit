<?php

	class Extension_SchemaDevKit extends Extension {
	/*-------------------------------------------------------------------------
		Definition:
	-------------------------------------------------------------------------*/

		public static $active = false;

		public function about() {
			return array(
				'name'			=> 'Schema DevKit',
				'version'		=> '0.9.0',
				'release-date'	=> '2011-08-31',
				'author'		=> array(
					'name'			=> 'Remie Bolte',
					'website'		=> 'http://github.com/remie/schemadevkit',
					'email'			=> 'r.bolte@gmail.com'
				)
			);
		}

		public function getSubscribedDelegates() {
			return array(
				array(
					'page'		=> '/frontend/',
					'delegate'	=> 'FrontendDevKitResolve',
					'callback'	=> 'frontendDevKitResolve'
				),
				array(
					'page'		=> '/frontend/',
					'delegate'	=> 'ManipulateDevKitNavigation',
					'callback'	=> 'manipulateDevKitNavigation'
				)
			);
		}

		public function install() {
			if(Symphony::ExtensionManager()->fetchStatus("debugdevkit") != EXTENSION_ENABLED) {
       			Administration::instance()->Page->pageAlert("You need to have the 'debugdevkit' extension installed and enabled.");
				return false;
			}
		}
		
		public function frontendDevKitResolve($context) {
			if (false or isset($_GET['validation'])) {
				require_once(EXTENSIONS . '/schemadevkit/content/content.schema.php');

				$context['devkit'] = new Content_SchemaDevKit_List();
				self::$active = true;
			} else if (false or isset($_GET['validate'])) {
				require_once(EXTENSIONS . '/schemadevkit/content/content.validate.php');

				$context['devkit'] = new Content_SchemaDevKit_Validate();
				self::$active = true;
			}
			
		}

		public function manipulateDevKitNavigation($context) {
			$xml = $context['xml'];
			$item = $xml->createElement('item');
			$item->setAttribute('name', __('Validation'));
			$item->setAttribute('handle', 'validation');
			$item->setAttribute('active', (self::$active ? 'yes' : 'no'));

			$parent = $xml->documentElement;

			if ($parent->hasChildNodes()) {
				$parent->appendChild($item);
			}

			else {
				$xml->documentElement->appendChild($item);
			}
		}
	}

?>