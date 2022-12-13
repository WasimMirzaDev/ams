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


Route::get('/', function () {
    return view('auth.login');
});




Auth::routes();
Route::middleware('auth')->group(function () {
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/logout', function(){
  Session::flush();
       Auth::logout();
       return redirect('login');
})->name('auth.logout');
Route::prefix('buildings')->name('buildings.')->group(function () {
    Route::get('/show', [App\Http\Controllers\BuildingController::class, 'index'])->name('show');
    Route::get('/edit/{id?}', [App\Http\Controllers\BuildingController::class, 'edit'])->name('edit');
    Route::get('/delete/{id?}', [App\Http\Controllers\BuildingController::class, 'destroy'])->name('delete');
    Route::post('/save', [App\Http\Controllers\BuildingController::class, 'store'])->name('save');
});


Route::prefix('extras')->name('extras.')->group(function () {
    Route::get('/show', [App\Http\Controllers\ExtrasController::class, 'index'])->name('show');
    Route::get('/edit/{id?}', [App\Http\Controllers\ExtrasController::class, 'edit'])->name('edit');
    Route::get('/delete/{id?}', [App\Http\Controllers\ExtrasController::class, 'destroy'])->name('delete');
    Route::post('/save', [App\Http\Controllers\ExtrasController::class, 'store'])->name('save');
});

Route::prefix('vouchers')->name('vouchers.')->group(function () {
  Route::get('/generate', [App\Http\Controllers\VoucherController::class, 'create']);
    Route::get('/show', [App\Http\Controllers\VoucherController::class, 'index'])->name('show');
    Route::get('/edit/{id?}', [App\Http\Controllers\VoucherController::class, 'edit'])->name('edit');
    Route::get('/delete/{id?}', [App\Http\Controllers\VoucherController::class, 'destroy'])->name('delete');
    Route::post('/save', [App\Http\Controllers\VoucherController::class, 'store'])->name('save');
});

Route::prefix('receivings')->name('receivings.')->group(function () {
    Route::get('/generate', [App\Http\Controllers\ReceivingController::class, 'create']);
    Route::get('/generatev2', [App\Http\Controllers\ReceivingController::class, 'createv2']);
    Route::get('/receivables', [App\Http\Controllers\ReceivingController::class, 'get_receivables']);
    Route::get('/show', [App\Http\Controllers\ReceivingController::class, 'index'])->name('show');
    Route::get('/edit/{id?}', [App\Http\Controllers\ReceivingController::class, 'edit'])->name('edit');
    Route::get('/delete/{id?}', [App\Http\Controllers\ReceivingController::class, 'destroy'])->name('delete');
    Route::post('/save', [App\Http\Controllers\ReceivingController::class, 'store'])->name('save');

    Route::get('/print/{id?}', [App\Http\Controllers\ReceivingController::class, 'print'])->name('print');
});

Route::prefix('ledger')->name('ledger.')->group(function (){
    Route::get('/create', [App\Http\Controllers\LedgerController::class, 'create'])->name('create');
    Route::post('/show', [App\Http\Controllers\LedgerController::class, 'show'])->name('show');
    Route::get('/rent-detail', [App\Http\Controllers\LedgerController::class, 'rent_detail'])->name('rent_detail');
});


Route::prefix('units')->name('units.')->group(function () {
    Route::get('/show', [App\Http\Controllers\UnitController::class, 'index'])->name('show');
    Route::get('/edit/{id?}', [App\Http\Controllers\UnitController::class, 'edit'])->name('edit');
    Route::get('/delete/{id?}', [App\Http\Controllers\UnitController::class, 'destroy'])->name('delete');
    Route::post('/save', [App\Http\Controllers\UnitController::class, 'store'])->name('save');
});

Route::prefix('tenants')->name('tenants.')->group(function () {
    Route::get('/show', [App\Http\Controllers\TenantController::class, 'index'])->name('show');
    Route::get('/edit/{id?}', [App\Http\Controllers\TenantController::class, 'edit'])->name('edit');
    Route::get('/delete/{id?}', [App\Http\Controllers\TenantController::class, 'destroy'])->name('delete');
    Route::post('/save', [App\Http\Controllers\TenantController::class, 'store'])->name('save');
    Route::get('/detail', [App\Http\Controllers\HomeController::class, 'tenant_detail']);
});

Route::prefix('expensetypes')->name('expensetypes.')->group(function () {
    Route::get('/show', [App\Http\Controllers\ExpensetypeController::class, 'index'])->name('show');
    Route::get('/edit/{id?}', [App\Http\Controllers\ExpensetypeController::class, 'edit'])->name('edit');
    Route::get('/delete/{id?}', [App\Http\Controllers\ExpensetypeController::class, 'destroy'])->name('delete');
    Route::post('/save', [App\Http\Controllers\ExpensetypeController::class, 'store'])->name('save');
});

Route::prefix('expenses')->name('expenses.')->group(function () {
    Route::get('/show', [App\Http\Controllers\ExpenseController::class, 'index'])->name('show');
    Route::get('/edit/{id?}', [App\Http\Controllers\ExpenseController::class, 'edit'])->name('edit');
    Route::get('/delete/{id?}', [App\Http\Controllers\ExpenseController::class, 'destroy'])->name('delete');
    Route::post('/save', [App\Http\Controllers\ExpenseController::class, 'store'])->name('save');
});

Route::prefix('pl')->name('pl.')->group(function (){
    Route::get('/show', [App\Http\Controllers\ProfitLossController::class, 'show'])->name('show');
    Route::post('/profit-loss', [App\Http\Controllers\ProfitLossController::class, 'generate'])->name('pl');
});

Route::prefix('rent-roll')->name('rent-roll.')->group(function (){
    Route::get('/generate', [App\Http\Controllers\RentRollController::class, 'generate'])->name('generate');
    Route::post('/show', [App\Http\Controllers\RentRollController::class, 'show'])->name('show');
});

Route::post('/tenants/show', [App\Http\Controllers\TenantController::class, 'add_to_tenant'])->name('add_to_tenant.save');


});



Route::get('login', function () {
    return view('auth.login');
})->name('login');
