<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompanyMasterController;
use App\Http\Controllers\CallAttendantController;
use App\Http\Controllers\CompanyInfoController;
use App\Http\Controllers\CallReportController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WlAdminController;
use App\Http\Controllers\ComponentController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\WlUserController;
use App\Http\Controllers\DistributorController;
use App\Http\Controllers\CompanyClientController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\ReferenceController;
use App\Http\Controllers\CallListController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\WLResetPasswordController;
use App\Http\Controllers\WLProfileController;
use App\Http\Controllers\CallAttendantResetPasswordController;
use App\Http\Controllers\callattendantProfileController;
use App\Http\Controllers\CallAttendantReferenceController;
use App\Http\Controllers\CallAttendantFaqController;
use App\Http\Controllers\MyTicketController;
use App\Http\Controllers\ComplaintsController;
use App\Http\Controllers\CallAttendantAdminController;
use App\Http\Controllers\CityController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\SystemSummaryReportController;
use App\Http\Controllers\CitySummaryReportController;
use App\Http\Controllers\CompanySummaryReportController;
use App\Http\Controllers\CustomerSummaryReportController;
use App\Http\Controllers\TopAnalyticReportController;

use App\Http\Controllers\CaptchaServiceController;
use App\Http\Controllers\ForgotPasswordController;

use App\Http\Controllers\AdminCustomerListController;
use App\Http\Controllers\AdminCustomerCompanyListController;
use App\Http\Controllers\AdminDistributorListController;

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\DownloadCallController;
use App\Http\Controllers\DistributorSummaryReportController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});
// Route::fallback(function () {
//     Auth::logout();
//     return redirect()->route('login');
// });

// user protected routes
// Route::group(['middleware' => ['auth', 'user'], 'prefix' => 'user'], function () {
//     Route::get('callattendance/', 'HomeController@index')->name('user_dashboard');
// });

// // admin protected routes
// Route::group(['middleware' => ['auth', 'admin'], 'prefix' => 'admin'], function () {
//     Route::get('admin/', 'HomeController@index')->name('admin_dashboard');
// });
// Route::get('/wladmin', function () {
//     return redirect()->route('wladmin.login');
// });
// Route::get('/callattendance', function () {
//     return redirect()->route('login');
// });
// Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
// Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login');
// Route::get('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Auth::routes(['register' => false]);
//Route::middleware('ipcheck')->group(function () {
Route::get('/', [HomeController::class, 'index'])->name('home');
// Route::get('admin/home', [HomeController::class, 'adminHome'])->name('admin.home')->middleware('is_admin');
// Route::get('wladmin/home', [HomeController::class, 'wladminHome'])->name('wladmin.home')->middleware('is_wl_admin');
// Route::get('iscallattendance/home', [HomeController::class, 'callattendanceHome'])->name('callattendance.home')->middleware('is_call_attendance');
//});
// Profile Routes
Route::prefix('profile')->name('profile.')->middleware('auth', 'ipcheck')->group(function () {
    Route::get('/', [HomeController::class, 'getProfile'])->name('detail');
    Route::post('/update', [HomeController::class, 'updateProfile'])->name('update');
    Route::post('/logout', [HomeController::class, 'logoutlog'])->name('logoutlog');
    Route::post('/change-password', [HomeController::class, 'changePassword'])->name('change-password');
    Route::post('/getCallChart', [HomeController::class, 'getCallChart'])->name('getCallChart');
    Route::post('/getLineChart', [HomeController::class, 'getLineChart'])->name('getLineChart');
    Route::post('/getLineChartSameDayResolve', [HomeController::class, 'getLineChartSameDayResolve'])->name('getLineChartSameDayResolve');
    Route::post('/getPiaChart', [HomeController::class, 'getPiaChart'])->name('getPiaChart');
    Route::post('/getDashboardCount', [HomeController::class, 'getDashboardCount'])->name('getDashboardCount');
    Route::post('/getAnalyticsLineChart', [HomeController::class, 'getAnalyticsLineChart'])->name('getAnalyticsLineChart');
});

// Roles
Route::resource('roles', App\Http\Controllers\RolesController::class);

// Permissions
Route::resource('permissions', App\Http\Controllers\PermissionsController::class);

