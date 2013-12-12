<?php

/**
 * Widget extension
 */
class WidgetPageWidget extends DataExtension {

	private static $db = array(
			'WidgetName' => 'Varchar'
	);
	private static $belongs_many_many = array(
			'WidgetAreas' => 'WidgetArea'
	);
	private static $summary_fields = array(
			'WidgetType',
			'WidgetName'
	);
	private static $field_labels = array(
			'WidgetType' => 'Widget Type',
			'WidgetName' => 'Widget Name'
	);

	public function updateCMSFields(\FieldList $fields) {
		parent::updateCMSFields($fields);

		$field = new TextField('WidgetName', 'Widget Name');
		$fields->add($field);
	}
	
	public function getWidgetType() {
		return $this->owner->cmsTitle();
	}

}
