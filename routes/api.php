<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('auth/login', 'API\AuthController@login');
Route::get('/', 'HomeController@index')->name('home');
Route::get('/posts/{post}/comments', 'CommentController@index');
Route::middleware('auth:api')->group(function () {


    Route::post('/posts/{post}/comment', 'CommentController@store');
    // users routes
    Route::apiResources(['user' => 'API\UserController']);
    Route::apiResources(['event' => 'API\EventController']);
    Route::get('user_profile/{id?}/{type?}', 'API\UserController@profile');
    Route::get('count/{id?}', 'API\UserController@dashboard');
    Route::post('importUsers', 'API\UserController@import');
    Route::get('findUser', 'API\UserController@search');
    Route::put('profile', 'API\UserController@updateProfile');
    // students routes
    Route::apiResources(['student' => 'API\StudentController']);
    Route::get('students/{level_id?}/{arm_id?}', 'API\StudentController@index');
    Route::get('profile/{id?}', 'API\StudentController@info');
    Route::delete('students', 'API\StudentController@destroy');
    Route::post('importStudents', 'API\StudentController@import');
    Route::get('findStudent/{id?}', 'API\StudentController@search');
    //Route::get('findStudents/{id}', 'API\StudentController@search');
    Route::put('student_update/{id}', 'API\StudentController@update');
    Route::put('updateStudent/{id?}', 'API\StudentController@updateStudent');
    Route::post('import_students',    'API\StudentController@import');
    Route::get('login_export', 'API\StudentController@exportLogin');
    Route::get('login_export/{level_id}/{arm_id}/{accountNumber?}', 'API\StudentController@exportLogins');
    Route::post('update_password', 'API\StudentController@updatePassword');
    // Levels routs
    Route::apiResources(['level' => 'API\LevelController']);
    Route::get('get_levels', 'API\LevelController@getLevels');
    Route::post('add_arms/', 'API\LevelController@add_arms');
    Route::get('arms/{id}', 'API\LevelController@arms');
    Route::get('arms', 'API\LevelController@level_arms');
    Route::put('arms/{id}', 'API\LevelController@update_arms');
    Route::delete('arms/{id}', 'API\LevelController@delete_arm');
    Route::get('check_arm/{id}', 'API\LevelController@check_arm');
    Route::apiResources(['arm' => 'API\ArmController']);


    //employee Routes
    Route::apiResources(['employee' => 'API\EmployeeController']);
    Route::post('importEmployees', 'API\EmployeeController@import');
    Route::put('employee', 'API\EmployeeController@update');
    Route::get('findEmployee', 'API\EmployeeController@search');
    Route::get('employees', 'API\EmployeeController@getEmployee');
    Route::delete('delete_emp/{id}', 'API\EmployeeController@destroy');
    Route::get('staff_profile/{id?}', 'API\EmployeeController@profile');

    //tutorsComment
    Route::post('tutorsComment', 'API\StaffcommentController@assignComment');
    Route::get('tutorsComment', 'API\StaffcommentController@getComments');
    Route::post('load_comments', 'API\StaffcommentController@loadComments');
    // Subject Routes


    Route::apiResources(['subjects' => 'API\SubjectController']);
    Route::post('level_subjects', 'API\SubjectController@level_subjects');
    Route::get('load_subjects/{level_id?}', 'API\SubjectController@loadListView');
    Route::get('load_list/{id?}/{is_score?}', 'API\SubjectController@loadSubjects');
    Route::delete('delete_list/{id}', 'API\SubjectController@delete_list');
    Route::post('cloneSubjects/{from}/{to}', 'API\SubjectController@cloneLevelSubject');

    Route::get('teacher_subjects/{staff_id?}', 'API\TeachersController@index');
    Route::delete('teacher_subjects', 'API\TeachersController@delete_list');
    Route::post('teacher_subjects', 'API\TeachersController@store');
    Route::get('teacher_subjects/{id}', 'API\TeachersController@loadSubjects');

    // Results routes
    Route::apiResources(['report' => 'API\ReportController']);
    Route::get('load_session', 'API\ReportController@session_list');
    Route::get('term', 'API\ReportController@term_list');
    Route::get('results/{id}', 'API\ReportController@student_list');
    Route::get('result/{report_id}/{student_id?}', 'API\ScoreController@studenResult');
    Route::get('pdf_download/{report_id}/{student_id?}', 'API\ReportController@pdfDownload');
    Route::put('report', 'API\ReportController@update');
    Route::get('load_results/{report_id}/{arm_id}', 'API\ActivationController@loadResults');
    Route::post('update_results', 'API\ActivationController@updateResults');

    Route::get('checkreport/{id}', 'API\ReportController@checkReport');
    Route::get('getArms/{report_id}', 'API\ReportController@getArms');
    Route::get('loadArms/{level_id}', 'API\LevelController@loadArms');
    Route::get('master/{report_id}/{arm_id?}', 'API\ReportController@masterCard');
    Route::get('export_master/{report_id}/{arm_id?}', 'API\ReportController@export_master');
// Scores Routes
    Route::apiResources(['scores' => 'API\ScoreController']);
    Route::post('import_scores', 'API\ScoreController@import');
    Route::post('primary_scores', 'API\PrimaryController@store');
    Route::post('importExcel', 'API\ScoreController@importExcel');
    Route::post('load_students', 'API\ScoreController@loadStudents');
    Route::post('load_performance', 'API\ScoreController@loadPerformance');
    Route::post('deleteScores', 'API\ScoreController@deleteScores');
    Route::get('load_report', 'API\ScoreController@index');
    Route::get('score_template/{report_id}/{subject_id}', 'API\ScoreController@score_template');
    Route::get('backup/{report_id}/{subject_id}', 'API\ScoreController@score_backup');
    Route::post('computeSummary/{report_id}/{arm_id}', 'API\ScoreController@computeSummary');


    //Chart Rooutes
    Route::get('chart', 'API\ChartController@index');
    Route::get('load_students/{id}', 'API\ChartController@load_students');
    Route::post('load_chart', 'API\ChartController@load_chart');

    //Mail Routes
    Route::post('send_mail', 'API\EmailController@send');

    // Ressulst Activation
    Route::get('load_activation/{report_id}/{arm_id}', 'API\ActivationController@loadActivation');
    Route::post('activate_result', 'API\ActivationController@store');
    Route::post('activate_report/{id}', 'API\ReportController@activate_report');
    // Assignment Routes
    Route::apiResources(['assignment' => 'API\AssignmentController']);
    Route::post('create_assignment', 'API\AssignmentController@store');
    Route::get('getAssignment', 'API\AssignmentController@index');
    Route::get('assignment_view/{id}', 'API\AssignmentController@show');
    Route::post('/upload_image', 'API\AssignmentController@zipUpload');
    Route::get('get_pdf/{id}', 'API\AssignmentController@getPdf');
    Route::get('get_student_pdf/{id}', 'API\AssignmentController@studentPDF');
    Route::post('student_assignment', 'API\AssignmentController@studentAssignment');
    Route::get('student_assignment/{id}', 'API\AssignmentController@checkAssignment');
    Route::get('get_students_assignment/{id}', 'API\AssignmentController@getStudentAssignment');
    Route::put('update_students_assignment', 'API\AssignmentController@updateStudentAssignment');
    Route::post('use_assignment', 'API\CbtController@useAssignment');
    // get_students_assignment
    // Assignment Routes
    Route::apiResources(['lesson' => 'API\LessonController']);
    Route::post('create_lesson', 'API\LessonController@store');
    Route::get('getLesson', 'API\LessonController@index');
    Route::get('lesson_view/{id}', 'API\LessonController@show');
    // School Route
    Route::apiResources(['school' => 'API\SchoolController']);
    Route::put('set_school/{school_id}', 'API\SchoolController@setSchool');
    Route::apiResources(['academic_session' => 'API\SessionController']);
    Route::put('academic_session', 'API\SessionController@update');
    // Grading System
    Route::apiResources(['grading' => "API\GradingController"]);
    Route::apiResources(['grading_group' => "API\GradinggroupController"]);
    Route::put('grading', 'API\GradingController@update');
    Route::put('grading_group', 'API\GradinggroupController@update');
    Route::get('gradinggroup', 'API\GradinggroupController@getGradinggroup');
    Route::apiResources(['comment' => "API\CommentController"]);
    Route::apiResources(['staff_comment' => "API\StaffcommentController"]);
    Route::put('comment', 'API\CommentController@update');
    Route::put('staff_comment', 'API\StaffcommentController@update');
    Route::get('result_config', 'API\GradingController@resultConfig');
    Route::post('result_config', 'API\GradingController@saveConfig');
    Route::post('generate_activation', 'API\ActivationkeyController@store');
    Route::post('get_activated', 'API\ActivationkeyController@activateResult');
    Route::post('deactivate_results', 'API\ActivationController@deactivateResults');
    Route::post('import_progress', 'API\ActivationController@importProgress');

    // video routes

    Route::apiResources(['videos' => "API\VideoController"]);
    Route::put('video', 'API\VideoController@update');
    Route::get('getVideo/{id}','API\VideoController@play');

    // Learning Domain
    Route::apiResources(['learning_domain' => "API\LDomainController"]);
    Route::put('learning_domain', 'API\LDomainController@update');
    Route::get('load_assessment/{arm_id}/{report_id}/{ld_id}', 'API\LDomainController@loadAssessment');
    Route::get('get_domain', 'API\LDomainController@getDomain');
    Route::post('assessment', 'API\LDomainController@insertAssessment');
    Route::post('delete_ssessment', 'API\LDomainController@removeAssement');


    // cbt
    Route::apiResources(['exams' => "API\ExamController"]);
    Route::get('exam/{id}', 'API\ExamController@show');
    Route::get('get_exam_arms/{id}', 'API\ExamController@getArms');
    Route::put('exams', 'API\ExamController@update');
    Route::get('load_exams', 'API\ExamController@loadExams');
    Route::get('exam_options', 'API\ExamController@examOptions');
    Route::post('question_options', 'API\ExamController@questionOptions');
    Route::get('questions/{id}', 'API\ExamController@Questions');
    Route::get('question/{id}', 'API\ExamController@Question');
    Route::delete('question/{id}', 'API\ExamController@deleteQuestion');
    Route::get('cbt/{id}', 'API\CbtController@takeExam');
    Route::get('cbt_scores/{id}', 'API\CbtController@cbtScores');
    Route::post('save_answers', 'API\CbtController@saveAnswers');
    Route::post('use_cbt', 'API\CbtController@useCBT');
    Route::get('review_answers/{exam_id}/{student_id?}', 'API\CbtController@CBT_Review');

    // History classes
    Route::get('transferlevels', 'API\HistoryController@index');
    Route::post('set_history', 'API\HistoryController@setHistory');
    Route::get('level_history', 'API\HistoryController@getHistory');
    Route::get('student_history/{id}', 'API\HistoryController@getStudentHistory');
    Route::get('getnew_students/{id}', 'API\HistoryController@getNewStudents');
    Route::post('promote_students', 'API\HistoryController@promoteStudents');
    Route::post('assign_arm', 'API\HistoryController@assignArm');
    Route::post('import_students_history', 'API\HistoryController@importHistoryLevel');
    Route::post('import_history_scores', 'API\HistoryController@importHistoryScores');
    Route::get('promoted_arms/{level_id}/{arm_id}/', 'API\HistoryController@promotedArm');

    // Chat Routes
    Route::get('messages', 'API\ChatsController@fetchMessages');
    Route::post('messages', 'API\ChatsController@sendMessages');
    Route::get('level_messages', 'API\ChatsController@fetchLevelMessage');
    Route::post('level_messages', 'API\ChatsController@sendLevelMessages');
    Route::get('get_chat_level', 'API\ChatsController@getLevel');
    Route::get('transcript/{student_id?}', 'API\ReportController@transcript');

    // Fees

    Route::apiResources(['fees' => 'API\FeesController']);
    Route::get('fee/{id}', 'API\FeesController@index');
    Route::delete('fees/{id}', 'API\FeesController@destroy');
    Route::put('fees/{id}', 'API\FeesController@update');
    Route::post('fee_description', 'API\FeesController@newDescription');
    Route::get('fee_description/{id}/{student_id?}', 'API\FeesController@feeDescriptions');
    Route::put('fee_description/{id}', 'API\FeesController@updateDescriptions');
    Route::delete('fee_description/{id}', 'API\FeesController@deleteDescription');

    // MESSAGES
    Route::post('messageApi', 'API\SmsController@createMessageApi');
    Route::get('messageApi', 'API\SmsController@MessageApi');
    Route::put('messageApi', 'API\SmsController@updateMessageApi');
    Route::delete('messageApi/{id}', 'API\SmsController@deleteMessageApi');

    Route::post('paystack', 'API\FeesController@createPaystack');
    Route::get('paystack', 'API\FeesController@paystack');
    Route::put('paystack', 'API\FeesController@updatePaystack');
    Route::delete('paystack/{id}', 'API\FeesController@deletePaystack');
    // Fee Activation
    Route::get('student_fees/{feegroup_id}/', 'API\FeeactivationController@loadActivation');
    Route::post('student_fees', 'API\FeeactivationController@store');
    Route::post('fee_pay', 'API\FeeactivationController@pay');
    Route::post('fee_pay/update', 'API\FeeactivationController@updatePayment');
    Route::post('confirmPayments','API\FeeactivationController@confirmPayments');
    Route::post('feeItems', 'API\FeeItemsController@store');
    Route::get('feeItems', 'API\FeeItemsController@index');
    Route::put('feeItems', 'API\FeeItemsController@update');
    Route::delete('feeItems/{id}', 'API\FeeItemsController@destroy');
    Route::post('queryFees', 'API\FeesController@queryFees');
    /// Creche Domain Routes
    Route::post('domains', 'API\DomainController@store');
    Route::get('domains', 'API\DomainController@index');
    Route::delete('domains/{id}', 'API\DomainController@destroy');
    Route::put('domains', 'API\DomainController@update');
    // Ratings
    Route::post('ratings', 'API\RatingController@store');
    Route::post('crecheComment', 'API\RatingController@createRating');
    Route::get('ratings', 'API\RatingController@index');
    Route::delete('ratings/{id}', 'API\RatingController@destroy');
    Route::put('ratings', 'API\RatingController@update');

    Route::post('subdomains', 'API\SubDomainController@store');
    Route::get('subdomains/{domain_id?}', 'API\SubDomainController@index');
    Route::delete('subdomains/{id}', 'API\SubDomainController@destroy');
    Route::put('subdomains', 'API\SubDomainController@update');
    Route::put('activateUser/{id}', 'API\UserController@activateUser');

    Route::get('accountNumbers', 'API\EcopayController@index');
    Route::post('createAccountNumber', 'API\EcopayController@store');
    Route::post('generateBulkAccountNumbers', 'API\EcopayController@generateBulkAccount');
    Route::post('getAccount', 'API\EcopayController@getAccount');
    Route::get('getAccountBalance/{id?}', 'API\EcopayController@getAccountBalance');
    Route::get('getTransactions/{accountNumber}', 'API\EcopayController@getTransactions');
    Route::get('getBillAmount/{feegroup_id}', 'API\EcopayController@getBillAmount');
    Route::post('createBills', 'API\EcopayController@createBills');
    Route::get('getAllBills', 'API\EcopayController@getAllBills');
    Route::post('billAccounts', 'API\EcopayController@billAccounts');
    Route::post('billAccount/{accountNumber}/{billId}/{amount}/{feegroupId}', 'API\EcopayController@billAccount');
    Route::delete('bill/{id}', 'API\EcopayController@deleteBill');
    Route::apiResources(['principalComments' => 'API\PrincipalcommentController']);
    Route::post('load_principal_comments', 'API\PrincipalcommentController@loadComments');
    Route::post('principal_comment', 'API\PrincipalcommentController@assignComment');
    Route::get('getAllComments', 'API\PrincipalcommentController@getAllComments');

//  Parents Routes
Route::get('parents','API\ParentsController@index');
Route::get('parent/{id?}','API\ParentsController@getParentById');
Route::post('parents','API\ParentsController@store');
Route::put('parents','API\ParentsController@update');
Route::delete('parents/{id}','API\ParentsController@delete');
Route::get('parents/walletBalance','API\EcopayController@parentWalletBalance');
Route::get('parents/bills','API\EcopayController@parentBills');
Route::post('parents/import','API\ParentsController@importParents');

Route::get('siblings/{id?}','API\ParentsController@siblings');
Route::post('siblings','API\ParentsController@addSiblings');
Route::delete('siblings/{id}','API\ParentsController@deleteSibling');
Route::get('siblings/results/{studentId?}','API\ParentsController@siblingsResult');
Route::apiResources(['templates' => 'API\TemplateController']);
Route::put('templates','API\TemplateController@update');
Route::get('schoolTemplates/{id?}','API\TemplateController@loadSchoolTemplates');
Route::post('schoolTemplates','API\TemplateController@createSchoolTemplate');
Route::delete('schoolTemplates/{id}','API\TemplateController@deleteSchoolTemplate');

Route::get('attendance/{report_id}/{arm_id}','API\AttendanceController@loadAttendance');
Route::post('attendance','API\AttendanceController@createAttendance');
Route::post('attendance/importExcel','API\AttendanceController@importAttendance');

// INVENTORY ROUTES

Route::get('inventory/category','API\Inventory\CategoryController@index');
Route::post('inventory/category','API\Inventory\CategoryController@createCategory');
Route::put('inventory/category','API\Inventory\CategoryController@update');
Route::delete('inventory/category/{id}','API\Inventory\CategoryController@delete');

Route::get('inventory/products','API\Inventory\ProductController@index');
Route::post('inventory/products','API\Inventory\ProductController@createProduct');
Route::put('inventory/products','API\Inventory\ProductController@update');
Route::delete('inventory/products/{id}','API\Inventory\ProductController@delete');
Route::post('inventory/products/purchases','API\Inventory\ProductController@purchasedProduct');
Route::delete('inventory/products/purchases/{id}','API\Inventory\ProductController@deletePurchased');
Route::get('inventory/products/purchases','API\Inventory\ProductController@getPurchases');
Route::post('inventory/products/price','API\Inventory\PriceController@createPrice');
Route::delete('inventory/products/price/{id}','API\Inventory\PriceController@delete');
Route::get('inventory/products/price','API\Inventory\PriceController@index');
Route::put('inventory/products/price','API\Inventory\PriceController@update');
Route::get('inventory/products/stock','API\Inventory\StockController@getAllStock');
Route::post('inventory/products/sell','API\Inventory\SalesController@createSales');

Route::get('inventory/items','API\Inventory\ItemsController@index');
Route::post('inventory/items','API\Inventory\ItemsController@createItem');
Route::put('inventory/items','API\Inventory\ItemsController@update');
Route::delete('inventory/items/{id}','API\Inventory\ItemsController@delete');

Route::get('inventory/sales','API\Inventory\SalesController@index');
Route::post('inventory/sales','API\Inventory\SalesController@createSales');
Route::put('inventory/sales','API\Inventory\SalesController@update');
Route::delete('inventory/sales/{id}','API\Inventory\SalesController@delete');
Route::get('inventory/sales/{id}','API\Inventory\SalesController@salesDetails');

Route::get('inventory/stock','API\Inventory\StockController@index');
Route::post('inventory/stock','API\Inventory\StockController@createStock');
Route::put('inventory/stock','API\Inventory\StockController@update');
Route::delete('inventory/stock/{id}','API\Inventory\StockController@delete');

Route::get('inventory/suppliers','API\Inventory\SuppliersController@index');
Route::post('inventory/suppliers','API\Inventory\SuppliersController@createSupplier');
Route::put('inventory/suppliers','API\Inventory\SuppliersController@update');
Route::delete('inventory/suppliers/{id}','API\Inventory\SuppliersController@delete');




});
