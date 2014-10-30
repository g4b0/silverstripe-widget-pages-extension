<?php

/**
 * Extension for widgets/WidgetArea
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

	public function SortedWidgets($filtered=true) {
		$retVal = $this->owner->ManyWidgets()->sort('WidgetAreaSort');
		if ($filtered && $retVal->count() > 0) {
			$retVal = $retVal->filter(array('Enabled' => 1));
		}
		return $retVal;
	}
	
	public function SortedWidgetsOld() {
		if ($this->owner->InheritWidgets == 0) {
			return $this->owner->Widgets()->sort('PageSort');
		} else {
			if ($this->owner->ParentID == 0 || !$this->owner->getParent()->hasExtension('WidgetPage')) {
				return null;
			}
			else {
				return $this->owner->getParent()->SortedWidgets(false);
			}
		}
	}

}
