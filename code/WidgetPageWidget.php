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
			'WidgetName'
	);

	public function updateCMSFields(\FieldList $fields) {
		parent::updateCMSFields($fields);

		$field = new TextField('WidgetName', 'Widget Name');
		$fields->add($field);
	}

}
