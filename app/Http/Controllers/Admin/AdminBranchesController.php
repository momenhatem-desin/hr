<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Branche;
use App\Models\Admin_branches;


class AdminBranchesController extends Controller
{
    // عرض الفروع للمستخدم مع تحديد الفروع المرتبطة
    public function edit($id)
    {
         $com_code = auth("admin")->user()->com_code;
        $admin = Admin::with('branches')->findOrFail($id);
        $branches = Branche::where('active', 1)->where('com_code','=',$com_code)->get();

        return view('admin.admins_accounts.branches', compact('admin', 'branches'));
    }

    // تحديث الفروع المرتبطة بالمستخدم
    public function update(Request $request, $id)
{
    $com_code = auth("admin")->user()->com_code;

    // تأكد إن فيه فروع جاية
    $branches = $request->branches ?? [];

    // حذف كل الفروع القديمة للمستخدم
    Admin_branches::where('admin_id', $id)
        ->where('com_code', $com_code)
        ->delete();

    // إضافة الفروع الجديدة
    foreach ($branches as $branch_id) {
        Admin_branches::create([
            'admin_id' => $id,
            'branch_id' => $branch_id,
            'com_code' => $com_code,
        ]);
    }

    return redirect()
        ->route('admin.admins_accounts.branches_edit', $id)
        ->with('success', 'تم تحديث الفروع بنجاح.');
}

}
