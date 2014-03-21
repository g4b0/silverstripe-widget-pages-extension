<?php

/**
 * Widget extension
 */
class WidgetPageWidget extends DataExtension {

	private static $db = array(
			'WidgetName' => 'Varchar(255)', // internal name
			'WidgetLabel' => 'Varchar(255)', // front end lablel
			'Disabled' => 'Boolean'
	);
	private static $belongs_many_many = array(
			'WidgetAreas' => 'WidgetArea'
	);
	private static $summary_fields = array(
			'WidgetType',
			'WidgetName',
			'WidgetLabel',
			'Disabled'
	);
	private static $field_labels = array(
			'WidgetType' => 'Widget Type',
			'WidgetName' => 'Widget Name'
	);

	public function updateCMSFields(\FieldList $fields) {
		parent::updateCMSFields($fields);

		$field = new TextField('WidgetLabel', 'Widget Label');
		$fields->add($field);
		
		$field = new TextField('WidgetName', 'Widget Name');
		$fields->add($field);
				
		$field = new CheckboxField('Disabled', 'Disabled');
		$fields->add($field);
	}
	
	public function getWidgetType() {
		return $this->owner->cmsTitle();
	}

}
