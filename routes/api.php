<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\GenderController;
use App\Http\Controllers\InterestController;
use App\Http\Controllers\SexController;
use App\Http\Controllers\SexualityController;

Route::get('/countries', [CountryController::class, 'render'])->name('countries.render');
Route::get('/cities/{id}', [CityController::class, 'render'])->name('countries.render');
Route::get('/genders', [GenderController::class, 'render'])->name('genders.render');
Route::get('/sexes', [SexController::class, 'render'])->name('sexes.render');
Route::get('/sexualities', [SexualityController::class, 'render'])->name('sexualities.render');
Route::get('/interests', [InterestController::class, 'render'])->name('interests.render');