// Users
Route::middleware('auth', 'ipcheck')->prefix('users')->name('users.')->group(function () {
    Route::get('/usersindex', [UserController::class, 'index'])->name('index');
    Route::get('/userscreate', [UserController::class, 'create'])->name('create');
    Route::post('/usersstore', [UserController::class, 'store'])->name('store');
    Route::get('/usersedit/{user}', [UserController::class, 'edit'])->name('edit');
    Route::put('/usersupdate/{user}', [UserController::class, 'update'])->name('update');
    Route::delete('/usersdelete/{user}', [UserController::class, 'delete'])->name('destroy');
    Route::get('/usersupdate/status/{user_id}/{status}', [UserController::class, 'updateStatus'])->name('status');

    Route::get('/usersimport-users', [UserController::class, 'importUsers'])->name('import');
    Route::post('/usersupload-users', [UserController::class, 'uploadUsers'])->name('upload');

    Route::get('export/', [UserController::class, 'export'])->name('export');
});
//company
Route::prefix('company')->name('company.')->middleware('auth', 'ipcheck')->group(function () {
    Route::any('/companyindex', [CompanyMasterController::class, 'index'])->name('index');
    Route::get('/companyinfo/{id?}', [CompanyMasterController::class, 'infoindex'])->name('info');
    Route::get('/{id?}/companycreate', [CompanyMasterController::class, 'createview'])->name('create');
    Route::post('/companystore', [CompanyMasterController::class, 'create'])->name('store');
    Route::get('/{id?}/companyComponentcreate', [CompanyMasterController::class, 'companycomponentcreate'])->name('componetcreate');
    Route::post('/companyComponentstore', [CompanyMasterController::class, 'companycomponentstore'])->name('companycomponentstore');
    Route::any('/companyComponents-list', [CompanyMasterController::class, 'componentslist'])->name('componentslist');
    Route::post('/companygetsystem', [CompanyMasterController::class, 'getsystem'])->name('getsystem');
    Route::post('/companygetcomponent', [CompanyMasterController::class, 'getcomponent'])->name('getcomponent');
    Route::get('/{id?}/componentsedit', [CompanyMasterController::class, 'componentsedit'])->name('componentsedit');
    Route::post('/companycomponentupdate', [CompanyMasterController::class, 'companycomponentupdate'])->name('companycomponentupdate');
    Route::post('/getCompany', [CompanyMasterController::class, 'getCompany'])->name('getCompany');
    Route::post('/geteditCompany', [CompanyMasterController::class, 'geteditCompany'])->name('geteditCompany');

    Route::post('/getCompanyClientEmail', [CompanyMasterController::class, 'getCompanyClientEmail'])->name('getCompanyClientEmail');

    Route::get('/{id?}/res-categoryCreate', [CompanyMasterController::class, 'resolutioncategorycreate'])->name('res-categorycreate');
    Route::post('/res-category/store/', [CompanyMasterController::class, 'resolutioncategorycreatestore'])->name('resolutioncategorycreatestore');

    Route::get('/{id?}/issue-typeCreate', [CompanyMasterController::class, 'issuetypecreate'])->name('issue-type');
    Route::post('/issue-type/store/', [CompanyMasterController::class, 'issuetypestore'])->name('issuetypestore');

    Route::get('/{id?}/call-competencyCreate', [CompanyMasterController::class, 'callcompetencycreate'])->name('call-competency');
    Route::post('/call-competency/store/', [CompanyMasterController::class, 'callcompetencystore'])->name('callcompetencystore');


    Route::get('/{id?}/support-typeCreate', [CompanyMasterController::class, 'supporttypecreate'])->name('support-type');
    Route::post('/support-type/store/', [CompanyMasterController::class, 'supporttypestore'])->name('supporttypestore');

    Route::get('/{Id}', [CompanyMasterController::class, 'editview'])->name('edit');
    Route::post('/companyupdate/{Id?}', [CompanyMasterController::class, 'update'])->name('update');
    Route::delete('/companydelete/{Id}', [CompanyMasterController::class, 'delete'])->name('delete');
    Route::get('/companycompanysidebar', [CompanyMasterController::class, 'companysidebar'])->name('companysidebar');
    Route::post('/companygetCity', [CompanyMasterController::class, 'getCity'])->name('getCity');
    Route::post('/companyemailvalidate', [CompanyMasterController::class, 'emailvalidate'])->name('emailvalidate');
    Route::post('/OEMCompanyNamevalidate', [CompanyMasterController::class, 'OEMCompanyNamevalidate'])->name('OEMCompanyNamevalidate');

    Route::post('/companyupdateStatus', [CompanyMasterController::class, 'updateStatus'])->name('updateStatus');
});

//company info
Route::prefix('companyinfo')->name('companyinfo.')->middleware('auth', 'ipcheck')->group(function () {
    Route::get('/companyinfoindex', [CompanyInfoController::class, 'index'])->name('index');
    Route::post('/GeneralDataStore', [CompanyInfoController::class, 'GeneralDataStore'])->name('GeneralDataStore');
    Route::post('/SocialMediaLinkStore', [CompanyInfoController::class, 'SocialMediaLinkStore'])->name('SocialMediaLinkStore');
    Route::post('/MessageforInactiveUserstore', [CompanyInfoController::class, 'MessageforInactiveUserstore'])->name('MessageforInactiveUserstore');
    // Route::get('/{Id}', [CompanyInfoController::class, 'editview'])->name('edit');
    // Route::post('/update/{Id?}', [CompanyInfoController::class, 'update'])->name('update');
});
Route::prefix('admincity')->name('admincity.')->middleware('auth', 'ipcheck')->group(function () {
    Route::get('/cityDetail', [CityController::class, 'cityDetail'])->name('cityDetail');
    Route::post('/addCity', [CityController::class, 'addCity'])->name('addCity');
    Route::get('/downloadCity', [CityController::class, 'downloadCity'])->name('downloadCity');
});
//call_attendant
Route::prefix('call_attendant')->name('call_attendant.')->middleware('auth', 'ipcheck')->group(function () {
    Route::any('/callattendantindex', [CallAttendantController::class, 'index'])->name('index');
    Route::get('/callattendantinfo/{id?}', [CallAttendantController::class, 'infoindex'])->name('info');
    Route::get('/callattendantcreate', [CallAttendantController::class, 'createview'])->name('create');
    Route::post('/callattendantstore', [CallAttendantController::class, 'create'])->name('store');
    Route::get('/callattendantedit/{Id}', [CallAttendantController::class, 'editview'])->name('edit');
    Route::post('/callattendantupdate', [CallAttendantController::class, 'update'])->name('update');
    Route::delete('/callattendantdelete/{Id}', [CallAttendantController::class, 'delete'])->name('destroy');
    Route::post('/updateStatus', [CallAttendantController::class, 'updateStatus'])->name('updateStatus');
    Route::post('/emailvalidate', [CallAttendantController::class, 'emailvalidate'])->name('emailvalidate');
});


