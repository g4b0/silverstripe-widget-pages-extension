<?php

/**
 * Widget Page extension
 */
class WidgetPage extends DataExtension {

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

		$fields->removeByName("Content");
	}

	public function SortedWidgets() {
		return $this->owner->Widgets()->sort('PageSort');
	}

}
