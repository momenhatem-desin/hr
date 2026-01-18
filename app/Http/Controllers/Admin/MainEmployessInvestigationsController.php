<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Finance_calender;
use App\Models\Finance_cln_periods;
use App\Models\Employee;
use App\Models\Admin_panel_setting;
use App\Models\Main_employee_investigation;

class MainEmployessInvestigationsController extends Controller
{

      public function index()

{
$com_code = auth('admin')->user()->com_code;
$data = get_cols_where_p_order2(new Finance_cln_periods(), array("*"), array("com_code" => $com_code), 'FINANCE_YR', 'DESC','MONTH_ID','ASC', 12);
if(!empty($data))
foreach($data as $info){
    // cheak status to open
    $info->currnetYear=get_cols_where_row(new Finance_calender(),array("is_open"),array("com_code"=>$com_code,"FINANCE_YR"=>$info->FINANCE_YR));
    $info->counterOpenMonth=get_count_where(new Finance_cln_periods(),array("com_code" => $com_code,"is_open"=>1));
    $info->counterPreviousMonthWatingOpen=Finance_cln_periods::where("com_code","=", $com_code)
    ->where("FINANCE_YR","=",$info->FINANCE_YR)
    ->where("MONTH_ID","<",$info->MONTH_ID)
    ->where("is_open","=",0)->count();
   // get_count_where(new Finance_cln_periods(),array("com_code" => $com_code,"is_open"=>0,"FINANCE_YR"=>$info->FINANCE_YR,"MONTH_ID"));
} 
return view('admin.main_EmployessInvestigations.index', ['data' => $data]);
}

