<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Finance_calender;
use App\Models\Finance_cln_periods;
use App\Models\Employee;
use App\Models\Main_salary_employee;
use App\Models\Admin_panel_setting;
use App\Models\Attenance_actions;
use App\Models\Attenance_departure;
use App\Models\Branche;
use App\Models\Departement;
use App\Models\jobs_categorie;
use App\Traits\GeneralTrait;
use App\Models\Main_salary_employees_sanctions;
use App\Models\Main_salary_employee_absence;
use App\Models\Main_salary_employees_rewards;
use App\Models\Main_salary_employees_allowances;
use App\Models\Main_salary_employees_loans;
use App\Models\Main_salary_employees_addtion;
use App\Models\Main_salary_employees_discound;
use App\Models\Main_salary_P_loans_akast;

class Main_salary_employeeController extends Controller
{
  use GeneralTrait;


  public function index()

  {
    $com_code = auth('admin')->user()->com_code;
    $data = get_cols_where_p_order2(new Finance_cln_periods(), array("*"), array("com_code" => $com_code), 'FINANCE_YR', 'DESC', 'MONTH_ID', 'ASC', 12);
    if (!empty($data))
      foreach ($data as $info) {
        // cheak status to open
        $info->currnetYear = get_cols_where_row(new Finance_calender(), array("is_open"), array("com_code" => $com_code, "FINANCE_YR" => $info->FINANCE_YR));
        $info->counterOpenMonth = get_count_where(new Finance_cln_periods(), array("com_code" => $com_code, "is_open" => 1));
        $info->counterPreviousMonthWatingOpen = Finance_cln_periods::where("com_code", "=", $com_code)
          ->where("FINANCE_YR", "=", $info->FINANCE_YR)
          ->where("MONTH_ID", "<", $info->MONTH_ID)
          ->where("is_open", "=", 0)->count();
        // get_count_where(new Finance_cln_periods(),array("com_code" => $com_code,"is_open"=>0,"FINANCE_YR"=>$info->FINANCE_YR,"MONTH_ID"));
      }
    return view('admin.Main_salary_employee.index', ['data' => $data]);
  }


  public function show($finance_cln_periods_id)
  {
    $com_code = auth('admin')->user()->com_code;
    $finance_cln_periods_data = get_cols_where_row(new Finance_cln_periods(), array("*"), array("com_code" => $com_code, 'id' => $finance_cln_periods_id));

    if (empty($finance_cln_periods_data)) {
      return redirect()->route('Main_salary_employee.index')->with(['error' => 'عفوا  غير قادر على الوصول الى البيانات المطلوبة ! ']);
    }
    $data = get_cols_where_p(new Main_salary_employee(), array("id", "employees_code", "emp_name", "total_benefits", "total_deductions", "final_the_net", "is_take_action_diss_collec", "is_stoped", "emp_Departments_code", "branch_id", "emp_jobs_id", "is_archived"), array("com_code" => $com_code, "finance_cln_periods_id" => $finance_cln_periods_id), 'id', 'ASC', PC);
    if (!empty($data)) {
      foreach ($data as $info) {
        $info->emp_name = get_field_value(new Employee(), "emp_name", array("com_code" => $com_code, "employees_code" => $info->employees_code));
        $info->emp_photo = get_field_value(new Employee(), "emp_photo", array("com_code" => $com_code, "employees_code" => $info->employees_code));
        $info->emp_gender = get_field_value(new Employee(), "emp_gender", array("com_code" => $com_code, "employees_code" => $info->employees_code));
        $info->branch_name = get_field_value(new Branche(), "name", array("com_code" => $com_code, "id" => $info->branch_id));
        $info->emp_Departments_name = get_field_value(new Departement(), "name", array("com_code" => $com_code, "id" => $info->emp_Departments_code));
        $info->job_name = get_field_value(new jobs_categorie(), "name", array("com_code" => $com_code, "id" => $info->emp_jobs_id));
      }
    }

    $other['branches'] = get_cols_where(new Branche(), array("id", "name"), array("com_code" => $com_code, "active" => 1));
    $other['departements'] = get_cols_where(new Departement(), array("id", "name"), array("com_code" => $com_code, "active" => 1));
    $other['jobs'] = get_cols_where(new jobs_categorie(), array("id", "name"), array("com_code" => $com_code, "active" => 1));
    $other['employess'] = get_cols_where(new Employee(), array("employees_code", "emp_name", "emp_sal", "day_price"), array("com_code" => $com_code), 'employees_code', 'ASC');
    $other['nothave'] = 0;
    if ($finance_cln_periods_data['is_open'] == 1) {
      if (!empty($other['employess'])) {
        foreach ($other['employess'] as $info) {
          $info->counter = get_count_where(new Main_salary_employee(), array("com_code" => $com_code, "employees_code" => $info->employees_code, "finance_cln_periods_id" => $finance_cln_periods_id));
          if ($info->counter == 0) {
            $other['nothave']++;
          }
        }
      }
    }
    $other['counter_salaries'] = get_count_where(new Main_salary_employee(), array("com_code" => $com_code, "finance_cln_periods_id" => $finance_cln_periods_id));
    $other['counter_salaries_wating_archive'] = get_count_where(new Main_salary_employee(), array("com_code" => $com_code, "finance_cln_periods_id" => $finance_cln_periods_id, 'is_archived' => 0));
    $other['counter_salaries_done_archive'] = get_count_where(new Main_salary_employee(), array("com_code" => $com_code, "finance_cln_periods_id" => $finance_cln_periods_id, 'is_archived' => 1));
    $other['counter_salaries_stop'] = get_count_where(new Main_salary_employee(), array("com_code" => $com_code, "finance_cln_periods_id" => $finance_cln_periods_id, 'is_stoped' => 1));
    return view('admin.Main_salary_employee.show', ['data' => $data, 'finance_cln_periods_data' => $finance_cln_periods_data, 'other' => $other]);
  }


