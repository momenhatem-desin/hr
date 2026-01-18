<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Branche;
use App\Models\Departement;
use App\Models\jobs_categorie;
use App\Models\Qualification;
use App\Models\Religion;
use App\Models\Countries;
use App\Models\Nationalitie;
use App\Models\governorates;
use App\Models\centers;
use App\Models\blood_groups;
use App\Models\Military_status;
use App\Models\driving_license_type;
use App\Models\Language;
use App\Models\Shifts_type;
use App\Models\Employees_files;
use App\Models\Employees_fixed_suits;
use App\Models\Allowances;
use App\Http\Requests\EmployeeRequest;
use App\Http\Requests\EmployessUpdateRequest;
use App\Models\Admin_panel_setting;
use App\Models\Alerts_system_monitoring;
use App\Traits\GeneralTrait;
use App\Models\Main_salary_employee;
use App\Models\Employee_salary_archive;
use Illuminate\Support\Facades\DB;


class EmployeesController extends Controller
{
      use GeneralTrait;
    public function index()
    {
        $com_code = auth()->user()->com_code;
        $data = get_cols_where_p(new Employee(), array("*"), array("com_code" => $com_code), "id", "DESC", PC);
        $other['branches'] = get_cols_where(new Branche(), array("id", "name"), array("com_code" => $com_code, "active" => 1));
        $other['departements'] = get_cols_where(new Departement(), array("id", "name"), array("com_code" => $com_code, "active" => 1));
        $other['jobs'] = get_cols_where(new jobs_categorie(), array("id", "name"), array("com_code" => $com_code, "active" => 1));
        $other['qualifications'] = get_cols_where(new Qualification(), array("id", "name"), array("com_code" => $com_code, "active" => 1));
        $other['religions'] = get_cols_where(new Religion(), array("id", "name"), array("com_code" => $com_code, "active" => 1));
        $other['nationalities'] = get_cols_where(new Nationalitie(), array("id", "name"), array("com_code" => $com_code, "active" => 1));
        $other['countires'] = get_cols_where(new Countries(), array("id", "name"), array("com_code" => $com_code, "active" => 1));
        $other['governorates'] = get_cols_where(new governorates(), array("id", "name"), array("com_code" => $com_code, "active" => 1));
        $other['centers'] = get_cols_where(new centers(), array("id", "name"), array("com_code" => $com_code, "active" => 1));
        $other['blood_groups'] = get_cols_where(new blood_groups(), array("id", "name"), array("com_code" => $com_code, "active" => 1));
        $other['military_status'] = get_cols_where(new Military_status(), array("id", "name"), array("active" => 1),'id','ASC');
        $other['driving_license_types'] = get_cols_where(new driving_license_type(), array("id", "name"), array("active" => 1,"com_code" => $com_code),'id','ASC');
        $other['shifts_types'] = get_cols_where(new Shifts_type(), array("id", "type","from_time","to_time","total_hour"), array("active" => 1,"com_code" => $com_code),'id','ASC');
        $other['languages'] = get_cols_where(new Language(), array("id", "name"), array("active" => 1,"com_code" => $com_code),'id','ASC');
     
         if(!empty($data)){
          foreach($data as $info){
            $info->counterUserBefor=get_count_where(new Main_salary_employee(),array("com_code" => $com_code,"employees_code"=>$info->employees_code));}
         }
        
    
        return view("admin.Employees.index", ['data' => $data,'other'=>$other]);
    }
    public function create()
    {
        $com_code = auth('admin')->user()->com_code;
        $other['branches'] = get_cols_where(new Branche(), array("id", "name"), array("com_code" => $com_code, "active" => 1));
        $other['departements'] = get_cols_where(new Departement(), array("id", "name"), array("com_code" => $com_code, "active" => 1));
        $other['jobs'] = get_cols_where(new jobs_categorie(), array("id", "name"), array("com_code" => $com_code, "active" => 1));
        $other['qualifications'] = get_cols_where(new Qualification(), array("id", "name"), array("com_code" => $com_code, "active" => 1));
        $other['religions'] = get_cols_where(new Religion(), array("id", "name"), array("com_code" => $com_code, "active" => 1));
        $other['nationalities'] = get_cols_where(new Nationalitie(), array("id", "name"), array("com_code" => $com_code, "active" => 1));
        $other['countires'] = get_cols_where(new Countries(), array("id", "name"), array("com_code" => $com_code, "active" => 1));
        $other['governorates'] = get_cols_where(new governorates(), array("id", "name"), array("com_code" => $com_code, "active" => 1));
        $other['centers'] = get_cols_where(new centers(), array("id", "name"), array("com_code" => $com_code, "active" => 1));
        $other['blood_groups'] = get_cols_where(new blood_groups(), array("id", "name"), array("com_code" => $com_code, "active" => 1));
        $other['military_status'] = get_cols_where(new Military_status(), array("id", "name"), array("active" => 1),'id','ASC');
        $other['driving_license_types'] = get_cols_where(new driving_license_type(), array("id", "name"), array("active" => 1,"com_code" => $com_code),'id','ASC');
        $other['shifts_types'] = get_cols_where(new Shifts_type(), array("id", "type","from_time","to_time","total_hour"), array("active" => 1,"com_code" => $com_code),'id','ASC');
        $other['languages'] = get_cols_where(new Language(), array("id", "name"), array("active" => 1,"com_code" => $com_code),'id','ASC');

        return view("admin.Employees.create", ['other' => $other]);
    }

