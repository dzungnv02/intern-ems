<?php
use Illuminate\Support\Facades\Request;

Route::post('student-add', 'StudentWebhookController@add_student');
Route::post('student-edit', 'StudentWebhookController@edit_student');
Route::post('student-delete', 'StudentWebhookController@delete_student');