  public function addManuallySalrary($finance_cln_periods_id, request $request)
  {
    $com_code = auth('admin')->user()->com_code;
    $finance_cln_periods_data = get_cols_where_row(new Finance_cln_periods(), array("*"), array("com_code" => $com_code, 'id' => $finance_cln_periods_id));

    if (empty($finance_cln_periods_data)) {
      return redirect()->route('Main_salary_employee.index')->with(['error' => 'عفوا  غير قادر على الوصول الى البيانات المطلوبة ! ']);
    }
    if ($finance_cln_periods_data['is_open'] != 1) {
      return redirect()->back()->with(['error' => 'عفوا لايمكن اضافه رواتب فى الشهر المالى الحالى ! ']);
    }

    $employData = get_cols_where_row(new Employee(), array("*"), array("com_code" => $com_code, "employees_code" => $request->employees_code_Add));
    if (empty($employData)) {
      return redirect()->back()->with(['error' => 'عفوا لايمكن الوصول الى بيانات هذا الموظف ! ']);
    }


    $dataSalaryToInser = array();
    $dataSalaryToInsert['Finance_cln_periods_id'] = $finance_cln_periods_id;
    $dataSalaryToInsert['employees_code'] = $employData['employees_code'];
    $dataSalaryToInsert['com_code'] = $com_code;
    $checkExsistsCounter = get_count_where(new Main_salary_employee(), $dataSalaryToInsert);
    if ($checkExsistsCounter > 0) {
      return redirect()->back()->with(['error' => 'عفوا  هذا الموظف بالفعل له راتب بهذا الشهر ! ']);
    }

    $dataSalaryToInsert['emp_name'] = $employData['emp_name'];
    $dataSalaryToInsert['day_price'] = $employData['day_price'];
    $dataSalaryToInsert['is_Sensitive_manger_data'] = $employData['is_Sensitive_manger_data'];
    $dataSalaryToInsert['branch_id'] = $employData['branch_id'];
    $dataSalaryToInsert['Functiona_status'] = $employData['Functiona_status'];
    $dataSalaryToInsert['emp_Departments_code'] = $employData['emp_Departments_code'];
    $dataSalaryToInsert['emp_jobs_id'] = $employData['emp_jobs_id'];
    $dataSalaryToInsert['emp_sal'] = $employData['emp_sal'];
    $LastSalaryData = get_cols_where_row_orderby(new Main_salary_employee(), array("final_the_net_after_colse"), array("com_code" => $com_code, "employees_code" => $employData['employees_code'], "is_archived" => 1), 'id', 'DESC');
    if (!empty($LastSalaryData)) {
      $dataSalaryToInsert['last_salary_remain_blance'] = $LastSalaryData['final_the_net_after_colse'];
    } else {
      $dataSalaryToInsert['last_salary_remain_blance'] = 0;
    }

    $dataSalaryToInsert['year_and_month'] = $finance_cln_periods_data['year_and_month'];
    $dataSalaryToInsert['FINANCE_YR'] = $finance_cln_periods_data['FINANCE_YR'];
    $dataSalaryToInsert['sal_cach_or_visa'] = $employData['sal_cach_or_visa'];
    $dataSalaryToInsert['added_by'] = auth('admin')->user()->id;
    $dataSalaryToInsert['archived_by'] = auth('admin')->user()->id;
    $flagInsert = insert(new Main_salary_employee, $dataSalaryToInsert, true);
    if (!empty($flagInsert)) {
      $this->Recalculate_main_salary_employee($flagInsert['id']);
    }




    return redirect()->back()->with(['success' => 'لقد تم اضافه راتب الموظف ']);
  }

  public function delete_salary(request $request)
  {

    if ($request->ajax()) {
      $com_code = auth('admin')->user()->com_code;
      $finance_cln_periods_data = get_cols_where_row(new Finance_cln_periods(), array("*"), array("com_code" => $com_code, 'id' => $request->the_finance_cln_periods_id, 'is_open' => 1));

      if (!empty($finance_cln_periods_data)) {
        $Main_salary_employee_Data = get_cols_where_row(new Main_salary_employee(), array("*"), array("com_code" => $com_code, "id" => $request->id, "finance_cln_periods_id" => $request->the_finance_cln_periods_id, "is_archived" => 0));
        if (!empty($Main_salary_employee_Data)) {
          destroy(new Main_salary_employee(), array("com_code" => $com_code, "id" => $request->id, "finance_cln_periods_id" => $request->the_finance_cln_periods_id, "is_archived" => 0));
          return json_encode("done");
        }
      }
    }
  }

