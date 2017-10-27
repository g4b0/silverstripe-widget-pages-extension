<?php

/**
 * Extension for DataObjects that permit them to have an arbitrary number of WidgetAreas
 */
class WidgetDataObject extends DataExtension
{

    private static $has_one = array();

    public function updateCMSFields(\FieldList $fields)
    {
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

                $config = GridFieldConfig_RelationEditor::create(Config::inst()->get('WidgetDataObject', 'num_per_row'))
                                ->removeComponentsByType("GridFieldAddNewButton")
                                ->addComponent($adder);

		// If dataobject is not yet created, there's no WidgetAreaSort field to check
                if ($this->owner->ID > 0) {
                    $config->addComponent(new GridFieldOrderableRows('WidgetAreaSort'));
                }

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
	public function onBeforeWrite() {
		parent::onBeforeWrite();

		$has_one = $this->owner->has_one();
		// Loop over each WidgetArea
		foreach ($has_one as $name => $class) {
			if ($class == 'WidgetArea') {
				// Create the WidgetArea if it not exist
				$dbName = $name . 'ID';
				$wa = $this->owner->$name();
				if (!$wa->exists()){
					$this->owner->$dbName = $wa->write();
				}
			}
		}
	}

    public function WidgetArea($name = '')
    {
        $retVal = null;

        $has_one = $this->owner->has_one();
        if (isset($has_one[$name])) {
            $retVal = $this->owner->$name()->SortedWidgets();
        }

        return $retVal;
    }
}
