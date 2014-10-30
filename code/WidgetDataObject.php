<?php

/**
 * Extension for DataObjects that permit them to have an arbitrary number of WidgetAreas
 */
class WidgetDataObject extends DataExtension {

	private static $has_one = array();

	public function updateCMSFields(\FieldList $fields) {
		parent::updateCMSFields($fields);

		// Loop over each WidgetArea
		$has_one = $this->owner->has_one();
		foreach ($has_one as $name => $class) {
			if ($class == 'WidgetArea') {

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
								->addComponent(new GridFieldOrderableRows('WidgetAreaSort'));

				// It's not possible to add existing through GridFieldAddExistingSearchButton since
				// it display by default $Title in its template and we want to search by $WidgetName
				$config->getComponentByType('GridFieldAddExistingAutocompleter')->setSearchFields(array('WidgetName'))->setResultsFormat('$WidgetName');

				// WidgetArea's Widget GridField
				$gridField = GridField::create($name, $name, $this->owner->$name()->SortedWidgets(false))->setConfig($config);
				$fields->addFieldToTab("Root.$name", $gridField);
			}
		}
	}

	/**
	 * Creates WidgetArea DataObjects in not aleready done.
	 */
	public function onAfterWrite() {
		parent::onAfterWrite();

		$has_one = $this->owner->has_one();
		// Loop over each WidgetArea
		foreach ($has_one as $name => $class) {
			if ($class == 'WidgetArea') {
				// Create the WidgetArea if it not exist
				$dbName = $name . 'ID';
				// Can't use $this->owner->$name()->ID since it doesn't
				// work with DataObjects, it works just with Pages. SS bug?
				if ($this->owner->$dbName == 0) {
					$wa = new WidgetArea();
					$wa->write();
					$this->owner->$dbName = $wa->ID;
					if ($this->owner->hasExtension('Versioned')) {
						$this->owner->writeWithoutVersion();
					} else {
						$dbg = $this->owner->$name();
						$this->owner->write();
					}
				}
			}
		}
	}

	public function WidgetArea($name = '') {
		$retVal = null;

		$has_one = $this->owner->has_one();
		if (isset($has_one[$name])) {
			$retVal = $this->owner->$name()->SortedWidgets();
		}

		return $retVal;
	}

}
