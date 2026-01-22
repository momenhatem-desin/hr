@extends('layouts.admin')
@section('title')
بيانات الموظفين
@endsection
@section("css")
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('contentheader')
قائمة الضبط
@endsection
@section('contentheaderactivelink')
<a href="{{ route('Employees.index') }}">     الموظفين</a>
@endsection
@section('contentheaderactive')
تعديل
@endsection
@section('content')
<div class="col-12">
   <div class="card">
      <div class="card-header">
         <h3 class="card-title card_title_center">  عرض تفاصيل بيانات الموظف
            <a  href="{{ route('Employees.edit',$data['id']) }}" class="btn btn-success btn-sm">تعديل</a>
              <a  href="{{ route('Employees.index')}}" class="btn btn-warning btn-sm">عودة</a>
         </h3>
      </div>
      <div class="card-body">
      
      
   <!-- /.card -->
   <div class="card card-primary card-outline">
      <div class="card-header">
        <h3 class="card-title text-right" style="width: 100%;
        text-align: right !important;">
          <i class="fas fa-edit"></i>
          البيانات المطلوبة للموظف
        </h3>
      </div>
      <div class="card-body">
      
        <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
          <li class="nav-item">
            <a class="nav-link @if(!Session::has('tabfiles') and !Session::has('jobs_data'))  active @endif " id="personal_date" data-toggle="pill" href="#custom-content-personal_data" role="tab" aria-controls="custom-content-personal_data" aria-selected="true">بيانات شخصية</a>
          </li>
          <li class="nav-item">
            <a class="nav-link @if(Session::has('jobs_data'))  active @endif" id="jobs_data" data-toggle="pill" href="#custom-content-jobs_data" role="tab" aria-controls="custom-content-jobs_data" aria-selected="false">بيانات وظيفة</a>
          </li>
          <li class="nav-item">
            <a class="nav-link @if(Session::has('tabfiles'))  active @endif " id="addtional_data" data-toggle="pill" href="#custom-content-addtional_data" role="tab" aria-controls="custom-content-addtional_data" aria-selected="false">بيانات اضافية</a>
          </li>
          
        </ul>
        <div class="tab-content" id="custom-content-below-tabContent">
         <div class="tab-pane fade @if(!Session::has('tabfiles') and !Session::has('jobs_data')) show active @endif " id="custom-content-personal_data" role="tabpanel" aria-labelledby="personal_date"> 
          <br>
            <div class="row">
         
               <div class="col-md-4">
                  <div class="form-group">
                     <label>    كود بصمة الموظف</label>
                     <input disabled  type="text" name="zketo_code" id="zketo_code" class="form-control" value="{{ $data['zketo_code'] }}" >
                     @error('zketo_code')
                     <span class="text-danger">{{ $message }}</span> 
                     @enderror
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group">
                     <label>    اسم الموظف  كاملا</label>
                     <input disabled type="text" name="emp_name" id="emp_name" class="form-control" value="{{ $data['emp_name'] }}" >
                  
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group">
                     <label>  نوع الجنس </label>
                     <select disabled   name="emp_gender" id="emp_gender" class="form-control">
                       <option value="">اختر النوع </option> 
                     <option   @if($data['emp_gender']==1) selected @endif  value="1">ذكر</option>
                     <option @if($data['emp_gender']==2 ) selected @endif value="1">انثي</option>
                     </select>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group">
                     <label>   الفرع التابع له الموظف </label>
                     <select disabled  name="branch_id" id="branch_id" class="form-control select2 ">
                        <option value="">اختر الفرع</option>
                        @if (@isset($other['branches']) && !@empty($other['branches']))
                        @foreach ($other['branches'] as $info )
                        <option @if($data['branch_id']==$info->id) selected="selected" @endif value="{{ $info->id }}"> {{ $info->name }} </option>
                        @endforeach
                        @endif
                     </select>
                  
                  </div>
               </div>


   

               <div class="col-md-4">
                  <div class="form-group">
                     <label>  المؤهل الدراسي</label>
                     <select disabled  name="Qualifications_id" id="Qualifications_id  " class="form-control select2 ">
                        <option value="">اختر المؤهل </option>
                        @if (@isset($other['qualifications']) && !@empty($other['qualifications']))
                        @foreach ($other['qualifications'] as $info )
                        <option @if($data['Qualifications_id']==$info->id) selected="selected" @endif value="{{ $info->id }}"> {{ $info->name }} </option>
                        @endforeach
                        @endif
                     </select>
               
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group">
                     <label>       سنة التخرج </label>
                     <input disabled type="text" name="Qualifications_year" id="Qualifications_year" class="form-control" value="{{ old('Qualifications_year',$data['Qualifications_year']) }}" >
                 
                  </div>
               </div>

               <div class="col-md-4">
                  <div class="form-group">
                     <label>   تقدير التخرج </label>
                     <select disabled   name="graduation_estimate" id="graduation_estimate" class="form-control">
                     <option @if($data['graduation_estimate']==1) selected @endif  value="1">مقبول</option>
                     <option @if($data['graduation_estimate']==2 ) selected @endif value="2">جيد</option>
                     <option @if($data['graduation_estimate']==3 ) selected @endif value="3">جيد مرتفع</option>
                     <option @if($data['graduation_estimate']==4 ) selected @endif value="4">جيد جداً</option>
                     <option @if($data['graduation_estimate']==5 ) selected @endif value="5">إمتياز </option>
                
                  </select>
                  
                  </div>
               </div>

               <div class="col-md-4">
                  <div class="form-group">
                     <label>       تخصص التخرج </label>
                     <input disabled type="text" name="Graduation_specialization" id="Graduation_specialization" class="form-control" value="{{ $data['Graduation_specialization'] }}" >
                   
                  </div>
               </div>


               
               <div class="col-md-4">
                  <div class="form-group">
                     <label>        تاريخ الميلاد </label>
                     <input disabled type="date" name="brith_date" id="brith_date" class="form-control" value="{{ old('brith_date',$data['brith_date']) }}" >
                
                  </div>
               </div>

               <div class="col-md-4">
                  <div class="form-group">
                     <label>         رقم بطاقة الهوية </label>
                     <input disabled type="text" name="emp_national_idenity" id="emp_national_idenity" class="form-control" value="{{ old('emp_national_idenity',$data['emp_national_idenity']) }}" >
                
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group">
                     <label>         مركز اصدار بطاقة الهوية  </label>
                     <input disabled type="text" name="emp_identityPlace" id="emp_identityPlace" class="form-control" value="{{ old('emp_identityPlace',$data['emp_identityPlace']) }}" >
                
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group">
                     <label>         تاريخ انتهاء بطاقة الهوية </label>
                     <input disabled type="date" name="emp_end_identityIDate" id="emp_end_identityIDate" class="form-control" value="{{ old('emp_end_identityIDate',$data['emp_end_identityIDate']) }}" >
                     @error('emp_end_identityIDate')
                     <span class="text-danger">{{ $message }}</span> 
                     @enderror
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group">
                     <label>   فصيلة الدم</label>
                     <select disabled  name="blood_group_id" id="blood_group_id" class="form-control select2 ">
                        <option value="">اختر الفصيلة</option>
                        @if (@isset($other['blood_groups']) && !@empty($other['blood_groups']))
                        @foreach ($other['blood_groups'] as $info )
                        <option @if(old('blood_group_id',$data['blood_group_id'])==$info->id) selected="selected" @endif value="{{ $info->id }}"> {{ $info->name }} </option>
                        @endforeach
                        @endif
                     </select>
            
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group">
                     <label>    الجنسية </label>
                     <select disabled  name="emp_nationality_id" id="emp_nationality_id" class="form-control select2 ">
                        <option value="">اختر الجنسية</option>
                        @if (@isset($other['nationalities']) && !@empty($other['nationalities']))
                        @foreach ($other['nationalities'] as $info )
                        <option @if(old('emp_nationality_id',$data['emp_nationality_id'])==$info->id) selected="selected" @endif value="{{ $info->id }}"> {{ $info->name }} </option>
                        @endforeach
                        @endif
                     </select>
              
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group">
                     <label>  اللغة الاساسية التي يتحدث بها</label>
                     <select disabled  name="emp_lang_id" id="emp_lang_id" class="form-control select2 ">
                        <option value="">اختر اللغة  </option>
                        @if (@isset($other['languages']) && !@empty($other['languages']))
                        @foreach ($other['languages'] as $info )
                        <option @if(old('emp_lang_id',$data['emp_lang_id'])==$info->id) selected="selected" @endif value="{{ $info->id }}"> {{ $info->name }} </option>
                        @endforeach
                        @endif
                     </select>
             
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group">
                     <label>    الديانة </label>
                     <select disabled  name="religion_id" id="religion_id" class="form-control select2 ">
                        <option value="">اختر الديانة  </option>
                        @if (@isset($other['religions']) && !@empty($other['religions']))
                        @foreach ($other['religions'] as $info )
                        <option @if(old('religion_id',$data['religion_id'])==$info->id) selected="selected" @endif value="{{ $info->id }}"> {{ $info->name }} </option>
                        @endforeach
                        @endif
                     </select>
           
                  </div>
               </div>

               <div class="col-md-4">
                  <div class="form-group">
                     <label>      البريد الالكتروني</label>
                     <input disabled type="text" name="emp_email" id="emp_email" class="form-control" value="{{ old('emp_email',$data['emp_email']) }}" >
                     @error('emp_email')
                     <span class="text-danger">{{ $message }}</span> 
                     @enderror
                  </div>
               </div>

               <div class="col-md-4">
                  <div class="form-group">
                     <label>    الدول</label>
                     <select disabled  name="country_id" id="country_id" class="form-control select2 ">
                        <option value="">اختر الدولة التابع لها الموظف</option>
                        @if (@isset($other['countires']) && !@empty($other['countires']))
                        @foreach ($other['countires'] as $info )
                        <option @if(old('country_id',$data['country_id'])==$info->id) selected="selected" @endif value="{{ $info->id }}"> {{ $info->name }} </option>
                        @endforeach
                        @endif
                     </select>
               
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group" id="governorates_Div">
                     <label>    المحافظات</label>
                     <select disabled  name="governorates_id" id="governorates_id" class="form-control select2 ">
                        <option value="">اختر المحافظة التابع لها الموظف</option>
                            @if (@isset($other['governorates']) && !@empty($other['governorates']))
                           @foreach ($other['governorates'] as $info )
                           <option @if(old('governorates_id',$data['governorates_id'])==$info->id) selected="selected" @endif value="{{ $info->id }}"> {{ $info->name }}
                           </option>
                           @endforeach
                           @endif
                     </select>
                
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group" id="centers_div">
                     <label>    المدينة/المركز</label>
                     <select disabled  name="city_id" id="city_id" class="form-control select2 ">
                        <option value="">اختر المدينة التابع لها الموظف</option>
                        @if (@isset($other['centers']) && !@empty($other['centers']))
                        @foreach ($other['centers'] as $info )
                        <option @if(old('city_id',$data['city_id'])==$info->id) selected="selected" @endif value="{{ $info->id }}"> {{ $info->name }}
                        </option>
                        @endforeach
                        @endif
                     </select>
         
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group">
                     <label>       عنوان الاقامة الحالي للموظف </label>
                     <input disabled type="text" name="staies_address" id="staies_address" class="form-control" value="{{ old('staies_address',$data['staies_address']) }}" >
     
                  </div>
               </div>

               <div class="col-md-4">
                  <div class="form-group">
                     <label>     هاتف المنزل  </label>
                     <input disabled type="text" name="emp_home_tel" id="emp_home_tel" class="form-control" value="{{ old('emp_home_tel',$data['emp_home_tel']) }}" >
           
                  </div>
               </div>

               <div class="col-md-4">
                  <div class="form-group">
                     <label>     هاتف العمل </label>
                     <input disabled type="text" name="emp_work_tel" id="emp_work_tel" class="form-control" value="{{ old('emp_work_tel',$data['emp_work_tel']) }}" >
              
                  </div>
               </div>

               <div class="col-md-4">
                  <div class="form-group">
                     <label>    حالة الخدمة العسكرية </label>
                     <select disabled  name="emp_military_id" id="emp_military_id" class="form-control select2 ">
                        <option value="">اختر  الحالة </option>
                        @if (@isset($other['military_status']) && !@empty($other['military_status']))
                        @foreach ($other['military_status'] as $info )
                        <option @if(old('emp_military_id',$data['emp_military_id'])==$info->id) selected="selected" @endif value="{{ $info->id }}"> {{ $info->name }} </option>
                        @endforeach
                        @endif
                     </select>
              
                  </div>
               </div>

               <div class="col-md-4 related_miltary_1" @if(old('emp_military_id',$data['emp_military_id'])!=1)   style="display: none;" @endif>
                  <div class="form-group">
                     <label>    تاريخ بداية الخدمة العسكرية	</label>
                     <input disabled type="date" name="emp_military_date_from" id="emp_military_date_from" class="form-control" value="{{ old('emp_military_date_from',$data['emp_military_date_from']) }}" >
              
                  </div>
               </div>


               <div class="col-md-4 related_miltary_1" @if(old('emp_military_id',$data['emp_military_id'])!=1)   style="display: none;" @endif>
                  <div class="form-group">
                     <label>    تاريخ نهاية الخدمة العسكرية	</label>
                     <input disabled type="date" name="emp_military_date_to" id="emp_military_date_to" class="form-control" value="{{ old('emp_military_date_to',$data['emp_military_date_to']) }}" >
          
                  </div>
               </div>

               <div class="col-md-4 related_miltary_1" @if(old('emp_military_id',$data['emp_military_id'])!=1)   style="display: none;" @endif>
                  <div class="form-group">
                     <label>     سلاح الخدمة العسكرية	</label>
                     <input disabled type="text" name="emp_military_wepon" id="emp_military_wepon" class="form-control" value="{{ old('emp_military_wepon',$data['emp_military_wepon']) }}" >
              
                  </div>
               </div>

               <div class="col-md-4 related_miltary_2" @if(old('emp_military_id',$data['emp_military_id'])!=2)   style="display: none;" @endif>
                  <div class="form-group">
                     <label>    تاريخ اعفاء الخدمة العسكرية	</label>
                     <input disabled type="date" name="exemption_date" id="exemption_date" class="form-control" value="{{ old('exemption_date',$data['exemption_date']) }}" >
           
                  </div>
               </div>


               <div class="col-md-4 related_miltary_2" @if(old('emp_military_id',$data['emp_military_id'])!=2)   style="display: none;" @endif>
                  <div class="form-group">
                     <label>    سبب اعفاء الخدمة العسكرية	</label>
                     <input disabled type="text" name="exemption_reason" id="exemption_reason" class="form-control" value="{{ old('exemption_reason',$data['exemption_reason']) }}" >
                     @error('exemption_reason')
                     <span class="text-danger">{{ $message }}</span> 
                     @enderror
                  </div>
               </div>

               <div class="col-md-4 related_miltary_3" @if(old('emp_military_id',$data['emp_military_id'])!=3)   style="display: none;" @endif>
                  <div class="form-group">
                     <label>  سبب ومدة تأجيل الخدمة العسكرية</label>
                     <input disabled type="text" name="postponement_reason" id="postponement_reason" class="form-control" value="{{ old('postponement_reason',$data['postponement_reason']) }}" >
                     @error('postponement_reason')
                     <span class="text-danger">{{ $message }}</span> 
                     @enderror
                  </div>
               </div>


               <div class="col-md-4">
                  <div class="form-group">
                     <label>    هل يمتلك رخصة قيادة</label>
                     <select disabled   name="does_has_Driving_License" id="does_has_Driving_License" class="form-control">
                        <option value="">  اختر الحالة</option>
                     <option   @if(old('graduation_estimate',$data['graduation_estimate'])==1) selected @endif  value="1">نعم </option>
                     <option @if(old('graduation_estimate',$data['graduation_estimate'])==0 and old('graduation_estimate')!="" ) selected @endif value="2">لا</option>
                
                  </select>
                     @error('does_has_Driving_License')
                     <span class="text-danger">{{ $message }}</span> 
                     @enderror
                  </div>
               </div>

               <div class="col-md-4 related_does_has_Driving_License"   @if(old('does_has_Driving_License',$data['does_has_Driving_License'])!=1)   style="display: none;" @endif>
                  <div class="form-group">
                     <label>  رقم رخصة القيادة</label>
                     <input disabled type="text" name="driving_License_degree" id="driving_License_degree" class="form-control" value="{{ old('driving_License_degree',$data['driving_License_degree']) }}" >
                     @error('driving_License_degree')
                     <span class="text-danger">{{ $message }}</span> 
                     @enderror
                  </div>
               </div>
               <div class="col-md-4 related_does_has_Driving_License"  @if(old('does_has_Driving_License',$data['does_has_Driving_License'])!=1)   style="display: none;" @endif>
                  <div class="form-group">
                     <label>  نوع رخصة القيادة</label>
                     <select disabled  name="driving_license_types_id" id="driving_license_types_id" class="form-control select2 ">
                        <option value="">اختر  الحالة </option>
                        @if (@isset($other['driving_license_types']) && !@empty($other['driving_license_types']))
                        @foreach ($other['driving_license_types'] as $info )
                        <option @if(old('driving_license_types_id',$data['driving_license_types_id'])==$info->id) selected="selected" @endif value="{{ $info->id }}"> {{ $info->name }} </option>
                        @endforeach
                        @endif
                     </select>
                     @error('driving_license_types_id')
                     <span class="text-danger">{{ $message }}</span>
                     @enderror
                  </div>
               </div>

               <div class="col-md-4">
                  <div class="form-group">
                     <label>    هل يمتلك  أقارب بالعمل </label>
                     <select disabled   name="has_Relatives" id="has_Relatives" class="form-control">
                        <option value="">  اختر الحالة</option>
                     <option   @if(old('has_Relatives',$data['has_Relatives'])==1) selected @endif  value="1">نعم </option>
                     <option @if(old('has_Relatives',$data['has_Relatives'])==0 and old('has_Relatives',$data['has_Relatives'])!="" ) selected @endif value="2">لا</option>
                
                  </select>
                     @error('has_Relatives')
                     <span class="text-danger">{{ $message }}</span> 
                     @enderror
                  </div>
               </div>

               <div class="col-md-8 Related_Relatives_detailsDiv"   @if(old('has_Relatives',$data['has_Relatives'])!=1)   style="display: none;" @endif>
                  <div class="form-group">
                     <label> تفاصيل الاقارب</label>
                     <textarea disabled  type="text" name="Relatives_details" id="Relatives_details" class="form-control" >
                        {{ old('Relatives_details',$data['Relatives_details']) }}

                     </textarea>
                     @error('Relatives_details')
                     <span class="text-danger">{{ $message }}</span> 
                     @enderror
                  </div>
               </div>

               <div class="col-md-4">
                  <div class="form-group">
                     <label>    هل يمتلك اعاقة / عمليات سابقة </label>
                     <select disabled   name="is_Disabilities_processes" id="is_Disabilities_processes" class="form-control">
                        <option value="">  اختر الحالة</option>
                     <option   @if(old('is_Disabilities_processes',$data['is_Disabilities_processes'])==1) selected @endif  value="1">نعم </option>
                     <option @if(old('is_Disabilities_processes',$data['is_Disabilities_processes'])==0 and old('is_Disabilities_processes')!="" ) selected @endif value="0">لا</option>
                
                  </select>
                     @error('is_Disabilities_processes')
                     <span class="text-danger">{{ $message }}</span> 
                     @enderror
                  </div>
               </div>

               <div class="col-md-8 Related_is_Disabilities_processesDiv" @if(old('is_Disabilities_processes',$data['is_Disabilities_processes'])!=1)   style="display: none;" @endif>
                  <div class="form-group">
                     <label> تفاصيل الاعاقة / عمليات سابقة</label>
                     <textarea disabled  type="text" name="Disabilities_processes" id="Disabilities_processes" class="form-control" >
                        {{ old('Disabilities_processes',$data['Disabilities_processes']) }}

                     </textarea>
                     @error('Disabilities_processes')
                     <span class="text-danger">{{ $message }}</span> 
                     @enderror
                  </div>
               </div>
               <div class="col-md-12 " >
                  <div class="form-group">
                     <label> ملاحظات علي الموظف </label>
                     <textarea disabled  type="text" name="notes" id="notes" class="form-control" >
                        {{ old('notes',$data['notes']) }}

                     </textarea>
                     @error('notes')
                     <span class="text-danger">{{ $message }}</span> 
                     @enderror
                  </div>
               </div>

           </div>

         </div>
        <div class="tab-pane fade @if(Session::has('jobs_data')) show  active @endif" id="custom-content-jobs_data" role="tabpanel" aria-labelledby="jobs_data">
            <br>
            <div class="row">
            <div class="col-md-4 " >
               <div class="form-group">
                  <label>   تاريخ التعيين </label>
                  <input disabled type="date" name="emp_start_date" id="emp_start_date" class="form-control" value="{{ old('emp_start_date',$data['emp_start_date']) }}" >
                  @error('emp_start_date')
                  <span class="text-danger">{{ $message }}</span> 
                  @enderror
               </div>
            </div>

            <div class="col-md-4">
               <div class="form-group">
                  <label>    الحالة الوظيفية </label>
                  <select disabled   name="Functiona_status" id="Functiona_status" class="form-control">
                   <option value="">اختر الحاله</option>  
                  <option   @if(old('Functiona_status',$data['Functiona_status'])==1) selected @endif  value="1">يعمل</option>
                  <option @if(old('Functiona_status',$data['Functiona_status'])==0 and old('Functiona_status')!="" ) selected @endif value="0">خارج الخدمة</option>
             
               </select>
                  @error('Functiona_status')
                  <span class="text-danger">{{ $message }}</span> 
                  @enderror
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group">
                  <label>   الادارة التابع لها الموظف </label>
                  <select disabled  name="emp_Departments_code" id="emp_Departments_code" class="form-control select2 ">
                     <option value="">اختر الادارة</option>
                     @if (@isset($other['departements']) && !@empty($other['departements']))
                     @foreach ($other['departements'] as $info )
                     <option @if(old('emp_Departments_code',$data['emp_Departments_code'])==$info->id) selected="selected" @endif value="{{ $info->id }}"> {{ $info->name }} </option>
                     @endforeach
                     @endif
                  </select>
                  @error('emp_Departments_code')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
            </div>

            <div class="col-md-4">
               <div class="form-group">
                  <label> وظيفة الموظف </label>
                  <select disabled  name="emp_jobs_id" id="emp_jobs_id" class="form-control select2 ">
                     <option value="">اختر الوظيفة</option>
                     @if (@isset($other['jobs']) && !@empty($other['jobs']))
                     @foreach ($other['jobs'] as $info )
                     <option @if(old('emp_jobs_id',$data['emp_jobs_id'])==$info->id) selected="selected" @endif value="{{ $info->id }}"> {{ $info->name }} </option>
                     @endforeach
                     @endif
                  </select>
                  @error('emp_jobs_id')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
            </div>

            <div class="col-md-4">
               <div class="form-group">
                  <label>  هل  له بصمة حضور وانصراف </label>
                  <select disabled   name="does_has_ateendance" id="does_has_ateendance" class="form-control">
                  <option   @if(old('does_has_ateendance',$data['does_has_ateendance'])==1) selected @endif  value="1">نعم</option>
                  <option @if(old('does_has_ateendance',$data['does_has_ateendance'])==0 and old('does_has_ateendance')!="" ) selected @endif value="0"> لا </option>
             
               </select>
                  @error('does_has_ateendance')
                  <span class="text-danger">{{ $message }}</span> 
                  @enderror
               </div>
            </div>

            <div class="col-md-4">
               <div class="form-group">
                  <label>  هل  له شفت ثابت </label>
                  <select disabled   name="is_has_fixced_shift" id="is_has_fixced_shift" class="form-control">
                     <option value="">اختر الحالة</option>
                  <option   @if(old('is_has_fixced_shift',$data['is_has_fixced_shift'])==1) selected @endif  value="1">نعم</option>
                  <option @if(old('is_has_fixced_shift',$data['is_has_fixced_shift'])==0 or old('is_has_fixced_shift')!="" ) selected @endif value="0"> لا </option>
             
               </select>
                  @error('is_has_fixced_shift')
                  <span class="text-danger">{{ $message }}</span> 
                  @enderror
               </div>
            </div>

            <div class="col-md-4 relatedfixced_shift"  @if(old('is_has_fixced_shift',$data['is_has_fixced_shift'])==0) style="display: none;" @endif>
               <div class="form-group">
                  <label>       أنواع الشفتات </label>
                  <select disabled  name="shift_type_id" id="shift_type_id" class="form-control select2 ">
                     <option value="">اختر الشفت </option>
                     @if (@isset($other['shifts_types']) && !@empty($other['shifts_types']))
                     @foreach ($other['shifts_types'] as $info )
                     <option @if(old('shift_type_id',$data['shift_type_id'])==$info->id) selected="selected" @endif value="{{ $info->id }}"> 
                        
                        @if($info->type==1) صباحي @elseif ($info->type==2) مسائي @else يوم كامل @endif
                        من
                        @php
                        $dt=new DateTime($info->from_time);
                        $time=$dt->format("h:i");
                        $newDateTime=date("A",strtotime($info->from_time));
                        $newDateTimeType= (($newDateTime=='am'||$newDateTime=='AM')?'صباحا ':'مساء'); 
                        @endphp
                       
                        {{ $time }}
                        {{ $newDateTimeType }}  
                        الي
                        @php
                        $dt=new DateTime($info->to_time);
                        $time=$dt->format("h:i");
                        $newDateTime=date("A",strtotime($info->to_time));
                        $newDateTimeType= (($newDateTime=='am'||$newDateTime=='AM')?'صباحا ':'مساء'); 
                        @endphp
                       
                        {{ $time }}
                        {{ $newDateTimeType }}  
                     عدد
                     {{ $info->total_hour*1  }} ساعات
                     
                     
                     
                     
                     </option>
                     @endforeach
                     @endif
                  </select>
                  @error('shifts_types_id')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
            </div>
            <div class="col-md-4" id="daily_work_hourDiv"   @if(old('is_has_fixced_shift',$data['is_has_fixced_shift'])==1) style="display: none;" @endif>
               <div class="form-group">
                  <label>       عدد ساعات العمل اليومي </label>
                  <input disabled type="text" name="daily_work_hour" id="daily_work_hour" oninput disabled="this.value=this.value.replace(/[^0-9.]/g,'');" class="form-control" value="{{ old('daily_work_hour',$data['daily_work_hour']) }}" >
                  @error('daily_work_hour')
                  <span class="text-danger">{{ $message }}</span> 
                  @enderror
               </div>
            </div>
            <div class="col-md-4" >
               <div class="form-group">
                  <label>     راتب الموظف الشهري </label>
                  <button id="showSalaryArchive" class="btn btn-sm btn-success" data-id="{{ $data['id'] }}">عرض الارشيف </button>
                  <input disabled type="text" name="emp_sal" id="emp_sal" oninput disabled="this.value=this.value.replace(/[^0-9.]/g,'');" class="form-control" value="{{ old('emp_sal',$data['emp_sal']*1) }}" >
                  @error('emp_sal')
                  <span class="text-danger">{{ $message }}</span> 
                  @enderror
               </div>
            </div>

            <div class="col-md-4">
               <div class="form-group">
                  <label>  هل  له حافز </label>
                  <select disabled   name="MotivationType" id="MotivationType" class="form-control">
                     <option value="">اختر الحالة</option>
                  <option   @if(old('MotivationType',$data['MotivationType'])==1) selected @endif  value="1">ثابت</option>
                  <option   @if(old('MotivationType',$data['MotivationType'])==2) selected @endif  value="2">متغير</option>
                  <option   @if(old('MotivationType',$data['MotivationType'])==0 or old('MotivationType')!="" ) selected @endif value="0"> لايوجد </option>
             
               </select>
                  @error('MotivationType')
                  <span class="text-danger">{{ $message }}</span> 
                  @enderror
               </div>
            </div>
            <div class="col-md-4 " id="MotivationDIV" @if(old('MotivationType',$data['MotivationType'])!=1) style="display: none;" @endif >
               <div class="form-group">
                  <label> قيمة الحافز الشهري الثابت </label>
                  <input disabled type="text" name="Motivation" id="Motivation" oninput disabled="this.value=this.value.replace(/[^0-9.]/g,'');" class="form-control" value="{{ old('Motivation',$data['Motivation']*1) }}" >
                  @error('Motivation')
                  <span class="text-danger">{{ $message }}</span> 
                  @enderror
               </div>
            </div>


            <div class="col-md-4">
               <div class="form-group">
                  <label>  هل  له تأمين اجتماعي  </label>
                  <select disabled   name="isSocialnsurance" id="isSocialnsurance" class="form-control">
                     <option value="">اختر الحالة</option>
                  <option   @if(old('isSocialnsurance',$data['isSocialnsurance'])==1) selected @endif  value="1">نعم</option>
                  <option @if(old('isSocialnsurance',$data['isSocialnsurance'])==0 and old('isSocialnsurance')!="" ) selected @endif value="0"> لا </option>
             
               </select>
                  @error('isSocialnsurance')
                  <span class="text-danger">{{ $message }}</span> 
                  @enderror
               </div>
            </div>
            <div class="col-md-4 relatedisSocialnsurance" " @if(old('isSocialnsurance',$data['isSocialnsurance'])!=1) style="display: none;" @endif  >
               <div class="form-group">
                  <label> قيمة التأمين المستقطع شهرياً </label>
                  <input disabled type="text" name="Socialnsurancecutmonthely" id="Socialnsurancecutmonthely" oninput disabled="this.value=this.value.replace(/[^0-9.]/g,'');" class="form-control" value="{{ old('Socialnsurancecutmonthely',$data['Socialnsurancecutmonthely']*1) }}" >
                  @error('Socialnsurancecutmonthely')
                  <span class="text-danger">{{ $message }}</span> 
                  @enderror
               </div>
            </div>

            <div class="col-md-4 relatedisSocialnsurance" " @if(old('isSocialnsurance',$data['isSocialnsurance'])!=1) style="display: none;" @endif  >
               <div class="form-group">
                  <label> رقم التامين الاجتماعي للموظف </label>
                  <input disabled type="text" name="SocialnsuranceNumber" id="SocialnsuranceNumber" class="form-control" value="{{ old('SocialnsuranceNumber',$data['SocialnsuranceNumber']) }}" >
                  @error('SocialnsuranceNumber')
                  <span class="text-danger">{{ $message }}</span> 
                  @enderror
               </div>
            </div>

            <div class="col-md-4">
               <div class="form-group">
                  <label>  هل  له تأمين طبي  </label>
                  <select disabled   name="ismedicalinsurance" id="ismedicalinsurance" class="form-control">
                     <option value="">اختر الحالة</option>
                  <option   @if(old('ismedicalinsurance',$data['ismedicalinsurance'])==1) selected @endif  value="1">نعم</option>
                  <option @if(old('ismedicalinsurance',$data['ismedicalinsurance'])==0 and old('ismedicalinsurance')!="" ) selected @endif value="0"> لا </option>
             
               </select>
                  @error('ismedicalinsurance')
                  <span class="text-danger">{{ $message }}</span> 
                  @enderror
               </div>
            </div>
            <div class="col-md-4 relatedismedicalinsurance" " @if(old('ismedicalinsurance',$data['ismedicalinsurance'])!=1) style="display: none;" @endif  >
               <div class="form-group">
                  <label> قيمة التأمين الطبي المستقطع شهرياً  </label>
                  <input disabled type="text" name="medicalinsurancecutmonthely" id="medicalinsurancecutmonthely" oninput disabled="this.value=this.value.replace(/[^0-9.]/g,'');" class="form-control" value="{{ old('medicalinsurancecutmonthely',$data['medicalinsurancecutmonthely']*1) }}" >
                  @error('medicalinsurancecutmonthely')
                  <span class="text-danger">{{ $message }}</span> 
                  @enderror
               </div>
            </div>

            <div class="col-md-4 relatedismedicalinsurance" " @if(old('ismedicalinsurance',$data['ismedicalinsurance'])!=1) style="display: none;" @endif  >
               <div class="form-group">
                  <label> رقم التامين الطبي للموظف  </label>
                  <input disabled type="text" name="medicalinsuranceNumber" id="medicalinsuranceNumber" class="form-control" value="{{ old('medicalinsuranceNumber',$data['medicalinsuranceNumber']) }}" >
                  @error('medicalinsuranceNumber')
                  <span class="text-danger">{{ $message }}</span> 
                  @enderror
               </div>
            </div>
            
            <div class="col-md-4">
               <div class="form-group">
                  <label> نوع صرف راتب الموظف  </label>
                  <select disabled   name="sal_cach_or_visa" id="sal_cach_or_visa" class="form-control">
                     <option value="">اختر الحالة</option>
                  <option   @if(old('sal_cach_or_visa',$data['sal_cach_or_visa'])==1) selected @endif  value="1">كاش</option>
                  <option   @if(old('sal_cach_or_visa',$data['sal_cach_or_visa'])==2) selected @endif  value="2">فيزا</option>
             
               </select>
                  @error('sal_cach_or_visa')
                  <span class="text-danger">{{ $message }}</span> 
                  @enderror
               </div>
            </div>

            <div class="col-md-4">
               <div class="form-group">
                  <label> هل له رصيد اجازات سنوي  </label>
                  <select disabled   name="is_active_for_Vaccation" id="is_active_for_Vaccation" class="form-control">
                     <option value="">اختر الحالة</option>
                  <option   @if(old('is_active_for_Vaccation',$data['is_active_for_Vaccation'])==1) selected @endif  value="1">نعم له سنوى</option>
                  <option   @if(old('is_active_for_Vaccation',$data['is_active_for_Vaccation'])==2) selected @endif  value="2">نعم له رصيد عن طريق ايام الحصور</option>
                  <option   @if(old('is_active_for_Vaccation',$data['is_active_for_Vaccation'])==0 and old('is_active_for_Vaccation')!=""  ) selected @endif  value="0">ليس له رصيد</option>
               </select>
                  @error('is_active_for_Vaccation')
                  <span class="text-danger">{{ $message }}</span> 
                  @enderror
               </div>
            </div>

            <div class="col-md-4 " >
               <div class="form-group">
                  <label>  شخص يمكن الرجوع اليه للضرورة  	</label>
                  <input disabled type="text" name="urgent_person_details" id="urgent_person_details" class="form-control" value="{{ $data['urgent_person_details'] }}" >
                  @error('urgent_person_details')
                  <span class="text-danger">{{ $message }}</span> 
                  @enderror
               </div>
            </div>
         <div class="col-md-4">
               <div class="form-group">
                  <label>  هل  له بدلات  ثابتة  </label>
                  <select disabled   name="Does_have_fixed_allowances" id="Does_have_fixed_allowances" class="form-control">
                     <option value="">اختر الحالة</option>
                  <option   @if(old('Does_have_fixed_allowances',$data['Does_have_fixed_allowances'])==1) selected @endif  value="1">نعم</option>
                  <option @if(old('Does_have_fixed_allowances',$data['Does_have_fixed_allowances'])==0 and old('Does_have_fixed_allowances')!="" ) selected @endif value="0"> لا </option>
             
               </select>
                  @error('Does_have_fixed_allowances')
                  <span class="text-danger">{{ $message }}</span> 
                  @enderror
               </div>
            </div>
            @if($data['Does_have_fixed_allowances']==1)
               <div class="col-md-12">
               <hr>
               <h3  style="width: 100%;font-size:17px;font-weight:bold;
        text-align: center !important;">
          البدلات الثابتة المضافه للموظف 
            
         </h3>
           <br>   
          <button style="margin: 4px;" id="load_add_allowances_model" data-toggle="modal"  data-target="#AddallowancesModel" class="btn btn-sm btn-success">اضافة بدل للموظف<i class="fa fa-arrow-up"></i></button> 

       @if(@isset($data['employees_fixed_suits']) and !@empty($data['employees_fixed_suits']) and count($data['employees_fixed_suits'])>0 )
         <table id="example2" class="table table-bordered table-hover">
            <thead class="custom_thead">
              
               <th>أسم البدل</th>
               <th> قيمة البدل</th>
               <th> تاريخ الاضافة</th>
               <th> تاريخ التحديث</th>
               <th> الاجراءات </th>
            </thead>
            <tbody>
               @foreach ( $data['employees_fixed_suits'] as $info )
               <tr>
                 
                  <td>{{ $info->allowances->name}}</td>
                  <td>{{ $info->value*1}}</td>
                   <td>
                     @php
                     $dt=new DateTime($info->created_at);
                     $date=$dt->format("Y-m-d");
                     $time=$dt->format("h:i");
                     $newDateTime=date("a",strtotime($info->created_at));
                     $newDateTimeType= (($newDateTime=='am'||$newDateTime=='AM')?'صباحا ':'مساء'); 
                     @endphp
                     {{ $date }} <br>
                     {{ $time }}
                     {{ $newDateTimeType }}  <br>
                     {{ $info->added->name }} 
                  </td>
                  <td>
                     @if($info->updated_by>0)
                     @php
                     $dt=new DateTime($info->updated_at);
                     $date=$dt->format("Y-m-d");
                     $time=$dt->format("h:i");
                     $newDateTime=date("a",strtotime($info->updated_at));
                     $newDateTimeType= (($newDateTime=='am'||$newDateTime=='AM')?'صباحا ':'مساء'); 
                     @endphp
                     {{ $date }}  <br>
                     {{ $time }}
                     {{ $newDateTimeType }}  <br>
                     {{ $info->updatedby->name }} 
                     @else
                     لايوجد
                     @endif
                  </td>
              
                    <td>    
                     <a  href="{{ route('Employees.destroy_allowances',$info->id) }}" class="btn are_you_shur  btn-danger btn-sm">حذف</a>
                     <button data-id="{{ $info->id }}"  class="btn btn-primary load_edit_allowances btn-sm">تعديل </a>
                  </td>
               </tr>
               @endforeach
            </tbody>
         </table>
         <br>
         @else
         <p class="bg-danger text-center"> عفوا لاتوجد بيانات لعرضها</p>
         @endif

     
            </div>
        
        @endif
         </div>
         </div>
          <div class="tab-pane fade @if(Session::has('tabfiles')) show active @endif  " id="custom-content-addtional_data" role="tabpanel" aria-labelledby="addtional_data">
            <br>
            <div class="row">
            <div class="col-md-4 " >
               <div class="form-group">
                  <label>  اسم الكفيل 	</label>
                  <input disabled type="text" name="emp_cafel" id="emp_cafel" class="form-control" value="{{ $data['emp_cafel'] }}" >
                  @error('emp_cafel')
                  <span class="text-danger">{{ $message }}</span> 
                  @enderror
               </div>
            </div>
            <div class="col-md-4 " >
               <div class="form-group">
                  <label>   رقم الباسبور ان وجد 	</label>
                  <input disabled type="text" name="emp_pasport_no" id="emp_pasport_no" class="form-control" value="{{ $data['emp_pasport_no'] }}" >
                  @error('emp_pasport_no')
                  <span class="text-danger">{{ $message }}</span> 
                  @enderror
               </div>
            </div>

            <div class="col-md-4 " >
               <div class="form-group">
                  <label>جهة اصدار الباسبور	</label>
                  <input disabled type="text" name="emp_pasport_from" id="emp_pasport_from" class="form-control" value="{{$data['emp_pasport_from'] }}" >
                  @error('emp_pasport_from')
                  <span class="text-danger">{{ $message }}</span> 
                  @enderror
               </div>
            </div>
            <div class="col-md-4 " >
               <div class="form-group">
                  <label>  تاريخ انتهاء الباسبور	</label>
                  <input disabled type="date" name="emp_pasport_exp" id="emp_pasport_exp" class="form-control" value="{{ old('emp_pasport_exp',$data['emp_pasport_exp']) }}" >
                  @error('emp_pasport_exp')
                  <span class="text-danger">{{ $message }}</span> 
                  @enderror
               </div>
            </div>

            <div class="col-md-8">
               <div class="form-group">
                  <label>    عنوان اقامة الموظف في بلده الام	</label>
                  <input disabled type="text" name="emp_Basic_stay_com" id="emp_Basic_stay_com" class="form-control" value="{{ old('emp_Basic_stay_com',$data['emp_Basic_stay_com']) }}" >
                  @error('emp_Basic_stay_com')
                  <span class="text-danger">{{ $message }}</span> 
                  @enderror
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group">
                  <label>   الصورة الشخصية للموظف</label>
                 
                    @if(!empty($data['emp_photo']))
                     <img src="{{ asset('assets/admin/uploads').'/' . $data['emp_photo'] }}" border-raduis: 50%; width="80px"; height="80px"; class="rounded-circle" alt="صورة الموظف">
                     <br>
                     <a  href="{{ route('Employees.download',['id'=>$data['id'],'field_name'=>'emp_photo']) }}" class="btn btn-info btn-sm">تحميل <span class="fa fa-download"></span></a>
                     @else
                     @if($data['emp_gender']==1)
                    <img src="{{ asset('assets/admin/imgs/male.png') }}" border-raduis: 50%; width="80px"; height="80px"; class="rounded-circle" alt="صورة الموظف">
                     @else
                  <img src="{{ asset('assets/admin/imgs/female.png')}}" border-raduis: 50%; width="80px"; height="80px"; class="rounded-circle" alt="صورة الموظف">
                     @endif
                     @endif
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group">
                  <label>  هل  له سماحيه الدخول الى النظام   </label>
                  <select disabled   name="is_active_login_system" id="is_active_login_system" class="form-control">
                     <option value="">اختر الحالة</option>
                  <option   @if(old('is_active_login_system',$data['is_active_login_system'])==1) selected @endif  value="1">نعم</option>
                  <option @if(old('is_active_login_system',$data['is_active_login_system'])==0 and old('is_active_login_system')!="" ) selected @endif value="0"> لا </option>
             
               </select>
                  @error('is_active_login_system')
                  <span class="text-danger">{{ $message }}</span> 
                  @enderror
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group">
                  <label>     السيرة الذاتية للموظف</label>
                  <input disabled type="file" name="emp_CV" id="emp_CV" class="form-control" value="{{ $data['emp_CV'] }}" >
                  <br>
                 @if(!empty($data['emp_CV']))
                  <a  href="{{ route('Employees.download',['id'=>$data['id'],'field_name'=>'emp_CV']) }}" class="btn btn-info btn-sm">تحميل <span class="fa fa-download"></span></a>
                 @else
                 لم يتم الارفاق
                 @endif
               </div>
            </div>
      
            <div class="col-md-12">
               <hr>
               <h3  style="width: 100%;font-size:17px;font-weight:bold;
        text-align: center !important;">
          الملفات المرفقة للموظف   
           
         </h3>

       @if(@isset($other['employees_files']) and !@empty($other['employees_files']) and count($other['employees_files'])>0 )
         <table id="example2" class="table table-bordered table-hover">
            <thead class="custom_thead">
              
               <th>الاسم</th>
               <th> الملف</th>
               <th> الاجراءات</th>
            </thead>
            <tbody>
               @foreach ( $other['employees_files'] as $info )
               <tr>
                 
                  <td>{{ $info->name}}</td>
                  <td>
                     @if(!empty($info->file_path))
                     <img src="{{ asset('assets/admin/uploads').'/' . $info->file_path }}" border-raduis: 50%; width="80px"; height="80px"; class="rounded-circle" alt="صورة الملف">
                     @else
                   لايوجد مرفقات
                     @endif
                  </td>
              
                  <td>
                     
                     <a  href="{{ route('Employees.destroy_files',$info->id) }}" class="btn are_you_shur  btn-danger btn-sm">حذف</a>
                       <a  href="{{ route('Employees.download_files',$info->id) }}" class="btn btn-primary btn-sm">تحميل <span class="fa fa-download"></span></a>
                  </td>
               </tr>
               @endforeach
            </tbody>
         </table>
         <br>
         @else
         <p class="bg-danger text-center"> عفوا لاتوجد بيانات لعرضها</p>
         @endif

         <button id="load_add_file_model" data-toggle="modal"  data-target="#AddfilesModel" class="btn btn-sm btn-success">أرفاق جديد<i class="fa fa-arrow-up"></i></button>
            </div>
         </div>

         </div>
     
        </div>
       
      </div>
      <!-- /.card -->
    </div>
    <!-- /.card -->


            
       
      </div>
   
   
   </div>