//Reset Password
Route::prefix('resetpassword')->name('resetpassword.')->middleware('auth', 'ipcheck')->group(function () {
    Route::get('/resetpasswordindex', [ResetPasswordController::class, 'index'])->name('index');
    Route::post('/resetpasswordchange-password', [ResetPasswordController::class, 'changePassword'])->name('changepassword');
});

//Profile
Route::prefix('profile')->name('profile.')->middleware('auth', 'ipcheck')->group(function () {
    Route::get('/profileindex', [ProfileController::class, 'index'])->name('index');
    Route::post('/profileupdate', [ProfileController::class, 'updateProfile'])->name('update');
});

//--------------------------------------------------------------------------------------------------------------------------------------------------------------Wl-Admin-Start------------------------------------------------------------------------------------------------------------------------------------------------------------------
//dashboard
Route::prefix('wladmin')->name('wladmin.')->middleware('auth', 'ipcheck')->group(function () {
    Route::get('/wladminDashboard', [HomeController::class, 'index'])->name('dashboard');
});

//wl-component
Route::prefix('component')->name('component.')->middleware('auth', 'ipcheck')->group(function () {
    Route::any('/componentindex', [ComponentController::class, 'index'])->name('index');
    Route::get('/componentcreate', [ComponentController::class, 'createview'])->name('create');
    Route::post('/componentComponent/store', [ComponentController::class, 'companycomponentstore'])->name('companycomponentstore');

    Route::post('/getcomponent', [ComponentController::class, 'getcomponent'])->name('getcomponent');
    Route::get('/componentedit/{id}', [ComponentController::class, 'edit'])->name('edit');
    Route::post('/componentcompanycomponentupdate', [ComponentController::class, 'companycomponentupdate'])->name('companycomponentupdate');
    Route::delete('/componentdelete/{id}', [ComponentController::class, 'componentdelete'])->name('componentdelete');

    Route::get('/componentgeneral', [ComponentController::class, 'general'])->name('general');
    Route::get('/resolution-category-create', [ComponentController::class, 'resolutioncategory'])->name('resolution-category');
    Route::post('/resolution-category/store', [ComponentController::class, 'resolutioncategorycreatestore'])->name('resolutioncategorycreatestore');

    Route::get('/componentissue-create', [ComponentController::class, 'issuetypecreate'])->name('issue');
    Route::post('/componentissue/store', [ComponentController::class, 'issuetypestore'])->name('issuetypestore');

    Route::get('/Call-Competency-create', [ComponentController::class, 'CallCompetency'])->name('CallCompetency');
    Route::post('/Call-Competency/store', [ComponentController::class, 'callcompetencystore'])->name('callcompetencystore');

    Route::get('/Support-Type-create', [ComponentController::class, 'SupportType'])->name('Support-Type');
    Route::post('/Support-Type/store', [ComponentController::class, 'supporttypestore'])->name('supporttypestore');
});

//wl-role  Service Support Commission Support Knowledge Support
Route::prefix('role')->name('role.')->middleware('auth', 'ipcheck')->group(function () {
    Route::get('/roleindex', [RoleController::class, 'index'])->name('index');
    Route::get('/{Id?}/roleinfo', [RoleController::class, 'infoindex'])->name('info');
    Route::get('/roleuser-list', [RoleController::class, 'userlist'])->name('user-list');
    Route::get('/rolecreate', [RoleController::class, 'createview'])->name('create');
    Route::post('/rolecreate', [RoleController::class, 'create'])->name('create');
    Route::get('/{Id?}/roleEdit', [RoleController::class, 'editview'])->name('edit');
    Route::post('/roleupdate', [RoleController::class, 'update'])->name('update');
    Route::delete('/roledelete/{Id}', [RoleController::class, 'delete'])->name('delete');
});