    public function store(EmployeeRequest $request){
     
 try {
    $com_code=auth('admin')->user()->com_code;
       $CheckExsists = get_cols_where_row(new Employee(), array("id"), array("emp_name" => $request->emp_name, 'com_code' => $com_code));
      if (!empty($CheckExsists)) {
        return redirect()->back()->with(['error' => 'عفوا  اسم الموظف مسجل من قبل ! '])->withInput();
    }

    $last_employee=get_cols_where_row_orderby(new Employee(),array("employees_code"),array('com_code'=>$com_code),'employees_code','DESC' );
     if(!empty($last_employee)){
        $dataToInsert['employees_code']=$last_employee['employees_code']+1;
     }else{  
        $dataToInsert['employees_code']=1;
     }
     $CheckExsists_zketo_code=Employee::select("id")->where('com_code','=',$com_code)->where('zketo_code','=',$request->zketo_code)->where('id','!=',$request->id)->first();
      if (!empty($CheckExsists_zketo_code)) {
        return redirect()->back()->with(['error' => 'عفوا  كود بصمة الموظف مسجل من قبل ! '])->withInput();
     }

   $dataToInsert['zketo_code']=$request->zketo_code;
   $dataToInsert['emp_name']=$request->emp_name;
   $dataToInsert['emp_gender']=$request->emp_gender;
   $dataToInsert['Qualifications_id']=$request->Qualifications_id;
   $dataToInsert['Qualifications_year']=$request->Qualifications_year;
   $dataToInsert['graduation_estimate']=$request->graduation_estimate;
   $dataToInsert['Graduation_specialization']=$request->Graduation_specialization;
   $dataToInsert['emp_national_idenity']=$request->emp_national_idenity;
   $dataToInsert['emp_end_identityIDate']=$request->emp_end_identityIDate;
   $dataToInsert['emp_identityPlace']=$request->emp_identityPlace;
   $dataToInsert['blood_group_id']=$request->blood_group_id;
   $dataToInsert['religion_id']=$request->religion_id;
   $dataToInsert['emp_lang_id']=$request->emp_lang_id;
   $dataToInsert['emp_email']=$request->emp_email;
   $dataToInsert['country_id']=$request->country_id;
   $dataToInsert['governorate_id']=$request->governorate_id ;
   $dataToInsert['city_id']=$request->city_id;
   $dataToInsert['emp_home_tel']=$request->emp_home_tel;
   $dataToInsert['emp_work_tel']=$request->emp_work_tel;
   $dataToInsert['emp_military_id']=$request->emp_military_id;
   $dataToInsert['emp_military_date_from']=$request->emp_military_date_from;
   $dataToInsert['emp_military_date_to']=$request->emp_military_date_to;
   $dataToInsert['emp_military_wepon']=$request->emp_military_wepon;
   $dataToInsert['exemption_date']=$request->exemption_date;
   $dataToInsert['exemption_reason']=$request->exemption_reason ;
   $dataToInsert['postponement_reason']=$request->postponement_reason;
   $dataToInsert['Date_resignation']=$request->Date_resignation;
   $dataToInsert['resignation_cause']=$request->resignation_cause;
   $dataToInsert['does_has_Driving_License']=$request->does_has_Driving_License;
   $dataToInsert['driving_License_degree']=$request->driving_License_degree;
   $dataToInsert['driving_license_types_id']=$request->driving_license_types_id;
   $dataToInsert['has_Relatives']=$request->has_Relatives;
   $dataToInsert['Relatives_details']=$request->Relatives_details;
   $dataToInsert['notes']=$request->notes;
   $dataToInsert['emp_start_date']=$request->emp_start_date;
   $dataToInsert['Functiona_status']=$request->Functiona_status;
   $dataToInsert['emp_Departments_code']=$request->emp_Departments_code ;
   $dataToInsert['emp_jobs_id']=$request->emp_jobs_id;
   $dataToInsert['does_has_ateendance']=$request->does_has_ateendance;
   $dataToInsert['is_has_fixced_shift']=$request->is_has_fixced_shift;
   $dataToInsert['shift_type_id']=$request->shift_type_id;
   if($request->is_has_fixced_shift==1){
    $shiftData=get_cols_where_row(new Shifts_type(),"total_hour",array("com_code"=>$com_code,"id"=>$request->shift_type_id));
    if(!empty($shiftData)){
     $dataToInsert['daily_work_hour']=$shiftData['total_hour']; 
    }else{
     return redirect()->back()->with(['error' => 'عفوا  غير قادر الوصول للبيانات الشفت المحدد للموظف ! ']);  
    }
   }else{
 $dataToInsert['daily_work_hour']=$request->daily_work_hour;
   }
   $dataToInsert['emp_sal']=$request->emp_sal;
   $dataToInsert['Motivation']=$request->Motivation;
   $dataToInsert['isSocialnsurance']=$request->isSocialnsurance;
   $dataToInsert['Socialnsurancecutmonthely']=$request->Socialnsurancecutmonthely;
   $dataToInsert['SocialnsuranceNumber']=$request->SocialnsuranceNumber;
   $dataToInsert['ismedicalinsurance']=$request->ismedicalinsurance;
   $dataToInsert['medicalinsurancecutmonthely']=$request->medicalinsurancecutmonthely;
   $dataToInsert['medicalinsuranceNumber']=$request->medicalinsuranceNumber;
   $dataToInsert['sal_cach_or_visa']=$request->sal_cach_or_visa;
   $dataToInsert['is_active_for_Vaccation']=$request->is_active_for_Vaccation;
   $dataToInsert['urgent_person_details']=$request->urgent_person_details;
   $dataToInsert['staies_address']=$request->staies_address;
   $dataToInsert['children_number']=$request->children_number ;
   $dataToInsert['emp_social_status_id']=$request->emp_social_status_id;
   $dataToInsert['Resignations_id']=$request->Resignations_id;
   $dataToInsert['bank_number_account']=$request->bank_number_account ;
   $dataToInsert['is_Disabilities_processes']=$request->is_Disabilities_processes;
   $dataToInsert['Disabilities_processes']=$request->Disabilities_processes;
   $dataToInsert['emp_nationality_id']=$request->emp_nationality_id;
   $dataToInsert['emp_cafel']=$request->emp_cafel;
   $dataToInsert['emp_pasport_no']=$request->emp_pasport_no;
   $dataToInsert['emp_pasport_from']=$request->emp_pasport_from;
   $dataToInsert['Does_have_fixed_allowances']=$request->Does_have_fixed_allowances;
   $dataToInsert['emp_pasport_exp']=$request->emp_pasport_exp;
   $dataToInsert['brith_date']=$request->brith_date;
   $dataToInsert['MotivationType']=$request->MotivationType;
   $dataToInsert['emp_Basic_stay_com']=$request->emp_Basic_stay_com;
   $dataToInsert['is_Sensitive_manager_data']=$request->is_Sensitive_manager_data;
   $dataToInsert['Does_have_fixed_allowances']=$request->Does_have_fixed_allowances;
   if(!empty($request->emp_sal)){
     $dataToInsert['day_price']=$request->emp_sal/30;
   }
    $dataToInsert['added_by'] = auth()->user()->id;
    $dataToInsert['com_code'] = $com_code;

   if ($request->has('emp_photo')) {
    $request->validate([
    'emp_photo' => 'required|mimes:png,jpg,jpeg|max:2000',
    ]);
    $the_file_path = uploadImage('assets/admin/uploads', $request->emp_photo);
    $dataToInsert['emp_photo'] = $the_file_path;
    }

    if ($request->has('emp_CV')) {
    $request->validate([
    'emp_CV' => 'required|mimes:png,jpg,jpeg,doc,docx,pdf|max:2000',
    ]);
    $the_file_path = uploadImage('assets/admin/uploads', $request->emp_CV);
    $dataToInsert['emp_CV'] = $the_file_path;
    }
    $flag=insert(new Employee(), $dataToInsert);
    if($flag){
      if( $dataToInsert['emp_sal']>0){
      $dataToInsertSalaryArchive['employees_id']=$flag['id'];
      $dataToInsertSalaryArchive['value']=$dataToInsert['emp_sal'];
      $dataToInsertSalaryArchive['added_by'] = auth()->user()->id;
      $dataToInsertSalaryArchive['com_code'] = $com_code;
      insert(new Employee_salary_archive(),$dataToInsertSalaryArchive);

      } 
    $is_active_alerts_system_monitoring=get_field_value(new Admin_panel_setting(),"is_active_alerts_system_monitoring",array("com_code"=>auth('admin')->user()->com_code));
    if($is_active_alerts_system_monitoring==1){
        /* start alerts system monitoring */
        $data_monitoring_insert['alert_modules_id']=2;
        $data_monitoring_insert['alert_movetype_id']=33;
        $data_monitoring_insert['content']="اضافه موظف جديد باسم ".$request->emp_name." كود  ". $dataToInsert['employees_code'];
        $data_monitoring_insert['foreign_key_table_id']=$flag['id'];
        $data_monitoring_insert['added_by']=auth('admin')->user()->id;
        $data_monitoring_insert['com_code']=auth('admin')->user()->com_code;
        $data_monitoring_insert['date']=date("Y-m-d");
        insert(new Alerts_system_monitoring(), $data_monitoring_insert,array("com_code"=>auth('admin')->user()->com_code));
         /* end alerts system monitoring */
    }
 
    }
      DB::commit();
     return redirect()->route('Employees.index')->with(['success' => 'تم اضافة البيانات بنجاح']);

    }catch(\Exception $ex){
        DB::rollBack();
        return redirect()->route('Employees.create')->with(['error'=>'عفوا حدث خطا  '.$ex->getMessage()]);
    }

    }

