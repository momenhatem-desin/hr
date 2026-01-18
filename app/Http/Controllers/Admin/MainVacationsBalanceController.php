<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin_panel_setting;
use App\Models\Allowances;
use App\Models\Attenance_departure;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Branche;
use App\Models\Departement;
use App\Models\jobs_categorie;
use App\Models\Qualification;
use App\Models\Religion;
use App\Models\Countries;
use App\Models\Nationalitie;
use App\Models\governorates;
use App\Models\centers;
use App\Models\blood_groups;
use App\Models\Military_status;
use App\Models\driving_license_type;
use App\Models\Language;
use App\Models\Shifts_type;
use App\Models\Employees_files;
use App\Models\Employees_fixed_suits;
use App\Models\Finance_calender;
use App\Models\Finance_cln_periods;
use App\Models\MainVacationsBalance;
use Illuminate\Support\Facades\DB;
use App\Traits\GeneralTrait;

class MainVacationsBalanceController extends Controller
{
  use GeneralTrait;
  public function index()
  {
    $com_code = auth("admin")->user()->com_code;
    $data = get_cols_where_p(new Employee(), array("*"), array("com_code" => $com_code), "id", "DESC");
    $other['branches'] = get_cols_where(new Branche(), array("id", "name"), array("com_code" => $com_code, "active" => 1));
    $other['departements'] = get_cols_where(new Departement(), array("id", "name"), array("com_code" => $com_code, "active" => 1));
    $other['jobs'] = get_cols_where(new jobs_categorie(), array("id", "name"), array("com_code" => $com_code, "active" => 1));
    $other['qualifications'] = get_cols_where(new Qualification(), array("id", "name"), array("com_code" => $com_code, "active" => 1));
    $other['religions'] = get_cols_where(new Religion(), array("id", "name"), array("com_code" => $com_code, "active" => 1));
    $other['nationalities'] = get_cols_where(new Nationalitie(), array("id", "name"), array("com_code" => $com_code, "active" => 1));
    $other['countires'] = get_cols_where(new Countries(), array("id", "name"), array("com_code" => $com_code, "active" => 1));
    $other['governorates'] = get_cols_where(new governorates(), array("id", "name"), array("com_code" => $com_code, "active" => 1));
    $other['centers'] = get_cols_where(new centers(), array("id", "name"), array("com_code" => $com_code, "active" => 1));
    $other['blood_groups'] = get_cols_where(new blood_groups(), array("id", "name"), array("com_code" => $com_code, "active" => 1));
    $other['military_status'] = get_cols_where(new Military_status(), array("id", "name"), array("active" => 1), 'id', 'ASC');
    $other['driving_license_types'] = get_cols_where(new driving_license_type(), array("id", "name"), array("active" => 1, "com_code" => $com_code), 'id', 'ASC');
    $other['shifts_types'] = get_cols_where(new Shifts_type(), array("id", "type", "from_time", "to_time", "total_hour"), array("active" => 1, "com_code" => $com_code), 'id', 'ASC');
    $other['languages'] = get_cols_where(new Language(), array("id", "name"), array("active" => 1, "com_code" => $com_code), 'id', 'ASC');

   if (!empty($data)) {
    foreach ($data as $key => $emp) {
        $balance = get_cols_where_row_orderby(new MainVacationsBalance(),array("net_balance"),array( "com_code" => $com_code,"employees_code"=>$emp['employees_code']),"id","DESC");
        $data[$key]['balance_vac'] = $balance['net_balance'] ?? 0;
    }
}

    return view("admin.employees_vacations_balance.index", ['data' => $data, 'other' => $other]);
  }