//wl-user
Route::prefix('user')->name('user.')->middleware('auth', 'ipcheck')->group(function () {
    Route::any('/wluserindex', [WlUserController::class, 'index'])->name('index');
    Route::get('/wluserinfo/{id?}', [WlUserController::class, 'infoindex'])->name('info');
    Route::get('/wlusercreate', [WlUserController::class, 'createview'])->name('create');
    Route::post('/wluserstore', [WlUserController::class, 'store'])->name('store');
    Route::get('wluseredit/{Id}', [WlUserController::class, 'editview'])->name('edit');
    Route::post('/wluserupdate', [WlUserController::class, 'update'])->name('update');
    Route::delete('/wluserdelete/{Id}', [WlUserController::class, 'delete'])->name('delete');
    Route::post('/wluserupdateStatus', [WlUserController::class, 'updateStatus'])->name('updateStatus');
    Route::post('/emailvalidate', [WlUserController::class, 'emailvalidate'])->name('emailvalidate');
});

//wl-distributor
Route::prefix('distributor')->name('distributor.')->middleware('auth', 'ipcheck')->group(function () {
    Route::any('/distributorindex', [DistributorController::class, 'index'])->name('index');
    Route::get('/{id?}/distributorcreate', [DistributorController::class, 'createview'])->name('create');
    Route::post('/distributorstore', [DistributorController::class, 'create'])->name('store');
    Route::get('/{Id}/distributoredit', [DistributorController::class, 'editview'])->name('edit');
    Route::post('/distributorupdate/{Id?}', [DistributorController::class, 'update'])->name('update');
    Route::delete('/distributordelete/{Id}', [DistributorController::class, 'delete'])->name('delete');
    Route::post('/distributorupdateStatus', [DistributorController::class, 'updateStatus'])->name('updateStatus');
    Route::get('/{Id}/distributorinfo', [DistributorController::class, 'infoview'])->name('info');

    Route::get('/{id?}/salescreate', [DistributorController::class, 'salescreate'])->name('salescreate');
    Route::post('/salesstore', [DistributorController::class, 'salesstore'])->name('salesstore');
    Route::get('/{id?}/salesedit', [DistributorController::class, 'saleseditview'])->name('salesedit');
    Route::post('/salesupdate', [DistributorController::class, 'salesupdate'])->name('salesupdate');
    Route::delete('/salesdelete/{Id}', [DistributorController::class, 'salesdelete'])->name('salesdelete');

    Route::get('/{id?}/technicalcreate', [DistributorController::class, 'technicalcreate'])->name('technicalcreate');
    Route::post('/technicalstore', [DistributorController::class, 'technicalstore'])->name('technicalstore');
    Route::get('/{id?}/technicaledit', [DistributorController::class, 'technicaleditview'])->name('technicaledit');
    Route::post('/technicalupdate', [DistributorController::class, 'technicalupdate'])->name('technicalupdate');
    Route::delete('/technicaldelete/{Id}', [DistributorController::class, 'technicaldelete'])->name('technicaldelete');

    Route::get('/{id?}/userdefinedcreate', [DistributorController::class, 'userdefinedcreate'])->name('userdefinedcreate');
    Route::post('/userdefinedstore', [DistributorController::class, 'userdefinedstore'])->name('userdefinedstore');
    Route::get('/{id?}/userdefinededit', [DistributorController::class, 'userdefinededitview'])->name('userdefinededit');
});

//wl-company client
Route::prefix('companyclient')->name('companyclient.')->middleware('auth', 'ipcheck')->group(function () {
    Route::any('/companyclientindex', [CompanyClientController::class, 'index'])->name('index');
    Route::get('/companyclientcreate', [CompanyClientController::class, 'createview'])->name('create');
    Route::post('/companyclientstore', [CompanyClientController::class, 'create'])->name('store');
    Route::get('/{Id}/companyclientinfo', [CompanyClientController::class, 'infoview'])->name('info');
    Route::get('/{id?}/companyclientbasicinfocreate', [CompanyClientController::class, 'basicinfocreate'])->name('basicinfocreate');
    Route::post('/companyclientbasicinfostore', [CompanyClientController::class, 'basicinfostore'])->name('basicinfostore');
    Route::get('/{Id}/companyclientedit', [CompanyClientController::class, 'editview'])->name('edit');
    Route::post('/companyclientupdate', [CompanyClientController::class, 'update'])->name('update');
    Route::delete('/companyclientdelete/{Id}', [CompanyClientController::class, 'delete'])->name('delete');
    Route::post('/companyclientupdateStatus', [CompanyClientController::class, 'updateStatus'])->name('updateStatus');

    Route::get('/{id?}/salescreate', [CompanyClientController::class, 'salescreate'])->name('salescreate');
    Route::post('/salesstore', [CompanyClientController::class, 'salesstore'])->name('salesstore');
    Route::get('/{id?}/salesedit', [CompanyClientController::class, 'saleseditview'])->name('salesedit');
    Route::post('/salesupdate', [CompanyClientController::class, 'salesupdate'])->name('salesupdate');
    Route::delete('/salesdelete/{Id}', [CompanyClientController::class, 'salesdelete'])->name('salesdelete');

    Route::get('/{id?}/technicalcreate', [CompanyClientController::class, 'technicalcreate'])->name('technicalcreate');
    Route::post('/technicalstore', [CompanyClientController::class, 'technicalstore'])->name('technicalstore');
    Route::get('/{id?}/technicaledit', [CompanyClientController::class, 'technicaleditview'])->name('technicaledit');
    Route::post('/technicalupdate', [CompanyClientController::class, 'technicalupdate'])->name('technicalupdate');
    Route::delete('/technicaldelete/{Id}', [CompanyClientController::class, 'technicaldelete'])->name('technicaldelete');

    Route::get('/{id?}/userdefinedcreate', [CompanyClientController::class, 'userdefinedcreate'])->name('userdefinedcreate');
    Route::post('/userdefinedstore', [CompanyClientController::class, 'userdefinedstore'])->name('userdefinedstore');
    Route::get('/{id?}/userdefinededit', [CompanyClientController::class, 'userdefinededitview'])->name('userdefinededit');

    Route::get('/uploadindex', [CompanyClientController::class, 'uploadindex'])->name('uploadindex');
    Route::post('/uploadsubmit', [CompanyClientController::class, 'uploadsubmit'])->name('uploadsubmit');
});