    public function edit($id)
    {
     $com_code = auth()->user()->com_code;
     $data = get_cols_where_row(new Employee(), array("*"), array('com_code' => $com_code,"id"=>$id));
      if (empty($data)) {
        return redirect()->route('admin.Employees.index')->with(['error' => 'عفوا  غير قادر الوصول للبيانات ! ']);
     }
        $other['branches'] = get_cols_where(new Branche(), array("id", "name"), array("com_code" => $com_code, "active" => 1));
        $other['departements'] = get_cols_where(new Departement(), array("id", "name"), array("com_code" => $com_code, "active" => 1));
        $other['jobs'] = get_cols_where(new jobs_categorie(), array("id", "name"), array("com_code" => $com_code, "active" => 1));
        $other['qualifications'] = get_cols_where(new Qualification(), array("id", "name"), array("com_code" => $com_code, "active" => 1));
        $other['religions'] = get_cols_where(new Religion(), array("id", "name"), array("com_code" => $com_code, "active" => 1));
        $other['nationalities'] = get_cols_where(new Nationalitie(), array("id", "name"), array("com_code" => $com_code, "active" => 1));
        $other['countires'] = get_cols_where(new Countries(), array("id", "name"), array("com_code" => $com_code, "active" => 1));
        $other['governorates'] = get_cols_where(new governorates(), array("id", "name"), array("com_code" => $com_code, "active" => 1));
        $other['centers'] = get_cols_where(new centers(), array("id", "name"), array("com_code" => $com_code, "active" => 1));
        $other['blood_groups'] = get_cols_where(new blood_groups(), array("id", "name"), array("com_code" => $com_code, "active" => 1));
        $other['military_status'] = get_cols_where(new Military_status(), array("id", "name"), array("active" => 1),'id','ASC');
        $other['driving_license_types'] = get_cols_where(new driving_license_type(), array("id", "name"), array("active" => 1,"com_code" => $com_code),'id','ASC');
        $other['shifts_types'] = get_cols_where(new Shifts_type(), array("id", "type","from_time","to_time","total_hour"), array("active" => 1,"com_code" => $com_code),'id','ASC');
        $other['languages'] = get_cols_where(new Language(), array("id", "name"), array("active" => 1,"com_code" => $com_code),'id','ASC');

        return view('admin.Employees.edit', ['data' => $data,'other'=>$other]);

    }

