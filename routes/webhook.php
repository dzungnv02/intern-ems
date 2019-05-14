<?php
use Illuminate\Support\Facades\Request;

Route::post('student-hook', ['uses' => 'StudentWebhookController', 'as' => 'StudentWebhookController']);