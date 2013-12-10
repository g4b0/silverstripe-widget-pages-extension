<?php

/**
* Widget Page
*/
class WidgetPage extends DataExtension {

	private static $has_many = array(
		'Widgets' => 'Widget.WidgetPage'
	);
	
	function updateCMSFields(\FieldList $fields) {
		parent::updateCMSFields($fields);
		
		$adder = new GridFieldAddNewMultiClass();

		if(is_array($this->owner->config()->get("allowed_widgets"))){
			$adder->setClasses($this->owner->config()->get("allowed_widgets"));
		}

		$config = GridFieldConfig_RecordEditor::create()
			->removeComponentsByType("GridFieldAddNewButton")
			->addComponent($adder)
			->addComponent(new GridFieldOrderableRows());

		$fields->addFieldToTab("Root.Main",
			GridField::create('Widgets','Widgets',$this->owner->Widgets())
				->setConfig($config)
			,"Content"
		);
		$fields->removeByName("Content");
		
	}

}


