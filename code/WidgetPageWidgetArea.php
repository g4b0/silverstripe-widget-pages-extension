<?php

/**
 * Widget extension
 */
class WidgetPageWidgetArea extends DataExtension {

	private static $many_many = array(
			'ManyWidgets' => 'Widget'
	);
	private static $many_many_extraFields = array(
			'ManyWidgets' => array(
					'WidgetAreaSort' => 'Int'
			)
	);

	public function SortedWidgets() {
		return $this->owner->ManyWidgets()->sort('WidgetAreaSort');
	}
	
	public function SortedWidgetsOld() {
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
