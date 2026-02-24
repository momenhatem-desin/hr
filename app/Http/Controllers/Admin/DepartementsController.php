<?php

namespace App\Http\Controllers\Admin;

use App\Models\Departement;
use App\Models\Employee;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\DepartementsRequest;
use Illuminate\Support\Facades\DB;

class DepartementsController extends Controller
{
    public function index()
    {
         if(auth('admin')->user()->is_master_admin==0){
        check_permission_sub_menue_actions_redirect(12);
        } 
        $com_code = auth('admin')->user()->com_code;
        $data = get_cols_where_p(new Departement(), array("*"), array('com_code' => $com_code), 'id', 'DESC', PC);
        if(!empty($data)){
             foreach($data as $info){
           $info->counterUsed=get_count_where(new Employee(),array("com_code"=>$com_code,"emp_Departments_code"=>$info->id) );
             }
}
        return view('admin.Departements.index', ['data' => $data]);
    }

    public function create()
    {
           if(auth('admin')->user()->is_master_admin==0){
        check_permission_sub_menue_actions_redirect(105);
        } 
        return view('admin.Departements.create');
    }

    public function store(DepartementsRequest $request)
    {
        try {
               if(auth('admin')->user()->is_master_admin==0){
        check_permission_sub_menue_actions_redirect(105);
        } 
            $com_code = auth('admin')->user()->com_code;
            $CheckExsists = get_cols_where_row(new Departement(), array('id'), array("com_code" => $com_code, 'name' => $request->name));
            if (!empty($CheckExsists)) {
                return redirect()->back()->with(['error' => 'عفوا اسم الادارة مسجل من قبل !'])->withInput();
            }
            DB::beginTransaction();
            $dataToinsert['name'] = $request->name;
            $dataToinsert['phones'] = $request->phones;
            $dataToinsert['notes'] = $request->notes;
            $dataToinsert['active'] = $request->active;
            $dataToinsert['added_by'] = auth('admin')->user()->id;
            $dataToinsert['com_code'] = $com_code;
            insert(new Departement(), $dataToinsert);
            DB::commit();
            return  redirect()->route('departements.index')->with(['success' => 'تم ادخال البيانات بنجاح']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفوا حدث خطأ ما ' . $ex->getMessage()])->withInput();
        }
    }

    public function edit($id)
    {
           if(auth('admin')->user()->is_master_admin==0){
        check_permission_sub_menue_actions_redirect(14);
        } 

        $com_code = auth('admin')->user()->com_code;
        $data = get_cols_where_row(new Departement(), array("*"), array('com_code' => $com_code, 'id' => $id));
        if (empty($data)) {
            return redirect()->route('departements.index')->with(['error' => 'عفوا غير قادر الي الوصول البي البيانات المطلوبة !']);
        }
        return view('admin.Departements.edit', ['data' => $data]);
    }
    public function update($id, DepartementsRequest $request)
    {
        try {
               if(auth('admin')->user()->is_master_admin==0){
        check_permission_sub_menue_actions_redirect(14);
        } 
            $com_code = auth('admin')->user()->com_code;
            $data = get_cols_where_row(new Departement(), array("*"), array('com_code' => $com_code, 'id' => $id));
            if (empty($data)) {
                return redirect()->route('departements.index')->with(['error' => 'عفوا غير قادر الي الوصول البي البيانات المطلوبة !']);
            }
            $CheckExsists = Departement::select("id")->where('com_code', '=', $com_code)->where('name', '=', $request->name)->where('id', '!=', $id)->first();
            if (!empty($CheckExsists)) {
                return redirect()->back()->with(['error' => 'عفوا اسم الادارة مسجل من قبل !'])->withInput();
            }
            DB::beginTransaction();
            $dataToUpdate['name'] = $request->name;
            $dataToUpdate['phones'] = $request->phones;
            $dataToUpdate['notes'] = $request->notes;
            $dataToUpdate['active'] = $request->active;
            $dataToUpdate['updated_by'] = auth('admin')->user()->id;
            update(new Departement(), $dataToUpdate, array('com_code' => $com_code, 'id' => $id));
            DB::commit();
            return redirect()->route('departements.index')->with(['success' => 'تم تحديث البيانات بنجاح']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفوا حدث خطأ ما ' . $ex->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        try {
        if(auth('admin')->user()->is_master_admin==0){
        check_permission_sub_menue_actions_redirect(13);
        } 
            $com_code = auth('admin')->user()->com_code;
            $data = get_cols_where_row(new Departement(), array("*"), array('com_code' => $com_code, 'id' => $id));
            if (empty($data)) {
                return redirect()->route('departements.index')->with(['error' => 'عفوا غير قادر الي الوصول البي البيانات المطلوبة !']);
            }
            $counterUsed=get_count_where(new Employee(),array("com_code"=>$com_code,"emp_Departments_code"=>$id) );
            if ($counterUsed>0) {
            return redirect()->route('departements.index')->with(['error' => 'عفوا لايمكن الحذف لانه مستخدم  من قبل فى النظام  ']);
            }

            DB::beginTransaction();
            destroy(new Departement(), array('com_code' => $com_code, 'id' => $id));
            DB::commit();
            return redirect()->route('departements.index')->with(['success' => 'تم حذف البيانات بنجاح']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفوا حدث خطأ ما ' . $ex->getMessage()])->withInput();
        }
    }
}
