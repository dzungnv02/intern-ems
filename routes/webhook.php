<?php
use Illuminate\Support\Facades\Request;

Route::post('student-add', 'StudentWebhookController@add_student');
Route::post('student-edit', 'StudentWebhookController@edit_student');
Route::get('student-delete/{id}', 'StudentWebhookController@delete_student');
