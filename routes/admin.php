<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Admin_panel_settingController;
use App\Http\Controllers\Admin\Finance_calendersController;
use App\Http\Controllers\Admin\BranchesController;
use App\Http\Controllers\Admin\ShiftsTypesController;
use App\Http\Controllers\Admin\DepartementsController;
use App\Http\Controllers\Admin\Jobs_categoriesController;
use App\Http\Controllers\Admin\QualificationsController;
use App\Http\Controllers\Admin\OccasionsController;
use App\Http\Controllers\Admin\ResignationsController;
use App\Http\Controllers\Admin\NationalitiesController;
use App\Http\Controllers\Admin\ReligionController;
use App\Http\Controllers\Admin\EmployeesController;
use App\Http\Controllers\Admin\AdditionalTypesController;
use App\Http\Controllers\Admin\AdminBranchesController;
use App\Http\Controllers\Admin\Alerts_system_monitoringController;
use App\Http\Controllers\Admin\DiscountTypesController;
use App\Http\Controllers\Admin\AllowancesController;
use App\Http\Controllers\Admin\Main_salary_employees_sanctionsController;
use App\Http\Controllers\Admin\MainSalaryRecord;
use App\Http\Controllers\Admin\Main_salary_employee_absenceController;
use App\Http\Controllers\Admin\Main_salary_employees_addtionController;
use App\Http\Controllers\Admin\Main_salary_employees_discoundController;
use App\Http\Controllers\Admin\Main_salary_employees_rewardsController;
use App\Http\Controllers\Admin\Main_salary_employees_allowancesController;
use App\Http\Controllers\Admin\Main_salary_employees_loansController;
use App\Http\Controllers\Admin\Main_salary_employees_p_loansController;
use App\Http\Controllers\Admin\Main_salary_employeeController;
use App\Http\Controllers\Admin\attenance_departureController;
use App\Http\Controllers\Admin\MainEmployessInvestigationsController;
use App\Http\Controllers\Admin\MainVacationsBalanceController;
use App\Http\Controllers\Admin\UserprofileController;
use App\Http\Controllers\Admin\Permission_rolesController;
use App\Http\Controllers\Admin\Permission_main_menuesController;
use App\Http\Controllers\Admin\Permission_sub_menuesController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\GrantsController;
use App\Http\Controllers\Admin\main_direct_grantsController;
use App\Http\Controllers\Admin\main_salary_employee_settlementsController;
use App\Http\Controllers\Admin\mainEmployeesDirectRewardsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

