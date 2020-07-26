<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// dashboard
Route::apiResources(['dashboard' => 'API\DashboardController']);
Route::apiResources(['admin-chat' => 'API\AdminChatController']);
Route::apiResources(['expenditure' => 'API\ExpensesController']);
Route::delete('expenditure/expenseDelete/{id}', 'API\ExpensesController@expenseDelete');


// settings
Route::get('settings/get_data', 'API\SettingsController@index');

Route::get('settings/get_data_expense', 'API\SettingsController@getExpenseType');
Route::post('settings/create_expense_type', 'API\SettingsController@createExpenseType');
Route::get('settings/get_expense_type_by_id/{e_type_id}', 'API\SettingsController@findExpenseType');
Route::post('settings/update_expense_type', 'API\SettingsController@updateExpenseType');
Route::delete('settings/delete_expense_type/{id}', 'API\SettingsController@deleteExpenseType');

Route::get('settings/get_data_fee', 'API\SettingsController@getFeeType');
Route::post('settings/create_fee_type', 'API\SettingsController@createFeeType');
Route::get('settings/get_fee_type_by_id/{fee_type_id}', 'API\SettingsController@findFeeType');
Route::post('settings/update_fee_type', 'API\SettingsController@updateFeeType');
Route::delete('settings/delete_fee_type/{id}', 'API\SettingsController@deleteFeeType');

Route::get('settings/course_fee/get', 'API\SettingsController@getCourseFee');
Route::post('settings/course_fee/create', 'API\SettingsController@createCourseFee');
Route::get('settings/course_fee/get_by_id/{fee_id}', 'API\SettingsController@findCourseFee');
Route::post('settings/course_fee/update', 'API\SettingsController@updateCourseFee');
Route::delete('settings/course_fee/delete/{id}', 'API\SettingsController@deleteCourseFee');



// message
Route::apiResources(['message' => 'API\MessageController']);
Route::get('message/student/{course_id}', 'API\MessageController@getStudentByCourse');
Route::post('message/get/student', 'API\MessageController@searchStudentByProgrameAcademic');
Route::post('message/send', 'API\MessageController@sendMessage');


// chat admin
Route::get('/user_list', 'API\ChatAdminController@userList')->name('user.list');
Route::get('/user_message/{userId}', 'API\ChatAdminController@userMessage')->name('user.message');
Route::post('/send_message', 'API\ChatAdminController@saveMessage')->name('user.message.send');
Route::post('/deletesinglemessage/{id}', 'API\ChatAdminController@deleteSingleMessage')->name('user.message.delete.single');
Route::post('/deleteallmessage/{id}', 'API\ChatAdminController@deleteAllMessage')->name('user.message.delete.all');




Route::apiResources(['user' => 'API\UserController']);
Route::get('profile', 'API\UserController@profile');
Route::put('profile', 'API\UserController@updateProfile');
Route::get('findUser', 'API\UserController@search');

Route::apiResources(['student' => 'API\StudentController']);
Route::get('max_student_id', 'API\StudentController@getMaxStudentId');
Route::get('globalFindStudent', 'API\StudentController@globalSearchStudent');
/* Route::get('findStudent/{student_id}', 'API\StudentController@searchStudent');*/
Route::get('student_by_id/{student_id}', 'API\StudentController@getStudentByIdForViewStudent');


//payment
Route::get('payment/get/student/{student_id}', 'API\FeeController@getStudentDetail');
Route::get('payment/api/get/student/{student_id}', 'API\FeeController@getStudentDetail');
Route::get('payment/get-extra-pay/{s_fee_id}', 'API\FeeController@getExtraPay');
Route::post('payment/save-pay', 'API\FeeController@savePayment');
Route::post('payment/extra-pay', 'API\FeeController@extraPayment');
Route::get('payment/get_payment_history', 'API\FeeController@getPaymentHistory');
Route::get('payment/globalFindStudentPayment', 'API\FeeController@globalSearchStudentPayment');


// transaction 
Route::get('payment/get_transaction-history', 'API\FeeController@getTransactionHistory');
Route::get('payment/globalFindTransaction', 'API\FeeController@globalSearchTransaction');
//pay invoice
Route::get('payment/full-print-invoice/{s_fee_id}', 'API\FeeController@printFullInvoice');
Route::get('payment/print-invoice/{receipt_id}', 'API\FeeController@printInvoice');
//update student fee
Route::post('payment/create/fee', 'API\FeeController@createFee');
Route::get('payment/get-student-fee-by-id/{s_fee_id}', 'API\FeeController@getStudentFeeById');
Route::post('payment/update-student-fee', 'API\FeeController@updateStudentFee');
Route::delete('payment/delete-student-fee/{s_fee_id}', 'API\FeeController@deleteStudentFee');
//update transacrion pay
Route::get('payment/get-transaction-by-id/{transact_id}', 'API\FeeController@getTransactionById');
Route::post('payment/update-transaction', 'API\FeeController@updateTransaction');
Route::delete('payment/delete-transaction/{transact_id}', 'API\FeeController@deleteTransaction');

// report

Route::get('report/student/all_student', 'API\ReportController@getAllStudentReport');
Route::get('report/student/global_find_student', 'API\ReportController@globalSearchStudentReport');
Route::get('report/student/{course_id}', 'API\ReportController@getStudentReportByCourse');

Route::get('report/payment/all_transact', 'API\ReportController@getAllTransactionReport');
Route::post('report/payment/get_from_to_date', 'API\ReportController@getTransactionReportByDate');

Route::get('report/payment/all_payment', 'API\ReportController@getAllPaymentReport');
Route::get('report/payment/globalFindStudentPaymentReport', 'API\ReportController@globalSearchStudentPaymentReport');

//course
Route::post('course/add', 'API\CourseController@addCourse');
Route::post('course/add_new', 'API\CourseController@addAnotherNewCourse');
Route::get('course/get', 'API\CourseController@loadCourses');
Route::post('course/get/filter', 'API\CourseController@searchCourses');
Route::get('course/edit/{id}', 'API\CourseController@loadCourseById');
Route::put('course/update/{id}', 'API\CourseController@updateCourse');
Route::delete('course/delete/{id}', 'API\CourseController@deleteCourse');
// academic
Route::post('course/academic/add', 'API\CourseController@addAcademicYear');
Route::get('course/academic/get', 'API\CourseController@loadAcademicsData');
// programe
Route::post('course/programe/add', 'API\CourseController@addPrograme');
Route::get('course/programe/get', 'API\CourseController@loadProgramesData');
// level
Route::post('course/level/add', 'API\CourseController@addLevel');
Route::get('course/level/get/{programe_id}', 'API\CourseController@loadLevelsData');
// shift
Route::post('course/shift/add', 'API\CourseController@addShift');
Route::get('course/shift/get', 'API\CourseController@loadShiftsData');
// time
Route::post('course/time/add', 'API\CourseController@addTime');
Route::get('course/time/get', 'API\CourseController@loadTimesData');
//batch
Route::post('course/batch/add', 'API\CourseController@addBatch');
Route::get('course/batch/get', 'API\CourseController@loadBatchesData');
// group
Route::post('course/group/add', 'API\CourseController@addGroup');
Route::get('course/group/get', 'API\CourseController@loadGroupsData');

