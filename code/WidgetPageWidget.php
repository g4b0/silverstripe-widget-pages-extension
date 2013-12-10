<?php

class WidgetPageWidget extends DataExtension {
	
	private static $db = array(
			'WidgetName' => 'Varchar'
	);
//	private static $has_one = array(
//			'WidgetPage' => 'WidgetPage'
//	);
	private static $belongs_many_many = array(
			'WidgetPage' => 'WidgetPage'
	);
	private static $summary_fields = array(
			'WidgetName'
	);
	
	public function updateCMSFields(\FieldList $fields) {
		parent::updateCMSFields($fields);
		
		$field = new TextField('WidgetName', 'Widget Name');
		$fields->add($field);		
	}
	
	public function Title() {
		//return $this->owner->WidgetName;
		return "BBB";
	}

}