//wl-Faq
Route::prefix('faq')->name('faq.')->middleware('auth', 'ipcheck')->group(function () {
    Route::any('/faqindex', [FaqController::class, 'index'])->name('index');
    Route::get('/faqcreate', [FaqController::class, 'createview'])->name('create');
    Route::post('/faqgetsubcomponent', [FaqController::class, 'getsubcomponent'])->name('getsubcomponent');

    Route::post('/faqstore', [FaqController::class, 'store'])->name('store');
    Route::get('/{Id}/faqEdit', [FaqController::class, 'editview'])->name('edit');
    Route::get('/{Id}/faqInfo', [FaqController::class, 'infoview'])->name('info');
    Route::post('/faqupdate/{Id?}', [FaqController::class, 'update'])->name('update');
    Route::delete('/faqdelete/{Id}', [FaqController::class, 'delete'])->name('delete');
    Route::get('/{Id?}/openDocument', [FaqController::class, 'openDocument'])->name('openDocument');
    Route::delete('/deletedoc', [FaqController::class, 'deletedoc'])->name('deletedoc');
});



//wl-Projects
Route::prefix('projects')->name('projects.')->middleware('auth', 'ipcheck')->group(function () {
    Route::any('/projectsindex', [ProjectsController::class, 'index'])->name('index');
    Route::get('/projects/create', [ProjectsController::class, 'createview'])->name('create');
    Route::post('/faqgetsubcomponent', [ProjectsController::class, 'getsubcomponent'])->name('getsubcomponent');

    Route::post('/projects/store', [ProjectsController::class, 'store'])->name('store');
    Route::get('/projects/edit/{id?}', [ProjectsController::class, 'editview'])->name('edit');
    Route::get('/projects/Info/{id?}', [ProjectsController::class, 'infoview'])->name('info');
    Route::post('/projects/update/{id?}', [ProjectsController::class, 'update'])->name('update');
    Route::delete('/projects/delete/{id?}', [ProjectsController::class, 'delete'])->name('delete');
    Route::get('/{Id?}/openDocument', [ProjectsController::class, 'openDocument'])->name('openDocument');
    Route::delete('/deletedoc', [ProjectsController::class, 'deletedoc'])->name('deletedoc');
});






//wl-reference
Route::prefix('reference')->name('reference.')->middleware('auth', 'ipcheck')->group(function () {
    Route::any('/referenceindex', [ReferenceController::class, 'index'])->name('index');
    Route::get('/referencecreate', [ReferenceController::class, 'createview'])->name('create');
    Route::post('/referencestore', [ReferenceController::class, 'store'])->name('store');
    Route::get('/{Id}/referenceedit', [ReferenceController::class, 'editview'])->name('edit');
    Route::post('/referenceupdate', [ReferenceController::class, 'update'])->name('update');
    Route::delete('/referencedelete/{Id}', [ReferenceController::class, 'delete'])->name('delete');
    Route::get('/{Id}/referenceInfo', [ReferenceController::class, 'infoview'])->name('info');
    Route::get('/{Id?}/openDocument', [ReferenceController::class, 'openDocument'])->name('openDocument');
    Route::delete('/deletedoc', [ReferenceController::class, 'deletedoc'])->name('deletedoc');
});

//wl-call list
Route::prefix('callList')->name('callList.')->middleware('auth', 'ipcheck')->group(function () {
    Route::any('/callListindex', [CallListController::class, 'index'])->name('index');
    Route::get('/callListinfo/{id?}', [CallListController::class, 'info'])->name('info');
    Route::delete('/callListinfo/delete/{id?}', [CallListController::class, 'delete'])->name('delete');
});

//wl-setting
Route::prefix('setting')->name('setting.')->middleware('auth', 'ipcheck')->group(function () {
    Route::get('/settingindex', [SettingController::class, 'index'])->name('index');
    Route::post('/generalSetting', [SettingController::class, 'generalSetting'])->name('generalSetting');
    Route::post('/socialSetting', [SettingController::class, 'socialSetting'])->name('socialSetting');
    Route::post('/mailSetting', [SettingController::class, 'mailSetting'])->name('mailSetting');
    // Route::get('/info', [SettingController::class, 'info'])->name('info');
});

