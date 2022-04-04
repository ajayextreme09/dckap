<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/cache-clear', function () {
	$exitCode = Artisan::call('cache:clear');
	$exitCode2 = Artisan::call('route:clear');
	$exitCode3 = Artisan::call('view:clear');
	$exitCode4 = Artisan::call('config:cache');
	return 'All cache cleared';
});

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', 'HomeController@index');
Route::get('/module/test_cases', 'HomeController@moduleTestCases');

Route::get('modules/list', 'HomeController@modulesList')->name('modules.list');
Route::post('module/save', 'HomeController@moduleSave')->name('module.save');
Route::post('module/update', 'HomeController@moduleUpdate')->name('module.update');
Route::post('modules/delete', 'HomeController@modulesDelete')->name('modules.delete');
Route::get('module/details/{id}', 'HomeController@moduleDetails')->name('module.details');

Route::get('test_cases/{module_id}', 'TestCasesController@testCases')->name('test.cases');
Route::post('testcase/save', 'TestCasesController@testCaseSave')->name('test.case.save');
Route::get('testcase/details/{id}', 'TestCasesController@getTestCase')->name('get.test.case');
Route::delete('testcase/delete/{id}', 'TestCasesController@testCaseDelete')->name('test.case.delete');