  public function ajax_search(Request $request)
  {
    if ($request->ajax()) {
      $com_code = auth('admin')->user()->com_code;
      $searchbycode = $request->searchbycode;
      $emp_name_search = $request->emp_name_search;
      $emp_Departments_code_search = $request->emp_Departments_code_search;
      $emp_jobs_id_search = $request->emp_jobs_id_search;
      $is_active_for_Vaccation_search = $request->is_active_for_Vaccation_search;
      $emp_gender_search = $request->emp_gender_search;
      $branch_id_search = $request->branch_id_search;
      $Functiona_status_search = $request->Functiona_status_search;
      $searchbyradiocode = $request->searchbyradiocode;

      if ($searchbycode == '') {
        //هنا نعمل شرط دائم التحقق
        $field1 = "id";
        $operator1 = ">";
        $value1 = 0;
      } else {
        if ($searchbyradiocode == 'zketo_code') {
          $field1 = "zketo_code";
          $operator1 = "=";
          $value1 = $searchbycode;
        } else {
          $field1 = "employees_code";
          $operator1 = "=";
          $value1 = $searchbycode;
        }
      }

      if ($emp_name_search == '') {
        //هنا نعمل شرط دائم التحقق
        $field2 = "id";
        $operator2 = ">";
        $value2 = 0;
      } else {
        $field2 = "emp_name";
        $operator2 = "like";
        $value2 = "%$emp_name_search%";
      }

      if ($emp_Departments_code_search == 'all') {
        //هنا نعمل شرط دائم التحقق
        $field3 = "id";
        $operator3 = ">";
        $value3 = 0;
      } else {
        $field3 = "emp_Departments_code";
        $operator3 = "=";
        $value3 = $emp_Departments_code_search;
      }

      if ($emp_jobs_id_search == 'all') {
        //هنا نعمل شرط دائم التحقق
        $field5 = "id";
        $operator5 = ">";
        $value5 = 0;
      } else {
        $field5 = "emp_jobs_id";
        $operator5 = "=";
        $value5 = $emp_jobs_id_search;
      }

      if ($Functiona_status_search == 'all') {
        //هنا نعمل شرط دائم التحقق
        $field6 = "id";
        $operator6 = ">";
        $value6 = 0;
      } else {
        $field6 = "Functiona_status";
        $operator6 = "=";
        $value6 = $Functiona_status_search;
      }

      if ($is_active_for_Vaccation_search == 'all') {
        //هنا نعمل شرط دائم التحقق
        $field7 = "id";
        $operator7 = ">";
        $value7 = 0;
      } else {
        $field7 = "is_active_for_Vaccation";
        $operator7 = "=";
        $value7 = $is_active_for_Vaccation_search;
      }

      if ($emp_gender_search == 'all') {
        //هنا نعمل شرط دائم التحقق
        $field8 = "id";
        $operator8 = ">";
        $value8 = 0;
      } else {
        $field8 = "emp_gender";
        $operator8 = "=";
        $value8 = $emp_gender_search;
      }
      if ($branch_id_search == 'all') {
        //هنا نعمل شرط دائم التحقق
        $field4 = "id";
        $operator4 = ">";
        $value4 = 0;
      } else {
        $field4 = "branch_id";
        $operator4 = "=";
        $value4 = $branch_id_search;
      }






      $data = Employee::select("*")->where($field1, $operator1, $value1)->where($field2, $operator2, $value2)->where($field3, $operator3, $value3)->where($field5, $operator5, $value5)->where($field6, $operator6, $value6)->where($field7, $operator7, $value7)->where($field4, $operator4, $value4)->where($field8, $operator8, $value8)->where('com_code', '=', $com_code)->orderby('id', 'DESC')->paginate(PC);
       if (!empty($data)) {
    foreach ($data as $key => $emp) {
        $balance = get_cols_where_row_orderby(new MainVacationsBalance(),array("net_balance"),array( "com_code" => $com_code,"employees_code"=>$emp['employees_code']),"id","DESC");
        $data[$key]['balance_vac'] = $balance['net_balance'] ?? 0;
    }
  }
      return view('admin.employees_vacations_balance.ajax_search', ['data' => $data]);
    }
  }