  public function showSalaryDetails($Main_salary_employeeID)
  {


    $com_code = auth('admin')->user()->com_code;
    $Main_salary_employee_Data = get_cols_where_row(new Main_salary_employee(), array("*"), array("com_code" => $com_code, "id" => $Main_salary_employeeID));
    if (empty($Main_salary_employee_Data)) {
      return redirect()->back()->with(['error' => 'عفوا غير قادر الوصول الى البيانات المطلوبه ! ']);
    }

    $finance_cln_periods_data = get_cols_where_row(new Finance_cln_periods(), array("*"), array("com_code" => $com_code, 'id' => $Main_salary_employee_Data['Finance_cln_periods_id']));
    if (empty($finance_cln_periods_data)) {
      return redirect()->back()->with(['error' => '  عفوا غير قادر الوصول الى البيانات المطلوبه  فى هذا الشهر! ']);
    }
    if ($Main_salary_employee_Data['is_archived'] == 0) {
      $this->Recalculate_main_salary_employee($Main_salary_employeeID);
      $Main_salary_employee_Data = get_cols_where_row(new Main_salary_employee(), array("*"), array("com_code" => $com_code, "id" => $Main_salary_employeeID));
    }
    $Main_salary_employee_Data['emp_name'] = get_field_value(new Employee(), "emp_name", array("com_code" => $com_code, "employees_code" => $Main_salary_employee_Data['employees_code']));
    $Main_salary_employee_Data['emp_gender'] = get_field_value(new Employee(), "emp_gender", array("com_code" => $com_code, "employees_code" => $Main_salary_employee_Data['employees_code']));
    $Main_salary_employee_Data['branch_name'] = get_field_value(new Branche(), "name", array("com_code" => $com_code, "id" => $Main_salary_employee_Data['branch_id']));
    $Main_salary_employee_Data['emp_Departments_name'] = get_field_value(new Departement(), "name", array("com_code" => $com_code, "id" => $Main_salary_employee_Data['emp_Departments_code']));
    $Main_salary_employee_Data['job_name'] = get_field_value(new jobs_categorie(), "name", array("com_code" => $com_code, "id" => $Main_salary_employee_Data['emp_jobs_id']));
    return view('admin.Main_salary_employee.showSalaryDetails', ['Main_salary_employee_Data' => $Main_salary_employee_Data, 'finance_cln_periods_data' => $finance_cln_periods_data]);
  }

  public function DoStopSalary($Main_salary_employeeID)
  {
    $com_code = auth('admin')->user()->com_code;
    $Main_salary_employee_Data = get_cols_where_row(new Main_salary_employee(), array("*"), array("com_code" => $com_code, "id" => $Main_salary_employeeID));
    if (empty($Main_salary_employee_Data)) {
      return redirect()->back()->with(['error' => 'عفوا غير قادر الوصول الى البيانات المطلوبه ! ']);
    }

    $finance_cln_periods_data = get_cols_where_row(new Finance_cln_periods(), array("*"), array("com_code" => $com_code, 'id' => $Main_salary_employee_Data['Finance_cln_periods_id']));
    if (empty($finance_cln_periods_data)) {
      return redirect()->back()->with(['error' => '  عفوا غير قادر الوصول الى البيانات المطلوبه  فى هذا الشهر! ']);
    }

    if ($Main_salary_employee_Data['is_archived'] == 1 or $finance_cln_periods_data['is_open'] != 1) {
      return redirect()->back()->with(['error' => 'عفوا لايمكن عمل هضا الاجراء حاليا  ! ']);
    }
    if ($Main_salary_employee_Data['is_stoped'] == 1) {
      return redirect()->back()->with(['error' => 'عفوا  المرتب بالفعل موقوف من قبل  ! ']);
    }

    $dataToUpdate['is_stoped'] = 1;

    $dataToUpdate['updated_by'] = auth('admin')->user()->id;
    update(new Main_salary_employee(), $dataToUpdate, array("com_code" => $com_code, "id" => $Main_salary_employeeID, "is_archived" => 0));
    return redirect()->back()->with(['success' => 'تم  ايقاف المرتب  ! ']);
  }

  public function UnStopSalary($Main_salary_employeeID)
  {
    $com_code = auth('admin')->user()->com_code;
    $Main_salary_employee_Data = get_cols_where_row(new Main_salary_employee(), array("*"), array("com_code" => $com_code, "id" => $Main_salary_employeeID));
    if (empty($Main_salary_employee_Data)) {
      return redirect()->back()->with(['error' => 'عفوا غير قادر الوصول الى البيانات المطلوبه ! ']);
    }

    $finance_cln_periods_data = get_cols_where_row(new Finance_cln_periods(), array("*"), array("com_code" => $com_code, 'id' => $Main_salary_employee_Data['Finance_cln_periods_id']));
    if (empty($finance_cln_periods_data)) {
      return redirect()->back()->with(['error' => '  عفوا غير قادر الوصول الى البيانات المطلوبه  فى هذا الشهر! ']);
    }

    if ($Main_salary_employee_Data['is_archived'] == 1 or $finance_cln_periods_data['is_open'] != 1) {
      return redirect()->back()->with(['error' => 'عفوا لايمكن عمل هذا الاجراء حاليا  ! ']);
    }
    if ($Main_salary_employee_Data['is_stoped'] == 0) {
      return redirect()->back()->with(['error' => 'عفوا  المرتب بالفعل غير موقوف    ! ']);
    }

    $dataToUpdate['is_stoped'] = 0;
    $dataToUpdate['updated_by'] = auth('admin')->user()->id;
    update(new Main_salary_employee(), $dataToUpdate, array("com_code" => $com_code, "id" => $Main_salary_employeeID, "is_archived" => 0));
    return redirect()->back()->with(['success' => 'تم  تفعيل المرتب  ! ']);
  }

