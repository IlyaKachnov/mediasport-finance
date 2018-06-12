<?php

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

Auth::routes();
Route::get('/', 'Auth\LoginController@showLoginForm');
Route::post('login', 'Auth\LoginController@login');
Route::group(['middleware' => ['auth']], function () {
    Route::get('/', 'AdministratorController@index');
    Route::get('/profile', 'AdministratorController@index');
    Route::patch('/profile/{user}', 'AdministratorController@update');
    Route::resource('users', 'UserController');
    //Route::get('user/activation/{token}','Auth\RegisterController@activateUser');
    Route::get('/logout', 'Auth\LoginController@logout');
    //avatar
    Route::post('dashboard/{user}/save-avatar', 'AdministratorController@storeAvatar');
    Route::post('user/{user}/change-password', 'AdministratorController@postChange');
    //referee
    Route::resource('referees', 'RefereeController', ['except' => ['show']]);
    Route::get('referees/report', ['uses' => 'RefereeController@showReport', 'as' => 'referees.report']);
    Route::post('referees/filter', ['uses' => 'RefereeController@filterReport', 'as' => 'referees.filter']);
    //gyms
    Route::resource('gyms', 'GymController', ['except' => ['show']]);
    Route::get('all-gyms', ['uses' => 'GymController@showAll', 'as' => 'gyms.all']);
    Route::get('gyms/report', ['uses' => 'GymController@showReport', 'as' => 'gyms.report']);
    Route::post('gyms/filter', ['uses' => 'GymController@filterReport', 'as' => 'gyms.filter']);
    //leagues
    Route::post('leagues/check-name', 'LeagueController@checkName');
    Route::resource('leagues', 'LeagueController', ['except' => ['show']]);
    Route::get('all-leagues', 'LeagueController@showAll');
    Route::get('leagues/{league}/add-gyms', 'LeagueController@addGyms');
    Route::get('leagues/{league}/load-gyms', 'LeagueController@loadGyms');
    Route::get('leagues/{league}/{gym}/edit-gyms', 'LeagueController@editGyms');
    Route::post('leagues/{league}/save-gyms', 'LeagueController@saveGyms');
    Route::patch('leagues/{league}/{gym}', 'LeagueController@updateGyms');
    Route::delete('leagues/{league}/{gym}', 'LeagueController@deleteGyms');
    //teams
    Route::get('leagues/{league}/teams', 'TeamController@index');
    Route::post('leagues/{league}/teams', 'TeamController@store');
    Route::patch('leagues/{league}/teams/{team}', 'TeamController@update');
    Route::delete('leagues/{league}/teams/{team}', 'TeamController@destroy');
    Route::get('teams/report', ['uses' => 'TeamController@showReport', 'as' => 'teams.report']);
    Route::post('teams/filter', ['uses' => 'TeamController@filterReport', 'as' => 'teams.filter']);
    Route::post('teams/check-name', 'TeamController@checkName');
    //prepays
    Route::get('teams/{team}/prepays', 'PrepayController@index');
    Route::post('teams/{team}/prepays', 'PrepayController@store');
    Route::patch('teams/{team}/prepays/{prepay}', 'PrepayController@update');
    Route::delete('teams/{team}/prepays/{prepay}', 'PrepayController@destroy');
    //debts
    Route::get('teams/{team}/debts', 'DebtController@index');
    Route::post('debts/{debt}/status', 'DebtController@changeStatus');
    //matches
    Route::get('gyms/{gym}/matches', ['uses' => 'MatchController@index', 'as' => 'matches.index']);
    Route::get('gyms/{gym}/matches/create', 'MatchController@create');
    Route::post('home-select', 'MatchController@ajaxHomeSelect');
    Route::post('ajax-select', 'MatchController@ajaxSelect');
    Route::post('gyms/{gym}/matches/ajax-edit', 'MatchController@ajaxEditSelect');
    //Route::get('leagues/{league}/matches/{heam}/{guestTeam}','MatchController@ajaxEditSelect');
    Route::get('gyms/matches/{match}/edit', 'MatchController@edit');
    Route::post('gyms/{gym}/matches', 'MatchController@store');
    Route::patch('gyms/{gym}/matches/{match}', 'MatchController@update');
    Route::delete('gyms/{gym}/matches/{match}', 'MatchController@destroy');
    Route::get('total/report', ['uses' => 'MatchController@showReport', 'as' => 'total.report']);
    Route::post('total/filter', ['uses' => 'MatchController@filterReport', 'as' => 'total.filter']);
    //check team balance
    Route::get('teams/{team}/check-balance', 'MatchController@checkBalance');
    //day expenses
    Route::get('gyms/day-expenses/create', 'DayExpenseController@create');
    Route::post('gyms/{gym}/day-expenses', 'DayExpenseController@store');
    Route::get('gyms/day-expenses/{dExp}/edit', 'DayExpenseController@edit');
    Route::patch('gyms/day-expenses/{dExp}', 'DayExpenseController@update');
    Route::delete('gyms/day-expenses/{dExp}', 'DayExpenseController@destroy');
    //fees
    Route::get('leagues/{league}/fees', 'EntranceFeeController@index');
    Route::get('leagues/{league}/fees/create', 'EntranceFeeController@create');
    Route::get('leagues/{league}/fees/{fee}/edit', 'EntranceFeeController@edit');
    Route::post('leagues/{league}/fees', 'EntranceFeeController@store');
    Route::patch('leagues/{league}/fees/{fee}', 'EntranceFeeController@update');
    Route::delete('leagues/{league}/fees/{fee}', 'EntranceFeeController@destroy');
    //incomes
    Route::resource('incomes', 'IncomeController', ['except' => ['show']]);
    //consumptions
    Route::resource('consumptions', 'ConsumptionController');
    //common report
    Route::get('common-reports', 'CommonReportController@showReport');
    Route::post('common/filter', ['uses' => 'CommonReportController@filterReport', 'as' => 'common.filter']);
});

