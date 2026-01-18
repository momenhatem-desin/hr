@extends('layouts.admin')
@section('title')
التسويات 
@endsection
@section('contentheader')
قائمة التسويات
@endsection
@section("css")
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection
@section('contentheaderactivelink')
<a href="{{ route('main_EmployessInvestigations.index') }}"> التسويات</a>
@endsection
@section('contentheaderactive')
عرض
@endsection
@section('content')
<style>
.modal-xl{
   max-width: 100%;
   margin: 0 auto;
   padding:0 !important;
}
</style>
<div class="col-12">
   <div class="card">
      <div class="card-header">
         <h3 class="card-title card_title_center">   بيانات  التسويات   للشهر المالى({{ $finance_cln_periods_data['month']->name }}لسنة {{ $finance_cln_periods_data['FINANCE_YR'] }})

         </h3>
      
       @if($finance_cln_periods_data['is_open']==1)<br>
         <button  class="btn btn-sm btn-success" data-toggle="modal" data-target="#AddModal">اضافة جديد</button>
         @endif
      </div>
      <form method="POST" action="{{ route('MainEmployeeSettlements.print_search') }}" target="_blank">
         @csrf
         <input type="hidden" id="the_finance_cln_periods_id" name="the_finance_cln_periods_id" value="{{ $finance_cln_periods_data['id'] }}">
      <div class="row" style="padding: 5px;">
               <div class="col-md-3">
                  <div class="form-group">
                     <label>  بحث بالموظفين </label>
                     <select name="employees_code_search" id="employees_code_search" class="form-control select2 ">
                        <option value="all"> بحث بالكل  </option>
                        @if (@isset($employess_for_search) && !@empty($employess_for_search))
                        @foreach ($employess_for_search as $info )
                        <option value="{{ $info->employees_code }}"> {{ $info->emp_name }} ({{ $info->employees_code }}) </option>
                        @endforeach
                        @endif
                     </select>
               
                        </div>
                     </div>
           

            <div class="col-md-2">
               <div class="form-group">
                
              <button type="post" class="btn btn_sm btn-info custom_button">طباعة البحث </button>

               </select>
               </div>
            </div>
      </div>
   </form> 
   <div class="card-body" id="ajax_responce_serachDiv" style="padding: 0px 5px">
      @if($data->count() > 0)
           <table id="example2" class="table table-bordered table-hover">
         <thead class="custom_thead">
            <th> اسم الموظف </th>
            <th> الاستحقاقات</th>
            <th> استقطاعات</th>
            <th>  التسويات</th>
            <th> ملاحظات</th>
            <th>   الاضافة</th>
            <th>  التحديث</th>
             <th> الحالة</th>
             <th> الاجراءات</th>
         </thead>
         <tbody>
            @foreach ( $data as $info )
            <tr>
               <td> {{ $info->emp_name }} </td>
                <td> {{ $info->total_for*1 }} </td>
                <td> {{ $info->total_on*1}} </td>
                 <td> {{ $info->final_total*1}} </td>
               <td> {{ $info->notes}} </td>
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
               <td> @if($info->is_archived==1) مؤرشف @else   مفتوح  @endif </td>
                  <td>
                     @if($info->is_archived==0)
                     <button data-id="{{ $info->id }}"  class="btn  load_edit_this_row  btn-primary btn-sm">تعديل</button>
                     <button data-id="{{ $info->id }}"  class="btn  delete_this_row  btn-danger btn-sm">حذف</button>
                     @endif
                  </td>
            </tr>
            @endforeach
         </tbody>
      </table>
         <br>
         <div class="col-md-12 text-center">
            {{ $data->links('pagination::bootstrap-5') }}
         </div>
     @else
    <p class="bg-danger text-center"> عفوا لاتوجد بيانات لعرضها</p>
     @endif
      </div>
   </div>
</div>

