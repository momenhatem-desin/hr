<?php

namespace App\Http\Controllers\Admin;

use App\Models\jobs_categorie;
use App\Models\Employee;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Jobs_categoriesRequest;
use Illuminate\Support\Facades\DB;

class Jobs_categoriesController extends Controller
{
  public function index()
  {
       if(auth('admin')->user()->is_master_admin==0){
        check_permission_sub_menue_actions_redirect(16);
        } 
    $com_code = auth('admin')->user()->com_code;
    $data = get_cols_where_p(new jobs_categorie(), array("*"), array('com_code' => $com_code), 'id', 'DESC', PC);
    if(!empty($data)){
    foreach($data as $info){
        $info->counterUsed=get_count_where(new Employee(),array("com_code"=>$com_code,"emp_jobs_id"=>$info->id) );
    }
}
    return view('admin.Jobs_categories.index', ['data' => $data]);
  }

  public function create()
  {
       if(auth('admin')->user()->is_master_admin==0){
        check_permission_sub_menue_actions_redirect(18);
        } 
    return view('admin.Jobs_categories.create');
  }

  public function store(Jobs_categoriesRequest $request)
  {
    try {
         if(auth('admin')->user()->is_master_admin==0){
        check_permission_sub_menue_actions_redirect(18);
        } 
      $com_code = auth('admin')->user()->com_code;
      $CheckExsists = get_cols_where_row(new jobs_categorie(), array("id"), array("name" => $request->name, 'com_code' => $com_code));
      if (!empty($CheckExsists)) {
        return redirect()->back()->with(['error' => 'عفوا  اسم الوظيفة مسجل من قبل ! '])->withInput();
      }
      DB::beginTransaction();
      $dataToInsert['name'] = $request->name;
      $dataToInsert['active'] = $request->active;
      $dataToInsert['added_by'] = auth('admin')->user()->id;
      $dataToInsert['com_code'] = $com_code;
      insert(new jobs_categorie(), $dataToInsert);
      DB::commit();
      return redirect()->route('jobs_categories.index')->with(['success' => 'تم اضافة البيانات بنجاح']);
    } catch (\Exception $ex) {
      DB::rollBack();
      return redirect()->back()->with(['error' => 'عفوا حدث خطأ ما ' . $ex->getMessage()])->withInput();
    }
  }

  public function edit($id)
  {
       if(auth('admin')->user()->is_master_admin==0){
        check_permission_sub_menue_actions_redirect(15);
        } 
    $com_code = auth('admin')->user()->com_code;
    $data = get_cols_where_row(new jobs_categorie(), array("*"), array("com_code" => $com_code, 'id' => $id));
    if (empty($data)) {
      return redirect()->route('jobs_categories.index')->with(['error' => 'عفوا غير قادر الي الوصول الي البيانات المطلوبة']);
    }
    return view('admin.Jobs_categories.edit', ['data' => $data]);
  }

  public function update($id, Jobs_categoriesRequest $request)
  {
    $com_code = auth('admin')->user()->com_code;
    try {
         if(auth('admin')->user()->is_master_admin==0){
        check_permission_sub_menue_actions_redirect(15);
        } 
      $CheckExsists = jobs_categorie::select("id")->where("com_code", "=", $com_code)->where('name', '=', $request->name)->where('id', '!=', $id)->first();
      if (!empty($CheckExsists)) {
        return redirect()->back()->with(['error' => 'عفوا  اسم الوظيفة مسجل من قبل ! '])->withInput();
      }
      DB::beginTransaction();
      $dataToUpdate['name'] = $request->name;
      $dataToUpdate['active'] = $request->active;
      $dataToUpdate['updated_by'] = auth('admin')->user()->id;
      update(new jobs_categorie(), $dataToUpdate, array("com_code" => $com_code, 'id' => $id));
      DB::commit();
      return redirect()->route('jobs_categories.index')->with(['success' => 'تم تحديث البيانات بنجاح']);
    } catch (\Exception $ex) {
      DB::rollBack();
      return redirect()->back()->with(['error' => 'عفوا حدث خطا ' . $ex->getMessage()])->withInput();
    }
    $com_code = auth('admin')->user()->com_code;
    $data = get_cols_where_row(new jobs_categorie(), array("*"), array("com_code" => $com_code, 'id' => $id));
    if (empty($data)) {
      return redirect()->route('jobs_categories.index')->with(['error' => 'عفوا غير قادر الي الوصول الي البيانات المطلوبة']);
    }

  }

  public function destroy($id)
  {
    try{
         if(auth('admin')->user()->is_master_admin==0){
        check_permission_sub_menue_actions_redirect(17);
        } 
      $com_code = auth('admin')->user()->com_code;
      $data = get_cols_where_row(new jobs_categorie(), array("*"), array("com_code" => $com_code, 'id' => $id));
      if (empty($data)) {
        return redirect()->route('jobs_categories.index')->with(['error' => 'عفوا غير قادر الي الوصول الي البيانات المطلوبة']);
      }
 $counterUsed=get_count_where(new Employee(),array("com_code"=>$com_code,"emp_jobs_id"=>$id) );
    if ($counterUsed>0) {
    return redirect()->route('jobs_categories.index')->with(['error' => 'عفوا لايمكن الحذف لانه مستخدم  من قبل فى النظام  ']);
    }
      DB::beginTransaction();
      destroy(new jobs_categorie(),array("com_code" => $com_code, 'id' => $id));
      DB::commit();
  return redirect()->route('jobs_categories.index')->with(['success'=>'تم الحذف بنجاح']);
    }catch(\Exception $ex){
      DB::rollBack();
      return redirect()->route('jobs_categories.index')->with(['error' => 'عفوا حدث خطا ' . $ex->getMessage()]);
    }
 

  }


}
