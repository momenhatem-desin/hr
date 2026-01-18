<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Grants;
use App\Http\Requests\grantsRequest;

class GrantsController extends Controller
{
     public function index()
    {
        $com_code = auth('admin')->user()->com_code;
        $data = get_cols_where_p(new Grants(), array("*"), array("com_code" => $com_code), 'id', 'DESC', PC);
        return view('admin.grants.index', ['data' => $data]);
    }

    public function create()
    {
        $com_code = auth('admin')->user()->com_code;
        return view('admin.grants.create');
    }

    public function store(grantsRequest $request)
    {
        try {

            $com_code = auth('admin')->user()->com_code;
            $CheckExsists = get_cols_where_row(new Grants(), array("id"), array("com_code" => $com_code, 'name' => $request->name));
            if (!empty($CheckExsists)) {
                return redirect()->back()->with(['error' => 'عفوا هذا الاسم مسجل من قبل '])->withInput();
            }
            DB::beginTransaction();
            $DataToInsert['name'] = $request->name;
            $DataToInsert['active'] = $request->active;
            $DataToInsert['added_by'] = auth('admin')->user()->id;
            $DataToInsert['com_code'] = $com_code;
            insert(new Grants(), $DataToInsert);
            DB::commit();
            return redirect()->route('grants.index')->with(['success' => 'تم تسجيل البيانات بنجاح']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفوا  حدث خطأ  ' . $ex->getMessage()])->withInput();
        }
    }

    public function edit($id)
    {

        $com_code = auth('admin')->user()->com_code;
        $data = get_cols_where_row(new Grants(), array("*"), array("com_code" => $com_code, 'id' => $id));
        if (empty($data)) {
            return redirect()->route('grants.index')->with(['error' => 'عفوا غير قادر للوصول الي البيانات المطلوبة']);
        }
        return view('admin.grants.edit', ['data' => $data]);
    }

    public function update($id,grantsRequest $request)
    {
        try {
            $com_code = auth('admin')->user()->com_code;
            $data = get_cols_where_row(new Grants(), array("*"), array("com_code" => $com_code, 'id' => $id));
            if (empty($data)) {
                return redirect()->route('grants.index')->with(['error' => 'عفوا غير قادر للوصول الي البيانات المطلوبة']);
            }
            $CheckExsists = Grants::select("id")->where("com_code", "=", $com_code)->where("name", "=", $request->name)->where('id', '!=', $id)->first();
            if (!empty($CheckExsists)) {
                return redirect()->back()->with(['error' => 'عفوا هذا الاسم مسجل من قبل '])->withInput();
            }
            DB::beginTransaction();
            $DataToUpdate['name'] = $request->name;
            $DataToUpdate['active'] = $request->active;
            $DataToUpdate['updated_by'] = auth('admin')->user()->id;
            update(new Grants(), $DataToUpdate, array("com_code" => $com_code, 'id' => $id));
            DB::commit();
            return redirect()->route('grants.index')->with(['success' => 'تم تحديث البيانات بنجاح']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفوا حدث خطأ  ' . $ex->getMessage()])->withInput();
        }
    }
    public function destroy($id)
    {
        try {
            $com_code = auth('admin')->user()->com_code;
            $data = get_cols_where_row(new Grants(), array("*"), array("com_code" => $com_code, 'id' => $id));
            if (empty($data)) {
                return redirect()->route('grants.index')->with(['error' => 'عفوا غير قادر للوصول الي البيانات المطلوبة']);
            }
            DB::beginTransaction();
            //مستقبل من عدم استخدمها
            destroy(new Grants(), array("com_code" => $com_code, 'id' => $id));
            DB::commit();
            return redirect()->route('grants.index')->with(['success' => 'تم الحذف  البيانات بنجاح']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'عفوا حدث خطأ  ' . $ex->getMessage()])->withInput();
        }
}
}
