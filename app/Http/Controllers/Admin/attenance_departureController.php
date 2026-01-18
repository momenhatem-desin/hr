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
use App\Models\Attenance_departure_actions_excel;
use App\Http\Requests\Attenance_departureUploadExcel;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\Attendance_departureImport;
use App\Models\Admin;
use App\Models\Admin_branches;
use App\Models\Branche;
use App\Models\jobs_categorie;
use App\Models\MainVacationsBalance;
use App\Models\Vactions_types;
use App\Models\weekdays;
use DateTime;
use App\Traits\GeneralTrait;


class attenance_departureController extends Controller
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
    return view('admin.attenance_departure.index', ['data' => $data]);
  }

  public function show($finance_cln_periods_id)
  {
    $com_code = auth('admin')->user()->com_code;
    $finance_cln_periods_data = get_cols_where_row(new Finance_cln_periods(), array("*"), array("com_code" => $com_code, 'id' => $finance_cln_periods_id));

    if (empty($finance_cln_periods_data)) {
      return redirect()->route('attenance_departure.index')->with(['error' => 'عفوا  غير قادر على الوصول الى البيانات المطلوبة ! ']);
    }

    $data = get_cols_where_p(new Employee(), array("*"), array("com_code" => $com_code), "id", "ASC", PC);
    $employess_for_search = get_cols_where(new Employee(), array("employees_code", "emp_name"), array("com_code" => $com_code), 'employees_code', 'ASC');
    $last_attendance_departure_date = get_cols_where_row_orderby(new Attenance_departure_actions_excel(), array("datetimeAction", "created_at", "added_by"), array("com_code" => $com_code, "finance_cln_periods_id" => $finance_cln_periods_id));
    if (!empty($last_attendance_departure_date)) {
      $last_attendance_departure_date['addend_by_name'] = get_field_value(new Admin(), 'name', array("id" => $last_attendance_departure_date['added_by'], "com_code" => $com_code));
    }
    return view('admin.attenance_departure.show', ['data' => $data, 'finance_cln_periods_data' => $finance_cln_periods_data, 'employess_for_search' => $employess_for_search, 'last_attendance_departure_date' => $last_attendance_departure_date]);
  }

  public function uploadExcelFile($finance_cln_periods_id)
  {
    $com_code = auth('admin')->user()->com_code;
    $finance_cln_periods_data = get_cols_where_row(new Finance_cln_periods(), array("*"), array("com_code" => $com_code, 'id' => $finance_cln_periods_id, "is_open" => 1));

    if (empty($finance_cln_periods_data)) {
      return redirect()->route('attenance_departure.index')->with(['error' => 'عفوا  غير قادر على الوصول الى البيانات المطلوبة ! ']);
    }

    return view('admin.attenance_departure.uploadExcelFile', ['finance_cln_periods_data' => $finance_cln_periods_data]);
  }

  public function DoUploadExcelFile($finance_cln_periods_id, Attenance_departureUploadExcel $request)
  {
    $com_code = auth('admin')->user()->com_code;
    $finance_cln_periods_data = get_cols_where_row(new Finance_cln_periods(), array("*"), array("com_code" => $com_code, 'id' => $finance_cln_periods_id, "is_open" => 1));

    if (empty($finance_cln_periods_data)) {
      return redirect()->route('attenance_departure.index')->with(['error' => 'عفوا  غير قادر على الوصول الى البيانات المطلوبة ! ']);
    }

    Excel::import(new Attendance_departureImport($finance_cln_periods_data), $request->excel_file);
    return redirect()->route('attenance_departure.show', $finance_cln_periods_data)->with(['success' => 'تم سحب البصمه بنجاح']);
  }

  public function show_passma_detalis($employees_code, $finance_cln_periods_id)
  {
    $com_code = auth('admin')->user()->com_code;
    $Employee_Date = get_cols_where_row(new Employee(), array("*"), array("com_code" => $com_code, 'employees_code' => $employees_code));
    if (empty($Employee_Date)) {
      return redirect()->route('attenance_departure.show')->with(['error' => 'عفوا  غير قادر على الوصول الى البيانات المطلوبة ! ']);
    }
    $finance_cln_periods_data = get_cols_where_row(new Finance_cln_periods(), array("*"), array("com_code" => $com_code, 'id' => $finance_cln_periods_id));

    if (empty($finance_cln_periods_data)) {
      return redirect()->route('attenance_departure.index')->with(['error' => 'عفوا  غير قادر على الوصول الى البيانات المطلوبة ! ']);
    }
    $this->calculate_employess_vactions_blance($employees_code);
    $this->calculate_employess_vactions_blance($employees_code);
    $setting = get_cols_where_row(new Admin_panel_setting(), array("is_outo_offect_passmaV"), array("com_code" => $com_code));
    $main_salary_employee = get_cols_where_row(new Main_salary_employee(), array("id"), array("com_code" => $com_code, "employees_code" => $employees_code, "is_archived" => 0, 'Finance_cln_periods_id' => $finance_cln_periods_id));
    if ($setting['is_outo_offect_passmaV'] == 2) {
      if (!empty($main_salary_employee)) {
        $this->Recalculate_auto_passma_variables($main_salary_employee['id']);
      }
    }




    return view('admin.attenance_departure.show_passma_detalis', ['Employee_Date' => $Employee_Date, 'finance_cln_periods_data' => $finance_cln_periods_data]);
  }

  function load_passma_archive(Request $request)
  {
    if ($request->ajax()) {
      $com_code = auth('admin')->user()->com_code;
      $attenance_departure_actions_excel = get_cols_where(new Attenance_departure_actions_excel(), array("*"), array("com_code" => $com_code, 'employees_code' => $request->employees_code, 'Finance_cln_periods_id' => $request->the_finance_cln_periods_id), 'datetimeAction', 'ASC');
      if (!empty($attenance_departure_actions_excel)) {
        foreach ($attenance_departure_actions_excel as $info) {
          $dt = new DateTime($info->datetimeAction);
          $date = $dt->format('Y-m-d');
          $nameOfday = date('l', strtotime($date));
          $info->week_day_name_arbic = get_field_value(new weekdays(), "name", array("name_en" => $nameOfday));
        }
      }
      return view("admin.attenance_departure.load_passma_archive", ['attenance_departure_actions_excel' => $attenance_departure_actions_excel]);
    }
  }

  //cheak_dayies_interval
  public function load_active_Attendance_departure(Request $request)
  {
    if ($request->ajax()) {
      $com_code = auth('admin')->user()->com_code;
      $other['Employee_Date'] = get_cols_where_row(new Employee(), array("*"), array("com_code" => $com_code, 'employees_code' => $request->employees_code));
      if (!empty($other['Employee_Date'])) {
        $setting = get_cols_where_row(new Admin_panel_setting(), array("is_pull_anuall_day_from_passma", "is_outo_offect_passmaV"), array("com_code" => $com_code));
        $other['finance_cln_periods_data'] = get_cols_where_row(new Finance_cln_periods(), array("*"), array("com_code" => $com_code, 'id' => $request->finance_cln_periods_id));
        if (!empty($other['finance_cln_periods_data'])) {
          // لو الشهر المالى مازال مفتوح ولم يأرشف
          if ($other['finance_cln_periods_data']['is_open'] == 1) {
            //cheak_dayies_interval
            //هنا بنجيب اكبر تاريخ واخر تاريخ تم سحب البصمه اليه 
            $max_attend_date = Attenance_departure::where("com_code", "=", $com_code)->where("finance_cln_periods_id", "=", $request->finance_cln_periods_id)->max("datetime_In");
            $to_date = $other['finance_cln_periods_data']['end_date_for_pasma'];
            $form_date = $other['finance_cln_periods_data']['start_date_for_pasma'];
            while ($form_date <= $to_date and $form_date <= $max_attend_date) {
              //هنا هنشوف الايام الفاضيه الموظف لم يعمل بيها بصمه وهنا اجراء التعبئه  تلقائى وتطبيق الظبط والاجازات
              $is_exists = get_cols_where_row(new Attenance_departure(), array("id"), array("com_code" => $com_code, "finance_cln_periods_id" => $request->finance_cln_periods_id, "employees_code" => $request->employees_code, "the_day_date" => $form_date));
              //لو السجل مش موجود لازم النظام ينزل له سجل ويطبق عليه المتغيرات ان وجد
              if (empty($is_exists)) {
                $dataToInsert_departure['shift_hour_contract'] = $other['Employee_Date']['daily_work_hour'];
                $dataToInsert_departure['employees_code'] = $request->employees_code;
                $dataToInsert_departure['finance_cln_periods_id'] = $request->finance_cln_periods_id;
                $dataToInsert_departure['com_code'] = $com_code;
                $dataToInsert_departure['added_by'] = auth('admin')->user()->id;
                $dataToInsert_departure['year_and_month'] = $other['finance_cln_periods_data']['year_and_month'];
                $dataToInsert_departure['branch_id'] = $other['Employee_Date']['branch_id'];
                $dataToInsert_departure['Functiona_status'] = $other['Employee_Date']['Functiona_status'];
                $main_salary_employee = get_cols_where_row(new Main_salary_employee(), array("id"), array("com_code" => $com_code, "employees_code" => $request->employees_code, "is_archived" => 0));
                if (!empty($main_salary_employee)) {
                  $dataToInsert_departure['main_salary_employee_id'] = $main_salary_employee['id'];
                }
                $dataToInsert_departure['the_day_date'] = $form_date;
                //هنا لسه فيه تطبيق متغيرات تلقائيه مث خصم رصيد الاجازات وسوف ين=تم استكماله 
                insert(new Attenance_departure(), $dataToInsert_departure, true);
              }
              $form_date = date("Y-m-d", strtotime($form_date . '+1 day'));
            }
          }
          $other['data'] = get_cols_where(new Attenance_departure(), array("*"), array("com_code" => $com_code, "finance_cln_periods_id" => $request->finance_cln_periods_id, "employees_code" => $request->employees_code), 'the_day_date', 'ASC');
          $other['cut_total'] = 0;
          $other['attendance_dely_total'] = 0;
          $other['early_departure_total'] = 0;
          $other['total_hours_total'] = 0;
          $other['absen_hours_total'] = 0;
          $other['additional_hours_total'] = 0;
          $other['vacations_types_id_total'] = 0;
          if (!empty($other['data'])) {
            foreach ($other['data'] as $info) {

              $nameOfday = date('l', strtotime($info->the_day_date));
              $info->week_day_name_arbic = get_field_value(new weekdays(), "name", array("name_en" => $nameOfday));
              $info->attendance_dep_action_counter = get_count_where(new Attenance_actions(), array("com_code" => $com_code, "attendance_departure_id" => $info->id, "finance_cln_periods_id" => $request->finance_cln_periods_id, "employees_code" => $request->employees_code));
              if ($info->cut != null) {
                $other['cut_total'] += $info->cut;
              }
              if ($info->attendance_dely != null) {
                $other['attendance_dely_total'] += $info->attendance_dely;
              }
              if ($info->early_departure != null) {
                $other['early_departure_total'] += $info->early_departure;
              }
              if ($info->total_hours != null) {
                $other['total_hours_total'] += $info->total_hours;
              }
              if ($info->absen_hours != null) {
                $other['absen_hours_total'] += $info->absen_hours;
              }
              if ($info->additional_hours != null) {
                $other['additional_hours_total'] += $info->additional_hours;
              }
              if ($info->vacations_types_id != null) {
                $other['vacations_types_id_total'] += 1;
              }
            }
          }

          $other['vactions_types'] = Vactions_types::all();
          $other['Vactions_types_distinct'] = Attenance_departure::where("com_code", "=", $com_code)->where("finance_cln_periods_id", "=", $request->finance_cln_periods_id)->where("employees_code", "=", "$request->employees_code")->where("vacations_types_id", '>', 0)->orderby("vacations_types_id", "ASC")->distinct()->get("vacations_types_id");
          if (!empty($other['Vactions_types_distinct'])) {
            foreach ($other['Vactions_types_distinct'] as $info) {
              $info->name = get_field_value(new Vactions_types(), "name", array("id" => $info->vacations_types_id));
              $info->counter = get_count_where(new Attenance_departure(), array("com_code" => $com_code, "finance_cln_periods_id" => $request->finance_cln_periods_id, "employees_code" => $request->employees_code, "vacations_types_id" => $info->vacations_types_id));
            }
          }

          return view("admin.attenance_departure.ajax_load_active_Attendance_departure", ['other' => $other, "max_attend_date" => $max_attend_date, "setting" => $setting]);
        }
      }
    }
  }



  function load_my_actions(Request $request)
  {
    if ($request->ajax()) {
      $com_code = auth('admin')->user()->com_code;
      $parent = get_cols_where_row(new Attenance_departure(), array("id", "datetime_In", "datetime_out", "is_archived"), array("com_code" => $com_code, 'employees_code' => $request->employees_code, 'Finance_cln_periods_id' => $request->finance_cln_periods_id, "id" => $request->id));
      $attenance_departure_actions = get_cols_where(new Attenance_actions(), array("*"), array("com_code" => $com_code, 'employees_code' => $request->employees_code, 'Finance_cln_periods_id' => $request->finance_cln_periods_id, "attendance_departure_id" => $request->id), 'datetimeAction', 'ASC');
      if (!empty($attenance_departure_actions)) {
        foreach ($attenance_departure_actions as $info) {
          $dt = new DateTime($info->datetimeAction);
          $date = $dt->format('Y-m-d');
          $nameOfday = date('l', strtotime($date));
          $info->week_day_name_arbic = get_field_value(new weekdays(), "name", array("name_en" => $nameOfday));
        }
      }
      return view("admin.attenance_departure.load_my_actions", ['attenance_departure_actions' => $attenance_departure_actions, 'parent' => $parent]);
    }
  }




  public function show_Manoual_attennce($finance_cln_periods_id)
  {
    $com_code = auth('admin')->user()->com_code;
    $admin = auth('admin')->user();
    $branches = $admin->branches;
    $setting = get_cols_where_row(new Admin_panel_setting(), array("is_outo_offect_passmaV"), array("com_code" => $com_code));


    // تحويل branches إلى مصفوفة IDs للتحقق
    $allowed_branch_ids = $branches->pluck('id')->toArray();

    $finance = Finance_cln_periods::where([
      "com_code" => $com_code,
      "id" => $finance_cln_periods_id
    ])->first();

    if (!$finance) {
      return redirect()->route('attenance_departure.index')
        ->with(['error' => 'عفوا غير قادر على الوصول للبيانات المطلوبة !']);
    }

    // توليد قائمة التواريخ الكاملة
    $dates = [];
    $current = strtotime($finance->start_date_for_pasma);
    $end = strtotime($finance->end_date_for_pasma);

    while ($current <= $end) {
      $dates[] = date("Y-m-d", $current);
      $current = strtotime("+1 day", $current);
    }

    // جلب الموظفين
    $employees = Employee::where("com_code", $com_code)
      ->orderBy("id", "ASC")
      ->get();

    // جلب سجلات الحضور مع branch_id
    $raw_attendance = Attenance_departure::where("com_code", $com_code)
      ->where("finance_cln_periods_id", $finance_cln_periods_id)
      ->select('employees_code', 'the_day_date', 'vacations_types_id', 'branch_id')
      ->get();

    $vactions = Vactions_types::orderBy("id", "ASC")->get();
    $vactions_map = $vactions->pluck('variables', 'id')->toArray();

    // تحضير مصفوفة الـ branch_id لكل موظف وتاريخ
    $branch_data = [];

    // تحضير مصفوفات البيانات
    $attendance_count = [];
    $attendance_data = [];

    foreach ($employees as $emp) {
      $attendance_data[$emp->employees_code] = [];
      $branch_data[$emp->employees_code] = [];

      foreach ($vactions_map as $id => $var) {
        $attendance_count[$emp->employees_code][$var] = 0;
      }
    }

    foreach ($raw_attendance as $row) {
      $date = date("Y-m-d", strtotime($row->the_day_date));
      $letter = $row->vacations_types_id ? ($vactions_map[$row->vacations_types_id] ?? "") : "";

      $attendance_data[$row->employees_code][$date] = $letter;
      $branch_data[$row->employees_code][$date] = $row->branch_id;

      if ($letter != "") {
        $attendance_count[$row->employees_code][$letter]++;
      }
    }

    $attendance_days = $dates;

    return view('admin.attenance_departure.show_Manoual_attennce', compact(
      'finance',
      'attendance_days',
      'attendance_data',
      'branch_data',
      'allowed_branch_ids',
      'employees',
      'attendance_count',
      'vactions',
      'branches',
      'setting',

    ));
  }
  public function saveManual(Request $request)
  {
    $com_code = auth('admin')->user()->com_code;

    DB::beginTransaction();
    try {

      // خريطة الحروف → id نوع الإجازة
      $vactions_map = Vactions_types::pluck('id', 'variables')
        ->toArray();

      foreach ($request->cells as $employees_code => $days) {

        foreach ($days as $date => $var) {

          // تجاهل الخلايا الفاضية
          if ($var == '' || !isset($vactions_map[$var])) {
            continue;
          }

          $vacation_type_id = $vactions_map[$var];
          //سجل الموظفين

          $Employee_Date = Employee::where([
            'com_code' => $com_code,
            'employees_code' => $employees_code
          ])->first();
          // البحث عن السجل
          $row = Attenance_departure::where([
            'com_code' => $com_code,
            'employees_code' => $employees_code,
            'the_day_date' => $date,
            'finance_cln_periods_id' => $request->finance_id,
          ])->first();
          //    dd($request->branch_id);
          if ($row) {
            // ✅ Update
            $row->update([
              'vacations_types_id' => $vacation_type_id,
              'updated_by' => auth('admin')->user()->id,
              'total_hours' => $Employee_Date->daily_work_hour,

            ]);
          } else {
            // ✅ Insert
            Attenance_departure::create([
              'com_code' => $com_code,
              'employees_code' => $employees_code,
              'the_day_date' => $date,
              'Functiona_status' => 1,
              'attendance_type' => 2,
              'total_hours' => $Employee_Date->daily_work_hour,
              'branch_id' => $request->branch_id_up,
              'finance_cln_periods_id' => $request->finance_id,
              'year_and_month' => $request->year_and_month,
              'vacations_types_id' => $vacation_type_id,
              'added_by' => auth('admin')->user()->id,
            ]);
          }
        }
      }

      DB::commit();
      $setting = get_cols_where_row(new Admin_panel_setting(), array("is_outo_offect_passmaV"), array("com_code" => $com_code));
      // نجيب كل سجلات الرواتب للموظفين في الفترة
      $main_salary_employees = get_cols_where(
        new Main_salary_employee(),
        ['id'],
        [
          'com_code' => $com_code,
          'Finance_cln_periods_id' => $request->finance_id,
          'is_archived' => 0
        ]
      );
      if ($setting['is_outo_offect_passmaV'] == 2) {
        if (!empty($main_salary_employees)) {
          foreach ($main_salary_employees as $row) {
            // نشغل نفس الدالة القديمة
            $this->Recalculate_auto_passma_variables($row['id']);
          }
        }
      }

      return response()->json(['status' => 'success']);
    } catch (\Exception $e) {
      DB::rollBack();
      return response()->json([
        'status' => 'error',
        'message' => $e->getMessage()
      ], 500);
    }
  }



  public function show_Manual_additional_hours($finance_cln_periods_id)
  {
    $com_code = auth('admin')->user()->com_code;
    $admin = auth('admin')->user();
    $branches = $admin->branches;

    // تحويل branches إلى مصفوفة IDs للتحقق
    $allowed_branch_ids = $branches->pluck('id')->toArray();

    $finance = Finance_cln_periods::where([
      "com_code" => $com_code,
      "id" => $finance_cln_periods_id
    ])->first();

    if (!$finance) {
      return redirect()->route('attenance_departure.index')
        ->with(['error' => 'عفوا غير قادر على الوصول للبيانات المطلوبة !']);
    }

    // توليد قائمة التواريخ الكاملة (Y-m-d)
    $dates = [];
    $current = strtotime($finance->start_date_for_pasma);
    $end = strtotime($finance->end_date_for_pasma);

    while ($current <= $end) {
      $dates[] = date("Y-m-d", $current);
      $current = strtotime("+1 day", $current);
    }

    // جلب الموظفين
    $employees = Employee::where("com_code", $com_code)
      ->orderBy("id", "ASC")
      ->get();

    // جلب سجلات الحضور الإضافي مع branch_id
    $raw_additional = Attenance_departure::where("com_code", $com_code)
      ->where("finance_cln_periods_id", $finance_cln_periods_id)
      ->whereNotNull('additional_hours')
      ->select('employees_code', 'the_day_date', 'additional_hours', 'branch_id')
      ->get();

    // مصفوفة بيانات ساعات إضافية
    $additional_data = [];
    $branch_data = []; // ← مصفوفة جديدة لبيانات الفرع
    $additional_total = [];

    foreach ($employees as $emp) {
      $additional_data[$emp->employees_code] = [];
      $branch_data[$emp->employees_code] = []; // ← تهيئة مصفوفة الفرع
      $additional_total[$emp->employees_code] = 0;
    }

    foreach ($raw_additional as $row) {
      $date = date("Y-m-d", strtotime($row->the_day_date));
      $hours = $row->additional_hours ?? 0;
      $additional_data[$row->employees_code][$date] = $hours;
      $branch_data[$row->employees_code][$date] = $row->branch_id; // ← تخزين branch_id
      $additional_total[$row->employees_code] += $hours;
    }

    $attendance_days = $dates;

    return view('admin.attenance_departure.show_Manual_additional_hours', compact(
      'finance',
      'attendance_days',
      'additional_data',
      'branch_data',        // ← إضافة هذا المتغير
      'allowed_branch_ids', // ← إضافة هذا المتغير
      'employees',
      'additional_total',
      'branches'            // ← إضافة هذا المتغير
    ));
  }
  public function saveManualAdditional(Request $request)
  {
    $com_code = auth('admin')->user()->com_code;

    DB::beginTransaction();
    try {
      foreach ($request->additional as $employees_code => $days) {
        foreach ($days as $date => $hours) {
          // تجاهل الخلايا الفاضية أو اللي قيمتها 0
          if ($hours === null || $hours === '' || floatval($hours) == 0) {
            $hours = 0; // ممكن نخزن 0 لو تحب
          }

          // البحث عن السجل
          $row = Attenance_departure::where([
            'com_code' => $com_code,
            'employees_code' => $employees_code,
            'the_day_date' => $date,
            'finance_cln_periods_id' => $request->finance_id
          ])->first();

          if ($row) {
            // ✅ Update
            $row->update([
              'additional_hours' => $hours,
              'updated_by' => auth('admin')->user()->id,
            ]);
          } else {
            // ✅ Insert جديد
            Attenance_departure::create([
              'com_code' => $com_code,
              'employees_code' => $employees_code,
              'the_day_date' => $date,
              'Functiona_status' => 1,
              'attendance_type' => 2, // إضافي
              'finance_cln_periods_id' => $request->finance_id,
              'additional_hours' => $hours,
              'added_by' => auth('admin')->user()->id,
            ]);
          }
        }
      }

      DB::commit();
      return response()->json(['status' => 'success']);
    } catch (\Exception $e) {
      DB::rollBack();
      return response()->json([
        'status' => 'error',
        'message' => $e->getMessage()
      ], 500);
    }
  }


  public function save_active_Attendance_departure(Request $request)
  {
    $debug = [];
    if ($request->ajax()) {
      $com_code = auth('admin')->user()->com_code;
      $attenance_departure = get_cols_where_row(new Attenance_departure(), array("the_day_date", "employees_code", "year_and_month", "vacations_types_id"), array("com_code" => $com_code, "id" => $request->id, "is_archived" => 0, "finance_cln_periods_id" => $request->finance_cln_periods_id, "employees_code" => $request->employees_code));
      if (!empty($attenance_departure)) {
        $dataToupdate['variables'] = $request->variables;
        $dataToupdate['cut'] = $request->cut;
        $dataToupdate['vacations_types_id'] = $request->vacations_types_id;
        $dataToupdate['attendance_dely'] = $request->attendance_dely;
        $dataToupdate['early_departure'] = $request->early_departure;
        $dataToupdate['azn_houres'] = $request->azn_houres;
        $dataToupdate['total_hours'] = $request->total_hours;
        $dataToupdate['absen_hours'] = $request->absen_hours;
        $dataToupdate['additional_hours'] = $request->additional_hours;
        $flag = update(new Attenance_departure(), $dataToupdate, array("com_code" => $com_code, "id" => $request->id, "is_archived" => 0, "finance_cln_periods_id" => $request->finance_cln_periods_id, "employees_code" => $request->employees_code));
        if ($flag) {
          if ($dataToupdate['vacations_types_id'] == 3 || $attenance_departure['vacations_types_id'] == 3 || $dataToupdate['vacations_types_id'] == 14 || $attenance_departure['vacations_types_id'] == 14) {
            $this->calculate_employess_vactions_blance($request->employees_code);


            $setting = get_cols_where_row(new Admin_panel_setting(), array("is_pull_anuall_day_from_passma"), array("com_code" => $com_code));
            $employess_Data = get_cols_where_row(new Employee(), array("is_done_Vaccation_formula", "is_active_for_Vaccation"), array("com_code" => $com_code, "employees_code" => $request->employees_code));
            if (!empty($employess_Data) and $setting['is_pull_anuall_day_from_passma'] == 1) {
              if ($employess_Data['is_active_for_Vaccation'] == 1  &&  $employess_Data['is_done_Vaccation_formula'] == 1) {

                $main_employee_vaction_blance = get_cols_where_row(new MainVacationsBalance(), array("spent_balance", "id"), array("com_code" => $com_code, "year_and_month" => $attenance_departure['year_and_month'], "employees_code" => $request->employees_code));
                if (!empty($main_employee_vaction_blance)) {
                  $A = get_count_where(new Attenance_departure(), array("com_code" => $com_code, "vacations_types_id" => 3, "finance_cln_periods_id" => $request->finance_cln_periods_id, "employees_code" => $request->employees_code));
                  $C = get_count_where(new Attenance_departure(), array("com_code" => $com_code, "vacations_types_id" => 14, "finance_cln_periods_id" => $request->finance_cln_periods_id, "employees_code" => $request->employees_code));
                  $dataToUpdateVaction['spent_balance'] = $A + $C;
                  $flag2 = update(new MainVacationsBalance(), $dataToUpdateVaction, array("id" => $main_employee_vaction_blance['id']));
                  if ($flag2) {
                    $this->reupdate_vactions_blance($request->employees_code);
                  }
                }
              }
            }
          } else {
            $this->calculate_employess_vactions_blance($request->employees_code);
          }

          $setting = get_cols_where_row(new Admin_panel_setting(), array("is_outo_offect_passmaV"), array("com_code" => $com_code));
          $main_salary_employee = get_cols_where_row(new Main_salary_employee(), array("id"), array("com_code" => $com_code, "employees_code" => $request->employees_code, "is_archived" => 0, 'Finance_cln_periods_id' => $request->finance_cln_periods_id));
          if ($setting['is_outo_offect_passmaV'] == 2) {
            if (!empty($main_salary_employee)) {
              $this->Recalculate_auto_passma_variables($main_salary_employee['id']);
            }
          }
          return json_encode("done");
        }
      }
    }
  }

  public function redo_update_action(Request $request)
  {
    if ($request->ajax()) {
      $com_code = auth('admin')->user()->com_code;
      $attenance_departure = get_cols_where_row(new Attenance_departure(), array("*"), array("com_code" => $com_code, "id" => $request->id, "is_archived" => 0, "finance_cln_periods_id" => $request->finance_cln_periods_id, "employees_code" => $request->employees_code));
      if (!empty($attenance_departure)) {
        $datetime_in = $request->datetime_in;
        $datetime_out = $request->datetime_out;
        if ($datetime_out != "") {
          // حنشوف فرق الدقئق بين اخر بصمه والبصمه الحاليه
          $seconds = strtotime($datetime_out) - strtotime($datetime_in);
          $hourdiff = $seconds / 60 / 60;
          $hourdiff = number_format((float)$hourdiff, 2, '.', '');
          $minutesiff = $seconds / 60;
          $minutesiff = number_format((float)$minutesiff, 2, '.', '');
          if ($hourdiff < 0) $hourdiff = $hourdiff * (-1);
          if ($minutesiff < 0) $minutesiff = $minutesiff * (-1);
          // نشتغل على متغيرات اقفال البصمه الحاليه
          $dateUpdate['datetime_In'] = date('Y-m-d H:i:s', strtotime($datetime_in));
          $dateUpdate['datetime_out'] = date('Y-m-d H:i:s', strtotime($datetime_out));
          $dateUpdate['dateOut'] = date('Y-m-d H:i:s', strtotime($datetime_out));
          $dateUpdate['timeIn'] = date('Y-m-d H:i:s', strtotime($datetime_in));
          $dateUpdate['timeOut'] = date('Y-m-d H:i:s', strtotime($datetime_out));
          $dateUpdate['total_hours'] = $hourdiff;
          if ($hourdiff < $attenance_departure['shift_hour_contract']) {
            $dateUpdate['additional_hours'] = 0;
            $dateUpdate['absen_hours'] = $attenance_departure['shift_hour_contract'] - $hourdiff;
          }
          if ($hourdiff > $attenance_departure['shift_hour_contract']) {
            $dateUpdate['additional_hours'] = $hourdiff - $attenance_departure['shift_hour_contract'];
            $dateUpdate['absen_hours'] = 0;
          }
          $flagupdateperent = update(new Attenance_departure(), $dateUpdate,  array("com_code" => $com_code, "id" => $request->id, "is_archived" => 0, "finance_cln_periods_id" => $request->finance_cln_periods_id, "employees_code" => $request->employees_code));
          if ($flagupdateperent) {

            return json_encode("done");
          }
        } else {
          $dataToupdate['total_hours'] = 0;
          $dataToupdate['additional_hours'] = 0;
          $dataToupdate['absen_hours'] = 0;
        }
      }
    }
  }

  public function print_one_passma($employees_code, $finance_cln_periods_id)
  {
    $com_code = auth('admin')->user()->com_code;

    $other['Employee_Date'] = get_cols_where_row(new Employee(), array("*"), array("com_code" => $com_code, 'employees_code' => $employees_code));
    if (empty($other['Employee_Date'])) {
      return redirect()->route('attenance_departure.index')->with(['error' => 'عفوا  غير قادر على الوصول الى البيانات المطلوبة ! ']);
    }
    $other['Employee_Date']['branch_name'] = get_field_value(new Branche(), "name", array("com_code" => $com_code, 'id' => $other['Employee_Date']['branch_id']));
    $other['Employee_Date']['jop_name'] = get_field_value(new jobs_categorie(), "name", array("com_code" => $com_code, 'id' => $other['Employee_Date']['emp_jobs_id']));
    $finance_cln_periods_data = get_cols_where_row(new Finance_cln_periods(), array("*"), array("com_code" => $com_code, 'id' => $finance_cln_periods_id));

    if (empty($finance_cln_periods_data)) {
      return redirect()->route('attenance_departure.index')->with(['error' => 'عفوا  غير قادر على الوصول الى البيانات المطلوبة ! ']);
    }

    $other['data'] = get_cols_where(new Attenance_departure(), array("*"), array("com_code" => $com_code, "finance_cln_periods_id" => $finance_cln_periods_id, "employees_code" => $employees_code), 'the_day_date', 'ASC');
    $other['cut_total'] = 0;
    $other['attendance_dely_total'] = 0;
    $other['early_departure_total'] = 0;
    $other['total_hours_total'] = 0;
    $other['absen_hours_total'] = 0;
    $other['additional_hours_total'] = 0;
    $other['vacations_types_id_total'] = 0;
    if (!empty($other['data'])) {
      foreach ($other['data'] as $info) {

        $nameOfday = date('l', strtotime($info->the_day_date));
        $info->week_day_name_arbic = get_field_value(new weekdays(), "name", array("name_en" => $nameOfday));
        $info->name_vac = get_field_value(new Vactions_types(), "name", array("id" => $info->vacations_types_id));
        $info->attendance_dep_action_counter = get_count_where(new Attenance_actions(), array("com_code" => $com_code, "attendance_departure_id" => $info->id, "finance_cln_periods_id" => $finance_cln_periods_id, "employees_code" => $employees_code));
        if ($info->cut != null) {
          $other['cut_total'] += $info->cut;
        }
        if ($info->attendance_dely != null) {
          $other['attendance_dely_total'] += $info->attendance_dely;
        }
        if ($info->early_departure != null) {
          $other['early_departure_total'] += $info->early_departure;
        }
        if ($info->total_hours != null) {
          $other['total_hours_total'] += $info->total_hours;
        }
        if ($info->absen_hours != null) {
          $other['absen_hours_total'] += $info->absen_hours;
        }
        if ($info->additional_hours != null) {
          $other['additional_hours_total'] += $info->additional_hours;
        }
        if ($info->vacations_types_id != null) {
          $other['vacations_types_id_total'] += 1;
        }
      }
    }
    return view('admin.attenance_departure.print_one_passma', ['other' => $other, 'finance_cln_periods_data' => $finance_cln_periods_data]);
  }
  public function do_is_outo_offect_passmaV(Request $request)
  {
    if ($request->ajax()) {
      $com_code = auth('admin')->user()->com_code;
      $setting = get_cols_where_row(new Admin_panel_setting(), array("is_outo_offect_passmaV"), array("com_code" => $com_code));
      $main_salary_employee = get_cols_where_row(new Main_salary_employee(), array("id"), array("com_code" => $com_code, "employees_code" => $request->employees_code, "is_archived" => 0, 'Finance_cln_periods_id' => $request->finance_cln_periods_id));
      if ($setting['is_outo_offect_passmaV'] == 3) {
        if (!empty($main_salary_employee)) {
          $this->Recalculate_auto_passma_variables($main_salary_employee['id']);
        }
      }


      return json_encode("done");
    }
  }


  public function do_is_outo_offect_passmaV_all(Request $request)
  {
    if ($request->ajax()) {
      $com_code = auth('admin')->user()->com_code;
      $setting = get_cols_where_row(new Admin_panel_setting(), array("is_outo_offect_passmaV"), array("com_code" => $com_code));
      // نجيب كل سجلات الرواتب للموظفين في الفترة
      $main_salary_employees = get_cols_where(
        new Main_salary_employee(),
        ['id'],
        [
          'com_code' => $com_code,
          'Finance_cln_periods_id' => $request->finance_cln_periods_id,
          'is_archived' => 0
        ]
      );
      if ($setting['is_outo_offect_passmaV'] == 3) {
        if (!empty($main_salary_employees)) {
          foreach ($main_salary_employees as $row) {
            // نشغل نفس الدالة القديمة
            $this->Recalculate_auto_passma_variables($row['id']);
          }
        }
      }




      return json_encode("done");
    }
  }
}
