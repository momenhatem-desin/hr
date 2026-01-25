<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Shifts_type;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Requests\ShiftTypesRequest;
use Illuminate\Support\Facades\DB;
class ShiftsTypesController extends Controller
{
public function index()
{
  if(auth('admin')->user()->is_master_admin==0){
        check_permission_sub_menue_actions_redirect(9);
        }    
$com_code = auth('admin')->user()->com_code;
$data = get_cols_where_p(new Shifts_type(), array("*"), array("com_code" => $com_code), 'id', 'DESC', PC);
if(!empty($data)){
    foreach($data as $info){
        $info->counterUsed=get_count_where(new Employee(),array("com_code"=>$com_code,"shift_type_id"=>$info->id) );
    }
}

return view('admin.ShiftsTypes.index', ['data' => $data]);
}
public function create()
{
  if(auth('admin')->user()->is_master_admin==0){
        check_permission_sub_menue_actions_redirect(104);
        }   
return view('admin.ShiftsTypes.create');
}
public function store(ShiftTypesRequest $request)
{
try {
  if(auth('admin')->user()->is_master_admin==0){
        check_permission_sub_menue_actions_redirect(104);
        }     
$dataToInsert['com_code'] = auth('admin')->user()->com_code;
$dataToInsert['type'] = $request->type;
$dataToInsert['from_time'] = $request->from_time;
$dataToInsert['to_time'] = $request->to_time;
$dataToInsert['total_hour'] = $request->total_hour;
$checkExsitsData = get_cols_where_row(new Shifts_type(), array("id"), $dataToInsert);
if (!empty($checkExsitsData)) {
return redirect()->back()->with(['error' => 'عفوا هذه البيانات مسجلة من قبل !'])->withInput();
}
$dataToInsert['active'] = $request->active;
$dataToInsert['added_by'] = auth('admin')->user()->id;
DB::beginTransaction();
insert(new Shifts_type(), $dataToInsert);
DB::commit();
return redirect()->route('ShiftsTypes.index')->with(['success' => 'تم تسجيل البيانات بنجاح']);
} catch (\Exception $ex) {
DB::rollBack();
return redirect()->back()->with(['error' => 'عفوا حدث خطأ ' . $ex->getMessage()])->withInput();
}
}
public function edit($id)
{
  if(auth('admin')->user()->is_master_admin==0){
        check_permission_sub_menue_actions_redirect(10);
        }     
$com_code = auth('admin')->user()->com_code;
$data = get_cols_where_row(new Shifts_type(), array("*"), array("com_code" => $com_code, 'id' => $id));
if (empty($data)) {
return redirect()->route('ShiftsTypes.index')->with(['error' => 'عفوا غير قادر الي الوصول للبيانات المطلوبة']);
}
return view('admin.ShiftsTypes.edit', ['data' => $data]);
}
public function update($id, ShiftTypesRequest $request)
{
try {
    if(auth('admin')->user()->is_master_admin==0){
        check_permission_sub_menue_actions_redirect(10);
        }   

$com_code = auth('admin')->user()->com_code;
$data = get_cols_where_row(new Shifts_type(), array("*"), array("com_code" => $com_code, 'id' => $id));
if (empty($data)) {
return redirect()->route('ShiftsTypes.index')->with(['error' => 'عفوا غير قادر الي الوصول للبيانات المطلوبة']);
}
$checkExsitsData = Shifts_type::select("id")->where('type', '=', $request->type)->where('from_time', '=', $request->from_time)->where('to_time', '=', $request->to_time)->where('total_hour', '=', $request->total_hour)->where('id', '!=', $id)->first();
if (!empty($checkExsitsData)) {
return redirect()->back()->with(['error' => 'عفوا هذه البيانات مسجلة من قبل !'])->withInput();
}
DB::beginTransaction();
$dataToUpdate['type'] = $request->type;
$dataToUpdate['from_time'] = $request->from_time;
$dataToUpdate['to_time'] = $request->to_time;
$dataToUpdate['total_hour'] = $request->total_hour;
$dataToUpdate['active'] = $request->active;
$dataToUpdate['updated_by'] = auth('admin')->user()->id;
$flag=update(new Shifts_type(), $dataToUpdate, array("com_code" => $com_code, 'id' => $id));
if($flag){
    $dataToUpdateEmployees['daily_work_hour']=$dataToUpdate['total_hour'];
    update(new Employee(), $dataToUpdateEmployees, array("com_code" => $com_code,'is_has_fixced_shift' =>1,'shift_type_id'=>$id));

}
DB::commit();
return redirect()->route('ShiftsTypes.index')->with(['success' => 'تم تحديث البيانات بنجاح']);
} catch (\Exception $ex) {
DB::rollBack();
return redirect()->back()->with(['error' => 'عفوا حدث خطأ ' . $ex->getMessage()])->withInput();
}
}
public function destroy($id)
{
try {
    if(auth('admin')->user()->is_master_admin==0){
        check_permission_sub_menue_actions_redirect(11);
        }   
$com_code = auth('admin')->user()->com_code;
$data = get_cols_where_row(new Shifts_type(), array("id"), array("com_code" => $com_code, 'id' => $id));
if (empty($data)) {
return redirect()->route('ShiftsTypes.index')->with(['error' => 'عفوا غير قادر الي الوصول للبيانات المطلوبة']);
}
$counterUsed=get_count_where(new Employee(),array("com_code"=>$com_code,"shift_type_id"=>$id) );
if ($counterUsed>0) {
return redirect()->route('ShiftsTypes.index')->with(['error' => 'عفوا لايمكن الحذف لانه مستخدم  من قبل فى النظام  ']);
}

DB::beginTransaction();
destroy(new Shifts_type(), array("com_code" => $com_code, 'id' => $id));
DB::commit();
return redirect()->route('ShiftsTypes.index')->with(['success' => 'تم حذف البيانات بنجاح']);
} catch (\Exception $ex) {
DB::rollBack();
return redirect()->back()->with(['error' => 'عفوا حدث خطأ ' . $ex->getMessage()])->withInput();
}
}
public function ajax_search(Request $request)
{
if ($request->ajax()) {
$com_code = auth('admin')->user()->com_code;    
$type_search = $request->type_search;
$hour_from_range = $request->hour_from_range;
$hour_to_range = $request->hour_to_range;
if ($type_search == 'all') {
//هنا نعمل شرط دائم التحقق
$field1 = "id";
$operator1 = ">";
$value1 = 0;
} else {
$field1 = "type";
$operator1 = "=";
$value1 = $type_search;
}
if ($hour_from_range == '') {
//هنا نعمل شرط دائم التحقق
$field2 = "id";
$operator2 = ">";
$value2 = 0;
} else {
$field2 = "total_hour";
$operator2 = ">=";
$value2 = $hour_from_range;
}
if ($hour_to_range == '') {
//هنا نعمل شرط دائم التحقق
$field3 = "id";
$operator3 = ">";
$value3 = 0;
} else {
$field3 = "total_hour";
$operator3 = "<=";
$value3 = $hour_to_range;
}
$data=Shifts_type::select("*")->where($field1,$operator1,$value1)->where($field2,$operator2,$value2)->where($field3,$operator3,$value3)->orderby('id','DESC')->paginate(PC);
if(!empty($data)){
    foreach($data as $info){
        $info->counterUsed=get_count_where(new Employee(),array("com_code"=>$com_code,"shift_type_id"=>$info->id) );
    }
}
return view('admin.ShiftsTypes.ajax_search',['data'=>$data]);
}
}
}