      public function update($id,EmployessUpdateRequest $request)
    {
    try {
            $com_code = auth()->user()->com_code;
     $data = get_cols_where_row(new Employee(), array("*"), array('com_code' => $com_code,"id"=>$id));
      if (empty($data)) {
        return redirect()->route('admin.Employees.edit')->with(['error' => 'عفوا  غير قادر الوصول للبيانات ! ']);
     } 
   
   $CheckExsists=Employee::select("id")->where('com_code','=',$com_code)->where('emp_name','=',$request->emp_name)->where('id','!=',$id)->first();
      if (!empty($CheckExsists)) {
        return redirect()->back()->with(['error' => 'عفوا  اسم الموظف مسجل من قبل ! '])->withInput();
     }
    
     $CheckExsists_zketo_code=Employee::select("id")->where('com_code','=',$com_code)->where('zketo_code','=',$request->zketo_code)->where('id','!=',$id)->first();
      if (!empty($CheckExsists_zketo_code)) {
        return redirect()->back()->with(['error' => 'عفوا  كود بصمة الموظف مسجل من قبل ! '])->withInput();
     }

 
   DB::beginTransaction();
   $dataToupdate['zketo_code']=$request->zketo_code;
   $dataToupdate['emp_name']=$request->emp_name;
   $dataToupdate['emp_gender']=$request->emp_gender;
   $dataToupdate['Qualifications_id']=$request->Qualifications_id;
   $dataToupdate['Qualifications_year']=$request->Qualifications_year;
   $dataToupdate['graduation_estimate']=$request->graduation_estimate;
   $dataToupdate['Graduation_specialization']=$request->Graduation_specialization;
   $dataToupdate['emp_national_idenity']=$request->emp_national_idenity;
   $dataToupdate['emp_end_identityIDate']=$request->emp_end_identityIDate;
   $dataToupdate['emp_identityPlace']=$request->emp_identityPlace;
   $dataToupdate['blood_group_id']=$request->blood_group_id;
   $dataToupdate['religion_id']=$request->religion_id;
   $dataToupdate['emp_lang_id']=$request->emp_lang_id;
   $dataToupdate['emp_email']=$request->emp_email;
   $dataToupdate['country_id']=$request->country_id;
   $dataToupdate['governorate_id']=$request->governorate_id ;
   $dataToupdate['city_id']=$request->city_id;
   $dataToupdate['emp_home_tel']=$request->emp_home_tel;
   $dataToupdate['emp_work_tel']=$request->emp_work_tel;
   $dataToupdate['emp_military_id']=$request->emp_military_id;
   $dataToupdate['emp_military_date_from']=$request->emp_military_date_from;
   $dataToupdate['emp_military_date_to']=$request->emp_military_date_to;
   $dataToupdate['emp_military_wepon']=$request->emp_military_wepon;
   $dataToupdate['exemption_date']=$request->exemption_date;
   $dataToupdate['exemption_reason']=$request->exemption_reason ;
   $dataToupdate['postponement_reason']=$request->postponement_reason;
   $dataToupdate['Date_resignation']=$request->Date_resignation;
   $dataToupdate['resignation_cause']=$request->resignation_cause;
   $dataToupdate['does_has_Driving_License']=$request->does_has_Driving_License;
   $dataToupdate['driving_License_degree']=$request->driving_License_degree;
   $dataToupdate['driving_license_types_id']=$request->driving_license_types_id;
   $dataToupdate['has_Relatives']=$request->has_Relatives;
   $dataToupdate['Relatives_details']=$request->Relatives_details;
   $dataToupdate['notes']=$request->notes;
   $dataToupdate['emp_start_date']=$request->emp_start_date;
   $dataToupdate['Functiona_status']=$request->Functiona_status;
   $dataToupdate['emp_Departments_code']=$request->emp_Departments_code ;
   $dataToupdate['emp_jobs_id']=$request->emp_jobs_id;
   $dataToupdate['does_has_ateendance']=$request->does_has_ateendance;
   $dataToupdate['is_has_fixced_shift']=$request->is_has_fixced_shift;
   $dataToupdate['shift_type_id']=$request->shift_type_id;

   if($request->is_has_fixced_shift==1){
    $shiftData=get_cols_where_row(new Shifts_type(),"total_hour",array("com_code"=>$com_code,"id"=>$request->shift_type_id));
    if(!empty($shiftData)){
     $dataToupdate['daily_work_hour']=$shiftData['total_hour']; 
    }else{
     return redirect()->back()->with(['error' => 'عفوا  غير قادر الوصول للبيانات الشفت المحدد للموظف ! ']);  
    }
   }else{
 $dataToupdate['daily_work_hour']=$request->daily_work_hour;
   }

  
   $dataToupdate['emp_sal']=$request->emp_sal;
   $dataToupdate['Motivation']=$request->Motivation;
   $dataToupdate['isSocialnsurance']=$request->isSocialnsurance;
   $dataToupdate['Socialnsurancecutmonthely']=$request->Socialnsurancecutmonthely;
   $dataToupdate['SocialnsuranceNumber']=$request->SocialnsuranceNumber;
   $dataToupdate['ismedicalinsurance']=$request->ismedicalinsurance;
   $dataToupdate['medicalinsurancecutmonthely']=$request->medicalinsurancecutmonthely;
   $dataToupdate['medicalinsuranceNumber']=$request->medicalinsuranceNumber;
   $dataToupdate['sal_cach_or_visa']=$request->sal_cach_or_visa;
   $dataToupdate['is_active_for_Vaccation']=$request->is_active_for_Vaccation;
   $dataToupdate['urgent_person_details']=$request->urgent_person_details;
   $dataToupdate['staies_address']=$request->staies_address;
   $dataToupdate['children_number']=$request->children_number ;
   $dataToupdate['emp_social_status_id']=$request->emp_social_status_id;
   $dataToupdate['Resignations_id']=$request->Resignations_id;
   $dataToupdate['bank_number_account']=$request->bank_number_account ;
   $dataToupdate['is_Disabilities_processes']=$request->is_Disabilities_processes;
   $dataToupdate['Disabilities_processes']=$request->Disabilities_processes;
   $dataToupdate['emp_nationality_id']=$request->emp_nationality_id;
   $dataToupdate['emp_cafel']=$request->emp_cafel;
   $dataToupdate['emp_pasport_no']=$request->emp_pasport_no;
   $dataToupdate['emp_pasport_from']=$request->emp_pasport_from;
   $dataToupdate['Does_have_fixed_allowances']=$request->Does_have_fixed_allowances;
   $dataToupdate['emp_pasport_exp']=$request->emp_pasport_exp;
   $dataToupdate['is_Sensitive_manager_data']=$request->is_Sensitive_manager_data;
   $dataToupdate['brith_date']=$request->brith_date;
   $dataToupdate['emp_Basic_stay_com']=$request->emp_Basic_stay_com;
   $dataToupdate['MotivationType']=$request->MotivationType;
   $dataToupdate['updated_by'] = auth()->user()->id;
   if(!empty($request->emp_sal) && is_numeric($request->emp_sal)){
    $dataToupdate['day_price'] = round($request->emp_sal / 30, 2);
}
   if ($request->has('emp_photo')) {
    $request->validate([
    'emp_photo' => 'required|mimes:png,jpg,jpeg|max:2000',
    ]);
    $the_file_path = uploadImage('assets/admin/uploads', $request->emp_photo);
    $dataToupdate['emp_photo'] = $the_file_path;
    }
    if ($request->has('emp_CV')) {
    $request->validate([
    'emp_CV' => 'required|mimes:png,jpg,jpeg,doc,docx,pdf|max:2000',
    ]);
    $the_file_path = uploadImage('assets/admin/uploads', $request->emp_CV);
    $dataToupdate['emp_CV'] = $the_file_path;
    }

 $flag=update(new Employee(),$dataToupdate,array('com_code' => $com_code,"id"=>$id) ) ; 
if($flag){
//نشوف لو فيه قيمة جديدة
      if( $dataToupdate['emp_sal']!=$data['emp_sal']){
      $dataToInsertSalaryArchive['employees_id']=$id;
      $dataToInsertSalaryArchive['value']=$dataToupdate['emp_sal'];
      $dataToInsertSalaryArchive['added_by'] = auth()->user()->id;
      $dataToInsertSalaryArchive['com_code'] = $com_code;
      insert(new Employee_salary_archive(),$dataToInsertSalaryArchive);
      }
   $labels = [
    'emp_name' => 'اسم الموظف',
    'emp_gender' => 'النوع',
    'Qualifications_id' => 'المؤهل',
    'Qualifications_year' => 'سنة التخرج',
    'graduation_estimate' => 'التقدير',
    'Graduation_specialization' => 'التخصص',
    'emp_national_idenity' => 'الرقم القومي',
    'emp_end_identityIDate' => 'انتهاء البطاقة',
    'emp_identityPlace' => 'جهة الإصدار',
    'blood_group_id' => 'فصيلة الدم',
    'religion_id' => 'الديانة',
    'emp_lang_id' => 'اللغة',
    'emp_email' => 'البريد الإلكتروني',
    'emp_sal' => 'الراتب',
    'emp_start_date' => 'تاريخ التعيين',
    'Functiona_status' => 'الحالة الوظيفية',
    'emp_Departments_code' => 'القسم',
    'emp_jobs_id' => 'الوظيفة',
    'does_has_ateendance' => 'يخضع للحضور',
    'daily_work_hour' => 'ساعات العمل اليومية',
    'notes' => 'ملاحظات',
    'is_active_for_Vaccation' => 'يخضع للإجازات',
    'bank_number_account' => 'رقم الحساب البنكي',
    'children_number' => 'عدد الأبناء',
    'emp_social_status_id' => 'الحالة الاجتماعية',
    'brith_date' => 'تاريخ الميلاد',
    'emp_nationality_id' => 'الجنسية',
     'Motivation' => 'قيمة الحافز الشهري الثابت',
    'Socialnsurancecutmonthely' => 'قيمة التأمين المستقطع شهرياً',
    'day_price' => 'راتب اليوم',
];

       $oldData = $data->toArray();
       $newData = $dataToupdate;
       unset($oldData['updated_by'], $oldData['added_by'],$oldData['com_code']);
       $changes = [];

        foreach ($newData as $key => $value) {
            if (isset($oldData[$key]) && $oldData[$key] != $value) {
               $label = $labels[$key] ?? $key;
                $changes[] = "تم تغيير {$label} من ({$oldData[$key]}) إلى ({$value})";
            }
        }
       
      $is_active_alerts_system_monitoring=get_field_value(new Admin_panel_setting(),"is_active_alerts_system_monitoring",array("com_code"=>auth('admin')->user()->com_code));
       if($is_active_alerts_system_monitoring==1){
        if (!empty($changes)) {

    $data_monitoring_insert = [
        'alert_modules_id' => 2,
        'alert_movetype_id' => 34,
        'content' => "تم بيانات موظف {$data['emp_name']} : " . implode(' ، ', $changes),
        'foreign_key_table_id' => $id,
        'added_by' => auth('admin')->user()->id,
        'com_code' => auth('admin')->user()->com_code,
        'date' => date("Y-m-d"),
    ];

    insert(new Alerts_system_monitoring(), $data_monitoring_insert, [
        "com_code" => auth('admin')->user()->com_code
    ]);
}
      }

    if($dataToupdate['Does_have_fixed_allowances']!=1){
    //يتم حذف البدلات الثابتة ان وجت وتحديث المرتب الحالى 
       destroy(new Employees_fixed_suits(),array('com_code' => $com_code,"employees_id"=>$id)); 
  }
  // لو يوجد راتب للموظف مفتوح نعيد احتسابه
  $currentsalrayData=get_cols_where_row(new Main_salary_employee(),array("id"),array("com_code"=>$com_code,"employees_code"=>$data['employees_code'],'is_archived'=>0));
  if(!empty($currentsalrayData)){
          $this->Recalculate_main_salary_employee($currentsalrayData['id']);
  }

}
       DB::commit();



        return redirect()->route('Employees.index')->with(['success' => 'تم تحديث البيانات بنجاح']);
        } catch (\Exception $ex) {
        
            DB::rollBack();
            return redirect()->back()->with(['error'=>'عفوا حدث خطا  '.$ex->getMessage()])->withInput();
       }
    }
   