  public function delete_salaryInternal($Main_salary_employeeID)
  {
    $com_code = auth('admin')->user()->com_code;
    $Main_salary_employee_Data = get_cols_where_row(new Main_salary_employee(), array("*"), array("com_code" => $com_code, "id" => $Main_salary_employeeID));
    if (empty($Main_salary_employee_Data)) {
      return redirect()->back()->with(['error' => 'عفوا غير قادر الوصول الى البيانات المطلوبه ! ']);
    }

    $finance_cln_periods_data = get_cols_where_row(new Finance_cln_periods(), array("*"), array("com_code" => $com_code, 'id' => $Main_salary_employee_Data['Finance_cln_periods_id']));
    if (empty($finance_cln_periods_data)) {
      return redirect()->back()->with(['error' => '  عفوا غير قادر الوصول الى البيانات المطلوبه  فى هذا الشهر! ']);
    }

    if ($Main_salary_employee_Data['is_archived'] == 1 or $finance_cln_periods_data['is_open'] != 1 or $Main_salary_employee_Data['is_stoped'] == 1) {
      return redirect()->back()->with(['error' => 'عفوا لايمكن عمل هذا الاجراء حاليا  ! ']);
    }


    destroy(new Main_salary_employee(), array("com_code" => $com_code, "id" => $Main_salary_employeeID, "is_archived" => 0));
    return redirect()->route("Main_salary_employee.show", $Main_salary_employee_Data['Finance_cln_periods_id'])->with(['success' => 'تم  حذف المرتب  ! ']);
  }

  public function load_archive_salary(Request $request)
  {
    if ($request->ajax()) {
      $com_code = auth('admin')->user()->com_code;
      $Main_salary_employeeID = $request->id;

      $Main_salary_employee_Data = get_cols_where_row(
        new Main_salary_employee(),
        array("*"),
        array(
          "com_code" => $com_code,
          "id" => $Main_salary_employeeID,
          "is_archived" => 0,
          "is_stoped" => 0
        )
      );

      $finance_cln_periods_data = get_cols_where_row(
        new Finance_cln_periods(),
        array("*"),
        array(
          "com_code" => $com_code,
          "id" => $Main_salary_employee_Data['Finance_cln_periods_id'],
          "is_open" => 1
        )
      );

      if (!empty($Main_salary_employee_Data)) {
        return view("admin.Main_salary_employee.load_archive_salary", [
          'Main_salary_employee_Data' => $Main_salary_employee_Data,
          'finance_cln_periods_data'  => $finance_cln_periods_data
        ]);
      }
    }
  }

