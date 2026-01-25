<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Employee;
use App\Models\Main_salary_employees_P_loans;
use App\Models\Main_salary_P_loans_akast;
use App\Models\Main_salary_employee;
use App\Models\Admin_panel_setting;
use App\Traits\GeneralTrait;
use Illuminate\Support\Arr;

class Main_salary_employees_p_loansController extends Controller
{
    use GeneralTrait;
public function index()
{
$com_code = auth('admin')->user()->com_code;
$data = get_cols_where_p(new Main_salary_employees_P_loans(), array("*"), array("com_code" => $com_code), 'id', 'DESC',PC);
 if(!empty($data)){
    foreach($data as $info){
      $info->emp_name=get_field_value(new Employee(),"emp_name",array("com_code"=>$com_code,"employees_code"=>$info->employees_code));
    }
}
$other['employess']=get_cols_where(new Employee(),array("employees_code","emp_name","emp_sal","day_price"),array("com_code"=>$com_code,'Functiona_status'=>1),"id","DESC");
//يوجد مشكله هنا 
return view('admin.Main_salary_employees_P_loans.index', ['data' => $data,'other'=>$other]);
}


public function checkExsistsBefore(Request $request){
    if($request->ajax()){
    $com_code = auth('admin')->user()->com_code;
       $checkExsistsBeforedata=get_count_where( new Main_salary_employees_P_loans(),array("com_code"=>$com_code,"employees_code"=>$request->employees_code_Add,'is_archived'=>0)); 
       if($checkExsistsBeforedata>0){
        return json_encode("exsists_before");
       }
        return json_encode("no_exsists_before");
    }
}


public function store(Request $request){
    if($request->ajax()){
    $com_code = auth('admin')->user()->com_code;
    $employee_data=get_cols_where_row(new Employee(),array("id"),array("com_code"=>$com_code,'employees_code'=>$request->employees_code_Add,'Functiona_status'=>1));
  
if(!empty($employee_data)){
     DB::beginTransaction();
      $dataToInsert['employees_code'] =$request->employees_code_Add;
      $dataToInsert['total'] =$request->total_Add;
      $dataToInsert['emp_sal'] =$request->emp_sal_add;
      $dataToInsert['month_kast_value'] =$request->month_kast_value_Add;
      $dataToInsert['month_number'] =$request->month_number_Add;
      $dataToInsert['year_and_month_start_date'] =$request->year_and_month_start_data_Add;
      $dataToInsert['year_and_month_start'] =date('Y-m', strtotime($request->year_and_month_start_data_Add));
       $dataToInsert['created_at'] =date("Y-m-d H:i:s");
      $dataToInsert['is_archived'] =0;
      $dataToInsert['notes'] =$request->notes_Add;
      $dataToInsert['added_by'] = auth('admin')->user()->id;
      $dataToInsert['com_code'] =$com_code;

      $flagParent= insert(new Main_salary_employees_P_loans(),$dataToInsert,true);
      if ($flagParent){
        //تقسيم الاقساط الشهرية تليقائيا
        $i=1;
        $effectiveDate=$dataToInsert['year_and_month_start'];
        while($i<=$dataToInsert['month_number']){
        $dataToInsertkast['main_salary_p_loans_id']=$flagParent['id'];
        $dataToInsertkast['month_kast_value']= $dataToInsert['month_kast_value'];
        $dataToInsertkast['employees_code'] =$request->employees_code_Add;    
        $dataToInsertkast['year_and_month']=$effectiveDate;
        $dataToInsertkast['state']=0;
        $dataToInsertkast['is_archived']=0;
        $dataToInsertkast['added_by'] = auth('admin')->user()->id;
        $dataToInsertkast['com_code'] =$com_code;
        insert(new Main_salary_P_loans_akast(),$dataToInsertkast);
       $i++;
       $effectiveDate=date('Y-m', strtotime("+1 months",strtotime($effectiveDate)));
        }
      }
      DB::commit();
      return json_encode("done");
  }
    }
}


public function load_akast_details(Request $request)
{
 $com_code = auth('admin')->user()->com_code;  
if ($request->ajax()) {
    $dataParentloan=get_cols_where_row(new Main_salary_employees_P_loans(),array("*"),array("com_code"=>$com_code,"id"=>$request->id));
  if(!empty($dataParentloan)){
     $aksatDetails['aksatDetalis']=get_cols_where(new Main_salary_P_loans_akast(),array("*"),array("com_code"=>$com_code,"main_salary_p_loans_id"=>$request->id),"id","ASC");
     if(!empty( $aksatDetails['aksatDetalis'])){
        foreach($aksatDetails['aksatDetalis'] as $info){
            //القسط الى عليه الدور فى الدفع
            //وانه غير مؤرشف  وانه غير مدفوع على مرتب
            $info->counterBeforeNotPaid=Main_salary_P_loans_akast::where("com_code","=",$com_code)->
            where("main_salary_p_loans_id","=","$request->id")->
            where("state","=",0)->
            where("id","<",$info->id)->count();
        }
     }

    }
    return view('admin.Main_salary_employees_P_loans.load_akast_details',['dataParentloan'=>$dataParentloan, 'aksatDetails' => $aksatDetails]);
}
}

public function do_edit_row(Request $request)
{
    if ($request->ajax()) {
        $com_code = auth('admin')->user()->com_code;

        $data_row = get_cols_where_row(
            new Main_salary_employees_P_loans(),
            ["*"],
            ["com_code" => $com_code, "id" => $request->id, 'is_archived' => 0, 'is_dismissail_done' => 0]
        );

        if (!empty($data_row)) {
            $employee_data = get_cols_where_row(
                new Employee(),
                ["id"],
                ["com_code" => $com_code, 'employees_code' => $request->employees_code_edit, 'Functiona_status' => 1]
            );

            if (!empty($employee_data)) {
                try {
                    DB::beginTransaction();

                    $dataToUpdate = [
                        'employees_code' => $request->employees_code_edit,
                        'total' => $request->total_edit,
                        'emp_sal' => $request->emp_sal_edit,
                        'month_kast_value' => $request->month_kast_value_edit,
                        'month_number' => $request->month_number_edit,
                        'year_and_month_start_date' => $request->year_and_month_start_data_edit,
                        'year_and_month_start' => date('Y-m', strtotime($request->year_and_month_start_data_edit)),
                        'notes' => $request->notes_edit,
                        'updated_by' => auth('admin')->user()->id,
                        'com_code' => $com_code,
                    ];

                    $flagParent = update(
                        new Main_salary_employees_P_loans(),
                        $dataToUpdate,
                        ["com_code" => $com_code, "id" => $request->id, 'is_archived' => 0, 'is_dismissail_done' => 0]
                    );

                    if ($flagParent) {
                        // إعادة توزيع الأقساط إذا تغيرت البيانات المهمة
                        if (
                            $data_row['total'] != $dataToUpdate['total'] ||
                            $data_row['month_number'] != $dataToUpdate['month_number'] ||
                            $data_row['year_and_month_start_date'] != $dataToUpdate['year_and_month_start_date'] ||
                            $data_row['month_kast_value'] != $dataToUpdate['month_kast_value']
                        ) {
                            $flagDelete = destroy(
                                new Main_salary_P_loans_akast(),
                                ["com_code" => $com_code, "main_salary_p_loans_id" => $request->id]
                            );

                            if ($flagDelete) {
                                $i = 1;
                                $effectiveDate = $dataToUpdate['year_and_month_start'];
                                while ($i <= $dataToUpdate['month_number']) {
                                    $dataToupdatekast = [
                                        'main_salary_p_loans_id' =>$request->id,
                                        'month_kast_value' => $dataToUpdate['month_kast_value'],
                                        'employees_code' =>$request->employees_code_edit,  
                                        'year_and_month' => $effectiveDate,
                                        'state' => 0,
                                        'is_archived' => 0,
                                        'added_by' => auth('admin')->user()->id,
                                        'com_code' => $com_code,
                                    ];
                                    insert(new Main_salary_P_loans_akast(), $dataToupdatekast);
                                    $i++;
                                    $effectiveDate = date('Y-m', strtotime("+1 months", strtotime($effectiveDate)));
                                }
                            }
                        }
                    }

                    DB::commit();
                    return response()->json("done");
                } catch (\Exception $ex) {
                    DB::rollBack();
                    return response()->json(['error' => 'حدث خطأ', 'details' => $ex->getMessage()]);
                }
            }
        }
    }
}



public function load_edit_row(Request $request)
{
 $com_code = auth('admin')->user()->com_code;  
if ($request->ajax()) {
    $data_row=get_cols_where_row(new Main_salary_employees_P_loans(),array("*"),array("com_code"=>$com_code,"id"=>$request->id,'is_archived'=>0,'is_dismissail_done'=>0));
    $employess=get_cols_where(new Employee(),array("emp_name","emp_sal","day_price","employees_code"),array("com_code"=>$com_code,"Functiona_status"=>1));


    return view('admin.Main_salary_employees_P_loans.load_edit_row',['data_row'=>$data_row,'employess'=>$employess]);
}
}


public function delete($id)
{
    try{
    $com_code = auth('admin')->user()->com_code;  
    $dataParentloan=get_cols_where_row(new Main_salary_employees_P_loans(),array("id"),array("com_code"=>$com_code,"id"=>$id));
    if(empty($dataParentloan)){
      return view('admin.Main_salary_employees_P_loans.index')->with(['error'=>'عفوا غير قادر على الوصل الى البيانات']);
    }
    if($dataParentloan['is_dismissail_done']==1){
      return view('admin.Main_salary_employees_P_loans.index')->with(['error'=>'عفوا لايمكن حذف سلفه تم صرفها بالفعل']);
    }
     if($dataParentloan['is_archived']==1){
      return view('admin.Main_salary_employees_P_loans.index')->with(['error'=>'عفوا لايمكن حذف سلفه تم ارشفتها بالفعل']);
    }
   DB::beginTransaction(); 
   $flagParent=  destroy(new  Main_salary_employees_P_loans(), array("com_code"=>$com_code,'is_archived'=>0,'is_dismissail_done'=>0,'id'=>$id)); 
   if($flagParent){
     destroy(new  Main_salary_P_loans_akast(), array("com_code"=>$com_code,'state'=>0,'main_salary_p_loans_id'=>$id)); 
   }
     DB::commit();
      return redirect()->route('Main_salary_employees_P_loans.index')->with(['success'=>' تم حذف البيانات بنجاح']);
     } catch (\Exception $ex) {
        DB::rollBack();
        return redirect()->back()->with(['error' => 'عفوا حدث خطأ ' . $ex->getMessage()])->withInput();
     }
}

public function ajax_search(Request $request)
{
 $com_code = auth('admin')->user()->com_code;  
if ($request->ajax()) {
$employees_code = $request->employees_code_search;
$is_archived = $request->is_archived_search;
$is_dismissail_done=$request->is_dismissail_done_search;
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
if ($is_dismissail_done == 'all') {
//هنا نعمل شرط دائم التحقق
$field2 = "id";
$operator2 = ">";
$value2 = 0;
} else {
$field2 = "is_dismissail_done";
$operator2 = "=";
$value2 = $is_dismissail_done;
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

$data=Main_salary_employees_P_loans::select("*")->where($field1,$operator1,$value1)->where($field2,$operator2,$value2)->where($field3,$operator3,$value3)->where('com_code','=',$com_code)->orderby('id','DESC')->paginate(PC);
 if(!empty($data)){
    foreach($data as $info){
      $info->emp_name=get_field_value(new Employee(),"emp_name",array("com_code"=>$com_code,"employees_code"=>$info->employees_code));
    }
}
return view('admin.Main_salary_employees_P_loans.ajax_search',['data'=>$data]);
}
}

public function print_search(Request $request)
{

$com_code = auth('admin')->user()->com_code;  
$employees_code = $request->employees_code_search;
$is_archived = $request->is_archived_search;
$is_dismissail_done=$request->is_dismissail_done_search;
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
if ($is_dismissail_done == 'all') {
//هنا نعمل شرط دائم التحقق
$field2 = "id";
$operator2 = ">";
$value2 = 0;
} else {
$field2 = "is_dismissail_done";
$operator2 = "=";
$value2 = $is_dismissail_done;
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
$other['total_sum']=0;
$data=Main_salary_employees_P_loans::select("*")->where($field1,$operator1,$value1)->where($field2,$operator2,$value2)->where($field3,$operator3,$value3)->where('com_code','=',$com_code)->orderby('id','DESC')->get('');
if(!empty($data)){
    foreach($data as $info){
      $info->emp_name=get_field_value(new Employee(),"emp_name",array("com_code"=>$com_code,"employees_code"=>$info->employees_code));
      $other['total_sum']+=$info->total;
    }
  }
$systemData=get_cols_where_row(new Admin_panel_setting(),array("image","phones","address","company_name"),array("com_code"=>$com_code));  
return view('admin.Main_salary_employees_P_loans.print_search',['data'=>$data,'systemData'=>$systemData,'other'=>$other]);
}

public function do_dismissal_done_now($id)
{
    try{
    $com_code = auth('admin')->user()->com_code;  
    $dataParentloan=get_cols_where_row(new Main_salary_employees_P_loans(),array("id"),array("com_code"=>$com_code,"id"=>$id));
    if(empty($dataParentloan)){
      return view('admin.Main_salary_employees_P_loans.index')->with(['error'=>'عفوا غير قادر على الوصل الى البيانات']);
    }
    if($dataParentloan['is_dismissail_done']==1){
      return view('admin.Main_salary_employees_P_loans.index')->with(['error'=>'عفوا تم صرف السلفه من قبل']);
    }
     if($dataParentloan['is_archived']==1){
      return view('admin.Main_salary_employees_P_loans.index')->with(['error'=>'عفوا لايمكن حذف سلفه تم ارشفتها بالفعل']);
    }
   DB::beginTransaction(); 
   $dataToInsert['is_dismissail_done']= 1;
   $dataToInsert['dismissail_by'] = auth('admin')->user()->id;
   $dataToInsert['dismissail_at'] = date("Y-m-d H:i:s");
   $dataToInsert['com_code']= $com_code;
    $flagParent = update(
                        new Main_salary_employees_P_loans(),
                        $dataToInsert,
                        ["com_code" => $com_code, "id" => $id, 'is_archived' => 0, 'is_dismissail_done' => 0]
                    );
        if($flagParent){
            $dataToupdatekast['is_parent_dismissail_done']=1;
            update(new Main_salary_P_loans_akast(),$dataToupdatekast,array("com_code"=>$com_code,'main_salary_p_loans_id'=>$id));
            $mainSalaryEmployee=get_cols_where_row(new Main_salary_employee(),array("id"),array("com_code"=>$com_code,"employees_code"=>$dataParentloan['employees_code'],"is_archived"=>0));
            if(!empty($mainSalaryEmployee)){
              $this->Recalculate_main_salary_employee($mainSalaryEmployee['id']);   
            }
        }            
     DB::commit();
      return redirect()->route('Main_salary_employees_P_loans.index')->with(['success'=>' تم صرف السلفه  بنجاح']);
     } catch (\Exception $ex) {
        DB::rollBack();
        return redirect()->back()->with(['error' => 'عفوا حدث خطأ ' . $ex->getMessage()])->withInput();
     }
}

public function DoCachpayNow(Request $request)
{
 $com_code = auth('admin')->user()->com_code;  
if ($request->ajax()) {
    $dataParentloan=get_cols_where_row(new Main_salary_employees_P_loans(),array("*"),array("com_code"=>$com_code,"id"=>$request->idparent,"is_archived"=>0,"is_dismissail_done"=>1));
  if(!empty($dataParentloan)){
    $dataKast=get_cols_where_row(new Main_salary_P_loans_akast(),array("*"),array("com_code"=>$com_code,"id"=>$request->id,"is_archived"=>0,"state"=>0));
   if(!empty($dataKast)){

      $counterBeforeNotPaid=Main_salary_P_loans_akast::where("com_code","=",$com_code)->
            where("main_salary_p_loans_id","=","$request->idparent")->
            where("state","=",0)->
            where("id","<",$request->id)->count();
         if( $counterBeforeNotPaid==0){
        $dataToUpdateAksatCach['state']=2;
        $dataToUpdateAksatCach['archived_by'] = auth('admin')->user()->id;
        $dataToUpdateAksatCach['is_archived'] =1;
        $dataToUpdateAksatCach['archived_at'] =date("Y-m-d H:i:s");
         $dataToUpdateAksatCach['updated_by'] = auth('admin')->user()->id;
         $flag=update(new Main_salary_P_loans_akast(),$dataToUpdateAksatCach,array("com_code"=>$com_code,"id"=>$request->id,"is_archived"=>0,"state"=>0));
         if($flag){
            return json_encode("done");
         }
         }   
            
   }
    }
   
}
}


}
