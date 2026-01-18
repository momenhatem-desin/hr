<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin_panel_setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Finance_calender;
use App\Models\Finance_cln_periods;
use App\Models\Employee;
use App\Models\Main_salary_employee_settlements;

class main_salary_employee_settlementsController extends Controller
{
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
        return view('admin.MainEmployeeSettlements.index', ['data' => $data]);
    }

    public function show($finance_cln_periods_id)
    {
        $com_code = auth('admin')->user()->com_code;
        $finance_cln_periods_data = get_cols_where_row(new Finance_cln_periods(), array("*"), array("com_code" => $com_code, 'id' => $finance_cln_periods_id));

        if (empty($finance_cln_periods_data)) {
            return redirect()->route('MainEmployeeSettlements.index')->with(['error' => 'عفوا  غير قادر على الوصول الى البيانات المطلوبة ! ']);
        }

        if ($finance_cln_periods_data['is_open'] == 0) {
            return redirect()->route('MainEmployeeSettlements.index')->with(['error' => 'عفوا لايمكن العمل على شهر مالى لم يفتح بعد! ']);
        }

        $data = get_cols_where_p(new Main_salary_employee_settlements(), array("*"), array("com_code" => $com_code, "finance_cln_periods_id" => $finance_cln_periods_id), 'id', 'DESC', PC);
        if (!empty($data)) {
            foreach ($data as $info) {
                $info->emp_name = get_field_value(new Employee(), "emp_name", array("com_code" => $com_code, "employees_code" => $info->employees_code));
            }
        }


        $employess_for_search = get_cols_where(new Employee(), array("employees_code", "emp_name", "emp_sal", "day_price"), array("com_code" => $com_code), 'employees_code', 'ASC');
        return view('admin.MainEmployeeSettlements.show', ['data' => $data, 'finance_cln_periods_data' => $finance_cln_periods_data, 'employess_for_search' => $employess_for_search]);
    }


    public function store(Request $request)
    {
        try {
            $com_code = auth('admin')->user()->com_code;
            DB::beginTransaction();
            $dataToinsert['finance_cln_periods_id'] = $request->finance_cln_periods_id;
            $dataToinsert['employees_code'] = $request->employees_code_Add;
            $dataToinsert['work_days_for'] = $request->work_days_for;
            $dataToinsert['work_days_for_total'] = $request->work_days_for_total;
            $dataToinsert['extra_days_for'] = $request->extra_days_for;
            $dataToinsert['extra_days_for_total'] = $request->extra_days_total_for;
            $dataToinsert['absence_back_for'] = $request->absence_back_for;
            $dataToinsert['absence_back_total_for'] = $request->absence_back_total_for;
            $dataToinsert['sanctions_back_for'] = $request->sanctions_back_for;
            $dataToinsert['sanctions_back_total_for'] = $request->sanctions_back_total_for;
            $dataToinsert['salary_difference_for'] = $request->salary_difference_for;
            $dataToinsert['award_for'] = $request->award_for;
            $dataToinsert['allowance_for'] = $request->allowance_for;
            $dataToinsert['total_for'] = $request->total_for;
            $dataToinsert['absence_on'] = $request->absence_on;
            $dataToinsert['absence_total_on'] = $request->absence_total_on;
            $dataToinsert['sanctions_on'] = $request->sanctions_on;
            $dataToinsert['sanctions_total_on'] = $request->sanctions_total_on;
            $dataToinsert['cash_discound_on'] = $request->cash_discount_on;
            $dataToinsert['allowance_on'] = $request->allowance_on;
            $dataToinsert['midical_insurance_on'] = $request->medical_insurance_on;
            $dataToinsert['social_insurance_on'] = $request->social_insurance_on;
            $dataToinsert['monthly_loan_on'] = $request->monthly_loan_on;
            $dataToinsert['permaneten_monthly_loan_on'] = $request->permanetn_monthly_loan_on;
            $dataToinsert['total_on'] = $request->total_on;
            $dataToinsert['final_total'] = $request->final_total;
            $dataToinsert['total_for'] = $request->total_for;
            $dataToinsert['emp_day_price'] = $request->day_price_add;
            $dataToinsert['emp_sal'] = $request->emp_sal_add;
            $dataToinsert['sanctions_on'] = $request->sanctions_on;
            $dataToinsert['notes'] = $request->notes;

            $dataToinsert['added_by'] = auth('admin')->user()->id;
            $dataToinsert['com_code'] = $com_code;
            insert(new Main_salary_employee_settlements(), $dataToinsert, array("com_code" => $com_code));
            DB::commit();
            return  redirect()->route('MainEmployeeSettlements.show', $request->finance_cln_periods_id)->with(['success' => 'تم ادخال البيانات بنجاح']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفوا حدث خطأ ما ' . $ex->getMessage()])->withInput();
        }
    }

    public function load_edit_row(Request $request)
    {
        $com_code = auth('admin')->user()->com_code;
        if ($request->ajax()) {
            $finance_cln_periods_data = get_cols_where_row(new Finance_cln_periods(), array("id"), array("com_code" => $com_code, 'id' => $request->the_finance_cln_periods_id, 'is_open' => 1));
            $data_row = get_cols_where_row(new Main_salary_employee_settlements(), array("*"), array("com_code" => $com_code, "id" => $request->id, 'is_archived' => 0, 'finance_cln_periods_id' => $request->the_finance_cln_periods_id));

            $employess = get_cols_where(new Employee(), array("employees_code", "emp_name", "emp_sal", "day_price"), array("com_code" => $com_code), 'employees_code', 'ASC');
        }
        return view('admin.MainEmployeeSettlements.load_edit_row', ['finance_cln_periods_data' => $finance_cln_periods_data, 'data_row' => $data_row, 'employess' => $employess]);
    }



    public function update($id, Request $request)
    {
        try {
            $com_code = auth("admin")->user()->com_code;
            $data = get_cols_where_row(new Main_salary_employee_settlements(), array("*"), array("com_code" => $com_code, 'id' => $id));
            if (empty($data)) {
                return redirect()->route('MainEmployeeSettlements.index')->with(['error' => 'عفوا غير قادر للوصول الي البيانات المطلوبة']);
            }
       
            DB::beginTransaction();
            $dataToUpdate['work_days_for'] = $request->work_days_forEdit;
            $dataToUpdate['work_days_for_total'] = $request->work_days_for_totalEdit;
            $dataToUpdate['extra_days_for'] = $request->extra_days_forEdit;
            $dataToUpdate['extra_days_for_total'] = $request->extra_days_total_forEdit;
            $dataToUpdate['absence_back_for'] = $request->absence_back_forEdit;
            $dataToUpdate['absence_back_total_for'] = $request->absence_back_total_forEdit;
            $dataToUpdate['sanctions_back_for'] = $request->sanctions_back_forEdit;
            $dataToUpdate['sanctions_back_total_for'] = $request->sanctions_back_total_forEdit;
            $dataToUpdate['salary_difference_for'] = $request->salary_difference_forEdit;
            $dataToUpdate['award_for'] = $request->award_forEdit;
            $dataToUpdate['allowance_for'] = $request->allowance_forEdit;
            $dataToUpdate['total_for'] = $request->total_forEdit;
            $dataToUpdate['absence_on'] = $request->absence_onEdit;
            $dataToUpdate['absence_total_on'] = $request->absence_total_onEdit;
            $dataToUpdate['sanctions_on'] = $request->sanctions_onEdit;
            $dataToUpdate['sanctions_total_on'] = $request->sanctions_total_onEdit;
            $dataToUpdate['cash_discound_on'] = $request->cash_discount_onEdit;
            $dataToUpdate['allowance_on'] = $request->allowance_onEdit;
            $dataToUpdate['midical_insurance_on'] = $request->medical_insurance_onEdit;
            $dataToUpdate['social_insurance_on'] = $request->social_insurance_onEdit;
            $dataToUpdate['monthly_loan_on'] = $request->monthly_loan_onEdit;
            $dataToUpdate['permaneten_monthly_loan_on'] = $request->permanetn_monthly_loan_onEdit;
            $dataToUpdate['total_on'] = $request->total_onEdit;
            $dataToUpdate['final_total'] = $request->final_totalEdit;
            $dataToUpdate['total_for'] = $request->total_forEdit;
            $dataToUpdate['sanctions_on'] = $request->sanctions_onEdit;
            $dataToUpdate['notes'] = $request->notes;
            $dataToUpdate['updated_by'] = auth('admin')->user()->id;
            $dataToUpdate['com_code'] = $com_code;
            update(new Main_salary_employee_settlements(), $dataToUpdate, array("com_code" => $com_code, 'id' => $id));
            DB::commit();
            return redirect()->back()->with(['success' => 'تم تحديث البيانات بنجاح']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفوا حدث خطأ  ' . $ex->getMessage()])->withInput();
        }
    }


 public function ajax_search(Request $request)
{
 $com_code = auth('admin')->user()->com_code;  
if ($request->ajax()) {
$employees_code = $request->employees_code_search;
$the_finance_cln_periods_id=$request->the_finance_cln_periods_id;
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

$data=Main_salary_employee_settlements::select("*")->where($field1,$operator1,$value1)->where('finance_cln_periods_id','=',$the_finance_cln_periods_id)->where('com_code','=',$com_code)->orderby('id','DESC')->paginate(PC);
if(!empty($data)){
    foreach($data as $info){
      $info->emp_name=get_field_value(new Employee(),"emp_name",array("com_code"=>$com_code,"employees_code"=>$info->employees_code));
    }
  }
return view('admin.MainEmployeeSettlements.ajax_search',['data'=>$data]);
}
}


public function print_search(Request $request)
{

$com_code = auth('admin')->user()->com_code;  
$employees_code = $request->employees_code_search;
$the_finance_cln_periods_id=$request->the_finance_cln_periods_id;
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

$finance_cln_periods_data=get_cols_where_row(new Finance_cln_periods(),array("*"),array("com_code"=>$com_code,'id'=>$the_finance_cln_periods_id));
$other['total_for']=0;
$other['total_on']=0;
$other['final_total']=0;
$data=Main_salary_employee_settlements::select("*")->where($field1,$operator1,$value1)->where('finance_cln_periods_id','=',$the_finance_cln_periods_id)->where('com_code','=',$com_code)->orderby('id','DESC')->get('');
if(!empty($data)){
    foreach($data as $info){
      $info->emp_name=get_field_value(new Employee(),"emp_name",array("com_code"=>$com_code,"employees_code"=>$info->employees_code));
      $other['total_for']+=$info->total_for;
      $other['total_on']+=$info->total_on;
      $other['final_total']+=$info->final_total;
    }
  }
$systemData=get_cols_where_row(new Admin_panel_setting(),array("image","phones","address","company_name"),array("com_code"=>$com_code));  
return view('admin.MainEmployeeSettlements.print_search',['data'=>$data,'finance_cln_periods_data'=>$finance_cln_periods_data,'systemData'=>$systemData,'other'=>$other]);
}



    public function delete_row(Request $request)
    {
        $com_code = auth('admin')->user()->com_code;
        if ($request->ajax()) {
            $finance_cln_periods_data = get_cols_where_row(new Finance_cln_periods(), array("id"), array("com_code" => $com_code, 'id' => $request->the_finance_cln_periods_id, 'is_open' => 1));
            $data_row = get_cols_where_row(new Main_salary_employee_settlements(), array("id"), array("com_code" => $com_code, "id" => $request->id, 'is_archived' => 0, 'finance_cln_periods_id' => $request->the_finance_cln_periods_id));
            if (!empty($finance_cln_periods_data and !empty($data_row))) {
                $flag = destroy(new  Main_salary_employee_settlements(), array("com_code" => $com_code, 'is_archived' => 0, 'finance_cln_periods_id' => $request->the_finance_cln_periods_id, 'id' => $request->id));
                return json_encode("done");
            }
        }
    }
}
