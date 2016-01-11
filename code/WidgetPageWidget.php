<?php

/**
 * Extension for widgets/Widget
 */
class WidgetPageWidget extends DataExtension
{

    private static $db = array(
            'WidgetName' => 'Varchar(255)', // internal name
            'WidgetLabel' => 'Varchar(255)', // front end lablel
    );
    private static $belongs_many_many = array(
            'WidgetAreas' => 'WidgetArea'
    );
    private static $summary_fields = array(
            'WidgetType',
            'WidgetName',
            'WidgetLabel',
            'Enabled'
    );
    private static $field_labels = array(
            'WidgetType' => 'Widget Type',
            'WidgetName' => 'Widget Name'
    );

    public function updateCMSFields(\FieldList $fields)
    {
        parent::updateCMSFields($fields);

        $fields->removeByName('Sort');
        $fields->removeByName('ParentID');
        
        $field = new TextField('WidgetLabel', 'Widget Label');
        $fields->add($field);
        
        $field = new TextField('WidgetName', 'Widget Name');
        $fields->add($field);
    }
    
    public function getWidgetType()
    {
        return $this->owner->cmsTitle();
    }
}
