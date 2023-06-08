<?php

namespace parzival42codes\laravelResourcesOptimisation;

use Illuminate\Support\Facades\Route;
use parzival42codes\laravelResourcesOptimisation\Http\Controllers\ResourcesController;

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

Route::get('resources/{fileName}', [
    ResourcesController::class,
    'show',
])
    ->where('fileName', '[a-zA-Z0-9\/\.]+')->name('laravelresourcesoptimisation');