     public function destroy($id)
    {
     $com_code = auth()->user()->com_code;
     $data = get_cols_where_row(new Employee(), array("employees_code","emp_name"), array('com_code' => $com_code,"id"=>$id));
      if (empty($data)) {
        return redirect()->route('admin.Employees.index')->with(['error' => 'عفوا  غير قادر الوصول للبيانات ! ']);
     }
     //تحقق من الصلاحيات وتحقق من عدم استخدام الموظف فى لنظام كليا
    $flag=destroy(new Employee(),array('com_code' => $com_code,"id"=>$id));
    if($flag){
    $is_active_alerts_system_monitoring=get_field_value(new Admin_panel_setting(),"is_active_alerts_system_monitoring",array("com_code"=>auth('admin')->user()->com_code));
    if($is_active_alerts_system_monitoring==1){
        /* start alerts system monitoring */
        $data_monitoring_insert['alert_modules_id']=2;
        $data_monitoring_insert['alert_movetype_id']=35;
        $data_monitoring_insert['content']="حذف موظف باسم ".$data['emp_name'];
        $data_monitoring_insert['foreign_key_table_id']=$id;
        $data_monitoring_insert['added_by']=auth('admin')->user()->id;
        $data_monitoring_insert['com_code']=auth('admin')->user()->com_code;
        $data_monitoring_insert['date']=date("Y-m-d");
        insert(new Alerts_system_monitoring(), $data_monitoring_insert,array("com_code"=>auth('admin')->user()->com_code));
         /* end alerts system monitoring */
    }
}
      return redirect()->route('Employees.index')->with(['success' => 'تم حذف البيانات بنجاح']);
    }

  