</div>
<div class="modal fade " id="AddfilesModel" >
   <div class="modal-dialog modal-xl">
     <div class="modal-content bg-info">
       <div class="modal-header">
         <h4 class="modal-title">اضافة مرفقات للموظف</h4>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times;</span></button>
       </div>
       <div class="modal-body custum_body_modal" >
    <form action="{{ route('Employees.add_files',$data['id']) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     <label>    اسم الملف  <span style="color:red;">*</span></label>
                     <input type="text" name="name" id="name" class="form-control" value="" required  oninvalid="setCustomValidity('ادخل هذا الحقل ')" onchange="try(setCustomValidity(''))">
               </div>
              </div>
            <div class="col-md-4">
               <div class="form-group">
                  <label> أختر الملف </label>
                  <input type="file" name="the_file" id="the_file" class="form-control" value="" required  oninvalid="setCustomValidity('ادخل هذا الحقل ')" onchange="try(setCustomValidity(''))">
               </div>
            </div>
             <div class="col-md-2">
               <div class="form-group text-center">
                  <button style="margin-top: 31px;" class="btn btn-sm btn-success" type="submit" name="submit">اضف الملف </button>
                  
               </div>
            </div>
        </div>
    </form>   
       </div>
       <div class="modal-footer justify-content-between">
         <button type="button" class="btn btn-outline-light" data-dismiss="modal">اغلاق</button>
         <button type="button" class="btn btn-outline-light">Save changes</button>
       </div>
     </div>
     <!-- /.modal-content -->
   </div>
   <!-- /.modal-dialog -->
 </div>
  @if($data['Does_have_fixed_allowances']==1)
 <div class="modal fade " id="AddallowancesModel" >
   <div class="modal-dialog modal-xl">
     <div class="modal-content bg-info">
       <div class="modal-header">
         <h4 class="modal-title">اضافة بدلات ثابتة للموظف</h4>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times;</span></button>
       </div>
       <div class="modal-body custum_body_modal" >
    <form action="{{ route('Employees.add_allowances',$data['id']) }}" method="post">
            @csrf
            <div class="row">
               <div class="col-md-4">
               <div class="form-group">
                  <label>  بيانات البدلات </label>
                  <select   name="allowances_id" id="allowances_id" class="form-control select2 ">
                     <option value="">اختر البدل</option>
                     @if (@isset($other['allowances']) && !@empty($other['allowances']))
                     @foreach ($other['allowances'] as $info )
                     <option  selected="selected" value="{{ $info->id }}"> {{ $info->name }} </option>
                     @endforeach
                     @endif
                  </select>
              
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group">
                  <label> قيمة البدل الثابت </label>
                  <input type="text" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="allowances_value" id="allowances_value" class="form-control" value="" required >
               </div>
            </div>
             <div class="col-md-2">
               <div class="form-group text-center">
                  <button id="do_add_allowances" style="margin-top: 31px;" class="btn btn-sm btn-success" type="submit" name="submit">اضف البدل الثابت </button>
                  
               </div>
            </div>
        </div>
    </form>   
       </div>
       <div class="modal-footer justify-content-between">
         <button type="button" class="btn btn-outline-light" data-dismiss="modal">اغلاق</button>
         <button type="button" class="btn btn-outline-light">Save changes</button>
       </div>
     </div>
     <!-- /.modal-content -->
   </div>
   <!-- /.modal-dialog -->
 </div>

 <div class="modal fade " id="fixedSuitModalUpdate" >
   <div class="modal-dialog modal-xl">
     <div class="modal-content bg-info">
       <div class="modal-header">
         <h4 class="modal-title">تحديث  البدلات الثابتة للموظفين    </h4>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times;</span></button>
       </div>
       <div class="modal-body" id="fixedSuitModalUpdateBody" style="background-color: white; color:black;">
       
       </div>
     </div>  
       <div class="modal-footer justify-content-between">
         <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
       </div>
     </div>
     <!-- /.modal-content -->
   </div>
   <!-- /.modal-dialog -->
 </div>

 <div class="modal fade " id="showSalaryArchiveModel" >
   <div class="modal-dialog modal-xl">
     <div class="modal-content bg-info">
       <div class="modal-header">
         <h4 class="modal-title">عرض سجلات ارشيف الراتب للموظف</h4>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times;</span></button>
       </div>
       <div class="modal-body" id="showSalaryArchiveModelBody" style="background-color:white;color:black;"">
  
       </div>
       <div class="modal-footer justify-content-between">
         <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
       </div>
     </div>
     <!-- /.modal-content -->
   </div>
   <!-- /.modal-dialog -->
 </div>

 @endif
