<?php

use Illuminate\Support\Facades\Route;

Route::get('/contact', function () {
    return \config('georestriction.admin_email');
});	