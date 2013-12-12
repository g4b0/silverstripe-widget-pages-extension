silverstripe-widget-pages-extension
===================================

# Widgets Pages Extension

Widgets Pages Extension is an enhancement for the actual widget module (http://addons.silverstripe.org/add-ons/silverstripe/widgets).
Freely inspired to burnbright/silverstripe-widgetpages, it adds Widget's Gridfields to the extended pages.

## Introduction

This module is a workaround for an old and annoying widget module: https://github.com/silverstripe/silverstripe-widgets/issues/20
It's also a Proof of Concept for an alternative way to manage widget through many_many relationsiph rather than the actual
has_many relationsiph. Extending the widget behaviour with this module you can be able to link existing widgets instead of
rewrite them again. Widgets are sortable inside their WidgetArea.

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
