<?php
use Illuminate\Support\Facades\Request;

Route::post('student-add', 'StudentWebhookController@add_student');