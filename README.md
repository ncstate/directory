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

You can also use WordPress's native "tagging" system to organize people in a less structured way.  For example, you could place a faculty member in a broader subgroup such as "Animal Behavior" and then provide tags for the specifc animals they study ("mice", "primates", and "birds," for example).

## Displaying Content

Due to the specific nuances and requirements of different sites, this plugin doesn't have a standarized view of directory information.  Rather, it provides a standarized approach of accessing directory information from the CMS.  A specific view (or views) can then be created that meet the needs of an individual site.

The `Person` custom post type can be queried as a stand post type.  You can also query posts based upon subgroup (i.e. custom taxonomy) or custom fields data to display a subset of people.  Default `index.php` and `single.php` are provided within the `views` directory of the plugin.

It is recommended that you place your customized versions of `index.php` and `single.php` within your theme.  This will allow you to update the plugin without overwriting your custom views. The plugin will first check to see if there is a `ncstate-directory/views/index.php` or `ncstate-directory/views/single.php` directory structure and file within the root of your theme.

## Shortcodes

If you do not want to create custom views within your theme, you can use shortcodes to display information from the `People` custom post type.

Information about a single person can be displayed with the following syntax:

```
[person unity_id="csthomp2"]
	[person_info field="first_name"]
	[person_info field="last_name"]
	[person_info field="email"]
[/person]
```

The `field` parameter corresponds to the custom field name of a `Person` post type.

You can display an entire subgroup of people with the folowing syntax:

```
[directory group="faculty"]
```

The HTML output for the `directory` shortcode can be set within the `views/directory_listing.php` file of the plugin.

## Removing People

Due to how the plugin pulls data from the campus directory, you cannot simply delete a person from WordPress.  If someone is simply deleted, they will be added again the next time your website queries the campus directory.  This only applies if the person was added through an OUC that is still listed within `Settings` of the `People` CPT.  If a person was added by specifying their Unity ID, simply remove their Unity ID from `Settings` page.

If you need to delete someone who was pulled through an active OUC, you will set that post to either `Draft` or `Private`.  This will be prevent the person from being listed on your public-facing site.

Removing a Unity ID or OUC on the `Settings` page will not remove the associated people from WordPress.