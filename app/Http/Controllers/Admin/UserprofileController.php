<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Http\Requests\UserprofileRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserprofileController extends Controller
{
    public function index(){
        $data=get_cols_where_row(new Admin(),array("*"),array("com_code"=>auth("admin")->user()->com_code,"id"=>auth("admin")->user()->id));
        return view("admin.userprofile.index",['data'=>$data]);

    }

     public function edit(){
        $data=get_cols_where_row(new Admin(),array("*"),array("com_code"=>auth("admin")->user()->com_code,"id"=>auth("admin")->user()->id));
        return view("admin.userprofile.edit",['data'=>$data]);

    }

   public function update(UserprofileRequest $request)
{
    $com_code = auth("admin")->user()->com_code;
    $admin_id = auth("admin")->user()->id;

    // جلب بيانات المستخدم الحالي
    $data = get_cols_where_row(
        new Admin(),
        ["*"],
        ["com_code" => $com_code, "id" => $admin_id]
    );

    if (empty($data)) {
        return redirect()->route('userprofile.index')
            ->with(['error' => 'عفواً غير قادر على الوصول للبيانات المطلوبة']);
    }

    // تحقق من البريد واسم المستخدم إذا موجودين عند مستخدم آخر
    $emailExists = DB::table('admins')
        ->where('com_code', $com_code)
        ->where('email', $request->email)
        ->where('id', '!=', $admin_id)
        ->exists();

    if ($emailExists) {
        return redirect()->back()->withInput()->with(['error' => 'البريد الإلكتروني مستخدم بالفعل']);
    }

    $usernameExists = DB::table('admins')
        ->where('com_code', $com_code)
        ->where('username', $request->username)
        ->where('id', '!=', $admin_id)
        ->exists();

    if ($usernameExists) {
        return redirect()->back()->withInput()->with(['error' => 'اسم المستخدم مستخدم بالفعل']);
    }

    // التحقق من كلمة المرور إذا اختار المستخدم نعم
    if ($request->checkForupdatePassword == 1 && empty($request->password)) {
        return redirect()->back()->withInput()->with(['error' => 'من فضلك أدخل كلمة المرور الجديدة']);
    }

    $data_to_update['username']=$request->email;
    $data_to_update['email']=$request->email;
    $data_to_update['updated_at']=date("Y-m-d H:i:s");
    // تحديث كلمة المرور إذا اختار المستخدم نعم
    if ($request->checkForupdatePassword == 1 && !empty($request->password)) {
        $data_to_update['password'] = bcrypt($request->password);
    }
   
    if ($request->has('image_edit')) {
    $request->validate([
    'image_edit' => 'required|mimes:png,jpg,jpeg,doc,docx,pdf|max:2000',
    ]);
    $the_file_path = uploadImage('assets/admin/uploads', $request->image_edit);
    $data_to_update['image'] = $the_file_path;
    }
    if(file_exists('assets/admin/uploads/' .$data['image']) and !empty($data['image'])){
        unlink('assets/admin/uploads/' .$data['image']);
    }


   $data_to_update['updated_by']=auth("admin")->user()->id;
    // تنفيذ التحديث
    $update = DB::table('admins')
        ->where('com_code', $com_code)
        ->where('id', $admin_id)
        ->update($data_to_update);

    if ($update) {
        return redirect()->route('userprofile.index')
            ->with(['success' => 'تم تحديث بيانات البروفيل بنجاح']);
    } else {
        return redirect()->back()
            ->with(['error' => 'لم يتم إجراء أي تغييرات']);
    }
}



    
}