//wl-Reset Password
Route::prefix('wlresetpassword')->name('wlresetpassword.')->middleware('auth', 'ipcheck')->group(function () {
    Route::get('/wlresetpasswordindex', [WLResetPasswordController::class, 'index'])->name('index');
    Route::post('/wlresetpasswordchange-password', [WLResetPasswordController::class, 'changePassword'])->name('changepassword');
});

//wl-Profile
Route::prefix('wlprofile')->name('wlprofile.')->middleware('auth', 'ipcheck')->group(function () {
    Route::get('/wlprofileindex', [WLProfileController::class, 'index'])->name('index');
    Route::post('/wlprofileupdate', [WLProfileController::class, 'updateProfile'])->name('update');
    Route::get('/wlcompanyprofile', [WLProfileController::class, 'companyProfile'])->name('companyinfo');
});

//--------------------------------------------------------------------------------------------------------------------------------------------------------Call-Attendant-Start-----------------------------------------------------------------------------------------------------------------------------------------------------------------
//Call Attendant dashboard
Route::prefix('callattendantAdmin')->name('callattendantAdmin.')->middleware('auth', 'ipcheck')->group(function () {
    Route::get('/home', [CallAttendantAdminController::class, 'homeindex'])->name('home');
    Route::get('/dashboard', [CallAttendantAdminController::class, 'index'])->name('dashboard');
    Route::post('/create', [CallAttendantAdminController::class, 'create'])->name('create');
    Route::post('/checkCustNumber', [CallAttendantAdminController::class, 'checkCustNumber'])->name('checkCustNumber');
    Route::get('/checkCallResponse', [CallAttendantAdminController::class, 'checkCallResponse'])->name('checkCallResponse');
    Route::post('/autofillCustNumberListComplain', [CallAttendantAdminController::class, 'autofillCustNumberListComplain'])->name('autofillCustNumberListComplain');
    Route::post('/update', [CallAttendantAdminController::class, 'update'])->name('update');
});

//Call Attendant Password
Route::prefix('callattendantresetpassword')->name('callattendantresetpassword.')->middleware('auth', 'ipcheck')->group(function () {
    Route::get('/index', [CallAttendantResetPasswordController::class, 'index'])->name('index');
    Route::post('/change-password', [CallAttendantResetPasswordController::class, 'changePassword'])->name('changepassword');
});

//Call Attendant Profile
Route::prefix('callattendantprofile')->name('callattendantprofile.')->middleware('auth', 'ipcheck')->group(function () {
    Route::get('/index', [callattendantProfileController::class, 'index'])->name('index');
    Route::post('/update', [callattendantProfileController::class, 'updateProfile'])->name('update');
});

//Call Attendant Reference
Route::prefix('callattendantreference')->name('callattendantreference.')->middleware('auth', 'ipcheck')->group(function () {
    Route::any('/index', [CallAttendantReferenceController::class, 'index'])->name('index');
    Route::post('/downloadDoc', [CallAttendantReferenceController::class, 'downloadDoc'])->name('downloadDoc');
    Route::post('/emailDoc', [CallAttendantReferenceController::class, 'emailDoc'])->name('emailDoc');
    Route::get('/{Id?}/openDoc', [CallAttendantReferenceController::class, 'openDoc'])->name('openDoc');
    Route::post('/openDocuments', [CallAttendantReferenceController::class, 'openDocuments'])->name('openDocuments');
    Route::post('/getContenType', [CallAttendantReferenceController::class, 'getContenType'])->name('getContenType');
});

//Call Attendant Faq
Route::prefix('callattendantfaq')->name('callattendantfaq.')->middleware('auth', 'ipcheck')->group(function () {
    Route::any('/index', [CallAttendantFaqController::class, 'index'])->name('index');
});

//Call Attendant My Ticket
Route::prefix('my-tickets')->name('my-tickets.')->middleware('auth', 'ipcheck')->group(function () {
    Route::any('/index', [MyTicketController::class, 'index'])->name('index');
    Route::get('/create', [MyTicketController::class, 'createview'])->name('create');
    Route::post('/create', [MyTicketController::class, 'create'])->name('create');
    Route::get('/{Id}', [MyTicketController::class, 'editview'])->name('edit');
    Route::post('/update/{Id?}', [MyTicketController::class, 'update'])->name('update');
    Route::delete('/delete/{Id}', [MyTicketController::class, 'delete'])->name('delete');
});

