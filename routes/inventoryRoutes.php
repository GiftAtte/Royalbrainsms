<?php

use Illuminate\Support\Facades\Route;


Route::get('inventory/category','API\Inventory\CategoryController@index');
Route::post('inventory/category','API\Inventory\CategoryController@createCategory');
Route::put('inventory/category','API\Inventory\CategoryController@update');
Route::delete('inventory/category/{id}','API\Inventory\CategoryController@delete');

Route::get('inventory/products','API\Inventory\ProductController@index');
Route::post('inventory/products','API\Inventory\ProductController@createProduct');
Route::put('inventory/products','API\Inventory\ProductController@update');
Route::delete('inventory/products/{id}','API\Inventory\ProductController@delete');
Route::post('inventory/products/purchases','API\Inventory\ProductController@purchasedProduct');
Route::delete('inventory/products/purchases/{id}','API\Inventory\ProductController@deletePurchased');
Route::get('inventory/products/purchases','API\Inventory\ProductController@getPurchases');
Route::post('inventory/products/price','API\Inventory\PriceController@createPrice');
Route::delete('inventory/products/price/{id}','API\Inventory\PriceController@delete');
Route::get('inventory/products/price','API\Inventory\PriceController@index');
Route::put('inventory/products/price','API\Inventory\PriceController@update');
Route::get('inventory/products/stock','API\Inventory\StockController@getAllStock');
Route::post('inventory/products/sell','API\Inventory\SalesController@createSales');

Route::get('inventory/items','API\Inventory\ItemController@index');
Route::post('inventory/items','API\Inventory\ItemController@createItem');
Route::put('inventory/items','API\Inventory\ItemController@update');
Route::delete('inventory/items/{id}','API\Inventory\ItemController@delete');

Route::get('inventory/stockItems','API\Inventory\StockItemController@index');
Route::post('inventory/stockItems','API\Inventory\StockItemController@store');
Route::put('inventory/stockItems','API\Inventory\StockItemController@update');
Route::delete('inventory/stockItems/{id}','API\Inventory\StockItemController@destroy');


Route::post('inventory/issueItems','API\Inventory\ItemController@issueItems');
Route::get('inventory/issueItems','API\Inventory\ItemController@getIssueItems');
Route::delete('inventory/issueItems/{id}','API\Inventory\ItemController@deleteIssueItems');
Route::put('inventory/issueItems/','API\Inventory\ItemController@updateIssueItems');
Route::put('inventory/returnItems/','API\Inventory\ItemController@returnItems');
Route::get('inventory/availableItems/{id}','API\Inventory\StockItemsController@getCurrentStock');


Route::get('inventory/sales','API\Inventory\SalesController@index');
Route::post('inventory/sales','API\Inventory\SalesController@createSales');
Route::put('inventory/sales','API\Inventory\SalesController@update');
Route::delete('inventory/sales/{id}','API\Inventory\SalesController@delete');
Route::get('inventory/sales/{id}','API\Inventory\SalesController@salesDetails');

Route::get('inventory/stock','API\Inventory\StockController@index');
Route::post('inventory/stock','API\Inventory\StockController@createStock');
Route::put('inventory/stock','API\Inventory\StockController@update');
Route::delete('inventory/stock/{id}','API\Inventory\StockController@delete');

Route::get('inventory/suppliers','API\Inventory\SuppliersController@index');
Route::post('inventory/suppliers','API\Inventory\SuppliersController@createSupplier');
Route::put('inventory/suppliers','API\Inventory\SuppliersController@update');
Route::delete('inventory/suppliers/{id}','API\Inventory\SuppliersController@delete');


Route::get('inventory/pettycash/{group_id?}','API\Inventory\PettycashController@index');
Route::post('inventory/pettycash','API\Inventory\PettycashController@store');
Route::put('inventory/pettycash','API\Inventory\PettycashController@update');
Route::delete('inventory/pettycash/{id}','API\Inventory\PettycashController@delete');
Route::get('inventory/pettyCashBalance/{group_id?}','API\Inventory\PettycashController@getPettyBalance');

Route::get('inventory/expenses/{group_id?}','API\Inventory\ExpenseController@index');
Route::post('inventory/pettycash/expenses','API\Inventory\ExpenseController@store');
Route::put('inventory/pettycash/expenses','API\Inventory\ExpenseController@update');
Route::delete('inventory/pettycash/expenses/{id}','API\Inventory\ExpenseController@delete');

Route::get('inventory/groups','API\Inventory\PettyGroupController@index');
Route::get('inventory/pettycash/groups/all','API\Inventory\PettyGroupController@getAllGroup');
Route::post('inventory/pettycash/groups','API\Inventory\PettyGroupController@store');
Route::put('inventory/pettycash/groups','API\Inventory\PettyGroupController@update');
Route::delete('inventory/pettycash/groups/{id}','API\Inventory\PettyGroupController@destroy');
