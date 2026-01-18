@extends('layouts.admin')
@section('title')
الاجور
@endsection
@section('contentheader')
قائمة الاجور
@endsection
@section("css")
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection
@section('contentheaderactivelink')
<a href="{{ route('Main_salary_employee.index') }}">   عرض الرواتب المفصله  </a>
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
         <h3 class="card-title card_title_center">   بيانات رواتب الموطفين  للرواتب الشهر المالى({{ $finance_cln_periods_data['month']->name }}لسنة {{ $finance_cln_periods_data['FINANCE_YR'] }})

         </h3>
      
       @if($finance_cln_periods_data['is_open']==1)<br>
         <button  href="{{ route('Allowances.create') }}" class="btn btn-sm btn-success" data-toggle="modal" data-target="#AddModal">اضافة راتب جديد</button>
         @endif
      </div>
      <form method="POST" action="{{ route('Main_salary_employee.print_search') }}" target="_blank">
         @csrf
         <input type="hidden" id="the_finance_cln_periods_id" name="the_finance_cln_periods_id" value="{{ $finance_cln_periods_data['id'] }}">
      <div class="row" style="padding: 5px;">
               <div class="col-md-3">
                  <div class="form-group">
                     <label>  بحث بالموظفين </label>
                     <select name="employees_code_search" id="employees_code_search" class="form-control select2 ">
                        <option value="all"> بحث بالكل  </option>
                        @if (@isset($other['employess']) && !@empty($other['employess']))
                        @foreach ($other['employess'] as $info )
                        <option value="{{ $info->employees_code }}"> {{ $info->emp_name }} ({{ $info->employees_code }}) </option>
                        @endforeach
                        @endif
                     </select>
               
                        </div>
                     </div>
                     <div class="col-md-3">
                  <div class="form-group">
                     <label> بحث بالفروع</label>
                     <select name="branch_id_search" id="branch_id_search" class="form-control select2 ">
                        <option value="all">بحث بالكل </option>
                        @if (@isset($other['branches']) && !@empty($other['branches']))
                        @foreach ($other['branches'] as $info )
                        <option value="{{ $info->id }}"> {{ $info->name }} </option>
                        @endforeach
                        @endif
                     </select>
                  </div>
               </div>
            <div class="col-md-3">
               <div class="form-group">
                  <label>بحث بالادارة</label>
                  <select name="emp_Departments_code_search" id="emp_Departments_code_search" class="form-control select2 ">
                     <option value="all"> بحث بالكل</option>
                     @if (@isset($other['departements']) && !@empty($other['departements']))
                     @foreach ($other['departements'] as $info )
                     <option value="{{ $info->id }}"> {{ $info->name }} </option>
                     @endforeach
                     @endif
                  </select>
               </div>
            </div>    

               <div class="col-md-3">
               <div class="form-group">
                  <label> بحث بالوظائف </label>
                  <select name="emp_jobs_id_search" id="emp_jobs_id_search" class="form-control select2 ">
                     <option value="all">بحث بالكل</option>
                     @if (@isset($other['jobs']) && !@empty($other['jobs']))
                     @foreach ($other['jobs'] as $info )
                     <option  value="{{ $info->id }}"> {{ $info->name }} </option>
                     @endforeach
                     @endif
                  </select>
               </div>
            </div>
                   <div class="col-md-3">
               <div class="form-group">
                  <label>  بحث بالحالة الوظفية</label>
                  <select  name="Functiona_status_search" id="Functiona_status_search" class="form-control">
                   <option value="all">بحث بالكل</option>  
                  <option  value="1">يعمل</option>
                  <option  value="0">خارج الخدمة</option>
               </select>
               </div>
            </div>
            <div class="col-md-3">
               <div class="form-group">
                  <label> نوع صرف راتب الموظف</label>
                  <select  name="sal_cach_or_visa_search" id="sal_cach_or_visa_search" class="form-control">
                     <option value="all">بحث بالكل </option>
                  <option   value="1">كاش</option>
                  <option  value="2">فيزا</option>
               </select>
               </div>
            </div>
            <div class="col-md-3">
               <div class="form-group">
                  <label> بحث  بحالة الايقاف </label>
                  <select  name="is_stoped_search" id="is_stoped_search" class="form-control select2">
                  <option  value="all">بحث بالحاله</option>
                  <option  value="0">مرتب مفعل</option>
                  <option  value="1"> مرتب موقوف</option>

               </select>
               </div>
            </div>
          <div class="col-md-3">
               <div class="form-group">
                  <label> بحث  بحالة الارشفة </label>
                  <select  name="is_archived_search" id="is_archived_search" class="form-control select2">
                  <option  value="all">بحث بالحاله</option>
                  <option  value="1">مؤرشف</option>
                  <option  value="0"> مفتوح</option>

               </select>
               </div>
            </div>

            <div class="col-md-12">
               <div class="form-group text-center">
                
              <button type="submit" class="btn btn_sm btn-info" name="submit_button" value="indetails">طباعة البحث مفصل لكل موظف </button>
               <button type="submit" class="btn btn_sm btn-dark" name="submit_button" value="intotal">طباعة البحث أجمالى </button>
               <button type="submit" class="btn btn_sm btn-primary" name="submit_button" value="intotal_withdetalis">طباعة البحث أجمالى مفصل </button>
               <hr>
               </select>
               </div>
            </div>
      </div>
      </form>
      <div class="card-body" id="ajax_responce_serachDiv" style="padding: 0px 5px">
      <h3 style="text-align: center;font-size:1.4vw;font-weight:bold;color:brown;">مرأه البحث</h3> 
      <table id="example2" class="table table-bordered table-hover" style="width: 80%;margin:0 auto">
           <tr style="background-color: lightblue">
            <th>عدد الرواتب</th>
            <th>عدد المرتبات فى انتظار الارشفه</th>
            <th>عدد المرتبات التى تم ارشفتها</th>
            <th> عدد الموقوف</th>
           </tr>
      <tr style="text-align: center">
            <td>{{  $other['counter_salaries']*1 }}</td>
            <td>{{  $other['counter_salaries_wating_archive']*1 }}</td>
            <td>{{  $other['counter_salaries_done_archive']*1 }}</td>
            <td>{{  $other['counter_salaries_stop']*1 }}</td>
      </tr>
      </table>
         @if(@isset($data) and !@empty($data))
           <table id="example2" class="table table-bordered table-hover">
         <thead class="custom_thead">
            <th>كود الموظف</th>
            <th> اسم الموظف</th>
            <th>  الفرع </th>
            <th>   الادارة</th>
            <th>  الوظيفة</th>
            <th> حاله الارشفه</th>
            <th>صورةالموظف</th>
             <th>الأجراءات</th>
         </thead>
         <tbody>
            @foreach ( $data as $info )
            <tr>
                <td>{{ $info->employees_code }}</td>
               <td> {{ $info->emp_name }}
               </td>
                <td>{{ $info->branch_name }}</td>
                <td>{{ $info->emp_Departments_name }}</td>
                <td>{{ $info->job_name }}</td>
                  <td> @if($info->is_archived==1) مؤرشف @else   مفتوح  @endif
               </td>
                <td>
                     @if(!empty($info->emp_photo))
                     <img src="{{ asset('assets/admin/uploads').'/' . $info->emp_photo }}" border-raduis: 50%; width="80px"; height="80px"; class="rounded-circle" alt="صورة الموظف">
                     @else
                     @if($info->emp_gender==1)
                    <img src="{{ asset('assets/admin/imgs/male.png') }}" border-raduis: 50%; width="80px"; height="80px"; class="rounded-circle" alt="صورة الموظف">
                     @else
                  <img src="{{ asset('assets/admin/imgs/female.png')}}" border-raduis: 50%; width="80px"; height="80px"; class="rounded-circle" alt="صورة الموظف">
                     @endif
                     @endif
                  </td>
                  <td>
                     @if($info->is_archived==0)
                     <button data-id="{{ $info->id }}" data-id="{{ $info->id }}" class="btn  delete_salary  btn-danger btn-sm">حذف</button>
                     @endif
                        <a href="{{ route('Main_salary_employee.showSalaryDetails',$info->id) }}"  class="btn  btn-success btn-sm">  التفاصيل </a>
                        <a target="_blank" class="btn btn-info btn-sm" style="color: white" href="{{ route("Main_salary_employee.print_salary",$info->id) }}">طباعة </a>
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
         <h4 class="modal-title">أضافة مرتب جديد للشهر المالى     </h4>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times;</span></button>
       </div>
       <div class="modal-body" id="AddModalBody" style="background-color: white; color:black;">
         <form action="{{ route('Main_salary_employee.addManuallySalrary',$finance_cln_periods_data['id']) }}" method="post">
          @csrf
         <div class="row">
           <div class="col-md-6">
                  <div class="form-group">
                     <label>   بيانات الموظفين ليس مضاف لهم راوتب بالشهر الحالى (عدد {{ $other['nothave']*1 }})</label>
                     <select name="employees_code_Add" id="employees_code_Add" class="form-control select2 ">
                        <option value="">اختر  الموظف </option>
                        @if (@isset($other['employess']) && !@empty($other['employess']))
                        @foreach ($other['employess'] as $info )
                        @if($info->counter>0) @continue @endif
                        <option value="{{$info->employees_code}}" data-s=" {{ $info->emp_sal}}"> {{ $info->emp_name}} ({{$info->employees_code}})  </option>
                        @endforeach
                        @endif
                     </select>
               
                  </div>
               </div>
            <div class="col-md-2 relatet_employees_add" style="display: none;" >
               <div class="form-group">
                  <label>   راتب الموظف الشهري</label>
                  <input readonly type="text" name="emp_sal_add" id="emp_sal_add"  class="form-control" value="0" >
               </div>
            </div>
              <div class="col-md-4">
               <div class="form-group text-left">
                  <button id="do_add_now" style="margin-top:33px; " class="btn btn-sm btn-danger" type="submit" name="submit"> فتح سجل راتب للموظف بهذا الشهر </button>
               </div>
            </div> 
         </form>
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
     //Initialize Select2 Elements
   $('.select2').select2({
     theme: 'bootstrap4'
   });

   $(document).ready(function(){
   
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

           $(document).on('change','#employees_code_edit',function(e){
        
         if($(this).val()==""){
            $(".relatet_employees_edit").hide();
            $("#emp_sal_edit").val(0);
            $("#day_price_edit").val(0);
         }else{
            var s =$("#employees_code_edit option:selected").data("s");
            var db =$("#employees_code_edit option:selected").data("db");

            $("#emp_sal_edit").val(s*1);
            $("#day_price_edit").val(db*1);
            $(".relatet_employees_edit").show();
         }
      });

   $(document).on('input','#value_Add',function(e){
         
         var value_Add=$(this).val();
         if (value_Add==""){value_Add=0;}
         var day_price_add=$("#day_price_add").val();
         $("#total_Add").val(value_Add*day_price_add*1);
      });
      
         $(document).on('input','#value_edit',function(e){
         
         var value_edit=$(this).val();
         if (value_edit==""){value_edit=0;}
         var day_price_edit=$("#day_price_edit").val();
         $("#total_edit").val(value_edit*day_price_edit*1);
      });

      $(document).on('click','#do_add_now',function(e){
        var employees_code_Add=$("#employees_code_Add").val();
        var sanctions_type_Add=$("#sanctions_type_Add").val();
        var total_Add=$("#total_Add").val();
        var value_Add=$("#value_Add").val();
        var notes_Add=$("#notes_Add").val();
        var day_price_add=$("#day_price_add").val();
        var the_finance_cln_periods_id=$("#the_finance_cln_periods_id").val();

        if(employees_code_Add==""){
         alert("من فضلك اختر الموظف");
         $("#employees_code_Add").focus();
         return false;
        }
    
   

      });

    $(document).on('change','#employees_code_search',function(e){
        ajax_search(); 
      }); 
      $(document).on('change','#branch_id_search',function(e){
        ajax_search(); 
      }); 
      $(document).on('change','#emp_Departments_code_search',function(e){
        ajax_search(); 
      }); 
      $(document).on('change','#emp_jobs_id_search',function(e){
        ajax_search(); 
      }); 
      $(document).on('change','#Functiona_status_search',function(e){
        ajax_search(); 
      }); 
      $(document).on('change','#sal_cach_or_visa_search',function(e){
        ajax_search(); 
      }); 
      
       $(document).on('change','#is_stoped_search',function(e){
        ajax_search(); 
      }); 

       $(document).on('change','#is_archived_search',function(e){
        ajax_search(); 
      }); 
   

   function ajax_search(){
   var employees_code_search=$("#employees_code_search").val();
   var branch_id_search=$("#branch_id_search").val();
   var emp_Departments_code_search=$("#emp_Departments_code_search").val();
   var emp_jobs_id_search=$("#emp_jobs_id_search").val();
   var Functiona_status_search=$("#Functiona_status_search").val();
   var sal_cach_or_visa_search=$("#sal_cach_or_visa_search").val();
   var is_stoped_search=$("#is_stoped_search").val();
   var is_archived_search=$("#is_archived_search").val();
   var the_finance_cln_periods_id=$("#the_finance_cln_periods_id").val();
   jQuery.ajax({
   url:'{{ route('Main_salary_employee.ajax_search') }}',
   type:'post',
   'dataType':'html',
   cache:false,
   data:{"_token":'{{ csrf_token() }}',
   employees_code_search:employees_code_search,
   branch_id_search:branch_id_search,
   is_stoped_search:is_stoped_search,
   sal_cach_or_visa_search:sal_cach_or_visa_search,
   Functiona_status_search:Functiona_status_search,
   emp_jobs_id_search:emp_jobs_id_search,
   emp_Departments_code_search:emp_Departments_code_search,
   is_archived_search:is_archived_search,
   the_finance_cln_periods_id:the_finance_cln_periods_id},
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
   var branch_id_search=$("#branch_id_search").val();
   var emp_Departments_code_search=$("#emp_Departments_code_search").val();
   var emp_jobs_id_search=$("#emp_jobs_id_search").val();
   var Functiona_status_search=$("#Functiona_status_search").val();
   var sal_cach_or_visa_search=$("#sal_cach_or_visa_search").val();
   var is_stoped_search=$("#is_stoped_search").val();
   var is_archived_search=$("#is_archived_search").val();
   var the_finance_cln_periods_id=$("#the_finance_cln_periods_id").val();
   var linkurl=$(this).attr("href");
   jQuery.ajax({
   url:linkurl,
   type:'post',
   'dataType':'html',
   cache:false,
   data:{"_token":'{{ csrf_token() }}',employees_code_search:employees_code_search,branch_id_search:branch_id_search,is_stoped_search:is_stoped_search,sal_cach_or_visa_search:sal_cach_or_visa_search,Functiona_status_search:Functiona_status_search,emp_jobs_id_search:emp_jobs_id_search,emp_Departments_code_search:emp_Departments_code_search,is_archived_search:is_archived_search,the_finance_cln_periods_id:the_finance_cln_periods_id},
   success: function(data){
   $("#ajax_responce_serachDiv").html(data);
   },
   error:function(){
   alert("عفوا لقد حدث خطأ ");
   }
   
   });
   
   });

     $(document).on('click','.delete_salary',function(e){
        var res=confirm("هل متاكد من الحذف ؟");
        if(!res){
         return false;
        }
        var id=$(this).data("id");
        var the_finance_cln_periods_id=$("#the_finance_cln_periods_id").val();
        $('#backup_freeze_modal').modal('show'); 
        
      jQuery.ajax({
      url:'{{ route('Main_salary_employee.delete_salary') }}',
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
      alert("عفوا لقد حدث خطأ ربما السجل غير موجود اومؤرشف");
      }
      
      });


 });
   
  

   
   
   });
   
</script>

@endsection