@endsection
@section("script")
<script src="{{ asset('assets/admin/plugins/select2/js/select2.full.min.js') }}"> </script>
<script>
   //Initialize Select2 Elements
   $('.select2').select2({
     theme: 'bootstrap4'
   });

   $(document).on('click','#do_add_allowances',function(e){
     var allowances_id=$("#allowances_id").val();
     if(allowances_id==""){
      alert("من فضلك اختر البدل الثابت")
      $("#allowances_id").focus();
      return false;
     }

     var allowances_value=$("#allowances_value").val();
     if(allowances_value==""||allowances_value==0){
      alert("من فضلك ادخل قيمة البدل الثابت")
      $("#allowances_value").focus();
      return false;
     }

      });

      $(document).on('click','#do_edit_allowances',function(e){
      var allowances_value=$("#allowances_value_edit").val();
      if(allowances_value==""||allowances_value==0){
         alert("من فضلك ادخل قيمة البدل الثابت")
         $("#allowances_value_edit").focus();
         return false;
      }

      });


    $(document).on('click','.load_edit_allowances',function(e){
   
    var id=$(this).data("id");
    
        
      jQuery.ajax({
      url:'{{ route('Employees.load_edit_allowances') }}',
      type:'post',
      'dataType':'html',
      cache:false,
      data:{"_token":'{{ csrf_token() }}',id:id},
      success: function(data){
      $("#fixedSuitModalUpdateBody").html(data);
      $("#fixedSuitModalUpdate").modal("show");
      $('.select2').select2();
      },
      error:function(){
      alert("عفوا لقد حدث خطأ ");
      }
      
      });
   });

      
    $(document).on('click','#showSalaryArchive',function(e){
   
    var id=$(this).data("id");
    
      jQuery.ajax({
      url:'{{ route('Employees.showSalaryArchive') }}',
      type:'post',
      'dataType':'html',
      cache:false,
      data:{"_token":'{{ csrf_token() }}',id:id},
      success: function(data){
      $("#showSalaryArchiveModelBody").html(data);
      $("#showSalaryArchiveModel").modal("show");
      $('.select2').select2();
      },
      error:function(){
      alert("عفوا لقد حدث خطأ ");
      }
      
      });


 });

 

</script>
@endsection

 