  public function do_archive_salary($Main_salary_employeeID)
  {
    $com_code = auth('admin')->user()->com_code;
    $Main_salary_employee_Data = get_cols_where_row(new Main_salary_employee(), array("*"), array("com_code" => $com_code, "id" => $Main_salary_employeeID));
    if (empty($Main_salary_employee_Data)) {
      return redirect()->back()->with(['error' => 'عفوا غير قادر الوصول الى البيانات المطلوبه ! ']);
    }

    $finance_cln_periods_data = get_cols_where_row(new Finance_cln_periods(), array("*"), array("com_code" => $com_code, 'id' => $Main_salary_employee_Data['Finance_cln_periods_id']));
    if (empty($finance_cln_periods_data)) {
      return redirect()->back()->with(['error' => '  عفوا غير قادر الوصول الى البيانات المطلوبه  فى هذا الشهر! ']);
    }

    if ($Main_salary_employee_Data['is_archived'] == 1 or $Main_salary_employee_Data['is_stoped'] == 1 or  $finance_cln_periods_data['is_open'] != 1) {
      return redirect()->back()->with(['error' => 'عفوا لايمكن عمل هضا الاجراء حاليا  ! ']);
    }

    $dataToUpdate['is_archived'] = 1;
    $dataToUpdate['archived_date'] = date("Y-m-d H:i:s");
    $dataToUpdate['archived_by'] = auth('admin')->user()->id;
    // هنا لو الموظف مستحق يتم حفظ الراتب ظرف الراواتب 
    //اذا كان بالسالب  بيتم ترحيل الرصيد السالب للشهر التالى 

    if ($Main_salary_employee_Data['final_the_net'] < 0) {
      //المرتب سلب سوف يتو ترحيله للشهر القادم
      $dataToUpdate['final_the_net_after_colse'] = $Main_salary_employee_Data['final_the_net'];
    } else {
      $dataToUpdate['final_the_net_after_colse'] = 0;
    }

    $flagUpdate = update(new Main_salary_employee(), $dataToUpdate, array("com_code" => $com_code, "id" => $Main_salary_employeeID, "is_archived" => 0, "is_stoped" => 0));
    if ($flagUpdate) {
      $dataToUpdate_variables['is_archived'] = 1;
      $dataToUpdate_variables['archived_at'] = date("Y-m-d H:i:s");
      $dataToUpdate_variables['archived_by'] = auth('admin')->user()->id;
      update(new Main_salary_employees_sanctions(), $dataToUpdate_variables, array("com_code" => $com_code, "main_salary_employee_id" => $Main_salary_employeeID, "finance_cln_periods_id" => $finance_cln_periods_data['id']));
      update(new Main_salary_employee_absence(), $dataToUpdate_variables, array("com_code" => $com_code, "main_salary_employee_id" => $Main_salary_employeeID, "finance_cln_periods_id" => $finance_cln_periods_data['id']));
      update(new Main_salary_employees_rewards(), $dataToUpdate_variables, array("com_code" => $com_code, "main_salary_employee_id" => $Main_salary_employeeID, "finance_cln_periods_id" => $finance_cln_periods_data['id']));
      update(new Main_salary_employees_allowances(), $dataToUpdate_variables, array("com_code" => $com_code, "main_salary_employee_id" => $Main_salary_employeeID, "finance_cln_periods_id" => $finance_cln_periods_data['id']));
      update(new Main_salary_employees_loans(), $dataToUpdate_variables, array("com_code" => $com_code, "main_salary_employee_id" => $Main_salary_employeeID, "finance_cln_periods_id" => $finance_cln_periods_data['id']));
      update(new Main_salary_employees_addtion(), $dataToUpdate_variables, array("com_code" => $com_code, "main_salary_employee_id" => $Main_salary_employeeID, "finance_cln_periods_id" => $finance_cln_periods_data['id']));
      update(new Main_salary_employees_discound(), $dataToUpdate_variables, array("com_code" => $com_code, "main_salary_employee_id" => $Main_salary_employeeID, "finance_cln_periods_id" => $finance_cln_periods_data['id']));
      Main_salary_P_loans_akast::where("com_code", "=", $com_code)->where("year_and_month", "=", $finance_cln_periods_data['year_and_month'])->where("is_parent_dismissail_done", "=", 1)->where("is_archived", "=", 0)->where("state", "!=", "2")->where("employees_code", "=", $Main_salary_employee_Data['employees_code'])->where("main_salary_employee_id", "=", $Main_salary_employeeID)->update($dataToUpdate_variables);

      $dataToUpdate2['is_archived'] = 1;
                    $dataToUpdate2['archived_at'] = date("Y-m-d H:i:s");
                    $dataToUpdate2['archived_by'] = auth('admin')->user()->id;
                    $dataToUpdate3['is_archived'] = 1;
                 update(new Attenance_departure(),$dataToUpdate2,array("com_code"=>$com_code,"finance_cln_periods_id"=>$finance_cln_periods_data['id'],"employees_code"=>$Main_salary_employee_Data['employees_code']));
                 update(new Attenance_actions(),$dataToUpdate3,array("com_code"=>$com_code,"finance_cln_periods_id"=>$finance_cln_periods_data['id'],"employees_code"=>$Main_salary_employee_Data['employees_code'])); 
    }
       
    return redirect()->back()->with(['success' => 'تم  أرشفة  المرتب  ! ']);
  }

  public function print_salary($Main_salary_employeeID)
  {
    $com_code = auth('admin')->user()->com_code;
    $Main_salary_employee_Data = get_cols_where_row(new Main_salary_employee(), array("*"), array("com_code" => $com_code, "id" => $Main_salary_employeeID));
    if (empty($Main_salary_employee_Data)) {
      return redirect()->back()->with(['error' => 'عفوا غير قادر الوصول الى البيانات المطلوبه ! ']);
    }

    $finance_cln_periods_data = get_cols_where_row(new Finance_cln_periods(), array("*"), array("com_code" => $com_code, 'id' => $Main_salary_employee_Data['Finance_cln_periods_id']));
    if (empty($finance_cln_periods_data)) {
      return redirect()->back()->with(['error' => '  عفوا غير قادر الوصول الى البيانات المطلوبه  فى هذا الشهر! ']);
    }

    $Main_salary_employee_Data['emp_name'] = get_field_value(new Employee(), "emp_name", array("com_code" => $com_code, "employees_code" => $Main_salary_employee_Data['employees_code']));
    $Main_salary_employee_Data['emp_gender'] = get_field_value(new Employee(), "emp_gender", array("com_code" => $com_code, "employees_code" => $Main_salary_employee_Data['employees_code']));
    $Main_salary_employee_Data['branch_name'] = get_field_value(new Branche(), "name", array("com_code" => $com_code, "id" => $Main_salary_employee_Data['branch_id']));
    $Main_salary_employee_Data['emp_Departments_name'] = get_field_value(new Departement(), "name", array("com_code" => $com_code, "id" => $Main_salary_employee_Data['emp_Departments_code']));
    $Main_salary_employee_Data['job_name'] = get_field_value(new jobs_categorie(), "name", array("com_code" => $com_code, "id" => $Main_salary_employee_Data['emp_jobs_id']));

    $systemData = get_cols_where_row(new Admin_panel_setting(), array("image", "phones", "address", "company_name"), array("com_code" => $com_code));
    return view('admin.Main_salary_employee.print_salary', ['Main_salary_employee_Data' => $Main_salary_employee_Data, 'finance_cln_periods_data' => $finance_cln_periods_data, 'systemData' => $systemData]);
  }