//Call Attendant Complaint
Route::prefix('complaint')->name('complaint.')->middleware('auth', 'ipcheck')->group(function () {
    Route::any('/index', [ComplaintsController::class, 'index'])->name('index');
    Route::get('/info/{Id?}', [ComplaintsController::class, 'info'])->name('info');
    Route::get('/create', [ComplaintsController::class, 'createview'])->name('create');
    Route::post('/create', [ComplaintsController::class, 'create'])->name('create');
    Route::get('/edit/{Id?}', [ComplaintsController::class, 'editview'])->name('edit');
    Route::post('/update', [ComplaintsController::class, 'update'])->name('update');
    Route::delete('/delete/{Id}', [ComplaintsController::class, 'delete'])->name('delete');
    Route::get('getExecutives/{id?}', [ComplaintsController::class, 'getExecutives'])->name('getExecutives');
    Route::post('getOemCompannyExecutives', [ComplaintsController::class, 'getOemCompannyExecutives'])->name('getOemCompannyExecutives');
    Route::get('livecall', [ComplaintsController::class, 'livecall'])->name('livecall');

    //additional Images & Videos
    Route::post('/additional/images/videos/store/{id?}', [ComplaintsController::class, 'additionalImagesVideosStore'])->name('additionalImagesVideosStore');

    //additional Recording
    Route::post('/additional/recording/store/{id?}', [ComplaintsController::class, 'additionalrecording'])->name('additionalRecordingStore');
    Route::delete('/additional/recording/delete/{id?}', [ComplaintsController::class, 'additionalrecordingdelete'])->name('additionalRecordingdelete');
});

Route::get('livecall', [ComplaintsController::class, 'livecall'])->name('livecall');
Route::get('callDetail', [ComplaintsController::class, 'callDetail'])->name('callDetail');

//System Summary Report
Route::prefix('systemsummary')->name('systemsummary.')->middleware('auth', 'ipcheck')->group(function () {
    Route::any('/systemsummaryindex', [SystemSummaryReportController::class, 'index'])->name('index');
    Route::post('/systemsummaryinfo', [SystemSummaryReportController::class, 'info'])->name('info');
    Route::get('/systemsummarycallinfo/{id?}', [SystemSummaryReportController::class, 'callinfo'])->name('callinfo');
});

//City Summary Report
Route::prefix('citysummary')->name('citysummary.')->middleware('auth', 'ipcheck')->group(function () {
    Route::any('/citysummaryindex', [CitySummaryReportController::class, 'index'])->name('index');
    Route::post('/citysummaryinfo', [CitySummaryReportController::class, 'info'])->name('info');
    Route::get('/citysummarycallinfo/{id?}', [CitySummaryReportController::class, 'callinfo'])->name('callinfo');
});

//Distributor Summary Report
Route::prefix('distributorsummary')->name('distributorsummary.')->middleware('auth', 'ipcheck')->group(function () {
    Route::any('/distributorsummaryindex', [DistributorSummaryReportController::class, 'index'])->name('index');
    Route::post('/distributorsummaryinfo', [DistributorSummaryReportController::class, 'info'])->name('info');
    Route::get('/distributorsummarycallinfo/{id?}', [DistributorSummaryReportController::class, 'callinfo'])->name('callinfo');
});


//Company Summary Report
Route::prefix('companysummary')->name('companysummary.')->middleware('auth', 'ipcheck')->group(function () {
    Route::any('/companysummaryindex', [CompanySummaryReportController::class, 'index'])->name('index');
    Route::post('/companysummaryinfo', [CompanySummaryReportController::class, 'info'])->name('info');
    Route::get('/companysummarycallinfo/{id?}', [CompanySummaryReportController::class, 'callinfo'])->name('callinfo');
});

//Customer Summary Report
Route::prefix('customersummary')->name('customersummary.')->middleware('auth', 'ipcheck')->group(function () {
    Route::any('/customersummaryindex', [CustomerSummaryReportController::class, 'index'])->name('index');
    Route::post('/customersummaryinfo', [CustomerSummaryReportController::class, 'info'])->name('info');
    Route::get('/customersummarycallinfo/{id?}', [CustomerSummaryReportController::class, 'callinfo'])->name('callinfo');
});

// Top Analytic Report
Route::prefix('topanalytic')->name('topanalytic.')->middleware('auth', 'ipcheck')->group(function () {
    Route::any('/topanalyticCities', [TopAnalyticReportController::class, 'index'])->name('index');
    Route::any('/topanalyticCompanies', [TopAnalyticReportController::class, 'topCompanies'])->name('topCompanies');
    Route::any('/topanalyticSystems', [TopAnalyticReportController::class, 'topSystems'])->name('topSystems');
    Route::any('/topanalyticCustomers', [TopAnalyticReportController::class, 'topCustomers'])->name('topCustomers');
});

Route::get('/reload-captcha', [CaptchaServiceController::class, 'reloadCaptcha']);
Route::get('/forgot-password', [ForgotPasswordController::class, 'index'])->name('forgotpassword.index');
Route::post('/forgot-password-sent', [ForgotPasswordController::class, 'forgotsubmit'])->name('forgotpassword.forgotsubmit');
Route::get('/reset-password/{id?}', [ForgotPasswordController::class, 'resetpassword'])->name('forgotpassword.resetpassword');
Route::post('/reset-password-submit', [ForgotPasswordController::class, 'resetpasswordsubmit'])->name('forgotpassword.resetpasswordsubmit');