 public function show($finance_cln_periods_id)
 {
  $com_code = auth('admin')->user()->com_code;
  $finance_cln_periods_data=get_cols_where_row(new Finance_cln_periods(),array("*"),array("com_code"=>$com_code,'id'=>$finance_cln_periods_id));

if(empty($finance_cln_periods_data)){
  return redirect()->route('main_EmployessInvestigations.index')->with(['error' => 'عفوا  غير قادر على الوصول الى البيانات المطلوبة ! ']);
  }
  
  if($finance_cln_periods_data['is_open']==0){
  return redirect()->route('main_EmployessInvestigations.index')->with(['error' => 'عفوا لايمكن العمل على شهر مالى لم يفتح بعد! ']);
  }

  $data = get_cols_where_p(new Main_employee_investigation(), array("*"), array("com_code" => $com_code,"finance_cln_periods_id"=>$finance_cln_periods_id),'id','DESC',PC);
  if(!empty($data)){
    foreach($data as $info){
      $info->emp_name=get_field_value(new Employee(),"emp_name",array("com_code"=>$com_code,"employees_code"=>$info->employees_code));
    }
  }

  
  $employess_for_search=get_cols_where(new Employee(),array("employees_code","emp_name","emp_sal","day_price"),array("com_code"=>$com_code),'employees_code','ASC' );
  return view('admin.main_EmployessInvestigations.show', ['data' => $data,'finance_cln_periods_data'=>$finance_cln_periods_data,'employess_for_search'=>$employess_for_search]);
}

public function checkExsistsBefore(Request $request){
    if($request->ajax()){
    $com_code = auth('admin')->user()->com_code;
       $checkExsistsBeforedata=get_count_where( new Main_employee_investigation(),array("com_code"=>$com_code,"finance_cln_periods_id"=>$request->the_finance_cln_periods_id,"employees_code"=>$request->employees_code_Add)); 
       if($checkExsistsBeforedata>0){
        return json_encode("exsists_before");
       }
        return json_encode("no_exsists_before");
    }
}

public function store(Request $request){
    if($request->ajax()){
    $com_code = auth('admin')->user()->com_code;
    $finance_cln_periods_data=get_cols_where_row(new Finance_cln_periods(),array("id"),array("com_code"=>$com_code,'id'=>$request->the_finance_cln_periods_id,'is_open'=>1));
  
if(!empty($finance_cln_periods_data)){
     DB::beginTransaction();
      $dataToInsert['finance_cln_periods_id'] = $request->the_finance_cln_periods_id;
      $dataToInsert['is_auto'] =0;
      $dataToInsert['employees_code'] =$request->employees_code_Add;
      $dataToInsert['content'] =$request->content_add;
      $dataToInsert['notes'] =$request->notes_Add;
      $dataToInsert['is_archived'] =0;
      $dataToInsert['added_by'] = auth('admin')->user()->id;
      $dataToInsert['com_code'] =$com_code;

  $flag=insert(new Main_employee_investigation(),$dataToInsert);
      DB::commit();
      return json_encode("done");
      
  }
    }
}


public function ajax_search(Request $request)
{
 $com_code = auth('admin')->user()->com_code;  
if ($request->ajax()) {
$employees_code = $request->employees_code_search;
$is_auto_search = $request->is_auto_search;
$is_archived = $request->is_archived_search;
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
if ($is_auto_search == 'all') {
//هنا نعمل شرط دائم التحقق
$field2 = "id";
$operator2 = ">";
$value2 = 0;
} else {
$field2 = "is_auto";
$operator2 = "=";
$value2 = $is_auto_search;
}
if ($is_archived == 'all') {
//هنا نعمل شرط دائم التحقق
$field3 = "id";
$operator3 = ">";
$value3 = 0;
} else {
$field3 = "is_archived";
$operator3 = "=";
$value3 = $is_archived;
}
$data=Main_employee_investigation::select("*")->where($field1,$operator1,$value1)->where($field2,$operator2,$value2)->where($field3,$operator3,$value3)->where('finance_cln_periods_id','=',$the_finance_cln_periods_id)->where('com_code','=',$com_code)->orderby('id','DESC')->paginate(PC);
if(!empty($data)){
    foreach($data as $info){
      $info->emp_name=get_field_value(new Employee(),"emp_name",array("com_code"=>$com_code,"employees_code"=>$info->employees_code));
    }
  }
return view('admin.main_EmployessInvestigations.ajax_search',['data'=>$data]);
}
}

public function delete_row(Request $request)
{
 $com_code = auth('admin')->user()->com_code;  
if ($request->ajax()) {
    $finance_cln_periods_data=get_cols_where_row(new Finance_cln_periods(),array("id"),array("com_code"=>$com_code,'id'=>$request->the_finance_cln_periods_id,'is_open'=>1));
    $data_row=get_cols_where_row(new Main_employee_investigation(),array("id"),array("com_code"=>$com_code,"id"=>$request->id,'is_archived'=>0,'finance_cln_periods_id'=>$request->the_finance_cln_periods_id));
    if(!empty($finance_cln_periods_data and !empty($data_row))){
  $flag= destroy(new  Main_employee_investigation(), array("com_code"=>$com_code,'is_archived'=>0,'finance_cln_periods_id'=>$request->the_finance_cln_periods_id,'id'=>$request->id));
    return json_encode("done");
    }
}
}

public function load_edit_row(Request $request)
{
 $com_code = auth('admin')->user()->com_code;  
if ($request->ajax()) {
     $finance_cln_periods_data=get_cols_where_row(new Finance_cln_periods(),array("id"),array("com_code"=>$com_code,'id'=>$request->the_finance_cln_periods_id,'is_open'=>1));
    $data_row=get_cols_where_row(new Main_employee_investigation(),array("*"),array("com_code"=>$com_code,"id"=>$request->id,'is_archived'=>0,'finance_cln_periods_id'=>$request->the_finance_cln_periods_id));

    $employess=Main_employee_investigation::where("com_code","=","$com_code")->where("finance_cln_periods_id","=",$request->the_finance_cln_periods_id)->distinct()->get("employees_code");
  if(!empty($employess)){
    foreach($employess as $info){
      $info->EmployeeData=get_cols_where_row(new Employee(),array("emp_name","emp_sal","day_price","employees_code"),array("com_code"=>$com_code,"employees_code"=>$info->employees_code));
    }

  }
    return view('admin.main_EmployessInvestigations.load_edit_row',['finance_cln_periods_data'=>$finance_cln_periods_data,'data_row'=>$data_row,'employess'=>$employess]);
}
}

public function do_edit_row(Request $request){
    if($request->ajax()){
    $com_code = auth('admin')->user()->com_code;
    $finance_cln_periods_data=get_cols_where_row(new Finance_cln_periods(),array("id"),array("com_code"=>$com_code,'id'=>$request->the_finance_cln_periods_id,'is_open'=>1));
     $data_row=get_cols_where_row(new Main_employee_investigation(),array("*"),array("com_code"=>$com_code,"id"=>$request->id,'is_archived'=>0,'finance_cln_periods_id'=>$request->the_finance_cln_periods_id));
if(!empty($finance_cln_periods_data) && !empty($data_row) ){
     DB::beginTransaction();
      
      $dataToUpdate['content'] =$request->content_edit;
      $dataToUpdate['is_archived'] =0;
      $dataToUpdate['notes'] =$request->notes_edit;
      $dataToUpdate['updated_by'] = auth('admin')->user()->id;
      $dataToUpdate['com_code'] =$com_code;

      $flag=update(new Main_employee_investigation(),$dataToUpdate,array("com_code"=>$com_code,'is_archived'=>0,'finance_cln_periods_id'=>$request->the_finance_cln_periods_id,"id"=>$request->id));
      DB::commit();
      return json_encode("done");
  }
    }
}

public function print_search(Request $request)
{

$com_code = auth('admin')->user()->com_code;  
$employees_code = $request->employees_code_search;
$is_auto_search = $request->is_auto_search;
$is_archived = $request->is_archived_search;
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
if ($is_auto_search == 'all') {
//هنا نعمل شرط دائم التحقق
$field2 = "id";
$operator2 = ">";
$value2 = 0;
} else {
$field2 = "is_auto";
$operator2 = "=";
$value2 = $is_auto_search;
}
if ($is_archived == 'all') {
//هنا نعمل شرط دائم التحقق
$field3 = "id";
$operator3 = ">";
$value3 = 0;
} else {
$field3 = "is_archived";
$operator3 = "=";
$value3 = $is_archived;
}
$finance_cln_periods_data=get_cols_where_row(new Finance_cln_periods(),array("*"),array("com_code"=>$com_code,'id'=>$the_finance_cln_periods_id));
$other['value_sum']=0;
$other['total_sum']=0;
$data=Main_employee_investigation::select("*")->where($field1,$operator1,$value1)->where($field2,$operator2,$value2)->where($field3,$operator3,$value3)->where('finance_cln_periods_id','=',$the_finance_cln_periods_id)->where('com_code','=',$com_code)->orderby('id','DESC')->get('');
if(!empty($data)){
    foreach($data as $info){
      $info->emp_name=get_field_value(new Employee(),"emp_name",array("com_code"=>$com_code,"employees_code"=>$info->employees_code));
      $other['value_sum']+=$info->value;
      $other['total_sum']+=$info->total;
    }
  }
$systemData=get_cols_where_row(new Admin_panel_setting(),array("image","phones","address","company_name"),array("com_code"=>$com_code));  
return view('admin.main_EmployessInvestigations.print_search',['data'=>$data,'finance_cln_periods_data'=>$finance_cln_periods_data,'systemData'=>$systemData,'other'=>$other]);
}

    
}
