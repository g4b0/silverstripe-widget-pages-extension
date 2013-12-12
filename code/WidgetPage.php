<?php

/**
 * Widget Page extension
 */
class WidgetPage extends DataExtension {

	private static $db = array(
			'InheritSideBar' => 'Boolean',
	);
	private static $defaults = array(
			'InheritSideBar' => true
	);
	private static $has_one = array(
					//'SideBar' => 'WidgetArea'
	);

	function updateCMSFields(\FieldList $fields) {
		parent::updateCMSFields($fields);

		/*
		 * Inherit
		 */
		$fields->addFieldToTab(
						"Root.Main", new CheckboxField("InheritSideBar", 'Inherit Sidebar From Parent')
		);

		// Check if this page needs its own WidgetArea
		if ($this->owner->InheritSideBar == false) {
			
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
					$config->getComponentByType('GridFieldAddExistingAutocompleter')->setSearchList(DataObject::get('Widget'));
					$config->getComponentByType('GridFieldAddExistingAutocompleter')->setSearchFields(array('WidgetName'))->setResultsFormat('$WidgetName');

					// WidgetArea's Widget GridField
					$gridField = GridField::create($name, $name, $this->owner->$name()->SortedWidgets())->setConfig($config);
					$fields->addFieldToTab("Root.$name", $gridField);
				}
			}
		}
	}

	/**
	 * Creates WidgetArea DataObjects in not aleready done.
	 */
	public function onAfterWrite() {
		parent::onAfterWrite();

		// Check if this page needs its own WidgetArea
		if ($this->owner->InheritSideBar == false) {
			$has_one = $this->owner->has_one();
			// Loop over each WidgetArea
			foreach ($has_one as $name => $class) {
				if ($class == 'WidgetArea') {
					// Create the WidgetArea if it not exist
					if ($this->owner->$name()->ID == 0) {
						$wa = new WidgetArea();
						$wa->write();
						$dbName = $name . 'ID';
						$this->owner->$dbName = $wa->ID;
						$this->owner->writeWithoutVersion();
					}
				}
			}
		}
	}
	
	public function WidgetArea($name='') {
		$retVal = null;
		
		// Check if this page needs its own WidgetArea
		if ($this->owner->InheritSideBar == false) {
			$has_one = $this->owner->has_one();
			if (isset($has_one[$name])) {
				$retVal = $this->owner->$name()->SortedWidgets();
			}
		} else {
			// Inherit the WidgetArea from its Parent
			if ($this->owner->ParentID > 0 && $this->owner->getParent()->hasExtension('WidgetPage')) {
				$retVal = $this->owner->getParent()->WidgetArea($name);
			}
		}
		
		return $retVal;		
	}

}