//report call list
Route::prefix('call_report')->name('call_report.')->middleware('auth', 'ipcheck')->group(function () {
    Route::any('/callreportindex', [CallReportController::class, 'index'])->name('index');
    Route::get('/callreportinfo/{id?}', [CallReportController::class, 'infoindex'])->name('info');
    Route::post('/getsystem', [CallReportController::class, 'getsystem'])->name('getsystem');
    Route::post('/getCallAttendant', [CallReportController::class, 'getCallAttendant'])->name('getCallAttendant');
    Route::post('/getSupportType', [CallReportController::class, 'getSupportType'])->name('getSupportType');
});

// CustomerList
Route::prefix('customer_list')->name('customer_list.')->middleware('auth', 'ipcheck')->group(function () {
    Route::any('/customer_index', [AdminCustomerListController::class, 'index'])->name('index');
    Route::post('/customer_info', [AdminCustomerListController::class, 'info'])->name('info');
    Route::get('/customercallinfo/{id?}', [AdminCustomerListController::class, 'callinfo'])->name('callinfo');
});
// customer_company_list
Route::prefix('customer_company_list')->name('customer_company_list.')->middleware('auth', 'ipcheck')->group(function () {
    Route::any('/customer_company_index', [AdminCustomerCompanyListController::class, 'index'])->name('index');
    Route::get('/customer_company_info/{id?}', [AdminCustomerCompanyListController::class, 'info'])->name('info');
    Route::post('/getCompanyClient', [AdminCustomerCompanyListController::class, 'getCompanyClient'])->name('getCompanyClient');
});

// distributor_list
Route::prefix('distributor_list')->name('distributor_list.')->middleware('auth', 'ipcheck')->group(function () {
    Route::any('/distributor_index', [AdminDistributorListController::class, 'index'])->name('index');
    Route::get('/distributor_info/{id?}', [AdminDistributorListController::class, 'info'])->name('info');
    Route::post('/getCompanyDistributor', [AdminDistributorListController::class, 'getCompanyDistributor'])->name('getCompanyDistributor');
});

// Admin Dashborad

Route::prefix('admindashboard')->name('admindashboard.')->middleware('auth', 'ipcheck')->group(function () {
    Route::post('/getCountData', [AdminDashboardController::class, 'index'])->name('getCountData');
    Route::post('/get_Bar_Chart', [AdminDashboardController::class, 'getBarChart'])->name('getBarChart');
    Route::post('/get_Line_Chart', [AdminDashboardController::class, 'getLineChart'])->name('getLineChart');
    Route::post('/get_LineChart_SameDay_Resolve', [AdminDashboardController::class, 'getLineChartSameDayResolve'])->name('getLineChartSameDayResolve');
    Route::post('/get_Line_Chart_LevelTwo', [AdminDashboardController::class, 'getLineChartLevelTwo'])->name('getLineChartLevelTwo');
    Route::post('/get_Pia_Chart', [AdminDashboardController::class, 'getPiaChart'])->name('getPiaChart');
});


//user attendance
Route::prefix('attendance')->name('attendance.')->middleware('auth', 'ipcheck')->group(function () {
    Route::any('/attendanceindex', [CallReportController::class, 'attendanceindex'])->name('index');
    Route::any('/attendanceinfo', [CallReportController::class, 'attendanceinfoindex'])->name('info');
    Route::any('/attendancedownload', [CallReportController::class, 'attendancedownload'])->name('download');
});

//Download Call
Route::prefix('downloadCall')->name('downloadCall.')->middleware('auth', 'ipcheck')->group(function () {
    Route::any('/downloadcallindex', [DownloadCallController::class, 'index'])->name('index');
    Route::any('/downloadcalldownload', [DownloadCallController::class, 'download'])->name('download');
});

Route::prefix('distributor')->name('distributor.')->middleware('auth', 'ipcheck')->group(function () {
    Route::get('/genrateDistributorExcel/{fname?}/{contact?}/{email?}/{state?}/{city?}/{daterange?}', [DistributorController::class, 'genrateDistributorExcel'])->name('genrateDistributorExcel');
});

//wl-company client
Route::prefix('companyclient')->name('companyclient.')->middleware('auth', 'ipcheck')->group(function () {
    Route::any('/genrateCompanyClientExcel/{fname?}/{email?}/{state?}/{city?}/{daterange?}', [CompanyClientController::class, 'genrateCompanyClientExcel'])->name('genrateCompanyClientExcel');
});

//distributor_list
Route::prefix('distributor_list')->name('distributor_list.')->middleware('auth', 'ipcheck')->group(function () {
    Route::get('/genrateDistributorExcel/{OEMCompany?}/{iDistributorId?}/{state?}/{city?}', [AdminDistributorListController::class, 'genrateDistributorExcel'])->name('genrateDistributorExcel');
});
//customer_company_list.genrateCustomerCompanyExcel
Route::prefix('customer_company_list')->name('customer_company_list.')->middleware('auth', 'ipcheck')->group(function () {
    Route::get('/genrateCustomerCompanyExcel/{OEMCompany?}/{iCompanyClientId?}/{state?}/{city?}', [AdminCustomerCompanyListController::class, 'genrateCustomerCompanyExcel'])->name('genrateCustomerCompanyExcel');
});
