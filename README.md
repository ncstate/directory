NC State Directory Plugin
=================

This plugin interfaces with `ldap.ncsu.edu` to pull the following information from the campus directory:

* Unity ID
* Email Address
* (Preferred) First Name
* Last Name
* Working Title
* Website
* Telephone Number
* Primary Role (i.e. faculty, staff, student)
* Campus Address

Information is pulled from the campus directory by specifying an OUC(s) and/or Unity ID(s).  The above information is then added to a `Person` custom post type's custom fields.  A person can also be manually added directly within WordPress.

## Working with Content

You can add additional data to the custom post type (CPT) by adding additional custom fields with WordPress's native custom field interface, or you could use a plugin such as Advanced Custom Fields to provide a more polished interface.

By default, your website will query the campus directory to get any changes or updates every 24 hours.  Sometimes it is necessary to manually override data that comes from the campus directory.  This is particularly useful for people with multiple working titles.  The plugin allows you to do that by disabling updates from the campus directory on a person-by-person basis.

## Content Organization

The plugin can group people into different subgroups.  By default, it places people into a `Faculty`, `Staff`, or `Student` subgroup based upon information from the campus directory.  You can add additional subgroups and manually place people within one or more of those subgroups.  This is useful when creating a staff listing based upon office or to place faculty members into a designated research area.

You can also use WordPress's native "tagging" system to organize people in a less structured way.  For example, you could place a faculty member in a broader subgroup such as "Animal Behavior" and the provide tags for the specifc animals they study ("mice", "primates" and "birds," for example).

## Displaying Content

Due to the specific nuances and requirements of different sites, this plugin doesn't have a standarized view of directory information.  Rather, it provides a standarized approach of accessing directory information from the CMS.  A specific view (or views) can then be created that meet the needs of an individual site.

## TODO:
* Mention custom fields format
* Shortcodes
* Querying different types of data/subgroups
* Default views and ways of overriding them
