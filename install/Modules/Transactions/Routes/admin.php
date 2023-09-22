<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::name('admin.')
    ->prefix('admin')
    ->namespace('Admin')
    ->group(function () {
        Route::middleware(['auth', 'verified'])
            ->name('transactions.')
            ->prefix('transactions')
            ->group(function () {
                Route::get('/reports', 'TransactionReportController@index')
                    ->name('reports.index');
                Route::get('/reports/datatable', 'TransactionReportDatatableController@handle')
                    ->name('reports.datatable');
                Route::get('/reports/datatable/print-preview', 'TransactionReportDatatableController@print')
                    ->name('reports.datatable.print-preview');
                Route::get('/reports/export-excel', 'ExportTransactionReportController@excel')
                    ->name('reports.exports.excel');
                Route::get('/reports/export-pdf', 'ExportTransactionReportController@pdf')
                    ->name('reports.exports.pdf');
                Route::get('/datatable', 'TransactionDatatableController@handle')
                    ->name('datatable');
            });
    });