  public function print_search(Request $request)
  {
    $com_code = auth('admin')->user()->com_code;

    $employees_code = $request->employees_code_search;
    $branch_id_search = $request->branch_id_search;
    $emp_Departments_code_search = $request->emp_Departments_code_search;
    $emp_jobs_id_search = $request->emp_jobs_id_search;
    $Functiona_status_search = $request->Functiona_status_search;
    $sal_cach_or_visa_search = $request->sal_cach_or_visa_search;
    $is_stoped_search = $request->is_stoped_search;
    $is_archived = $request->is_archived_search;
    $the_finance_cln_periods_id = $request->the_finance_cln_periods_id;

    $finance_cln_periods_data = get_cols_where_row(new Finance_cln_periods(), array("*"), array("com_code" => $com_code, 'id' => $the_finance_cln_periods_id));
    if (empty($finance_cln_periods_data)) {
      return redirect()->back()->with(['error' => '  عفوا غير قادر الوصول الى البيانات المطلوبه  فى هذا الشهر! ']);
    }

    if ($employees_code == 'all') {
      //هنا نعمل شرط دائم التحقق
      $field1 = "id";
      $operator1 = ">";
      $value1 = 0;
    } else {
      $field1 = "employees_code";
      $operator1 = "=";
      $value1 = $employees_code;
    }
    if ($branch_id_search == 'all') {
      //هنا نعمل شرط دائم التحقق
      $field2 = "id";
      $operator2 = ">";
      $value2 = 0;
    } else {
      $field2 = "branch_id";
      $operator2 = "=";
      $value2 = $branch_id_search;
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
      $field4 = "id";
      $operator4 = ">";
      $value4 = 0;
    } else {
      $field4 = "emp_jobs_id";
      $operator4 = "=";
      $value4 = $emp_jobs_id_search;
    }

    if ($Functiona_status_search == 'all') {
      //هنا نعمل شرط دائم التحقق
      $field5 = "id";
      $operator5 = ">";
      $value5 = 0;
    } else {
      $field5 = "Functiona_status";
      $operator5 = "=";
      $value5 = $Functiona_status_search;
    }
    if ($sal_cach_or_visa_search == 'all') {
      //هنا نعمل شرط دائم التحقق
      $field6 = "id";
      $operator6 = ">";
      $value6 = 0;
    } else {
      $field6 = "sal_cach_or_visa";
      $operator6 = "=";
      $value6 = $sal_cach_or_visa_search;
    }
    if ($is_stoped_search == 'all') {
      //هنا نعمل شرط دائم التحقق
      $field7 = "id";
      $operator7 = ">";
      $value7 = 0;
    } else {
      $field7 = "is_stoped";
      $operator7 = "=";
      $value7 = $is_stoped_search;
    }

    if ($is_archived == 'all') {
      //هنا نعمل شرط دائم التحقق
      $field8 = "id";
      $operator8 = ">";
      $value8 = 0;
    } else {
      $field8 = "is_archived";
      $operator8 = "=";
      $value8 = $is_archived;
    }
    $data = Main_salary_employee::select("*")->where($field1, $operator1, $value1)->where($field2, $operator2, $value2)->where($field3, $operator3, $value3)->where($field4, $operator4, $value4)->where($field5, $operator5, $value5)->where($field6, $operator6, $value6)->where($field7, $operator7, $value7)->where($field8, $operator8, $value8)->where('Finance_cln_periods_id', '=', $the_finance_cln_periods_id)->where('com_code', '=', $com_code)->orderby('id', 'DESC')->get();
    if ($request->submit_button == "intotal_withdetalis") {
      $total['emp_sal'] = 0;
      $total['day_price'] = 0;
      $total['additions'] = 0;
      $total['motivation'] = 0;
      $total['addtional_days_counter'] = 0;
      $total['addtional_days'] = 0;
      $total['fixed_suits'] = 0;
      $total['changable_suits'] = 0;
      $total['total_benefits'] = 0;
      $total['absence_days_counter'] = 0;
      $total['absence_days'] = 0;
      $total['sanctions_days_counter_type1'] = 0;
      $total['sanctions_days_total_type1'] = 0;
      $total['monthly_loan'] = 0;
      $total['permanent_loan'] = 0;
      $total['discount'] = 0;
      $total['medicalinsurancecutmonthely'] = 0;
      $total['socialinsurancecutmonthely'] = 0;
      $total['total_deductions'] = 0;
      $total['last_salary_remain_blance'] = 0;
      $total['final_the_net'] = 0;
    }
    if ($data->count() > 0) {
      foreach ($data as $info) {
        $info->emp_name = get_field_value(new Employee(), "emp_name", array("com_code" => $com_code, "employees_code" => $info->employees_code));
        $info->emp_photo = get_field_value(new Employee(), "emp_photo", array("com_code" => $com_code, "employees_code" => $info->employees_code));
        $info->emp_gender = get_field_value(new Employee(), "emp_gender", array("com_code" => $com_code, "employees_code" => $info->employees_code));
        $info->branch_name = get_field_value(new Branche(), "name", array("com_code" => $com_code, "id" => $info->branch_id));
        $info->emp_Departments_name = get_field_value(new Departement(), "name", array("com_code" => $com_code, "id" => $info->emp_Departments_code));
        $info->job_name = get_field_value(new jobs_categorie(), "name", array("com_code" => $com_code, "id" => $info->emp_jobs_id));
        if ($request->submit_button == "intotal_withdetalis") {
          $total['emp_sal'] += $info->emp_sal;
          $total['day_price'] += $info->day_price;
          $total['additions'] += $info->additions;
          $total['motivation'] += $info->motivation;
          $total['addtional_days_counter'] += $info->addtional_days_counter;
          $total['addtional_days'] += $info->addtional_days;
          $total['fixed_suits'] += $info->fixed_suits;
          $total['changable_suits'] += $info->changable_suits;
          $total['total_benefits'] += $info->total_benefits;
          $total['absence_days_counter'] += $info->absence_days_counter;
          $total['absence_days'] += $info->absence_days;
          $total['sanctions_days_counter_type1'] += $info->sanctions_days_counter_type1;
          $total['sanctions_days_total_type1'] += $info->sanctions_days_total_type1;
          $total['monthly_loan'] += $info->monthly_loan;
          $total['permanent_loan'] += $info->permanent_loan;
          $total['discount'] += $info->discount;
          $total['medicalinsurancecutmonthely'] += $info->medicalinsurancecutmonthely;
          $total['socialinsurancecutmonthely'] += $info->socialinsurancecutmonthely;
          $total['total_deductions'] += $info->total_deductions;
          $total['last_salary_remain_blance'] += $info->last_salary_remain_blance;
          $total['final_the_net'] += $info->final_the_net;
        }
      }
    }

    $systemData = get_cols_where_row(new Admin_panel_setting(), array("image", "phones", "address", "company_name"), array("com_code" => $com_code));

    if ($request->submit_button == "indetails") {
      return view('admin.Main_salary_employee.print_search_indetails', ['data' => $data, 'systemData' => $systemData, 'finance_cln_periods_data' => $finance_cln_periods_data]);
    } elseif ($request->submit_button == "intotal") {
      $other['total_benefits'] = Main_salary_employee::where($field1, $operator1, $value1)->where($field2, $operator2, $value2)->where($field3, $operator3, $value3)->where($field4, $operator4, $value4)->where($field5, $operator5, $value5)->where($field6, $operator6, $value6)->where($field7, $operator7, $value7)->where($field8, $operator8, $value8)->where('Finance_cln_periods_id', '=', $the_finance_cln_periods_id)->where('com_code', '=', $com_code)->sum('total_benefits');
      $other['total_deductions'] = Main_salary_employee::where($field1, $operator1, $value1)->where($field2, $operator2, $value2)->where($field3, $operator3, $value3)->where($field4, $operator4, $value4)->where($field5, $operator5, $value5)->where($field6, $operator6, $value6)->where($field7, $operator7, $value7)->where($field8, $operator8, $value8)->where('Finance_cln_periods_id', '=', $the_finance_cln_periods_id)->where('com_code', '=', $com_code)->sum('total_deductions');
      $other['final_the_net'] = Main_salary_employee::where($field1, $operator1, $value1)->where($field2, $operator2, $value2)->where($field3, $operator3, $value3)->where($field4, $operator4, $value4)->where($field5, $operator5, $value5)->where($field6, $operator6, $value6)->where($field7, $operator7, $value7)->where($field8, $operator8, $value8)->where('Finance_cln_periods_id', '=', $the_finance_cln_periods_id)->where('com_code', '=', $com_code)->sum('final_the_net');
      $other['emp_sal'] = Main_salary_employee::where($field1, $operator1, $value1)->where($field2, $operator2, $value2)->where($field3, $operator3, $value3)->where($field4, $operator4, $value4)->where($field5, $operator5, $value5)->where($field6, $operator6, $value6)->where($field7, $operator7, $value7)->where($field8, $operator8, $value8)->where('Finance_cln_periods_id', '=', $the_finance_cln_periods_id)->where('com_code', '=', $com_code)->sum('emp_sal');


      return view('admin.Main_salary_employee.print_search_intotal', ['data' => $data, 'other' => $other, 'systemData' => $systemData, 'finance_cln_periods_data' => $finance_cln_periods_data]);
    } else {
      return view('admin.Main_salary_employee.print_search_intotal_withdetalis', ['data' => $data, 'total' => $total, 'systemData' => $systemData, 'finance_cln_periods_data' => $finance_cln_periods_data]);
    }
  }


