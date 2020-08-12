<?php
use Illuminate\Support\Facades\Request;

Route::post('student-hook', ['uses' => 'StudentWebhookController']);
Route::post('parent-hook', ['uses' => 'ParentWebhookController']);
Route::post('classes-hook', ['uses' => 'ClassesWebhookController']);
Route::post('teacher-hook', ['uses' => 'TeacherWebhookController']);
Route::post('ems-hook', ['uses' => 'ZohoSyncWebhookController']);