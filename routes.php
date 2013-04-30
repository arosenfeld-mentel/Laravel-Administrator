<?php

/**
 * Filters
 */
require __DIR__.'/filters.php';


/**
 * View Composers
 */
require __DIR__.'/viewComposers.php';

$secure = Request::secure();


/**
 * Routes
 */

Route::get('(:bundle)', array(
	'as' => 'admin_dashboard',
	'uses' => 'administrator::admin@dashboard',
	'before' => 'validate_admin|add_assets', //only needs to validate admin and add assets
));

//The route group for all other requests needs to validate admin, model, and add assets
Route::group(array('before' => 'validate_admin|validate_model|add_assets'), function() use ($secure)
{
	//Model Index
	Route::get('(:bundle)/(:any)', array(
		'as' => 'admin_index',
		'uses' => 'administrator::admin@index',
		'https' => $secure,
	));

	//Get Item
	Route::get('(:bundle)/(:any)/(:num)', array(
		'as' => 'admin_get_item',
		'uses' => 'administrator::admin@item',
		'https' => $secure,
	));

	//New Item
	Route::get('(:bundle)/(:any)/new', array(
		'as' => 'admin_new_item',
		'uses' => 'administrator::admin@item',
		'https' => $secure,
	));

	//Update a relationship's items with constraints
	Route::post('(:bundle)/(:any)/update_options', array(
		'as' => 'admin_update_options',
		'uses' => 'administrator::admin@update_options',
		'https' => $secure,
	));

	//Display an image or file field's image or file
	Route::get('(:bundle)/(:any)/file', array(
		'as' => 'admin_display_file',
		'uses' => 'administrator::admin@display_file',
		'https' => $secure,
	));
});

//CSRF protection in forms
Route::group(array('before' => 'validate_admin|validate_model|csrf'), function() use ($secure)
{
	//Save Item
	Route::post('(:bundle)/(:any)/(:num?)/save', array(
		'as' => 'admin_save_item',
		'uses' => 'administrator::admin@save',
		'https' => $secure,
	));

	//Delete Item
	Route::post('(:bundle)/(:any)/(:num)/delete', array(
		'as' => 'admin_delete_item',
		'uses' => 'administrator::admin@delete',
		'https' => $secure,
	));

	//Get results
	Route::post('(:bundle)/(:any)/results', array(
		'as' => 'admin_get_results',
		'uses' => 'administrator::admin@results',
		'https' => $secure,
	));

	//Custom Action
	Route::post('(:bundle)/(:any)/(:num)/custom_action', array(
		'as' => 'admin_custom_action',
		'uses' => 'administrator::admin@custom_action',
		'https' => $secure,
	));
});

//Standard validation without csrf
Route::group(array('before' => 'validate_admin|validate_model|disable_profiler'), function() use ($secure)
{
	//File Uploads
	Route::post('(:bundle)/(:any)/(:any)/file_upload', array(
		'as' => 'admin_file_upload',
		'uses' => 'administrator::admin@file_upload',
		'https' => $secure,
	));

	//Updating Rows Per Page
	Route::post('(:bundle)/(:any)/rows_per_page', array(
		'as' => 'admin_rows_per_page',
		'uses' => 'administrator::admin@rows_per_page',
		'https' => $secure,
	));
});

//Settings Pages
Route::get('(:bundle)/settings/(:any)', array(
	'as' => 'admin_settings',
	'before' => 'validate_admin|validate_settings|add_assets',
	'uses' => 'administrator::admin@settings',
	'https' => $secure,
));

//Settings POSTs
Route::group(array('before' => 'validate_admin|validate_settings|csrf'), function() use ($secure)
{
	//Save Item
	Route::post('(:bundle)/settings/(:any)/save', array(
		'as' => 'admin_settings_save',
		'uses' => 'administrator::admin@settings_save',
		'https' => $secure,
	));

	//Custom Action
	Route::post('(:bundle)/settings/(:any)/custom_action', array(
		'as' => 'admin_settings_custom_action',
		'uses' => 'administrator::admin@custom_action',
		'https' => $secure,
	));
});

//Settings file upload
Route::post('(:bundle)/settings/(:any)/(:any)/file_upload', array(
	'before' => 'validate_admin|validate_settings|disable_profiler',
	'as' => 'admin_settings_file_upload',
	'uses' => 'administrator::admin@file_upload',
	'https' => $secure,
));

//Display a settings file
Route::get('(:bundle)/settings/(:any)/file', array(
	'before' => 'validate_admin|validate_settings',
	'as' => 'admin_settings_display_file',
	'uses' => 'administrator::admin@display_file',
	'https' => $secure,
));