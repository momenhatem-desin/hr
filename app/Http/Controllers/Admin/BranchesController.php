<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Branche;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Requests\BrachesRequest;
use App\Models\Admin_panel_setting;
use App\Models\Alerts_system_monitoring;
use Illuminate\Support\Facades\DB;
class BranchesController extends Controller
{
public function index()
{
   if(auth('admin')->user()->is_master_admin==0){
        check_permission_sub_menue_actions_redirect(6);
        }  
$com_code = auth('admin')->user()->com_code;
$data = get_cols_where_p(new Branche(), array("*"), array("com_code" => $com_code), "id", "DESC", PC);
if(!empty($data)){
    foreach($data as $info){
        $info->counterUsed=get_count_where(new Employee(),array("com_code"=>$com_code,"branch_id"=>$info->id) );
    }
}
return view('admin.Branches.index', ['data' => $data]);
}

public function create()
{
  if(auth('admin')->user()->is_master_admin==0){
        check_permission_sub_menue_actions_redirect(103);
        }    
return view('admin.Branches.create');
}
public function store(BrachesRequest $requst)
{
try {
     if(auth('admin')->user()->is_master_admin==0){
        check_permission_sub_menue_actions_redirect(103);
        } 
$com_code = auth('admin')->user()->com_code;
$checkExsists = get_cols_where_row(new Branche(), array("id"), array("com_code" => $com_code, 'name' => $requst->name));
if (!empty($checkExsists)) {
return redirect()->back()->with(['error' => 'عفوا اسم الفرع مسجل من قبل !'])->withInput();
}
DB::beginTransaction();
$dataToInsert['name'] = $requst->name;
$dataToInsert['address'] = $requst->address;
$dataToInsert['phones'] = $requst->phones;
$dataToInsert['email'] = $requst->email;
$dataToInsert['active'] = $requst->active;
$dataToInsert['added_by'] = auth('admin')->user()->id;
$dataToInsert['com_code'] = $com_code;
$flag=insert(new Branche(), $dataToInsert,true);
if($flag){
    $is_active_alerts_system_monitoring=get_field_value(new Admin_panel_setting(),"is_active_alerts_system_monitoring",array("com_code"=>auth('admin')->user()->com_code));
    if($is_active_alerts_system_monitoring==1){
        /* start alerts system monitoring */
        $data_monitoring_insert['alert_modules_id']=1;
        $data_monitoring_insert['alert_movetype_id']=6;
        $data_monitoring_insert['content']="اضافه مخزن جديد باسم ".$requst->name;
        $data_monitoring_insert['foreign_key_table_id']=$flag['id'];
        $data_monitoring_insert['added_by']=auth('admin')->user()->id;
        $data_monitoring_insert['com_code']=auth('admin')->user()->com_code;
        $data_monitoring_insert['date']=date("Y-m-d");
        insert(new Alerts_system_monitoring(), $data_monitoring_insert,array("com_code"=>auth('admin')->user()->com_code));
         /* end alerts system monitoring */
    }
}
DB::commit();
return redirect()->route('branches.index')->with(['success' => 'تم ادخال البيانات بنجاح']);
} catch (\Exception $ex) {
DB::rollBack();
return redirect()->back()->with(['error' => 'عفوا حدث خطأ ما ' . $ex->getMessage()])->withInput();
}
}
public function edit($id)
{
   if(auth('admin')->user()->is_master_admin==0){
        check_permission_sub_menue_actions_redirect(7);
        }   
$com_code = auth('admin')->user()->com_code;
$data = get_cols_where_row(new Branche(), array("*"), array("id" => $id, 'com_code' => $com_code));
if (empty($data)) {
return redirect()->route('branches.index')->with(['error' => 'عفوا غير قادر علي الوصول الي البيانات المطلوبة']);
}
return view('admin.Branches.edit', ['data' => $data]);
}
public function update($id,BrachesRequest $requst)
{
try{
     if(auth('admin')->user()->is_master_admin==0){
        check_permission_sub_menue_actions_redirect(7);
        } 
$com_code = auth('admin')->user()->com_code;
$data = get_cols_where_row(new Branche(), array("*"), array("id" => $id, 'com_code' => $com_code));
if (empty($data)) {
return redirect()->route('branches.index')->with(['error' => 'عفوا غير قادر علي الوصول الي البيانات المطلوبة']);
}
DB::beginTransaction();
$dataToUpdate['name'] = $requst->name;
$dataToUpdate['address'] = $requst->address;
$dataToUpdate['phones'] = $requst->phones;
$dataToUpdate['email'] = $requst->email;
$dataToUpdate['active'] = $requst->active;
$dataToUpdate['updated_by'] = auth('admin')->user()->id;
$flag=update(new Branche(),$dataToUpdate,array("id" => $id, 'com_code' => $com_code));
if($flag){
    $is_active_alerts_system_monitoring=get_field_value(new Admin_panel_setting(),"is_active_alerts_system_monitoring",array("com_code"=>auth('admin')->user()->com_code));
    if($is_active_alerts_system_monitoring==1){
        /* start alerts system monitoring */
        $data_monitoring_insert['alert_modules_id']=1;
        $data_monitoring_insert['alert_movetype_id']=7;
        if($data['name']!=$dataToUpdate['name']){
            $updatelable="تم تغير الاسم من ".$data['name']. " "."الى"." ".$dataToUpdate['name'];
        }

        $data_monitoring_insert['content']="تعديل الفرع باسم ".$data['name']." ".$updatelable;
        $data_monitoring_insert['foreign_key_table_id']=$id;
        $data_monitoring_insert['added_by']=auth('admin')->user()->id;
        $data_monitoring_insert['com_code']=auth('admin')->user()->com_code;
        $data_monitoring_insert['date']=date("Y-m-d");
        insert(new Alerts_system_monitoring(), $data_monitoring_insert,array("com_code"=>auth('admin')->user()->com_code));
         /* end alerts system monitoring */
    }
}
DB::commit();
return redirect()->route('branches.index')->with(['success' => 'تم تحديث البيانات بنجاح']);
}catch(\Exception $ex){
DB::rollBack();
return redirect()->back()->with(['error'=>'عفوا حدث خطا  '.$ex->getMessage()])->withInput();
}
} 
public function destroy($id){
try{
     if(auth('admin')->user()->is_master_admin==0){
        check_permission_sub_menue_actions_redirect(8);
        } 
$com_code = auth('admin')->user()->com_code;
$data = get_cols_where_row(new Branche(), array("*"), array("id" => $id, 'com_code' => $com_code));
if (empty($data)) {
return redirect()->route('branches.index')->with(['error' => 'عفوا غير قادر علي الوصول الي البيانات المطلوبة']);
}
$counterUsed=get_count_where(new Employee(),array("com_code"=>$com_code,"branch_id"=>$id) );
if ($counterUsed>0) {
return redirect()->route('branches.index')->with(['error' => 'عفوا لايمكن حذف البيانات لانه تم استخدمها من قبل فى النظام ']);
}
DB::beginTransaction();
$flag=destroy(new Branche(),array("id" => $id, 'com_code' => $com_code));
if($flag){
    $is_active_alerts_system_monitoring=get_field_value(new Admin_panel_setting(),"is_active_alerts_system_monitoring",array("com_code"=>auth('admin')->user()->com_code));
    if($is_active_alerts_system_monitoring==1){
        /* start alerts system monitoring */
        $data_monitoring_insert['alert_modules_id']=1;
        $data_monitoring_insert['alert_movetype_id']=8;
        $data_monitoring_insert['content']="حذف مخزن باسم ".$data['name'];
        $data_monitoring_insert['foreign_key_table_id']=$id;
        $data_monitoring_insert['added_by']=auth('admin')->user()->id;
        $data_monitoring_insert['com_code']=auth('admin')->user()->com_code;
        $data_monitoring_insert['date']=date("Y-m-d");
        insert(new Alerts_system_monitoring(), $data_monitoring_insert,array("com_code"=>auth('admin')->user()->com_code));
         /* end alerts system monitoring */
    }
}
DB::commit();
return redirect()->route('branches.index')->with(['success' => 'تم حذف البيانات بنجاح']);
}catch(\Exception $ex){
DB::rollBack();
return redirect()->route('branches.index')->with(['error'=>'عفوا حدث خطا  '.$ex->getMessage()]);
}
}   
}