define('PC', 10);
Route::group(['prefix' => 'admin', 'middleware' => 'auth:admin'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/logout', [LoginController::class, 'logout'])->name('admin.logout');
    /*  بداية الضبط العام */
    Route::get('/generalSettings', [Admin_panel_settingController::class, 'index'])->name('admin_panel_settings.index');
    Route::get('/generalSettingsEdit', [Admin_panel_settingController::class, 'edit'])->name('admin_panel_settings.edit');
    Route::post('/generalSettingsupdate', [Admin_panel_settingController::class, 'update'])->name('admin_panel_settings.update');
    /*  بداية  تكويد السنوات المالية */
    Route::get('/finance_calender/delete/{id}', [Finance_calendersController::class, 'destroy'])->name('finance_calender.delete');
    Route::post('/finance_calender/show_year_monthes', [Finance_calendersController::class, 'show_year_monthes'])->name('finance_calender.show_year_monthes');
    Route::get('/finance_calender/do_open/{id}', [Finance_calendersController::class, 'do_open'])->name('finance_calender.do_open');
    Route::resource('/finance_calender', Finance_calendersController::class);
    /* بداية الفروع */
    Route::get("/branches", [BranchesController::class, 'index'])->name('branches.index');
    Route::get("/branchesCreate", [BranchesController::class, 'create'])->name('branches.create');
    Route::post("/branchesStore", [BranchesController::class, 'store'])->name('branches.store');
    Route::get("/branchesEdit/{id}", [BranchesController::class, 'edit'])->name('branches.edit');
    Route::post("/branchesUpdate/{id}", [BranchesController::class, 'update'])->name('branches.update');
    Route::get("/branchesDelete/{id}", [BranchesController::class, 'destroy'])->name('branches.destroy');
    /* بداية انواع شفتات الموظفين */
    Route::get("/ShiftsTypes", [ShiftsTypesController::class, 'index'])->name('ShiftsTypes.index');
    Route::get("/ShiftsTypesCreate", [ShiftsTypesController::class, 'create'])->name('ShiftsTypes.create');
    Route::post("/ShiftsTypesStore", [ShiftsTypesController::class, 'store'])->name('ShiftsTypes.store');
    Route::get("/ShiftsTypesEdit/{id}", [ShiftsTypesController::class, 'edit'])->name('ShiftsTypes.edit');
    Route::post("/ShiftsTypesUpdate/{id}", [ShiftsTypesController::class, 'update'])->name('ShiftsTypes.update');
    Route::get("/ShiftsTypesDestroy/{id}", [ShiftsTypesController::class, 'destroy'])->name('ShiftsTypes.destroy');
    Route::post("/ShiftsTypesajax_search/", [ShiftsTypesController::class, 'ajax_search'])->name('ShiftsTypes.ajax_search');
    /*  بداية الادارات*/
    Route::get('/departements', [DepartementsController::class, 'index'])->name('departements.index');
    Route::get('/departementsCreate', [DepartementsController::class, 'create'])->name('departements.create');
    Route::post('/departementsStore', [DepartementsController::class, 'store'])->name('departements.store');
    Route::get('/departementsEdit/{id}', [DepartementsController::class, 'edit'])->name('departements.edit');
    Route::post('/departementsUpdate/{id}', [DepartementsController::class, 'update'])->name('departements.update');
    Route::get('/departementsDestroy/{id}', [DepartementsController::class, 'destroy'])->name('departements.destroy');
    /*  بداية فئات الوظائف*/
    Route::get('/jobs_categories', [Jobs_categoriesController::class, 'index'])->name('jobs_categories.index');
    Route::get('/jobs_categoriesCreate', [Jobs_categoriesController::class, 'create'])->name('jobs_categories.create');
    Route::post('/jobs_categoriesStore', [Jobs_categoriesController::class, 'store'])->name('jobs_categories.store');
    Route::get('/jobs_categoriesEdit/{id}', [Jobs_categoriesController::class, 'edit'])->name('jobs_categories.edit');
    Route::post('/jobs_categoriesUpdate/{id}', [Jobs_categoriesController::class, 'update'])->name('jobs_categories.update');
    Route::get('/jobs_categoriesDestroy/{id}', [Jobs_categoriesController::class, 'destroy'])->name('jobs_categories.destroy');
    /*  بداية مؤهلات الموظفين*/
    Route::get('/Qualifications', [QualificationsController::class, 'index'])->name('Qualifications.index');
    Route::get('/QualificationsCreate', [QualificationsController::class, 'create'])->name('Qualifications.create');
    Route::post('/QualificationsStore', [QualificationsController::class, 'store'])->name('Qualifications.store');
    Route::get('/QualificationsEdit/{id}', [QualificationsController::class, 'edit'])->name('Qualifications.edit');
    Route::post('/QualificationsUpdate/{id}', [QualificationsController::class, 'update'])->name('Qualifications.update');
    Route::get('/QualificationsDestroy/{id}', [QualificationsController::class, 'destroy'])->name('Qualifications.destroy');
    /*  بداية  المناسبات الرسمية*/
    Route::get('/occasions', [OccasionsController::class, 'index'])->name('occasions.index');
    Route::get('/occasionsCreate', [OccasionsController::class, 'create'])->name('occasions.create');
    Route::post('/occasionsStore', [OccasionsController::class, 'store'])->name('occasions.store');
    Route::get('/occasionsEdit/{id}', [OccasionsController::class, 'edit'])->name('occasions.edit');
    Route::post('/occasionsUpdate/{id}', [OccasionsController::class, 'update'])->name('occasions.update');
    Route::get('/occasionsDestroy/{id}', [OccasionsController::class, 'destroy'])->name('occasions.destroy');


    /*  بداية  انواع ترك العمل */
    Route::get('/Resignations', [ResignationsController::class, 'index'])->name('Resignations.index');
    Route::get('/ResignationsCreate', [ResignationsController::class, 'create'])->name('Resignations.create');
    Route::post('/ResignationsStore', [ResignationsController::class, 'store'])->name('Resignations.store');
    Route::get('/ResignationsEdit/{id}', [ResignationsController::class, 'edit'])->name('Resignations.edit');
    Route::post('/ResignationsUpdate/{id}', [ResignationsController::class, 'update'])->name('Resignations.update');
    Route::get('/ResignationsDestroy/{id}', [ResignationsController::class, 'destroy'])->name('Resignations.destroy');

    /*  بداية  انواع  الجنسيات */
    Route::get('/Nationalities', [NationalitiesController::class, 'index'])->name('Nationalities.index');
    Route::get('/NationalitiesCreate', [NationalitiesController::class, 'create'])->name('Nationalities.create');
    Route::post('/NationalitiesStore', [NationalitiesController::class, 'store'])->name('Nationalities.store');
    Route::get('/NationalitiesEdit/{id}', [NationalitiesController::class, 'edit'])->name('Nationalities.edit');
    Route::post('/NationalitiesUpdate/{id}', [NationalitiesController::class, 'update'])->name('Nationalities.update');
    Route::get('/NationalitiesDestroy/{id}', [NationalitiesController::class, 'destroy'])->name('Nationalities.destroy');

    /*  بداية  انواع  الديانات */
    Route::get('/Religions/index', [ReligionController::class, 'index'])->name('Religions.index');
    Route::get('/Religions/create', [ReligionController::class, 'create'])->name('Religions.create');
    Route::post('/Religions/store', [ReligionController::class, 'store'])->name('Religions.store');
    Route::get('/Religions/edit/{id}', [ReligionController::class, 'edit'])->name('Religions.edit');
    Route::post('/Religions/update/{id}', [ReligionController::class, 'update'])->name('Religions.update');
    Route::get('/Religions/destroy/{id}', [ReligionController::class, 'destroy'])->name('Religions.destroy');

    /*  بداية  الموظفين   */
    Route::get('/Employees/index', [EmployeesController::class, 'index'])->name('Employees.index');
    Route::get('/Employees/create', [EmployeesController::class, 'create'])->name('Employees.create');
    Route::post('/Employees/store', [EmployeesController::class, 'store'])->name('Employees.store');
    Route::get('/Employees/edit/{id}', [EmployeesController::class, 'edit'])->name('Employees.edit');
    Route::post('/Employees/update/{id}', [EmployeesController::class, 'update'])->name('Employees.update');
    Route::get('/Employees/destroy/{id}', [EmployeesController::class, 'destroy'])->name('Employees.destroy');
    Route::post("/Employees/get_governorates", [EmployeesController::class, 'get_governorates'])->name('Employees.get_governorates');
    Route::post("/Employees/get_centers", [EmployeesController::class, 'get_centers'])->name('Employees.get_centers');
    Route::get('/Employees/show/{id}', [EmployeesController::class, 'show'])->name('Employees.show');
    Route::post("/Employees/ajax_search", [EmployeesController::class, 'ajax_search'])->name('Employees.ajax_search');
    Route::get('/Employees/download/{id}/{field_name}', [EmployeesController::class, 'download'])->name('Employees.download');
    Route::post('/Employees/add_files/{id}', [EmployeesController::class, 'add_files'])->name('Employees.add_files');
    Route::get('/Employees/download_files/{id}', [EmployeesController::class, 'download_files'])->name('Employees.download_files');
    Route::get('/Employees/destroy_files/{id}', [EmployeesController::class, 'destroy_files'])->name('Employees.destroy_files');
    Route::post('/Employees/add_allowances/{id}', [EmployeesController::class, 'add_allowances'])->name('Employees.add_allowances');
    Route::get('/Employees/destroy_allowances/{id}', [EmployeesController::class, 'destroy_allowances'])->name('Employees.destroy_allowances');
    Route::post("/Employees/load_edit_allowances", [EmployeesController::class, 'load_edit_allowances'])->name('Employees.load_edit_allowances');
    Route::post("/Employees/do_edit_allowances/{id}", [EmployeesController::class, 'do_edit_allowances'])->name('Employees.do_edit_allowances');
    Route::post("/Employees/showSalaryArchive", [EmployeesController::class, 'showSalaryArchive'])->name('Employees.showSalaryArchive');



    /*  بداية  انواع  اضافى الراتب */
    Route::get('/AdditionalTypes/index', [AdditionalTypesController::class, 'index'])->name('AdditionalTypes.index');
    Route::get('/AdditionalTypes/create', [AdditionalTypesController::class, 'create'])->name('AdditionalTypes.create');
    Route::post('/AdditionalTypes/store', [AdditionalTypesController::class, 'store'])->name('AdditionalTypes.store');
    Route::get('/AdditionalTypes/edit/{id}', [AdditionalTypesController::class, 'edit'])->name('AdditionalTypes.edit');
    Route::post('/AdditionalTypes/update/{id}', [AdditionalTypesController::class, 'update'])->name('AdditionalTypes.update');
    Route::get('/AdditionalTypes/destroy/{id}', [AdditionalTypesController::class, 'destroy'])->name('AdditionalTypes.destroy');

    /*  بداية  انواع  الخصم الراتب */
    Route::get('/DiscountTypes/index', [DiscountTypesController::class, 'index'])->name('DiscountTypes.index');
    Route::get('/DiscountTypes/create', [DiscountTypesController::class, 'create'])->name('DiscountTypes.create');
    Route::post('/DiscountTypes/store', [DiscountTypesController::class, 'store'])->name('DiscountTypes.store');
    Route::get('/DiscountTypes/edit/{id}', [DiscountTypesController::class, 'edit'])->name('DiscountTypes.edit');
    Route::post('/DiscountTypes/update/{id}', [DiscountTypesController::class, 'update'])->name('DiscountTypes.update');
    Route::get('/DiscountTypes/destroy/{id}', [DiscountTypesController::class, 'destroy'])->name('DiscountTypes.destroy');


    /*  بداية  انواع  البدالات الراتب */
    Route::get('/Allowances/index', [AllowancesController::class, 'index'])->name('Allowances.index');
    Route::get('/Allowances/create', [AllowancesController::class, 'create'])->name('Allowances.create');
    Route::post('/Allowances/store', [AllowancesController::class, 'store'])->name('Allowances.store');
    Route::get('/Allowances/edit/{id}', [AllowancesController::class, 'edit'])->name('Allowances.edit');
    Route::post('/Allowances/update/{id}', [AllowancesController::class, 'update'])->name('Allowances.update');
    Route::get('/Allowances/destroy/{id}', [AllowancesController::class, 'destroy'])->name('Allowances.destroy');
    /********************************** نهاية  موديول بيانات الموظفين  ******************************************************************************************************/
    /********************************** بداية  موديول المرتبات  ******************************************************************************************************/
    /*  بداية  السجلات الرئيسية للرواتب */
    Route::get('/MainSalaryRecord/index', [MainSalaryRecord::class, 'index'])->name('MainSalaryRecord.index');
    Route::post('/MainSalaryRecord/do_open_month/{id}', [MainSalaryRecord::class, 'do_open_month'])->name('MainSalaryRecord.do_open_month');
    Route::post('/MainSalaryRecord/load_open_monthModel', [MainSalaryRecord::class, 'load_open_monthModel'])->name('MainSalaryRecord.load_open_monthModel');
    Route::get('/MainSalaryRecord/do_close_month/{id}', [MainSalaryRecord::class, 'do_close_month'])->name('MainSalaryRecord.do_close_month');
    Route::get('/MainSalaryRecord/open_month_admin/{id}', [MainSalaryRecord::class, 'open_month_admin'])->name('MainSalaryRecord.open_month_admin');

    //   Route::post('/MainSalaryRecord/store', [MainSalaryRecord::class, 'store'])->name('MainSalaryRecord.store');
    //  Route::get('/MainSalaryRecord/edit/{id}', [MainSalaryRecord::class, 'edit'])->name('MainSalaryRecord.edit');
    //   Route::post('/MainSalaryRecord/update/{id}', [MainSalaryRecord::class, 'update'])->name('MainSalaryRecord.update');
    //  Route::get('/MainSalaryRecord/destroy/{id}', [MainSalaryRecord::class, 'destroy'])->name('MainSalaryRecord.destroy');

    /*  بداية    جزاءات الرواتب */
    Route::get('/MainSalarySanctions/index', [Main_salary_employees_sanctionsController::class, 'index'])->name('MainSalarySanctions.index');
    Route::get('/MainSalarySanctions/show/{id}', [Main_salary_employees_sanctionsController::class, 'show'])->name('MainSalarySanctions.show');
    Route::post('/MainSalarySanctions/store', [Main_salary_employees_sanctionsController::class, 'store'])->name('MainSalarySanctions.store');
    Route::post('/MainSalarySanctions/checkExsistsBefore', [Main_salary_employees_sanctionsController::class, 'checkExsistsBefore'])->name('MainSalarySanctions.checkExsistsBefore');
    Route::post('/MainSalarySanctions/ajax_search', [Main_salary_employees_sanctionsController::class, 'ajax_search'])->name('MainSalarySanctions.ajax_search');
    Route::post('/MainSalarySanctions/delete_row', [Main_salary_employees_sanctionsController::class, 'delete_row'])->name('MainSalarySanctions.delete_row');
    Route::post('/MainSalarySanctions/load_edit_row', [Main_salary_employees_sanctionsController::class, 'load_edit_row'])->name('MainSalarySanctions.load_edit_row');
    Route::post('/MainSalarySanctions/do_edit_row', [Main_salary_employees_sanctionsController::class, 'do_edit_row'])->name('MainSalarySanctions.do_edit_row');
    Route::post('/MainSalarySanctions/print_search', [Main_salary_employees_sanctionsController::class, 'print_search'])->name('MainSalarySanctions.print_search');


    /*  بداية    غيابات الرواتب */
    Route::get('/Main_salary_employee_absence/index', [Main_salary_employee_absenceController::class, 'index'])->name('Main_salary_employee_absence.index');
    Route::get('/Main_salary_employee_absence/show/{id}', [Main_salary_employee_absenceController::class, 'show'])->name('Main_salary_employee_absence.show');
    Route::post('/Main_salary_employee_absence/store', [Main_salary_employee_absenceController::class, 'store'])->name('Main_salary_employee_absence.store');
    Route::post('/Main_salary_employee_absence/checkExsistsBefore', [Main_salary_employee_absenceController::class, 'checkExsistsBefore'])->name('Main_salary_employee_absence.checkExsistsBefore');
    Route::post('/Main_salary_employee_absence/ajax_search', [Main_salary_employee_absenceController::class, 'ajax_search'])->name('Main_salary_employee_absence.ajax_search');
    Route::post('/Main_salary_employee_absence/delete_row', [Main_salary_employee_absenceController::class, 'delete_row'])->name('Main_salary_employee_absence.delete_row');
    Route::post('/Main_salary_employee_absence/load_edit_row', [Main_salary_employee_absenceController::class, 'load_edit_row'])->name('Main_salary_employee_absence.load_edit_row');
    Route::post('/Main_salary_employee_absence/do_edit_row', [Main_salary_employee_absenceController::class, 'do_edit_row'])->name('Main_salary_employee_absence.do_edit_row');
    Route::post('/Main_salary_employee_absence/print_search', [Main_salary_employee_absenceController::class, 'print_search'])->name('Main_salary_employee_absence.print_search');

    /*  بداية    اضافى الرواتب */
    Route::get('/Main_salary_employees_addtion/index', [Main_salary_employees_addtionController::class, 'index'])->name('Main_salary_employees_addtion.index');
    Route::get('/Main_salary_employees_addtion/show/{id}', [Main_salary_employees_addtionController::class, 'show'])->name('Main_salary_employees_addtion.show');
    Route::post('/Main_salary_employees_addtion/store', [Main_salary_employees_addtionController::class, 'store'])->name('Main_salary_employees_addtion.store');
    Route::post('/Main_salary_employees_addtion/checkExsistsBefore', [Main_salary_employees_addtionController::class, 'checkExsistsBefore'])->name('Main_salary_employees_addtion.checkExsistsBefore');
    Route::post('/Main_salary_employees_addtion/ajax_search', [Main_salary_employees_addtionController::class, 'ajax_search'])->name('Main_salary_employees_addtion.ajax_search');
    Route::post('/Main_salary_employees_addtion/delete_row', [Main_salary_employees_addtionController::class, 'delete_row'])->name('Main_salary_employees_addtion.delete_row');
    Route::post('/Main_salary_employees_addtion/load_edit_row', [Main_salary_employees_addtionController::class, 'load_edit_row'])->name('Main_salary_employees_addtion.load_edit_row');
    Route::post('/Main_salary_employees_addtion/do_edit_row', [Main_salary_employees_addtionController::class, 'do_edit_row'])->name('Main_salary_employees_addtion.do_edit_row');
    Route::post('/Main_salary_employees_addtion/print_search', [Main_salary_employees_addtionController::class, 'print_search'])->name('Main_salary_employees_addtion.print_search');


    /*  بداية    الخصومات المالية الرواتب */
    Route::get('/Main_salary_employees_discound/index', [Main_salary_employees_discoundController::class, 'index'])->name('Main_salary_employees_discound.index');
    Route::get('/Main_salary_employees_discound/show/{id}', [Main_salary_employees_discoundController::class, 'show'])->name('Main_salary_employees_discound.show');
    Route::post('/Main_salary_employees_discound/store', [Main_salary_employees_discoundController::class, 'store'])->name('Main_salary_employees_discound.store');
    Route::post('/Main_salary_employees_discound/checkExsistsBefore', [Main_salary_employees_discoundController::class, 'checkExsistsBefore'])->name('Main_salary_employees_discound.checkExsistsBefore');
    Route::post('/Main_salary_employees_discound/ajax_search', [Main_salary_employees_discoundController::class, 'ajax_search'])->name('Main_salary_employees_discound.ajax_search');
    Route::post('/Main_salary_employees_discound/delete_row', [Main_salary_employees_discoundController::class, 'delete_row'])->name('Main_salary_employees_discound.delete_row');
    Route::post('/Main_salary_employees_discound/load_edit_row', [Main_salary_employees_discoundController::class, 'load_edit_row'])->name('Main_salary_employees_discound.load_edit_row');
    Route::post('/Main_salary_employees_discound/do_edit_row', [Main_salary_employees_discoundController::class, 'do_edit_row'])->name('Main_salary_employees_discound.do_edit_row');
    Route::post('/Main_salary_employees_discound/print_search', [Main_salary_employees_discoundController::class, 'print_search'])->name('Main_salary_employees_discound.print_search');

    /*  بداية   المكافئات المالية الرواتب */
    Route::get('/Main_salary_employees_rewards/index', [Main_salary_employees_rewardsController::class, 'index'])->name('Main_salary_employees_rewards.index');
    Route::get('/Main_salary_employees_rewards/show/{id}', [Main_salary_employees_rewardsController::class, 'show'])->name('Main_salary_employees_rewards.show');
    Route::post('/Main_salary_employees_rewards/store', [Main_salary_employees_rewardsController::class, 'store'])->name('Main_salary_employees_rewards.store');
    Route::post('/Main_salary_employees_rewards/checkExsistsBefore', [Main_salary_employees_rewardsController::class, 'checkExsistsBefore'])->name('Main_salary_employees_rewards.checkExsistsBefore');
    Route::post('/Main_salary_employees_rewards/ajax_search', [Main_salary_employees_rewardsController::class, 'ajax_search'])->name('Main_salary_employees_rewards.ajax_search');
    Route::post('/Main_salary_employees_rewards/delete_row', [Main_salary_employees_rewardsController::class, 'delete_row'])->name('Main_salary_employees_rewards.delete_row');
    Route::post('/Main_salary_employees_rewards/load_edit_row', [Main_salary_employees_rewardsController::class, 'load_edit_row'])->name('Main_salary_employees_rewards.load_edit_row');
    Route::post('/Main_salary_employees_rewards/do_edit_row', [Main_salary_employees_rewardsController::class, 'do_edit_row'])->name('Main_salary_employees_rewards.do_edit_row');
    Route::post('/Main_salary_employees_rewards/print_search', [Main_salary_employees_rewardsController::class, 'print_search'])->name('Main_salary_employees_rewards.print_search');

    /*  بداية   البدلات المالية الرواتب */
    Route::get('/Main_salary_employees_allowances/index', [Main_salary_employees_allowancesController::class, 'index'])->name('Main_salary_employees_allowances.index');
    Route::get('/Main_salary_employees_allowances/show/{id}', [Main_salary_employees_allowancesController::class, 'show'])->name('Main_salary_employees_allowances.show');
    Route::post('/Main_salary_employees_allowances/store', [Main_salary_employees_allowancesController::class, 'store'])->name('Main_salary_employees_allowances.store');
    Route::post('/Main_salary_employees_allowances/checkExsistsBefore', [Main_salary_employees_allowancesController::class, 'checkExsistsBefore'])->name('Main_salary_employees_allowances.checkExsistsBefore');
    Route::post('/Main_salary_employees_allowances/ajax_search', [Main_salary_employees_allowancesController::class, 'ajax_search'])->name('Main_salary_employees_allowances.ajax_search');
    Route::post('/Main_salary_employees_allowances/delete_row', [Main_salary_employees_allowancesController::class, 'delete_row'])->name('Main_salary_employees_allowances.delete_row');
    Route::post('/Main_salary_employees_allowances/load_edit_row', [Main_salary_employees_allowancesController::class, 'load_edit_row'])->name('Main_salary_employees_allowances.load_edit_row');
    Route::post('/Main_salary_employees_allowances/do_edit_row', [Main_salary_employees_allowancesController::class, 'do_edit_row'])->name('Main_salary_employees_allowances.do_edit_row');
    Route::post('/Main_salary_employees_allowances/print_search', [Main_salary_employees_allowancesController::class, 'print_search'])->name('Main_salary_employees_allowances.print_search');

    /*  بداية   السلف الشهرية  المالية الرواتب */
    Route::get('/Main_salary_employees_loans/index', [Main_salary_employees_loansController::class, 'index'])->name('Main_salary_employees_loans.index');
    Route::get('/Main_salary_employees_loans/show/{id}', [Main_salary_employees_loansController::class, 'show'])->name('Main_salary_employees_loans.show');
    Route::post('/Main_salary_employees_loans/store', [Main_salary_employees_loansController::class, 'store'])->name('Main_salary_employees_loans.store');
    Route::post('/Main_salary_employees_loans/checkExsistsBefore', [Main_salary_employees_loansController::class, 'checkExsistsBefore'])->name('Main_salary_employees_loans.checkExsistsBefore');
    Route::post('/Main_salary_employees_loans/ajax_search', [Main_salary_employees_loansController::class, 'ajax_search'])->name('Main_salary_employees_loans.ajax_search');
    Route::post('/Main_salary_employees_loans/delete_row', [Main_salary_employees_loansController::class, 'delete_row'])->name('Main_salary_employees_loans.delete_row');
    Route::post('/Main_salary_employees_loans/load_edit_row', [Main_salary_employees_loansController::class, 'load_edit_row'])->name('Main_salary_employees_loans.load_edit_row');
    Route::post('/Main_salary_employees_loans/do_edit_row', [Main_salary_employees_loansController::class, 'do_edit_row'])->name('Main_salary_employees_loans.do_edit_row');
    Route::post('/Main_salary_employees_loans/print_search', [Main_salary_employees_loansController::class, 'print_search'])->name('Main_salary_employees_loans.print_search');

    /*  بداية   السلف المستديمة  المالية الرواتب */
    Route::get('/Main_salary_employees_p_loans/index', [Main_salary_employees_p_loansController::class, 'index'])->name('Main_salary_employees_p_loans.index');
    Route::post('/Main_salary_employees_p_loans/load_edit_row', [Main_salary_employees_p_loansController::class, 'load_edit_row'])->name('Main_salary_employees_p_loans.load_edit_row');
    Route::post('/Main_salary_employees_p_loans/store', [Main_salary_employees_p_loansController::class, 'store'])->name('Main_salary_employees_p_loans.store');
    Route::post('/Main_salary_employees_p_loans/checkExsistsBefore', [Main_salary_employees_p_loansController::class, 'checkExsistsBefore'])->name('Main_salary_employees_p_loans.checkExsistsBefore');
    Route::post('/Main_salary_employees_p_loans/ajax_search', [Main_salary_employees_p_loansController::class, 'ajax_search'])->name('Main_salary_employees_p_loans.ajax_search');
    Route::get('/Main_salary_employees_p_loans/delete/{id}', [Main_salary_employees_p_loansController::class, 'delete'])->name('Main_salary_employees_p_loans.delete');
    Route::post('/Main_salary_employees_p_loans/load_akast_details', [Main_salary_employees_p_loansController::class, 'load_akast_details'])->name('Main_salary_employees_p_loans.load_akast_details');
    Route::post('/Main_salary_employees_p_loans/do_edit_row', [Main_salary_employees_p_loansController::class, 'do_edit_row'])->name('Main_salary_employees_p_loans.do_edit_row');
    Route::post('/Main_salary_employees_p_loans/print_search', [Main_salary_employees_p_loansController::class, 'print_search'])->name('Main_salary_employees_p_loans.print_search');
    Route::get('/Main_salary_employees_p_loans/do_dismissal_done_now/{id}', [Main_salary_employees_p_loansController::class, 'do_dismissal_done_now'])->name('Main_salary_employees_p_loans.do_dismissal_done_now');
    Route::post('/Main_salary_employees_p_loans/DoCachpayNow', [Main_salary_employees_p_loansController::class, 'DoCachpayNow'])->name('Main_salary_employees_p_loans.DoCachpayNow');

    /*  بداية  الرواتب النهائيه المفصله */
    Route::get('/Main_salary_employee/index', [Main_salary_employeeController::class, 'index'])->name('Main_salary_employee.index');
    Route::get('/Main_salary_employee/show/{id}', [Main_salary_employeeController::class, 'show'])->name('Main_salary_employee.show');
    Route::post('/Main_salary_employee/delete_salary', [Main_salary_employeeController::class, 'delete_salary'])->name('Main_salary_employee.delete_salary');
    Route::post('/Main_salary_employee/addManuallySalrary/{id}', [Main_salary_employeeController::class, 'addManuallySalrary'])->name('Main_salary_employee.addManuallySalrary');
    Route::get('/Main_salary_employee/showSalaryDetails/{id}', [Main_salary_employeeController::class, 'showSalaryDetails'])->name('Main_salary_employee.showSalaryDetails');
    Route::post('/Main_salary_employee/ajax_search', [Main_salary_employeeController::class, 'ajax_search'])->name('Main_salary_employee.ajax_search');
    Route::post('/Main_salary_employee/print_search', [Main_salary_employeeController::class, 'print_search'])->name('Main_salary_employee.print_search');
    Route::get('/Main_salary_employee/DoStopSalary/{id}', [Main_salary_employeeController::class, 'DoStopSalary'])->name('Main_salary_employee.DoStopSalary');
    Route::get('/Main_salary_employee/UnStopSalary/{id}', [Main_salary_employeeController::class, 'UnStopSalary'])->name('Main_salary_employee.UnStopSalary');
    Route::get('/Main_salary_employee/delete_salaryInternal/{id}', [Main_salary_employeeController::class, 'delete_salaryInternal'])->name('Main_salary_employee.delete_salaryInternal');
    Route::post('/Main_salary_employee/load_archive_salary', [Main_salary_employeeController::class, 'load_archive_salary'])->name('Main_salary_employee.load_archive_salary');
    Route::post('/Main_salary_employee/do_archive_salary/{id}', [Main_salary_employeeController::class, 'do_archive_salary'])->name('Main_salary_employee.do_archive_salary');
    Route::get('/Main_salary_employee/print_salary/{id}', [Main_salary_employeeController::class, 'print_salary'])->name('Main_salary_employee.print_salary');

    /********************************** نهاية موديول المرتبات  ******************************************************************************************************/
    /********************************** بداية موديول البصمة  ******************************************************************************************************/
    /*  بداية  موديل جهاز البصمة   */

    Route::get('/attenance_departure/index', [attenance_departureController::class, 'index'])->name('attenance_departure.index');
    Route::get('/attenance_departure/show/{id}', [attenance_departureController::class, 'show'])->name('attenance_departure.show');
    Route::get('/attenance_departure/show_passma_detalis/{employees_code}/{finance_cln_periods_id}', [attenance_departureController::class, 'show_passma_detalis'])->name('attenance_departure.show_passma_detalis');
    Route::post('/attenance_departure/ajax_search', [attenance_departureController::class, 'ajax_search'])->name('attenance_departure.ajax_search');
    Route::post('/attenance_departure/load_passma_archive', [attenance_departureController::class, 'load_passma_archive'])->name('attenance_departure.load_passma_archive');
    Route::get('/attenance_departure/print_one_passma/{employees_code}/{finance_cln_periods_id}', [attenance_departureController::class, 'print_one_passma'])->name('attenance_departure.print_one_passma');
    Route::get('/attenance_departure/uploadExcelFile/{id}', [attenance_departureController::class, 'uploadExcelFile'])->name('attenance_departure.uploadExcelFile');
    Route::post('/attenance_departure/DoUploadExcelFile/{id}', [attenance_departureController::class, 'DoUploadExcelFile'])->name('attenance_departure.DoUploadExcelFile');
    Route::post('/attenance_departure/load_active_Attendance_departure', [attenance_departureController::class, 'load_active_Attendance_departure'])->name('attenance_departure.load_active_Attendance_departure');
    Route::post('/attenance_departure/load_my_actions', [attenance_departureController::class, 'load_my_actions'])->name('attenance_departure.load_my_actions');
    Route::post('/attenance_departure/save_active_Attendance_departure', [attenance_departureController::class, 'save_active_Attendance_departure'])->name('attenance_departure.save_active_Attendance_departure');
    Route::post('/attenance_departure/redo_update_action', [attenance_departureController::class, 'redo_update_action'])->name('attenance_departure.redo_update_action');
    Route::post('/attenance_departure/do_is_outo_offect_passmaV', [attenance_departureController::class, 'do_is_outo_offect_passmaV'])->name('attenance_departure.do_is_outo_offect_passmaV');

    /*********************************************manwel attendance 1 ************************************************************************************************************/
    Route::get('/attenance_departure/show_Manoual_attennce/{finance_cln_periods_id}', [attenance_departureController::class, 'show_Manoual_attennce'])->name('attenance_departure.show_Manoual_attennce');
    Route::post('/attenance_departure/saveManual', [attenance_departureController::class, 'saveManual'])->name('attenance_departure.saveManual');
    Route::get('/attenance_departure/show_Manual_additional_hours/{finance_cln_periods_id}', [attenance_departureController::class, 'show_Manual_additional_hours'])->name('attenance_departure.show_Manual_additional_hours');
    Route::post('/attenance_departure/saveManualAdditional', [attenance_departureController::class, 'saveManualAdditional'])->name('attenance_departure.saveManualAdditional');
    Route::post('/attenance_departure/do_is_outo_offect_passmaV_all', [attenance_departureController::class, 'do_is_outo_offect_passmaV_all'])->name('attenance_departure.do_is_outo_offect_passmaV_all');
    /********************************************* end manwel attendance 1 ************************************************************************************************************/
    /******************************************************بدايه موديل الرصيد السنوى*****************************************************************************************/
    Route::get('/employees_vacations_balance/index', [MainVacationsBalanceController::class, 'index'])->name('employees_vacations_balance.index');
    Route::post("/employees_vacations_balance/ajax_search", [MainVacationsBalanceController::class, 'ajax_search'])->name('employees_vacations_balance.ajax_search');
    Route::get('/employees_vacations_balance/show/{id}', [MainVacationsBalanceController::class, 'show'])->name('employees_vacations_balance.show');
    Route::post('/employees_vacations_balance/load_edit_row', [MainVacationsBalanceController::class, 'load_edit_row'])->name('employees_vacations_balance.load_edit_row');
    Route::post('/employees_vacations_balance/do_edit_row', [MainVacationsBalanceController::class, 'do_edit_row'])->name('employees_vacations_balance.do_edit_row');
    /******************************************************نهايه موديل الرصيد السنوى*****************************************************************************************/
    /******************************************************بدايه موديل التحقياقات الاداريه*****************************************************************************************/
    Route::get('/main_EmployessInvestigations/index', [MainEmployessInvestigationsController::class, 'index'])->name('main_EmployessInvestigations.index');
    Route::get('/main_EmployessInvestigations/show/{id}', [MainEmployessInvestigationsController::class, 'show'])->name('main_EmployessInvestigations.show');
    Route::post('/main_EmployessInvestigations/store', [MainEmployessInvestigationsController::class, 'store'])->name('main_EmployessInvestigations.store');
    Route::post('/main_EmployessInvestigations/checkExsistsBefore', [MainEmployessInvestigationsController::class, 'checkExsistsBefore'])->name('main_EmployessInvestigations.checkExsistsBefore');
    Route::post('/main_EmployessInvestigations/ajax_search', [MainEmployessInvestigationsController::class, 'ajax_search'])->name('main_EmployessInvestigations.ajax_search');
    Route::post('/main_EmployessInvestigations/delete_row', [MainEmployessInvestigationsController::class, 'delete_row'])->name('main_EmployessInvestigations.delete_row');
    Route::post('/main_EmployessInvestigations/load_edit_row', [MainEmployessInvestigationsController::class, 'load_edit_row'])->name('main_EmployessInvestigations.load_edit_row');
    Route::post('/main_EmployessInvestigations/do_edit_row', [MainEmployessInvestigationsController::class, 'do_edit_row'])->name('main_EmployessInvestigations.do_edit_row');
    Route::post('/main_EmployessInvestigations/print_search', [MainEmployessInvestigationsController::class, 'print_search'])->name('main_EmployessInvestigations.print_search');
    /******************************************************نهايه موديل التحقياقات الاداريه*****************************************************************************************/
    /****************************************************** user profile start *****************************************************************************************/
    Route::get('/userprofile/index', [UserprofileController::class, 'index'])->name('userprofile.index');
    Route::get('/userprofile/edit', [UserprofileController::class, 'edit'])->name('userprofile.edit');
    Route::post('/userprofile/update', [UserprofileController::class, 'update'])->name('userprofile.update');

    /****************************************************** user profile end *****************************************************************************************/
    /****************************************************** موديل مراقبه النظام بدايه   *****************************************************************************************/
    Route::get('/system_monitoring/index', [Alerts_system_monitoringController::class, 'index'])->name('system_monitoring.index');
    Route::post('/system_monitoring/ajax_search', [Alerts_system_monitoringController::class, 'ajax_search'])->name('system_monitoring.ajax_search');
    Route::post('/system_monitoring/do_undo_mark', [Alerts_system_monitoringController::class, 'do_undo_mark'])->name('system_monitoring.do_undo_mark');

    /****************************************************** موديل مراقبه النظام نهايه   *****************************************************************************************/

    /*    ═══════ ೋღ start admins ღೋ ═══════                    */
    Route::get('/admins_accounts/index', [AdminController::class, 'index'])->name('admin.admins_accounts.index');
    Route::get('/admins_accounts/create', [AdminController::class, 'create'])->name('admin.admins_accounts.create');
    Route::post('/admins_accounts/store', [AdminController::class, 'store'])->name('admin.admins_accounts.store');
    Route::get('/admins_accounts/edit/{id}', [AdminController::class, 'edit'])->name('admin.admins_accounts.edit');
    Route::post('/admins_accounts/update/{id}', [AdminController::class, 'update'])->name('admin.admins_accounts.update');
    Route::post('/admins_accounts/ajax_search', [AdminController::class, 'ajax_search'])->name('admin.admins_accounts.ajax_search');
    Route::get('/admins_accounts/details/{id}', [AdminController::class, 'details'])->name('admin.admins_accounts.details');
    Route::post('/admins_accounts/add_treasuries/{id}', [AdminController::class, 'add_treasuries'])->name('admin.admins_accounts.add_treasuries');
    Route::get('/admins_accounts/delete_treasuries/{rowid}/{userid}', [AdminController::class, 'delete_treasuries'])->name('admin.admins_accounts.delete_treasuries');
    Route::post('/admins_accounts/add_stores/{id}', [AdminController::class, 'add_stores'])->name('admin.admins_accounts.add_stores');
    Route::get('/admins_accounts/delete_stores/{rowid}/{userid}', [AdminController::class, 'delete_stores'])->name('admin.admins_accounts.delete_stores');
    // عرض الفروع الخاصة بمستخدم معين
    Route::get('admins/branches/{id}', [AdminBranchesController::class, 'edit'])->name('admin.admins_accounts.branches_edit');
    // تحديث الفروع للمستخدم
    Route::post('admins/branches/{id}', [AdminBranchesController::class, 'update'])->name('admin.admins_accounts.branches_update');

    /*     ═══════ ೋღ end admins  ღೋ ═══════ 


/*     ═══════ ೋღ start  permission  ღೋ ═══════              */
    Route::get('/permission_roles/index', [Permission_rolesController::class, 'index'])->name('admin.permission_roles.index');
    Route::get('/permission_roles/create', [Permission_rolesController::class, 'create'])->name('admin.permission_roles.create');
    Route::get('/permission_roles/edit/{id}', [Permission_rolesController::class, 'edit'])->name('admin.permission_roles.edit');
    Route::get('/permission_roles/delete/{id}', [Permission_rolesController::class, 'delete'])->name('admin.permission_roles.delete');
    Route::post('/permission_roles/store', [Permission_rolesController::class, 'store'])->name('admin.permission_roles.store');
    Route::post('/permission_roles/update/{id}', [Permission_rolesController::class, 'update'])->name('admin.permission_roles.update');
    Route::get('/permission_roles/details/{id}', [Permission_rolesController::class, 'details'])->name('admin.permission_roles.details');
    Route::post('/permission_roles/Add_permission_main_menues/{id}', [Permission_rolesController::class, 'Add_permission_main_menues'])->name('admin.permission_roles.Add_permission_main_menues');
    Route::get('/permission_roles/delete_permission_main_menues/{id}', [Permission_rolesController::class, 'delete_permission_main_menues'])->name('admin.permission_roles.delete_permission_main_menues');
    Route::post('/permission_roles/load_add_permission_roles_sub_menu', [Permission_rolesController::class, 'load_add_permission_roles_sub_menu'])->name('admin.permission_roles.load_add_permission_roles_sub_menu');
    Route::post('/permission_roles/add_permission_roles_sub_menu/{id}', [Permission_rolesController::class, 'add_permission_roles_sub_menu'])->name('admin.permission_roles.add_permission_roles_sub_menu');
    Route::get('/permission_roles/delete_permission_sub_menues/{id}', [Permission_rolesController::class, 'delete_permission_sub_menues'])->name('admin.permission_roles.delete_permission_sub_menues');
    Route::post('/permission_roles/load_add_roles_actions', [Permission_rolesController::class, 'load_add_roles_actions'])->name('admin.permission_roles.load_add_roles_actions');
    Route::post('/permission_roles/add_roles_actions/{id}', [Permission_rolesController::class, 'add_roles_actions'])->name('admin.permission_roles.add_roles_actions');
    Route::get('/permission_roles/delete_permission_sub_menues_actions/{id}', [Permission_rolesController::class, 'delete_permission_sub_menues_actions'])->name('admin.permission_roles.delete_permission_sub_menues_actions');



    /*       ═══════ ೋღ  end permission ღೋ ═══════                 */

    /*     ═══════ ೋღ start  permission_main_menues  ღೋ ═══════              */
    Route::get('/permission_main_menues/index', [Permission_main_menuesController::class, 'index'])->name('admin.permission_main_menues.index');
    Route::get('/permission_main_menues/create', [Permission_main_menuesController::class, 'create'])->name('admin.permission_main_menues.create');
    Route::get('/permission_main_menues/edit/{id}', [Permission_main_menuesController::class, 'edit'])->name('admin.permission_main_menues.edit');
    Route::post('/permission_main_menues/store', [Permission_main_menuesController::class, 'store'])->name('admin.permission_main_menues.store');
    Route::post('/permission_main_menues/update/{id}', [Permission_main_menuesController::class, 'update'])->name('admin.permission_main_menues.update');
    Route::get('/permission_main_menues/delete/{id}', [Permission_main_menuesController::class, 'delete'])->name('admin.permission_main_menues.delete');

    /*       ═══════ ೋღ  end permission_main_menues ღೋ ═══════                 */

    /*     ═══════ ೋღ start  permission_sub_menues  ღೋ ═══════              */
    Route::get('/permission_sub_menues/index', [Permission_sub_menuesController::class, 'index'])->name('admin.permission_sub_menues.index');
    Route::get('/permission_sub_menues/create', [Permission_sub_menuesController::class, 'create'])->name('admin.permission_sub_menues.create');
    Route::get('/permission_sub_menues/edit/{id}', [Permission_sub_menuesController::class, 'edit'])->name('admin.permission_sub_menues.edit');
    Route::post('/permission_sub_menues/store', [Permission_sub_menuesController::class, 'store'])->name('admin.permission_sub_menues.store');
    Route::post('/permission_sub_menues/update/{id}', [Permission_sub_menuesController::class, 'update'])->name('admin.permission_sub_menues.update');
    Route::post('/permission_sub_menues/ajax_search/', [Permission_sub_menuesController::class, 'ajax_search'])->name('admin.permission_sub_menues.ajax_search');
    Route::post('/permission_sub_menues/ajax_do_add_permission/', [Permission_sub_menuesController::class, 'ajax_do_add_permission'])->name('admin.permission_sub_menues.ajax_do_add_permission');
    Route::post('/permission_sub_menues/ajax_load_edit_permission/', [Permission_sub_menuesController::class, 'ajax_load_edit_permission'])->name('admin.permission_sub_menues.ajax_load_edit_permission');
    Route::post('/permission_sub_menues/ajax_do_edit_permission/', [Permission_sub_menuesController::class, 'ajax_do_edit_permission'])->name('admin.permission_sub_menues.ajax_do_edit_permission');
    Route::get('/permission_sub_menues/delete/{id}', [Permission_sub_menuesController::class, 'delete'])->name('admin.permission_sub_menues.delete');
    Route::post('/permission_sub_menues/ajax_do_delete_permission/', [Permission_sub_menuesController::class, 'ajax_do_delete_permission'])->name('admin.permission_sub_menues.ajax_do_delete_permission');
/*     ═══════ ೋღ end  permission_sub_menues  ღೋ ═══════              */



    /******************************************************بدايه موديل التسويات *****************************************************************************************/
    Route::get('/MainEmployeeSettlements/index', [main_salary_employee_settlementsController::class, 'index'])->name('MainEmployeeSettlements.index');
    Route::get('/MainEmployeeSettlements/show/{id}', [main_salary_employee_settlementsController::class, 'show'])->name('MainEmployeeSettlements.show');
    Route::post('/MainEmployeeSettlements/store', [main_salary_employee_settlementsController::class, 'store'])->name('MainEmployeeSettlements.store');
    Route::post('/MainEmployeeSettlements/checkExsistsBefore', [main_salary_employee_settlementsController::class, 'checkExsistsBefore'])->name('MainEmployeeSettlements.checkExsistsBefore');
    Route::post('/MainEmployeeSettlements/ajax_search', [main_salary_employee_settlementsController::class, 'ajax_search'])->name('MainEmployeeSettlements.ajax_search');
    Route::post('/MainEmployeeSettlements/delete_row', [main_salary_employee_settlementsController::class, 'delete_row'])->name('MainEmployeeSettlements.delete_row');
    Route::post('/MainEmployeeSettlements/load_edit_row', [main_salary_employee_settlementsController::class, 'load_edit_row'])->name('MainEmployeeSettlements.load_edit_row');
    Route::post('/MainEmployeeSettlements/update/{id}', [main_salary_employee_settlementsController::class, 'update'])->name('MainEmployeeSettlements.update');
    Route::post('/MainEmployeeSettlements/print_search', [main_salary_employee_settlementsController::class, 'print_search'])->name('MainEmployeeSettlements.print_search');
    /******************************************************نهايه موديل التسويات *****************************************************************************************/

    /*  بداية   المكافئات المالية الرواتب */
    Route::get('/Main_salary_Directrewards/index', [mainEmployeesDirectRewardsController::class, 'index'])->name('Main_salary_Directrewards.index');
    Route::get('/Main_salary_Directrewards/show/{id}', [mainEmployeesDirectRewardsController::class, 'show'])->name('Main_salary_Directrewards.show');
    Route::post('/Main_salary_Directrewards/store', [mainEmployeesDirectRewardsController::class, 'store'])->name('Main_salary_Directrewards.store');
    Route::post('/Main_salary_Directrewards/checkExsistsBefore', [mainEmployeesDirectRewardsController::class, 'checkExsistsBefore'])->name('Main_salary_Directrewards.checkExsistsBefore');
    Route::post('/Main_salary_Directrewards/ajax_search', [mainEmployeesDirectRewardsController::class, 'ajax_search'])->name('Main_salary_Directrewards.ajax_search');
    Route::post('/Main_salary_Directrewards/delete_row', [mainEmployeesDirectRewardsController::class, 'delete_row'])->name('Main_salary_Directrewards.delete_row');
    Route::post('/Main_salary_Directrewards/load_edit_row', [mainEmployeesDirectRewardsController::class, 'load_edit_row'])->name('Main_salary_Directrewards.load_edit_row');
    Route::post('/Main_salary_Directrewards/do_edit_row', [mainEmployeesDirectRewardsController::class, 'do_edit_row'])->name('Main_salary_Directrewards.do_edit_row');
    Route::post('/Main_salary_Directrewards/print_search', [mainEmployeesDirectRewardsController::class, 'print_search'])->name('Main_salary_Directrewards.print_search');

    /*  بداية انواع  المنح */
    Route::get('/grants', [GrantsController::class, 'index'])->name('grants.index');
    Route::get('/grantsCreate', [GrantsController::class, 'create'])->name('grants.create');
    Route::post('/grantsStore', [GrantsController::class, 'store'])->name('grants.store');
    Route::get('/grantsEdit/{id}', [GrantsController::class, 'edit'])->name('grants.edit');
    Route::post('/grantsUpdate/{id}', [GrantsController::class, 'update'])->name('grants.update');
    Route::get('/grantsDestroy/{id}', [GrantsController::class, 'destroy'])->name('grants.destroy');

    /*  بداية   المنح المباشره الرواتب */
    Route::get('/Main_direct_grants/index', [main_direct_grantsController::class, 'index'])->name('Main_direct_grants.index');
    Route::get('/Main_direct_grants/show/{id}', [main_direct_grantsController::class, 'show'])->name('Main_direct_grants.show');
    Route::post('/Main_direct_grants/store', [main_direct_grantsController::class, 'store'])->name('Main_direct_grants.store');
    Route::post('/Main_direct_grants/checkExsistsBefore', [main_direct_grantsController::class, 'checkExsistsBefore'])->name('Main_direct_grants.checkExsistsBefore');
    Route::post('/Main_direct_grants/ajax_search', [main_direct_grantsController::class, 'ajax_search'])->name('Main_direct_grants.ajax_search');
    Route::post('/Main_direct_grants/delete_row', [main_direct_grantsController::class, 'delete_row'])->name('Main_direct_grants.delete_row');
    Route::post('/Main_direct_grants/load_edit_row', [main_direct_grantsController::class, 'load_edit_row'])->name('Main_direct_grants.load_edit_row');
    Route::post('/Main_direct_grants/do_edit_row', [main_direct_grantsController::class, 'do_edit_row'])->name('Main_direct_grants.do_edit_row');
    Route::post('/Main_direct_grants/print_search', [main_direct_grantsController::class, 'print_search'])->name('Main_direct_grants.print_search');
});




Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => 'guest:admin'], function () {
    Route::get('login', [LoginController::class, 'show_login_view'])->name('admin.showlogin');
    Route::post('login', [LoginController::class, 'login'])->name('admin.login');
});
Route::fallback(function () {
    return view('admin.error.error');
});