<div class="modal fade " id="AddModal" >
   <div class="modal-dialog modal-xl">
     <div class="modal-content bg-info">
       <div class="modal-header">
         <h4 class="modal-title">أضافة تسويه جديده بالشهر المالى </h4>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times;</span></button>
       </div>
       <div class="modal-body" id="AddModalBody" style="background-color: white; color:black;">
            <form id="addTaswiaForm" action="{{route('MainEmployeeSettlements.store') }} " method="POST" >
                @csrf
                    <input type="hidden" id="finance_cln_periods_id" name="finance_cln_periods_id" value="{{ $finance_cln_periods_data['id'] }}">
           <div class="row">
           <div class="col-md-3">
                  <div class="form-group">
                     <label>  بيانات الموظفين </label>
                     <select name="employees_code_Add" id="employees_code_Add" class="form-control select2 ">
                        <option value="">اختر  الموظف </option>
                        @if (@isset($employess_for_search) && !@empty($employess_for_search))
                        @foreach ($employess_for_search as $info )
                        <option value="{{$info->employees_code}}" data-s=" {{ $info->emp_sal}}" data-db=" {{  $info->day_price}}"> {{ $info->emp_name}} ({{$info->employees_code}}) </option>
                        @endforeach
                        @endif
                     </select>
               
                  </div>
               </div>
            <div class="col-md-3 relatet_employees_add" style="display: none;" >
               <div class="form-group">
                  <label>   راتب الموظف الشهري</label>
                  <input readonly type="text" name="emp_sal_add" id="emp_sal_add"  class="form-control" value="0" >
               </div>
            </div>   
                <div class="col-md-3 relatet_employees_add" style="display: none;" >
               <div class="form-group">
                  <label>أجر اليوم الواحد</label>
                  <input readonly type="text" name="day_price_add" id="day_price_add"  class="form-control" value="0" >
               </div>
            </div>  
           
     
            <div class="row">
          
            <div class=" col-md-6" style="border: 1px solid lightslategray;
               border-radius: 10px;
               
               ">
               <p class="text-center" style="color: brown;font-size: 16px;">مستحقات</p>
               <hr>
               <div class="row">
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="work_days_for">      عدد ايام عمل  </label>
                     <input name="work_days_for" required  id="work_days_for" value="0" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"    class="form-control caluclateChange" >
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="work_days_total_for">       اجمالي  </label>
                     <input readonly="" required name="work_days_for_total"  id="work_days_for_total" value="0" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"   class="form-control" >
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="extra_days_for">      عدد ايام اضافي  </label>
                     <input name="extra_days_for" required  id="extra_days_for" required value="0" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"    class="form-control caluclateChange" >
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="extra_days_total_for">       اجمالي  </label>
                     <input readonly="" name="extra_days_total_for" required  id="extra_days_total_for" value="0" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"   class="form-control" >
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="absence_back_for">       عدد ايام رد غياب  </label>
                     <input name="absence_back_for"  id="absence_back_for" value="0" required oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"    class="form-control caluclateChange" >
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="absence_back_total_for">       اجمالي  </label>
                     <input readonly="" name="absence_back_total_for" required id="absence_back_total_for" value="0" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"   class="form-control" >
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="sanctions_back_for">       عدد ايام رد جزاء  </label>
                     <input name="sanctions_back_for" required  id="sanctions_back_for" value="0" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"    class="form-control caluclateChange" >
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="sanctions_back_total_for">       اجمالي  </label>
                     <input readonly="" required name="sanctions_back_total_for"  id="sanctions_back_total_for" value="0" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"   class="form-control " >
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="salary_difference_for">        فرق راتب  </label>
                     <input name="salary_difference_for" required id="salary_difference_for" value="0" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"    class="form-control caluclateChange" >
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="award_for">         مكافئة  </label>
                     <input name="award_for"  required id="award_for" value="0" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"    class="form-control caluclateChange" >
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="allowance_for">         بدل  </label>
                     <input name="allowance_for" required  id="allowance_for" value="0" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"    class="form-control caluclateChange" >
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="total_for">       اجمالي الاستحقاقات  </label>
                     <input style="background-color: lightgoldenrodyellow" readonly="" required  name="total_for"  id="total_for" value="0" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"   class="form-control" >
                  </div>
               </div>
            </div>
            </div>
            <div class=" col-md-6" style="border: 1px solid lightslategray;
               border-radius: 10px;
           
               ">
               <p class="text-center" style="color: brown;font-size: 16px;">استقطاعات</p>
               <hr>
               <div class="row">
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="absence_on">         عدد ايام غياب  </label>
                     <input name="absence_on" required  id="absence_on" value="0" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"    class="form-control caluclateChange" >
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="absence_total_on">       اجمالي  </label>
                     <input readonly="" name="absence_total_on" required  id="absence_total_on" value="0" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"   class="form-control" >
                  </div>
               </div>
               </div>
               <div class="row">
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="sanctions_on">         عدد ايام جزاء  </label>
                     <input name="sanctions_on" required  id="sanctions_on" value="0" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"    class="form-control caluclateChange" >
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="sanctions_total_on">       اجمالي  </label>
                     <input readonly=""  required name="sanctions_total_on"  id="sanctions_total_on" value="0" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"   class="form-control" >
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="cash_discount_on">         خصم نقدي  </label>
                     <input name="cash_discount_on" required  id="cash_discount_on" value="0" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"    class="form-control caluclateChange" >
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="allowance_on">            زي   </label>
                     <input name="allowance_on"  required id="allowance_on" value="0" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"    class="form-control caluclateChange" >
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="medical_insurance_on">          تأمين طبي  </label>
                     <input name="medical_insurance_on" required  id="medical_insurance_on" value="0" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"    class="form-control caluclateChange"  >
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="social_insurance_on">          تأمين اجتماعي  </label>
                     <input name="social_insurance_on" required id="social_insurance_on" value="0" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"    class="form-control caluclateChange" >
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="monthly_loan_on">           سلف شهرية  </label>
                     <input name="monthly_loan_on"  required id="monthly_loan_on" value="0" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"    class="form-control caluclateChange" >
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="permanetn_monthly_loan_on">           سلف مستديمة  </label>
                     <input name="permanetn_monthly_loan_on"  required id="permanetn_monthly_loan_on" value="0" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"    class="form-control caluclateChange" >
                  </div>
               </div>
            </div>
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="total_on">       اجمالي استقطاعات  </label>
                     <input style="background-color: lightgoldenrodyellow" readonly="" required name="total_on"  id="total_on" value="0" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"   class="form-control" >
                  </div>
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group  " >
                  <label for="final_total">       صافي اجمالي التسوية  </label>
                  <input style="background-color: lavenderblush" readonly="" required name="final_total"  id="final_total" value="0" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"   class="form-control" >
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group  " >
                  <label for="notes">      ملاحظات</label>
                  <textarea name="notes"   id="notes" class="form-control" ></textarea>
               </div>
            </div>
         
            <div class="clearfix"></div>
            <div class="col-md-12 text-center" style="margin-top: 10px;">
               <hr>
               <button id="do_add"   class="btn btn-success btn-sm" type="submit" >اضافة</button>
               <button type="button" class="btn  text-white btn-danger btn-sm  " data-dismiss="modal">الغاء</button>
            </div>
         </div>
           </div>
         </form>
     
     </div>  
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
        </div>
    </div>
     <!-- /.modal-content -->
   </div>
   <!-- /.modal-dialog -->
 </div>


 <div class="modal fade " id="EditModal" >
   <div class="modal-dialog modal-xl">
     <div class="modal-content bg-info">
       <div class="modal-header">
         <h4 class="modal-title">تحديث تسويات  الموظفين بالشهر المالى </h4>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times;</span></button>
       </div>
       <div class="modal-body" id="EditModalBody" style="background-color: white; color:black;">
       
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