    public function get_governorates(Request $request)
    {
        if ($request->ajax()) {
            $country_id = $request->country_id;
            $other['governorates'] = get_cols_where(new governorates(), array("id", "name"), array("com_code" => auth()->user()->com_code, 'countires_id' => $country_id));
            return view('admin.Employees.get_governorates',['other'=>$other]);
        }
    }

    public function get_centers(Request $request)
    {
        if ($request->ajax()) {
            $governorates_id = $request->governorates_id;
            $other['centers'] = get_cols_where(new centers(), array("id", "name"), array("com_code" => auth()->user()->com_code, 'governorates_id' => $governorates_id));
            return view('admin.Employees.get_centers',['other'=>$other]);
        }
    }

public function ajax_search(Request $request)
{
if ($request->ajax()) {
 $com_code = auth('admin')->user()->com_code;  
$searchbycode = $request->searchbycode;
$emp_name_search = $request->emp_name_search;
$emp_Departments_code_search = $request->emp_Departments_code_search;
$emp_jobs_id_search = $request->emp_jobs_id_search;
$sal_cach_or_visa_search = $request->sal_cach_or_visa_search;
$emp_gender_search = $request->emp_gender_search;
$branch_id_search = $request->branch_id_search;
$Functiona_status_search = $request->Functiona_status_search;
$searchbyradiocode = $request->searchbyradiocode;

if ($searchbycode == '') {
//هنا نعمل شرط دائم التحقق
$field1 = "id";
$operator1 = ">";
$value1 = 0;
} else {
if($searchbyradiocode=='zketo_code'){
$field1 = "zketo_code";
$operator1 = "=";
$value1 = $searchbycode;
}else{
$field1 = "employees_code";
$operator1 = "=";
$value1 = $searchbycode;
}
}

if ($emp_name_search == '') {
//هنا نعمل شرط دائم التحقق
$field2 = "id";
$operator2 = ">";
$value2 = 0;
} else {
$field2 = "emp_name";
$operator2 = "like";
$value2 = "%$emp_name_search%";
}

if ($emp_Departments_code_search =='all') {
//هنا نعمل شرط دائم التحقق
$field3 = "id";
$operator3 = ">";
$value3 = 0;
} else {
$field3 = "emp_Departments_code";
$operator3 = "=";
$value3 =$emp_Departments_code_search;
}

if ($emp_jobs_id_search =='all') {
//هنا نعمل شرط دائم التحقق
$field5 = "id";
$operator5 = ">";
$value5 = 0;
} else {
$field5 = "emp_jobs_id";
$operator5 = "=";
$value5 =$emp_jobs_id_search;
}

if ($Functiona_status_search =='all') {
//هنا نعمل شرط دائم التحقق
$field6 = "id";
$operator6 = ">";
$value6 = 0;
} else {
$field6 = "Functiona_status";
$operator6 = "=";
$value6 =$Functiona_status_search;
}

if ($sal_cach_or_visa_search =='all') {
//هنا نعمل شرط دائم التحقق
$field7 = "id";
$operator7 = ">";
$value7 = 0;
} else {
$field7 = "sal_cach_or_visa";
$operator7 = "=";
$value7 =$sal_cach_or_visa_search;
}

if ($emp_gender_search =='all') {
//هنا نعمل شرط دائم التحقق
$field8 = "id";
$operator8 = ">";
$value8 = 0;
} else {
$field8 = "emp_gender";
$operator8 = "=";
$value8 =$emp_gender_search;
}
if ($branch_id_search =='all') {
//هنا نعمل شرط دائم التحقق
$field4 = "id";
$operator4 = ">";
$value4 = 0;
} else {
$field4 = "branch_id";
$operator4 = "=";
$value4 =$branch_id_search;
}






$data=Employee::select("*")->where($field1,$operator1,$value1)->where($field2,$operator2,$value2)->where($field3,$operator3,$value3)->where($field5,$operator5,$value5)->where($field6,$operator6,$value6)->where($field7,$operator7,$value7)->where($field4,$operator4,$value4)->where($field8,$operator8,$value8)->where('com_code','=',$com_code)->orderby('id','DESC')->paginate(PC);
   if(!empty($data)){
          foreach($data as $info){
            $info->counterUserBefor=get_count_where(new Main_salary_employee(),array("com_code" => $com_code,"employees_code"=>$info->employees_code));}
         }
        
return view('admin.Employees.ajax_search',['data'=>$data]);
}
}


