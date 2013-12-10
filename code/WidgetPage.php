<?php

/**
 * Widget Page extension
 */
class WidgetPage extends DataExtension {

	private static $db = array(
			'InheritWidgets' => 'Boolean'
	);
	private static $defaults = array(
			'InheritWidgets' => 1
	);
	private static $many_many = array(
			'Widgets' => 'Widget'
	);
	private static $many_many_extraFields = array(
			'Widgets' => array(
					'PageSort' => 'Int'
			)
	);

	function updateCMSFields(\FieldList $fields) {
		parent::updateCMSFields($fields);

		/*
		 * Inherith
		 */
		$inherit = new CheckboxField('InheritWidgets', 'Inherit Widgets from Parents');
		$fields->addFieldToTab("Root.Main", $inherit, "Content");
		
		if ($this->owner->InheritWidgets == 0) {
		
			/*
			 * Widget gridfield 
			 */
			$adder = new GridFieldAddNewMultiClass();

			// Allowed classes
			$allowed = $this->owner->config()->get("allowed_widgets");
			if (is_array($allowed)) {
				// Filter classes for creating new ones
				$adder->setClasses($allowed);
			}

			$config = GridFieldConfig_RelationEditor::create()
							->removeComponentsByType("GridFieldAddNewButton")
							->addComponent($adder)
							->addComponent(new GridFieldOrderableRows('PageSort'));

			// It's not possible to add existing through GridFieldAddExistingSearchButton since
			// it display by default $Title in its template and we want to search by $WidgetName
			$config->getComponentByType('GridFieldAddExistingAutocompleter')->setSearchFields(array('WidgetName'))->setResultsFormat('$WidgetName');

			$gridField = GridField::create('Widgets', 'Widgets', $this->owner->Widgets())->setConfig($config);
			$fields->addFieldToTab("Root.Main", $gridField, "Content");
		}

		$fields->removeByName("Content");
	}

	
	public function SortedWidgets() {
		if ($this->owner->InheritWidgets == 0) {
			return $this->owner->Widgets()->sort('PageSort');
		} else {
			if ($this->owner->ParentID == 0 || !$this->owner->getParent()->hasExtension('WidgetPage')) {
				return null;
			}
			else {
				return $this->owner->getParent()->SortedWidgets();
			}
		}
	}

}
