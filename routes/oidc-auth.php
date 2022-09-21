<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->get(
    config('oidc-auth.callback_route'),
    'LaravelOIDCAuth\CallbackController@callback'
)->name('oidc-auth.callback');