  public function show($id)
    {
     $com_code = auth()->user()->com_code;
     $data = get_cols_where_row(new Employee(), array("*"), array('com_code' => $com_code,"id"=>$id));
      if (empty($data)) {
        return redirect()->route('admin.Employees.index')->with(['error' => 'عفوا  غير قادر الوصول للبيانات ! ']);
     }
        $other['branches'] = get_cols_where(new Branche(), array("id", "name"), array("com_code" => $com_code, "id" => $data['branch_id']));
        $other['departements'] = get_cols_where(new Departement(), array("id", "name"), array("com_code" => $com_code, "id" => $data['emp_Departments_code']));
        $other['jobs'] = get_cols_where(new jobs_categorie(), array("id", "name"), array("com_code" => $com_code, "id" => $data['emp_jobs_id']));
        $other['qualifications'] = get_cols_where(new Qualification(), array("id", "name"), array("com_code" => $com_code,"id" => $data['Qualifications_id']));
        $other['religions'] = get_cols_where(new Religion(), array("id", "name"), array("com_code" => $com_code, "id" => $data['religion_id ']));
        $other['nationalities'] = get_cols_where(new Nationalitie(), array("id", "name"), array("com_code" => $com_code,"id" => $data['emp_nationality_id']));
        $other['countires'] = get_cols_where(new Countries(), array("id", "name"), array("com_code" => $com_code, "id" => $data['country_id']));
        $other['governorates'] = get_cols_where(new governorates(), array("id", "name"), array("com_code" => $com_code, "id" => $data['governorate_id']));
        $other['centers'] = get_cols_where(new centers(), array("id", "name"), array("com_code" => $com_code, "id" => $data['city_id']));
        $other['blood_groups'] = get_cols_where(new blood_groups(), array("id", "name"), array("com_code" => $com_code, "id" => $data['emp_Departments_code']));
        $other['military_status'] = get_cols_where(new Military_status(), array("id", "name"), array("active" => 1),'id','ASC');
        $other['driving_license_types'] = get_cols_where(new driving_license_type(), array("id", "name"), array("active" => 1,"com_code" => $com_code),'id','ASC');
        $other['shifts_types'] = get_cols_where(new Shifts_type(), array("id", "type","from_time","to_time","total_hour"), array("active" => 1,"com_code" => $com_code,'id'=>$data['shift_type_id']),'id','ASC');
        $other['languages'] = get_cols_where(new Language(), array("id", "name"), array("active" => 1,"com_code" => $com_code,'id'=>$data['emp_lang_id']),'id','ASC');
        $other['employees_files'] =get_cols_where(new Employees_files(), array("*"), array("com_code" => $com_code,"employees_id"=>$id));
        if( $data['Does_have_fixed_allowances'] ==1){
        $data['employees_fixed_suits'] =get_cols_where(new Employees_fixed_suits(), array("*"), array("com_code" => $com_code,"employees_id"=>$id));
          $other['allowances'] =get_cols_where(new Allowances(), array("id", "name"), array("active" => 1,"com_code" => $com_code),'id','ASC');
        }

        return view('admin.Employees.show', ['data' => $data,'other'=>$other]);

    }


    public function download($id,$field_name)
    {
     $com_code = auth()->user()->com_code;
     $data = get_cols_where_row(new Employee(), array("$field_name"), array('com_code' => $com_code,"id"=>$id));
      if (empty($data)) {
        return redirect()->route('admin.Employees.index')->with(['error' => 'عفوا  غير قادر الوصول للبيانات ! ']);
     }
     $file_path="assets/admin/uploads/".$data[$field_name];
     return response()->download($file_path);



    }


      public function add_files($id,request $request)
    {
    try {
      $com_code = auth()->user()->com_code;
     $data = get_cols_where_row(new Employee(), array("*"), array('com_code' => $com_code,"id"=>$id));
      if (empty($data)) {
        return redirect()->back()->with(['error' => 'عفوا  غير قادر الوصول للبيانات ! ']);
     } 
   
   $CheckExsists=Employees_files::select("id")->where('com_code','=',$com_code)->where('name','=',$request->name)->where('employees_id','=',$id)->first();
      if (!empty($CheckExsists)) {
        return redirect()->back()->with(['error' => 'عفوا  اسم الموظف مسجل من قبل ! ']);
     }
    


   DB::beginTransaction();
   $dataToInsert['name']=$request->name;
   $dataToInsert['employees_id']=$request->id;


   if ($request->has('the_file')) {
    $request->validate([
    'the_file' => 'required|mimes:png,jpg,jpeg,doc,docx,pdf|max:2000',
    ]);
    $the_file_path = uploadImage('assets/admin/uploads', $request->the_file);
    $dataToInsert['file_path'] = $the_file_path;
    }

   $dataToInsert['added_by'] = auth()->user()->id;
   $dataToInsert['com_code'] = $com_code;

   
 insert(new Employees_files(),$dataToInsert); 
       DB::commit();



        return redirect()->back()->with(['success' => 'تم اضافة البيانات بنجاح','tabfiles'=>'files']);
        } catch (\Exception $ex) {
        
            DB::rollBack();
            return redirect()->back()->with(['error'=>'عفوا حدث خطا  '.$ex->getMessage()]);
       }
    }


      public function add_allowances($id,request $request)
    {
    try {
      $com_code = auth()->user()->com_code;
     $data = get_cols_where_row(new Employee(), array("*"), array('com_code' => $com_code,"id"=>$id));
      if (empty($data)) {
        return redirect()->back()->with(['error' => 'عفوا  غير قادر الوصول للبيانات ! ','jobs_data'=>'jobs_data']);
     } 
   
   $CheckExsists=Employees_fixed_suits::select("id")->where('com_code','=',$com_code)->where('allowances_id','=',$request->allowances_id)->where('employees_id','=',$id)->first();
      if (!empty($CheckExsists)) {
        return redirect()->back()->with(['error' => 'عفوا  هذا البدل مسجل من قبل ! ','jobs_data'=>'jobs_data']);
     }
    


   DB::beginTransaction();
   
   $dataToInsert['employees_id']=$id;
   $dataToInsert['allowances_id']=$request->allowances_id;
   $dataToInsert['value']=$request->allowances_value;
   $dataToInsert['added_by'] = auth('admin')->user()->id;
   $dataToInsert['com_code'] = $com_code;

   
 $flag=insert(new Employees_fixed_suits(),$dataToInsert); 
 if($flag){
   $currentsalrayData=get_cols_where_row(new Main_salary_employee(),array("id"),array("com_code"=>$com_code,"employees_code"=>$data['employees_code'],'is_archived'=>0));
  if(!empty($currentsalrayData)){
          $this->Recalculate_main_salary_employee($currentsalrayData['id']);
  }
 }
       DB::commit();
        return redirect()->back()->with(['success' => 'تم اضافة البدل بنجاح','jobs_data'=>'jobs_data']);
        } catch (\Exception $ex) {
        
            DB::rollBack();
            return redirect()->back()->with(['error'=>'عفوا حدث خطا  '.$ex->getMessage()]);
       }
    }


