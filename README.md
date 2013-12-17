# Widgets Pages Extension

Freely inspired to burnbright/silverstripe-widgetpages, it adds Widget's Gridfields to the extended pages or DataObjects.
Widgets Pages Extension is an enhancement for the actual widget module (http://addons.silverstripe.org/add-ons/silverstripe/widgets).

## Introduction

This module is a workaround for an old and annoying widget module bug: https://github.com/silverstripe/silverstripe-widgets/issues/20
It's also a Proof of Concept for an alternative way to manage widget through many_many relationsiph rather than the actual
has_many relationsiph. Extending the widget behaviour with this module you can be able to link existing widgets instead of
rewrite them again. Widgets are sortable inside their WidgetArea. Also DataObjects can have their widges.

## Requirements

 * SilverStripe 3.1

### Installation

Install the module through [composer](http://getcomposer.org):

	composer zirak/widget-pages-extension

Extend the desired pages through the following yaml:

	:::yml
	Page:
	  extensions:
	    - WidgetPage

Define the WidgetAreas in the $has_one relationship, and specify which $allowed_widgets are ok for this page type

	:::php
	class Page extends SiteTree {

		private static $db = array(
		);
		private static $has_one = array(
				'HeaderBar' => 'WidgetArea',
				'FooterBar' => 'WidgetArea'
		);
		private static $allowed_widgets = array(
				'StandardWidget',
				'TwitterWidget',
				'FacebookWidget'
		);
	}

Run a `dev/build`, and adjust your templates to include the resulting WidgetArea view by calling $WidgetArea
function passing it the WidgetArea name as parameter. It will loops through all its widgets.

	:::HTML
	<div class="typography">
		<h2>HeaderBar</h2>
		<% loop $WidgetArea(HeaderBar) %>
			$WidgetHolder
		<% end_loop %>
	</div>

	<div class="typography">
		<h2>FooterBar</h2>
		<% loop $WidgetArea(FooterBar) %>
			$WidgetHolder
		<% end_loop %>
	</div>

To enable WidgetAreas in backend remove the check from "Inherit Sidebar From Parent" and save the page. 
The module will create the WidgetAreas and you're now able to start populating them. If you leave the flag
on the page will search Widget in its Parent since it find the SiteTree root, then it stops and return a void
WidgetArea.

### Installing a widget

See widget module docs (http://addons.silverstripe.org/add-ons/silverstripe/widgets).

### Adding widgets to other pages

You have to do a couple things to get a Widget to work on a page.

* Install the Widgets Pages Extension module, see above.
* Add one or more WidgetArea field to your Page. 
* run dev/build?flush=all

**mysite/code/Page.php**

	:::php
	class Page extends SiteTree {

		private static $db = array(
		);
		// Add 4 WidgetAreas
		private static $has_one = array(
				'HeaderBar' => 'WidgetArea',
				'SidebarBar' => 'WidgetArea'
				'CenterWidgetArea' => 'WidgetArea'
				'FooterBar' => 'WidgetArea'
		);
		private static $allowed_widgets = array(
				'StandardWidget',
				'TwitterWidget',
				'FacebookWidget'
		);
	}

In this case, you need to alter your templates to loop over $WidgetArea(HeaderBar), $WidgetArea(SidebarBar), 
$WidgetArea(CenterWidgetArea) and $WidgetArea(FooterBar).

## Writing your own widgets

See widget module docs (http://addons.silverstripe.org/add-ons/silverstripe/widgets).

### Adding widgets to DataObjects

A DataObject can be renderd as a page, it just need a Route and a Controller. With Widgets Pages Extension also 
DataObjects can have their Widgets. A sample is following:

## The DataObject

**mysite/code/DoSurfboard.php**

	:::php	
	class DoSurfboard extends DataObject {

		private static $db = array(
				'Name' => 'Varchar',
				'Color' => 'Varchar'
		);
		private static $has_one = array(
				'LeftSidebar' => 'WidgetArea'
		);
		private static $summary_fields = array (
				'Name',
				'Color'
		);

	}

## Adding Widgets Pages Extension to DataObject

**mysite/code/_config/extensions.yml**

	:::yml
	---
	Name: my-extensions
	---
	DoSurfboard:
		extensions:
			- WidgetDataObject

## The route

**mysite/code/_config/routes.yml**

	:::yml
	---
	Name: myroutes
	After: framework/routes#coreroutes
	---
	Director:
			rules:
					'surfboard//$ID!': 'ShowSurfboard'

## The Controller

**mysite/code/Controllers/ShowSurfboard.php**

	:::php	
	class ShowSurfboard extends ContentController {

		private static $url_handlers = array('$ID!' => 'handleAction');

		public function index(SS_HTTPRequest $req) {
			$id = $req->param('ID');

			// Use theme from the site config
			if (($config = SiteConfig::current_site_config()) && $config->Theme) {
				SSViewer::set_theme($config->Theme);
			}
			$themedir = $_SERVER['DOCUMENT_ROOT'] . '/' . SSViewer::get_theme_folder() . '/templates/';

			$surfboard = DataObject::get_by_id('DoSurfboard', $id);

			if ($surfboard) {
				//Return our $Data array to use on the page
				$Data = array('DoSurfboard' => $surfboard);
				$this->Customise($Data);
				return $this->renderWith($themedir . 'ShowSurfboard.ss');
			} else {
				//Not found
				return $this->httpError(404, 'Not found');
			}
		}
	}

## The Template

**themes/theme-name/templates/ShowSurfboard.ss**

	<% with DoSurfboard %>
	<h3>$Name</h3>
	<p>$Color</p>
	<div class="typography">
		<h2>LeftSidebar</h2>
		<% loop $WidgetArea(LeftSidebar) %>
			$WidgetHolder
		<% end_loop %>
	</div>
	<% end_with %>

