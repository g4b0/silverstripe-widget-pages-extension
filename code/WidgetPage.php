<?php

/**
 * Extension for Page that permit them to have an arbitrary number of WidgetAreas
 */
class WidgetPage extends WidgetDataObject {

	private static $db = array(
			'InheritSideBar' => 'Boolean',
	);
	private static $defaults = array(
			'InheritSideBar' => true
	);

	public function updateCMSFields(\FieldList $fields) {

		// Inherit
		$fields->addFieldToTab(
						"Root.Main", new CheckboxField("InheritSideBar", 'Inherit Sidebar From Parent')
		);

		// Check if this page needs its own WidgetArea
		if ($this->owner->InheritSideBar == false) {
			parent::updateCMSFields($fields);
		}
	}

	/**
	 * Creates WidgetArea DataObjects in not aleready done.
	 */
	public function onBeforeWrite() {

		// Check if this page needs its own WidgetArea
		if ($this->owner->InheritSideBar == false) {
			parent::onBeforeWrite();
		}
	}


	public function WidgetArea($name='') {
		$retVal = null;

		// Check if this page needs its own WidgetArea
		if ($this->owner->InheritSideBar == true) {
			// Inherit the WidgetArea from its Parent
			if ($this->owner->ParentID > 0 && $this->owner->getParent()->hasExtension('WidgetPage')) {
				$retVal = $this->owner->getParent()->WidgetArea($name);
			}
		} else {
			$retVal = parent::WidgetArea($name);
		}

		return $retVal;
	}

}