    public function destroy_files($id)
    {
     $com_code = auth()->user()->com_code;
     $data = get_cols_where_row(new Employees_files(), array("id"), array('com_code' => $com_code,"id"=>$id));
      if (empty($data)) {
        return redirect()->back()->with(['error' => 'عفوا  غير قادر الوصول للبيانات ! ']);
     }
     //تحقق من الصلاحيات وتحقق من عدم استخدام الموظف فى لنظام كليا
    destroy(new Employees_files(),array('com_code' => $com_code,"id"=>$id));
      return redirect()->back()->with(['success' => 'تم حذف البيانات بنجاح','tabfiles'=>'files']);
    }
     public function destroy_allowances($id)
    {
      try{
     $com_code = auth("admin")->user()->com_code;
     $data = get_cols_where_row(new Employees_fixed_suits(), array("id","employees_id"), array('com_code' => $com_code,"id"=>$id));
      if (empty($data)) {
        return redirect()->back()->with(['error' => 'عفوا  غير قادر الوصول للبيانات ! ','jobs_data'=>'jobs_data']);
     }
       $dataEmployee = get_cols_where_row(new Employee(), array("*"), array('com_code' => $com_code,"id"=>$data['employees_id']));
       if (empty($dataEmployee)) {
        return redirect()->back()->with(['error' => 'عفوا  غير قادر الوصول للبيانات ! ','jobs_data'=>'jobs_data']);
     }
     $counterUserBefor=get_count_where(new Main_salary_employee(),array("com_code" => $com_code,"employees_code"=>$data['employees_code']));
      if ($counterUserBefor=!0) {
        return redirect()->back()->with(['error' => 'عفوا هذا الموظف مفتوح لها سجلات للرواتب من قبل ومتاح فقط تعديله لخارج الخدمة ! ','jobs_data'=>'jobs_data']);
     }
     //تحقق من الصلاحيات وتحقق من عدم استخدام الموظف فى لنظام كليا
   DB::beginTransaction();
    $flag =destroy(new Employees_fixed_suits(),array('com_code' => $com_code,"id"=>$id));
     if($flag){
  $currentsalrayData=get_cols_where_row(new Main_salary_employee(),array("id"),array("com_code"=>$com_code,"employees_code"=>$dataEmployee['employees_code'],'is_archived'=>0));
  if(!empty($currentsalrayData)){
          $this->Recalculate_main_salary_employee($currentsalrayData['id']);
  }
       }
       DB::commit();
      return redirect()->back()->with(['success' => 'تم حذف البيانات بنجاح','jobs_data'=>'jobs_data']);
         
       } catch (\Exception $ex) {
        
            DB::rollBack();
            return redirect()->back()->with(['error'=>'عفوا حدث خطا  '.$ex->getMessage()]);
       }
    }

   public function download_files($id)
    {
     $com_code = auth()->user()->com_code;
     $data = get_cols_where_row(new Employees_files(), array("file_path"), array('com_code' => $com_code,"id"=>$id));
      if (empty($data)) {
        return redirect()->back()->with(['error' => 'عفوا  غير قادر الوصول للبيانات ! ']);
     }
     $file_path="assets/admin/uploads/".$data['file_path'];
     return response()->download($file_path);



    }


  public function load_edit_allowances(Request $request)
{
 
if ($request->ajax()) {
    $com_code = auth('admin')->user()->com_code; 
    $data = get_cols_where_row(new Employees_fixed_suits(), array("*"), array('com_code' => $com_code,"id"=>$request->id));
     return view('admin.Employees.load_edit_allowances',['data' => $data]);
    }
     
}

  public function do_edit_allowances($id,Request $request)
    {
    try{
     $com_code = auth("admin")->user()->com_code;
     $data = get_cols_where_row(new Employees_fixed_suits(), array("id","employees_id"), array('com_code' => $com_code,"id"=>$id));
      if (empty($data)) {
        return redirect()->back()->with(['error' => 'عفوا  غير قادر الوصول للبيانات ! ','jobs_data'=>'jobs_data']);
     }
      $dataEmployee = get_cols_where_row(new Employee(), array("*"), array('com_code' => $com_code,"id"=>$data['employees_id']));
       if (empty($dataEmployee)) {
        return redirect()->back()->with(['error' => 'عفوا  غير قادر الوصول للبيانات ! ','jobs_data'=>'jobs_data']);
     }
     //تحقق من الصلاحيات وتحقق من عدم استخدام الموظف فى لنظام كليا
   DB::beginTransaction();
     $dataToupdate['value']=$request->allowances_value_edit;
     $dataToupdate['updated_by'] = auth()->user()->id;
     $dataToupdate['updated_at'] =date("Y-m-d H:i:s");

    $flag =update(new Employees_fixed_suits(),$dataToupdate,array('com_code' => $com_code,"id"=>$id));
    if($flag){
   $currentsalrayData=get_cols_where_row(new Main_salary_employee(),array("id"),array("com_code"=>$com_code,"employees_code"=>$dataEmployee['employees_code'],'is_archived'=>0));
  if(!empty($currentsalrayData)){
          $this->Recalculate_main_salary_employee($currentsalrayData['id']);
  }
 }
       DB::commit();
      return redirect()->back()->with(['success' => 'تم تحديث البيانات بنجاح','jobs_data'=>'jobs_data']);
         
       } catch (\Exception $ex) {
        
            DB::rollBack();
            return redirect()->back()->with(['error'=>'عفوا حدث خطا  '.$ex->getMessage()]);
       }
    }

  public function showSalaryArchive(Request $request){

    if($request->ajax()){
      $com_code = auth()->user()->com_code;
      $data=get_cols_where(new Employee_salary_archive(),array("*"),array("com_code"=>$com_code,"employees_id"=>$request->id));
       return view('admin.Employees.showSalaryArchive',['data' => $data]);
    }
  }  

}