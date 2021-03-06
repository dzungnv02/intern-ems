<?php
use Illuminate\Support\Facades\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::middleware('auth:api')->get('/user', 'LoginController@getUser');

Route::group(['middleware' => 'api.headers'], function () {
    // Exam
    Route::get('/get-list-exam', 'ExaminationController@index');
    Route::post('/create-exam', 'ExaminationController@createExam');
    Route::post('/delete-exam', 'ExaminationController@deleteExam');
    Route::post('/update-exam', 'ExaminationController@updateExam');
    Route::post('/edit-exam', 'ExaminationController@editExam');
    Route::get('/get-nameclass', 'ExaminationController@getNameClass');

    // Student
    Route::get('/get-list-student', 'StudentController@index');
    Route::get('/get-student', 'StudentController@getStudent');
    Route::post('/delete-student', 'StudentController@deleteStudent');
    //Route::post('/update-student', 'StudentController@updateStudent');
    //Route::post('/add-student', 'StudentController@addStudent');

    Route::get('/student/get-profile', 'StudentController@getStudent');
    Route::get('/student/get-activity', 'StudentController@getStudentActivity');
    Route::get('/student/get-exam-result', 'StudentController@getExamResult');
    Route::get('/student/get-payments', 'StudentController@getPaymentHistory');
    Route::post('/student/save', 'StudentController@saveStudent');
    Route::post('/student/save-activity', 'StudentController@saveActivity');
    Route::get('/student/attendance', 'AttendanceController@getAttendanceByStudent');


    // Parent
    Route::get('/parent/list', 'ParentController@getParentList');
    Route::post('/parent/parent_student/change', 'ParentController@mapingParentStudent');

    // Course
    Route::get('/get-list-course', 'CourseController@getListCourse');
    Route::get('/delete-course', 'CourseController@deleteCourse');
    Route::post('/create-course', 'CourseController@createCourse');
    Route::get('/edit-course', 'CourseController@getEditCourse');
    Route::post('/edit-course', 'CourseController@editCourse');

    //Class
    Route::get('/get-list-class', 'ClassController@getListClass');
    Route::get('/delete-class', 'ClassController@deleteClass');
    
    //Disable create class
    //Route::post('/create-class', 'ClassController@createClass');
    Route::get('/edit-class', 'ClassController@getEditClass');
    Route::post('/edit-class', 'ClassController@editClass');
    Route::get('/get-list-class-student', 'ClassController@getListStudentOfClass');
    Route::get('/delete-student-class', 'ClassController@deleteStudentOfClass');
    Route::get('/get-name-teacher', 'ClassController@getNameTeacher');
    Route::get('/get-name-course', 'ClassController@getNameCourse');
    Route::post('/add-student-to-class', 'ClassController@addStudentToClass');
    Route::post('/update-status-class', 'ClassController@updateClassStatus');
    Route::get('/get-list-enroll-class', 'ClassController@getListClassByStatus');
    Route::get('/get-student-not-in-class', 'ClassController@getListStudentNotInClass');
    Route::get('/auto-update-status', 'ClassController@autoUpdateStatus');

    Route::get('/class/attendance', 'AttendanceController@attendanceTable');
    Route::get('/class/attendance/by-date', 'AttendanceController@getAttendanceByDate');
    Route::post('/class/attendance/check', 'AttendanceController@attendanceCheck');

    // Teacher
    Route::get('/get-list-teacher', 'TeacherController@index');
    Route::post('/create-teacher', 'TeacherController@store');
    Route::post('/delete-teacher', 'TeacherController@destroy');
    Route::post('/update-teacher', 'TeacherController@update');
    Route::get('/edit-teacher', 'TeacherController@edit');
    Route::get('/get-teacher-schedule', 'TeacherController@getTeacherSchedule');
    Route::post('/add_teacher_schedule', 'TeacherController@addTeacherSchedule');
    Route::get('/teacher_weekly_time_table', 'TeacherController@weeklyTimeTable');

    // Time table
    Route::get('/calc_time_table', 'TimeTableController@calculate_time_table');
    Route::post('/save-time-table', 'TimeTableController@save_time_table');

    Route::get('/get-list-timetable', 'TimeTableController@index');
    Route::get('/edit-timetable', 'TimeTableController@edit');
    Route::post('/update-timetable', 'TimeTableController@update2');

    // Roll call
    Route::get('/get-list-roll-call-student', 'RollCallController@getListRollCallStudent');
    Route::post('/roll-call-student', 'RollCallController@rollCallStudent');
    Route::post('/update-note', 'RollCallController@updateNote');

    // Holiday
    Route::get('/get-list-holiday', 'HolidayController@index');
    Route::post('/delete-holiday', 'HolidayController@destroy');
    Route::post('/add-holiday', 'HolidayController@store');
    Route::get('/countries', 'HolidayController@getCountries');

    //Point-Exam
    Route::post('/add-pointexam', 'PointExamController@addPointStudent');
    Route::get('/get-listpointexam', 'PointExamController@getListPointExam');
    Route::get('/get-liststudent', 'PointExamController@getListStudent');
    Route::get('/get-pointexam', 'PointExamController@getPointExam');
    Route::post('update-point', 'PointExamController@updatePoint');

    Route::post('/add-staff', 'StaffController@addStaff');
    Route::get('/get-list-staff', 'StaffController@getListStaff')->name('getListStaff');

    Route::post('/delete-staff', 'StaffController@deleteStaff')->name('deleteStaff');
    Route::post('/edit-password-staff', 'StaffController@editPasswordStaff');

    Route::post('/add-teacher', 'TeacherController@store');
    Route::get('/get-teacher', 'TeacherController@edit');
    Route::post('/edit-teacher', 'TeacherController@update');
    Route::get('/list-teachers', 'TeacherController@index');
    Route::post('/delete-teacher', 'TeacherController@deleteTeacher');

    //Route::get('/branch/list', 'BranchController@list');
    Route::get('/branch/get', 'BranchController@getBranch');
    Route::post('/branch/insert', 'BranchController@insertBranch');
    Route::post('/branch/update', 'BranchController@updateBranch');
    Route::post('/branch/delete', 'BranchController@deleteBranch');

    Route::get('invoice/get-last-invoice/{student_id}/{class_id}', 'InvoiceController@calc_prepaid_tutor_fee');
    Route::post('invoice/export', 'InvoiceController@export');
    Route::get('invoice/clean_export/{file}', 'InvoiceController@clean_export');

    Route::get('/compensated/list','CompensatedClassController@index');
});

Route::get('/branch/list', 'BranchController@list');