  public function ajax_search(Request $request)
  {
    $com_code = auth('admin')->user()->com_code;
    if ($request->ajax()) {

      $employees_code = $request->employees_code_search;
      $branch_id_search = $request->branch_id_search;
      $emp_Departments_code_search = $request->emp_Departments_code_search;
      $emp_jobs_id_search = $request->emp_jobs_id_search;
      $Functiona_status_search = $request->Functiona_status_search;
      $sal_cach_or_visa_search = $request->sal_cach_or_visa_search;
      $is_stoped_search = $request->is_stoped_search;
      $is_archived = $request->is_archived_search;
      $the_finance_cln_periods_id = $request->the_finance_cln_periods_id;

      if ($employees_code == 'all') {
        //هنا نعمل شرط دائم التحقق
        $field1 = "id";
        $operator1 = ">";
        $value1 = 0;
      } else {
        $field1 = "employees_code";
        $operator1 = "=";
        $value1 = $employees_code;
      }
      if ($branch_id_search == 'all') {
        //هنا نعمل شرط دائم التحقق
        $field2 = "id";
        $operator2 = ">";
        $value2 = 0;
      } else {
        $field2 = "branch_id";
        $operator2 = "=";
        $value2 = $branch_id_search;
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
        $field4 = "id";
        $operator4 = ">";
        $value4 = 0;
      } else {
        $field4 = "emp_jobs_id";
        $operator4 = "=";
        $value4 = $emp_jobs_id_search;
      }

      if ($Functiona_status_search == 'all') {
        //هنا نعمل شرط دائم التحقق
        $field5 = "id";
        $operator5 = ">";
        $value5 = 0;
      } else {
        $field5 = "Functiona_status";
        $operator5 = "=";
        $value5 = $Functiona_status_search;
      }
      if ($sal_cach_or_visa_search == 'all') {
        //هنا نعمل شرط دائم التحقق
        $field6 = "id";
        $operator6 = ">";
        $value6 = 0;
      } else {
        $field6 = "sal_cach_or_visa";
        $operator6 = "=";
        $value6 = $sal_cach_or_visa_search;
      }
      if ($is_stoped_search == 'all') {
        //هنا نعمل شرط دائم التحقق
        $field7 = "id";
        $operator7 = ">";
        $value7 = 0;
      } else {
        $field7 = "is_stoped";
        $operator7 = "=";
        $value7 = $is_stoped_search;
      }

      if ($is_archived == 'all') {
        //هنا نعمل شرط دائم التحقق
        $field8 = "id";
        $operator8 = ">";
        $value8 = 0;
      } else {
        $field8 = "is_archived";
        $operator8 = "=";
        $value8 = $is_archived;
      }
      $data = Main_salary_employee::select("*")->where($field1, $operator1, $value1)->where($field2, $operator2, $value2)->where($field3, $operator3, $value3)->where($field4, $operator4, $value4)->where($field5, $operator5, $value5)->where($field6, $operator6, $value6)->where($field7, $operator7, $value7)->where($field8, $operator8, $value8)->where('Finance_cln_periods_id', '=', $the_finance_cln_periods_id)->where('com_code', '=', $com_code)->orderby('id', 'DESC')->paginate(PC);
      if (!empty($data)) {
        foreach ($data as $info) {
          $info->emp_name = get_field_value(new Employee(), "emp_name", array("com_code" => $com_code, "employees_code" => $info->employees_code));
          $info->emp_photo = get_field_value(new Employee(), "emp_photo", array("com_code" => $com_code, "employees_code" => $info->employees_code));
          $info->emp_gender = get_field_value(new Employee(), "emp_gender", array("com_code" => $com_code, "employees_code" => $info->employees_code));
          $info->branch_name = get_field_value(new Branche(), "name", array("com_code" => $com_code, "id" => $info->branch_id));
          $info->emp_Departments_name = get_field_value(new Departement(), "name", array("com_code" => $com_code, "id" => $info->emp_Departments_code));
          $info->job_name = get_field_value(new jobs_categorie(), "name", array("com_code" => $com_code, "id" => $info->emp_jobs_id));
        }
      }
      $other['counter_salaries'] = Main_salary_employee::where($field1, $operator1, $value1)->where($field2, $operator2, $value2)->where($field3, $operator3, $value3)->where($field4, $operator4, $value4)->where($field5, $operator5, $value5)->where($field6, $operator6, $value6)->where($field7, $operator7, $value7)->where($field8, $operator8, $value8)->where('Finance_cln_periods_id', '=', $the_finance_cln_periods_id)->where('com_code', '=', $com_code)->count();

      $other['counter_salaries_wating_archive'] = Main_salary_employee::where($field1, $operator1, $value1)->where($field2, $operator2, $value2)->where($field3, $operator3, $value3)->where($field4, $operator4, $value4)->where($field5, $operator5, $value5)->where($field6, $operator6, $value6)->where($field7, $operator7, $value7)->where($field8, $operator8, $value8)->where('is_archived', '=', 0)->where('Finance_cln_periods_id', '=', $the_finance_cln_periods_id)->where('com_code', '=', $com_code)->count();


      $other['counter_salaries_done_archive'] = Main_salary_employee::where($field1, $operator1, $value1)->where($field2, $operator2, $value2)->where($field3, $operator3, $value3)->where($field4, $operator4, $value4)->where($field5, $operator5, $value5)->where($field6, $operator6, $value6)->where($field7, $operator7, $value7)->where($field8, $operator8, $value8)->where('is_archived', '=', 1)->where('Finance_cln_periods_id', '=', $the_finance_cln_periods_id)->where('com_code', '=', $com_code)->count();


      $other['counter_salaries_stop'] = Main_salary_employee::where($field1, $operator1, $value1)->where($field2, $operator2, $value2)->where($field3, $operator3, $value3)->where($field4, $operator4, $value4)->where($field5, $operator5, $value5)->where($field6, $operator6, $value6)->where($field7, $operator7, $value7)->where($field8, $operator8, $value8)->where('is_stoped', '=', 1)->where('Finance_cln_periods_id', '=', $the_finance_cln_periods_id)->where('com_code', '=', $com_code)->count();


      return view('admin.Main_salary_employee.ajax_search', ['data' => $data, 'other' => $other]);
    }
  }
}
