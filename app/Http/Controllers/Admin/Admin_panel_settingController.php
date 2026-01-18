<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin_panel_setting;
use App\Http\Requests\Admin_panel_settingRequest;
use Illuminate\Http\Request;

class Admin_panel_settingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(auth()->user()->is_master_admin==0){
        check_permission_sub_menue_actions_redirect(1);
        }  
        $com_code = auth()->user()->com_code;
        $data = Admin_panel_setting::select('*')->where('com_code', $com_code)->first();
        return view('admin.Admin_panel_setting.index', ['data' => $data]);

    }

    public function edit()
    {
         if(auth()->user()->is_master_admin==0){
        check_permission_sub_menue_actions_redirect(2);
        }  
        $com_code = auth()->user()->com_code;
        $data = Admin_panel_setting::select('*')->where('com_code', $com_code)->first();
        return view('admin.Admin_panel_setting.edit', ['data' => $data]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Admin_panel_settingRequest $request)
    {
        try {
             if(auth()->user()->is_master_admin==0){
               check_permission_sub_menue_actions_redirect(2);
             } 
            $com_code = auth()->user()->com_code;
            $data = Admin_panel_setting::select('image')->where('com_code', $com_code)->first();
            $dataToUpdate['company_name'] = $request->company_name;
            $dataToUpdate['phones'] = $request->phones;
            $dataToUpdate['address'] = $request->address;
            $dataToUpdate['email'] = $request->email;
            $dataToUpdate['after_miniute_calculate_delay'] = $request->after_miniute_calculate_delay;
            $dataToUpdate['after_miniute_calculate_early_departure'] = $request->after_miniute_calculate_early_departure;
            $dataToUpdate['after_miniute_quarterday'] = $request->after_miniute_quarterday;
            $dataToUpdate['after_time_half_daycut'] = $request->after_time_half_daycut;
            $dataToUpdate['after_time_allday_daycut'] = $request->after_time_allday_daycut;
            $dataToUpdate['type_vacation'] = $request->type_vacation;
            $dataToUpdate['quinty_vacstion'] = $request->quinty_vacstion;
            $dataToUpdate['monthly_vacation_balance'] = $request->monthly_vacation_balance;
            $dataToUpdate['after_days_begin_vacation'] = $request->after_days_begin_vacation;
            $dataToUpdate['first_balance_begin_vacation'] = $request->first_balance_begin_vacation;
            $dataToUpdate['sanctions_value_first_abcence'] = $request->sanctions_value_first_abcence;
            $dataToUpdate['sanctions_value_second_abcence'] = $request->sanctions_value_second_abcence;
            $dataToUpdate['sanctions_value_thaird_abcence'] = $request->sanctions_value_thaird_abcence;
            $dataToUpdate['sanctions_value_forth_abcence'] = $request->sanctions_value_forth_abcence;
            $dataToUpdate['less_than_miniute_neglecting_passmaa'] =$request->less_than_miniute_neglecting_passmaa;
            $dataToUpdate['max_hours_take_pasma_as_addtional'] =$request->max_hours_take_pasma_as_addtional;
             $dataToUpdate['is_transfer_vaccation'] =$request->is_transfer_vaccation;
            $dataToUpdate['is_pull_anuall_day_from_passma'] =$request->is_pull_anuall_day_from_passma;
            $dataToUpdate['is_outo_offect_passmaV'] =$request->is_outo_offect_passmaV;
            $dataToUpdate['number_addinal_get'] =$request->number_addinal_get;
            $dataToUpdate['updated_by'] = auth()->user()->id;
    
            if ($request->has('image')) {
                $request->validate([
                'image' => 'required|mimes:png,jpg,jpeg|max:2000',
                ]);
                $the_file_path = uploadImage('assets/admin/uploads', $request->image);
                $dataToUpdate['image'] = $the_file_path;
                }
                if(file_exists('assets/admin/uploads' . $data['image']) and !empty($data['image'])) {
                    unlink('assets/admin/uploads' . $data['image']);
                }


            Admin_panel_setting::where(['com_code' => $com_code])->update($dataToUpdate);
            return redirect()->route('admin_panel_settings.index')->with(['success' => 'تم تحديث البيانات بنجاح']);
        } catch (\Exception $ex) {
            return redirect()->back()->with(['error' => 'عفوا حدث خطأ ما'])->withInput();
        }
    }
}