  public function show($id)
  {
    $com_code = auth("admin")->user()->com_code;
    $data = get_cols_where_row(new Employee(), array("*"), array('com_code' => $com_code, "id" => $id));
    if (empty($data)) {
      return redirect()->route('admin.employees_vacations_balance.index')->with(['error' => 'عفوا  غير قادر الوصول للبيانات ! ']);
    }
    $other['branches'] = get_cols_where(new Branche(), array("id", "name"), array("com_code" => $com_code, "id" => $data['branch_id']));
    $other['departements'] = get_cols_where(new Departement(), array("id", "name"), array("com_code" => $com_code, "id" => $data['emp_Departments_code']));
    $other['jobs'] = get_cols_where(new jobs_categorie(), array("id", "name"), array("com_code" => $com_code, "id" => $data['emp_jobs_id']));
    //بدايه داله احتساب رصيد اجازات السنوى والشهرى
    $this->calculate_employess_vactions_blance($data['employees_code']);
    $this->calculate_employess_vactions_blance($data['employees_code']);

    $other['finance_calender'] = get_cols_where(new Finance_calender(), array("*"), array("com_code" => $com_code), 'id', 'ASC');

    //السنه الماليه المفعله
    $other['finance_calender_open_year'] = get_cols_where_row(new Finance_calender(), array("*"), array("com_code" => $com_code, "is_open" => 1));
    if (!empty($other['finance_calender_open_year'])) {
      $other['main_employees_vacations_balance'] = get_cols_where(new MainVacationsBalance(), array("*"), array("employees_code" => $data['employees_code'], "FINANCE_YR" => $other['finance_calender_open_year']['FINANCE_YR']), "id", "ASC");
    }
    $admin_panel_settingData = get_cols_where_row(new Admin_panel_setting(), array("is_pull_anuall_day_from_passma"), array('com_code' => $com_code));

    return view('admin.employees_vacations_balance.show', ['data' => $data, 'other' => $other, 'admin_panel_settingData' => $admin_panel_settingData]);
  }




  public function load_edit_row(Request $request)
  {
    $com_code = auth('admin')->user()->com_code;
    if ($request->ajax()) {
    
     $other['data_row'] = get_cols_where_row(new MainVacationsBalance(), array("*"), array("com_code" => $com_code, "id" => $request->id));
     $other['cheack_currentOpenMonth']= get_cols_where_row(new Finance_cln_periods(), array("id", "FINANCE_YR", "year_and_month"), array("com_code" => $com_code, "is_open" => 1));
  
      return view('admin.employees_vacations_balance.load_edit_row', ['other' => $other]);
    }
  }

  public function do_edit_row(Request $request)
  {
    if ($request->ajax()) {
      
      $com_code = auth('admin')->user()->com_code;
     $data_row = get_cols_where_row(new MainVacationsBalance(), array("*"), array("com_code" => $com_code, "id" => $request->id));
     $cheack_currentOpenMonth= get_cols_where_row(new Finance_cln_periods(), array("id", "FINANCE_YR", "year_and_month"), array("com_code" => $com_code, "is_open" => 1));
      if (!empty($data_row) && !empty($cheack_currentOpenMonth)) {
        DB::beginTransaction();

        $dataToUpdate_blance['carryover_from_previous_month'] = $request->carryover_from_previous_month;
        $dataToUpdate_blance['total_available_balance'] = $data_row['current_month_balance'] + $request->carryover_from_previous_month;
        $dataToUpdate_blance['spent_balance'] = $request->spent_balance;
       $dataToUpdate_blance['net_balance'] =$dataToUpdate_blance['total_available_balance'] - $request->spent_balance;
        $dataToUpdate_blance['notes'] = $request->notes;
        $dataToUpdate_blance['updated_by'] = auth('admin')->user()->id;
        $dataToUpdate_blance['com_code'] = $com_code;
        
        $flag = update(new MainVacationsBalance(), $dataToUpdate_blance, array("com_code" => $com_code, "id" => $request->id));
        if ($flag) {
           $this->calculate_employess_vactions_blance($data_row['employees_code']);
        }
        
        DB::commit();
        return json_encode("done");
      }
    }
  }
}
