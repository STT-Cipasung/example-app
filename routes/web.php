<?php

use App\Http\Controllers\JobController;
use Illuminate\Support\Facades\Route;

Route::get('', function (){
    return to_route('jobs.index');
});

Route::resource('jobs', JobController::class)->only(['index']);