@endsection
@section('script')
<script src="{{ asset('assets/admin/plugins/select2/js/select2.full.min.js') }}"> </script>
<script>
   $(document).ready(function(){ 
     //Initialize Select2 Elements
   $('.select2').select2({
     theme: 'bootstrap4'
   });

  

      
    function do_calculate() {
        var main_salary_record_id = $("#main_salary_record_id_form").val();
        if (main_salary_record_id == "") {
            alert("من فضلك اختر سجل الراتب");
            return false;
        }
        var employees_code = $("#employees_code_Add").val();
        if (employees_code == "") {
            alert("من فضلك ادخل كود  الموظف");
            return false;
        }
       if (!$("#day_price_add").length) {
//            alert("غير قادر للوصول لبيانات الموظف");
            return false;
        }
        var emp_day_price_form = $("#day_price_add").val();
        var work_days_for = $("#work_days_for").val();
        if (work_days_for == "") work_days_for = 0;
        var work_days_for_total = work_days_for * emp_day_price_form * 1;
        $("#work_days_for_total").val(work_days_for_total*1);
        var extra_days_for = $("#extra_days_for").val();
        if (extra_days_for == "") extra_days_for = 0;
        var extra_days_total_for = extra_days_for * emp_day_price_form * 1;
        $("#extra_days_total_for").val(extra_days_total_for*1);
        var absence_back_for = $("#absence_back_for").val();
        if (absence_back_for == "") absence_back_for = 0;
        var absence_back_total_for = absence_back_for * emp_day_price_form * 1;
         absence_back_total_for = parseFloat(absence_back_total_for).toFixed(2);
        $("#absence_back_total_for").val(absence_back_total_for*1);
        var sanctions_back_for = $("#sanctions_back_for").val();
        if (sanctions_back_for == "") sanctions_back_for = 0;
        var sanctions_back_total_for = sanctions_back_for * emp_day_price_form * 1;
        $("#sanctions_back_total_for").val(sanctions_back_total_for*1);
        var salary_difference_for = $("#salary_difference_for").val();
        if (salary_difference_for == "") salary_difference_for = 0;
        var award_for = $("#award_for").val();
        if (award_for == "") award_for = 0;
        var allowance_for = $("#allowance_for").val();
        if (allowance_for == "") allowance_for = 0;
        var total_for = parseFloat(work_days_for_total) + parseFloat(extra_days_total_for) + parseFloat(absence_back_total_for) + parseFloat(sanctions_back_total_for) +
            parseFloat(salary_difference_for) + parseFloat(award_for) + parseFloat(allowance_for);
        total_for = parseFloat(total_for).toFixed(2);
        $("#total_for").val(total_for * 1);
        /*      **/
        var absence_on = $("#absence_on").val();
        if (absence_on == "") absence_on = 0;
        var absence_total_on = absence_on * emp_day_price_form * 1;
        $("#absence_total_on").val(absence_total_on*1);
        var sanctions_on = $("#sanctions_on").val();
    
        if (sanctions_on == "") sanctions_on = 0;
        var sanctions_total_on = sanctions_on * emp_day_price_form * 1;
        $("#sanctions_total_on").val(sanctions_total_on*1);
        var cash_discount_on = $("#cash_discount_on").val();
        if (cash_discount_on == "") cash_discount_on = 0;
         
        var allowance_on = $("#allowance_on").val();
        if (allowance_on == "") allowance_on = 0;
        var medical_insurance_on = $("#medical_insurance_on").val();
        if (medical_insurance_on == "") medical_insurance_on = 0;
        var social_insurance_on = $("#social_insurance_on").val();
        if (social_insurance_on == "") social_insurance_on = 0;
        var monthly_loan_on = $("#monthly_loan_on").val();
        if (monthly_loan_on == "") monthly_loan_on = 0;
        var permanetn_monthly_loan_on = $("#permanetn_monthly_loan_on").val();
        if (permanetn_monthly_loan_on == "") permanetn_monthly_loan_on = 0;
        var total_on = parseFloat(absence_total_on) + parseFloat(sanctions_total_on) + 
         parseFloat(cash_discount_on) + parseFloat(allowance_on) + parseFloat(medical_insurance_on) 
         + parseFloat(social_insurance_on)
         + parseFloat(monthly_loan_on) + parseFloat(permanetn_monthly_loan_on);
   
            total_on = parseFloat(total_on).toFixed(2);  

     
        $("#total_on").val(total_on * 1);
        var final_total = (total_for - total_on);
        final_total = parseFloat(final_total).toFixed(2);
        $("#final_total").val(final_total * 1);
    }
    
    
        function do_calculate_update() {

        var emp_day_price_form = $("#day_price_edit").val();
        var work_days_for = $("#work_days_forEdit").val();
        if (work_days_for == "") work_days_for = 0;
        var work_days_for_total = work_days_for * emp_day_price_form * 1;
        $("#work_days_for_totalEdit").val(work_days_for_total*1);
        var extra_days_for = $("#extra_days_forEdit").val();
        if (extra_days_for == "") extra_days_for = 0;
        var extra_days_total_for = extra_days_for * emp_day_price_form * 1;
        $("#extra_days_total_forEdit").val(extra_days_total_for*1);
        var absence_back_for = $("#absence_back_forEdit").val();
        if (absence_back_for == "") absence_back_for = 0;
        var absence_back_total_for = absence_back_for * emp_day_price_form * 1;
         absence_back_total_for = parseFloat(absence_back_total_for).toFixed(2);
        $("#absence_back_total_forEdit").val(absence_back_total_for*1);
        var sanctions_back_for = $("#sanctions_back_forEdit").val();
        if (sanctions_back_for == "") sanctions_back_for = 0;
        var sanctions_back_total_for = sanctions_back_for * emp_day_price_form * 1;
        $("#sanctions_back_total_forEdit").val(sanctions_back_total_for*1);
        var salary_difference_for = $("#salary_difference_forEdit").val();
        if (salary_difference_for == "") salary_difference_for = 0;
        var award_for = $("#award_forEdit").val();
        if (award_for == "") award_for = 0;
        var allowance_for = $("#allowance_forEdit").val();
        if (allowance_for == "") allowance_for = 0;
        var total_for = parseFloat(work_days_for_total) + parseFloat(extra_days_total_for) + parseFloat(absence_back_total_for) + parseFloat(sanctions_back_total_for) +
            parseFloat(salary_difference_for) + parseFloat(award_for) + parseFloat(allowance_for);
        total_for = parseFloat(total_for).toFixed(2);
        $("#total_forEdit").val(total_for * 1);
        /*      **/
        var absence_on = $("#absence_onEdit").val();
        if (absence_on == "") absence_on = 0;
        var absence_total_on = absence_on * emp_day_price_form * 1;
        $("#absence_total_onEdit").val(absence_total_on*1);
        var sanctions_on = $("#sanctions_onEdit").val();
    
        if (sanctions_on == "") sanctions_on = 0;
        var sanctions_total_on = sanctions_on * emp_day_price_form * 1;
        $("#sanctions_total_onEdit").val(sanctions_total_on*1);
        var cash_discount_on = $("#cash_discount_onEdit").val();
        if (cash_discount_on == "") cash_discount_on = 0;
         
        var allowance_on = $("#allowance_onEdit").val();
        if (allowance_on == "") allowance_on = 0;
        var medical_insurance_on = $("#medical_insurance_onEdit").val();
        if (medical_insurance_on == "") medical_insurance_on = 0;
        var social_insurance_on = $("#social_insurance_onEdit").val();
        if (social_insurance_on == "") social_insurance_on = 0;
        var monthly_loan_on = $("#monthly_loan_onEdit").val();
        if (monthly_loan_on == "") monthly_loan_on = 0;
        var permanetn_monthly_loan_on = $("#permanetn_monthly_loan_onEdit").val();
        if (permanetn_monthly_loan_on == "") permanetn_monthly_loan_on = 0;
        var total_on = parseFloat(absence_total_on) + parseFloat(sanctions_total_on) + 
         parseFloat(cash_discount_on) + parseFloat(allowance_on) + parseFloat(medical_insurance_on) 
         + parseFloat(social_insurance_on)
         + parseFloat(monthly_loan_on) + parseFloat(permanetn_monthly_loan_on);
   
            total_on = parseFloat(total_on).toFixed(2);  

     
        $("#total_onEdit").val(total_on * 1);
        var final_total = (total_for - total_on);
        final_total = parseFloat(final_total).toFixed(2);
        $("#final_totalEdit").val(final_total * 1);
    }
    
    
    
    
    $(document).on('input', '.caluclateChange', function(e) {
        do_calculate();
    });
       
    $(document).on('input', '.caluclateChangeEdit', function(e) {
        do_calculate_update();
    });
   
  

    $(document).on('change','#employees_code_search',function(e){
        ajax_search(); 
      }); 
      
   
   function ajax_search(){
   var employees_code_search=$("#employees_code_search").val();
   var the_finance_cln_periods_id=$("#the_finance_cln_periods_id").val();
   jQuery.ajax({
   url:'{{ route('MainEmployeeSettlements.ajax_search') }}',
   type:'post',
   'dataType':'html',
   cache:false,
   data:{"_token":'{{ csrf_token() }}',employees_code_search:employees_code_search,the_finance_cln_periods_id:the_finance_cln_periods_id},
   success: function(data){
   $("#ajax_responce_serachDiv").html(data);
   },
   error:function(){
   alert("عفوا لقد حدث خطأ ");
   }
   
   });
}
   $(document).on('click','#ajax_pagination_in_search a',function(e){
   e.preventDefault();
   var employees_code_search=$("#employees_code_search").val();
   var the_finance_cln_periods_id=$("#the_finance_cln_periods_id").val();
   var linkurl=$(this).attr("href");
   jQuery.ajax({
   url:linkurl,
   type:'post',
   'dataType':'html',
   cache:false,
   data:{"_token":'{{ csrf_token() }}',employees_code_search:employees_code_search,the_finance_cln_periods_id:the_finance_cln_periods_id},
   success: function(data){
   $("#ajax_responce_serachDiv").html(data);
   },
   error:function(){
   alert("عفوا لقد حدث خطأ ");
   }
   
   });
   
   });

    $(document).on('click','.delete_this_row',function(e){
        var res=confirm("هل متاكد من الحذف ؟");
        if(!res){
         return false;
        }
        
        var id=$(this).data("id");
        var the_finance_cln_periods_id=$("#the_finance_cln_periods_id").val();
        $('#backup_freeze_modal').modal('show'); 
        
      jQuery.ajax({
      url:'{{ route('MainEmployeeSettlements.delete_row') }}',
      type:'post',
      'dataType':'json',
      cache:false,
      data:{"_token":'{{ csrf_token() }}',id:id,the_finance_cln_periods_id:the_finance_cln_periods_id},
      success: function(data){
          ajax_search(); 
         
         setTimeout(function () {
            $("#backup_freeze_modal").modal("hide");
         }, 1000);
      },
      error:function(){
          setTimeout(function () {
            $("#backup_freeze_modal").modal("hide");
         }, 1000);  
      alert("عفوا لقد حدث خطأ ");
      }
      
      });


 });
   

  $(document).on('click','.load_edit_this_row',function(e){
   
    var id=$(this).data("id");
    var the_finance_cln_periods_id=$("#the_finance_cln_periods_id").val();
      jQuery.ajax({
      url:'{{ route('MainEmployeeSettlements.load_edit_row') }}',
      type:'post',
      'dataType':'html',
      cache:false,
      data:{"_token":'{{ csrf_token() }}',id:id,the_finance_cln_periods_id:the_finance_cln_periods_id},
      success: function(data){
      $("#EditModalBody").html(data);
      $("#EditModal").modal("show");
      $("#AddModalBody").html("");
      $('.select2').select2();
      },
      error:function(){
      alert("عفوا لقد حدث خطأ ");
      }
      
      });


 });
  



       $(document).on('change','#employees_code_Add',function(e){
        
         if($(this).val()==""){
            $(".relatet_employees_add").hide();
            $("#emp_sal_add").val(0);
            $("#day_price_add").val(0);
         }else{
            var s =$("#employees_code_Add option:selected").data("s");
            var db =$("#employees_code_Add option:selected").data("db");

            $("#emp_sal_add").val(s*1);
            $("#day_price_add").val(db*1);
            $(".relatet_employees_add").show();
         }
      });
   
   });
   
</script>

@endsection