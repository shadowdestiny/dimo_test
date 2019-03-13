<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'middleware' => 'api',
    'prefix'     => 'auth',
], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('recovery', 'AuthController@recovery');
    Route::post('confirm_password', 'AuthController@confirm_password');
    Route::get('me', function () {
        return response()->json(auth()->user());
    });
});

$router->middleware(['api', 'json'])
       ->namespace('API')
       ->group(function ($router) {
           $router->get('test', function () {
               dd(\Carbon\Carbon::now()->subYears(1));
           });
           $router->group(['prefix' => 'code'], function ($router) {
               $router->post('/send', 'AuthyController@send')
                      ->name('code.send');
               $router->post('/verify', 'AuthyController@verify')
                      ->name('code.verify');
           });
           $router->get('home/{client}', 'HomeController@index')->name('home');
           $router->get('dispatch', 'HomeController@send')->name('home.send');
           $router->group(['prefix' => 'clients'], function ($router) {
               $router->get('{client}', 'ClientsController@show')
                      ->name('clients.show');
               $router->get('clients/{type}/{status}', 'ClientsController@clients')
                   ->name('clients.status');
               $router->get('detailsClient/{client_info}', 'ClientsController@detailsClient')
                   ->name('clients.status');
               $router->post('{client}', 'ClientsController@store')
                      ->name('clients.store');
               $router->post('record/save', 'ClientsController@record')
                   ->name('clients.record');
               $router->patch('{client}', 'ClientsController@update')
                      ->name('clients.update');
               $router->get('{client}/loans', 'ClientsController@loans')
                      ->name('clients.loans');
               $router->get('{client}/wallets', 'ClientsController@wallets')
                      ->name('clients.wallets');
               $router->get('{client}/profile', 'ClientsController@profile')
                      ->name('clients.profile');
               $router->post('{client}/data', 'ClientsController@data')
                      ->name('clients.data');
               $router->post('{client}/token', 'ClientsController@token')->name('clients.token');
               $router->get('{client}/notification', 'ClientsController@notification')->name('clients.notification');
           });
           $router->group(['prefix' => 'steps'], function ($router) {
               $router->get('{step}', 'StepsController@show')
                      ->name('steps.show');
               $router->get('steps/{uuid}', 'StepsController@steps')
                   ->name('steps.show');
               $router->post('steps/add', 'StepsController@add');
               $router->post('steps/drop', 'StepsController@drop');
               $router->post('steps/edit', 'StepsController@update');
               $router->get('steps_list/list', 'StepsController@stepsList')
                   ->name('steps.list');
               $router->get('client_steps/{uuid}', 'StepsController@getClient');
           });

           $router->group(['prefix' => 'questions'], function ($router) {
               $router->get('list/{step_uuid}', 'QuestionController@questionBySteep');
               $router->post('update', 'QuestionController@update');
               $router->post('delete', 'QuestionController@drop');
               $router->post('add', 'QuestionController@add');
           });

           $router->group(['prefix' => 'settings'], function ($router) {
               $router->get('list', 'SettingController@setting');
               $router->post('update', 'SettingController@update');
           });

           $router->group(['prefix' => 'level_amounts'], function ($router) {
               $router->get('list/{level_uuid}', 'LevelsAmountsController@listAmount');
               $router->post('update', 'LevelsAmountsController@update');
           });

           $router->group(['prefix' => 'answers'], function ($router) {
               $router->post('/', 'AnswersController@store')
                      ->name('answers.store');
               $router->get('load/answers', 'AnswersController@answers')
                   ->name('answers.store');
               $router->patch('/', 'AnswersController@update')
                      ->name('answers.update');
           });
           $router->group(['prefix' => 'loans'], function ($router) {
               $router->post('/', 'LoansController@store')
                      ->name('loans.store');
               $router->get('{loan}', 'LoansController@show')
                      ->name('loans.show');
               $router->get('loanDetail/{loan_id}', 'LoansController@loanDetail')
                      ->name('loanDetail.show');

               $router->get('counts/{type}', 'LoansController@getAllLoanCount')
                   ->name('loans.counts');
               $router->get('loans/{type}', 'LoansController@getAllLoan')
                   ->name('loans.all');
               $router->get('{loan}/accept', 'LoansController@accept')
                      ->name('loans.accept');
               $router->post('status', 'LoansController@status')
                      ->name('loans.status');
               $router->post('check', 'LoansController@check')
                      ->name('loans.check');
               $router->post('{loan}/detail', 'LoansController@details')
                      ->name('loans.details');
           });
           $router->group(['prefix' => 'wallets'], function ($router) {
               $router->delete('{wallet}', 'ClientWalletsController@destroy')
                      ->name('wallets.destroy');
           });
           $router->group(['prefix' => 'levels'], function ($router) {
               $router->get('/', 'LevelsController@index')
                ->name('levels.index');
               $router->get('/{level}', 'LevelsController@show')
                ->name('levels.show');
               $router->post('/detail', 'LevelsController@detail')
                      ->name('levels.detail');
               $router->post('update', 'LevelsController@update');
           });

           $router->group(['prefix' => 'transactions'], function ($router) {
               $router->post('/amortization', 'TransactionsController@amortization');
               $router->post('/accepted', 'TransactionsController@accepted');
               $router->post('/generate', 'TransactionsController@generate');
               $router->post('/test', 'TransactionsController@test');
           });
       });
