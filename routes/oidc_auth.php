<?php

Route::middleware('web')->get(
    config('oidc_auth.callback_route'),
    'LaravelOIDCAuth\CallbackController@callback'
)->name('oidc-auth.callback');
