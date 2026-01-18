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
<a href="{{ route('Main_salary_employees_addtion.index') }}">  إضافى أيام  </a>
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
         <h3 class="card-title card_title_center">   بيانات  إضافى أيام  للرواتب الشهر المالى({{ $finance_cln_periods_data['month']->name }}لسنة {{ $finance_cln_periods_data['FINANCE_YR'] }})

         </h3>
      
       @if($finance_cln_periods_data['is_open']==1)<br>
         <button  href="{{ route('Allowances.create') }}" class="btn btn-sm btn-success" data-toggle="modal" data-target="#AddModal">اضافة جديد</button>
         @endif
      </div>
      <form method="POST" action="{{ route('Main_salary_employees_addtion.print_search') }}" target="_blank">
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

            <div class="col-md-2">
               <div class="form-group">
                
              <button type="post" class="btn btn_sm btn-info custom_button">طباعة البحث </button>

               </select>
               </div>
            </div>
      </div>
      </form> 
      <div class="card-body" id="ajax_responce_serachDiv" style="padding: 0px 5px">
         @if(@isset($data) and !@empty($data))
           <table id="example2" class="table table-bordered table-hover">
         <thead class="custom_thead">
            <th> كود الموظف </th>
            <th> اسم الموظف</th>
            <th> عدد ايام  الاضافى</th>
            <th> قيمة الاضافى</th>
            <th>  تاريخ الاضافة</th>
            <th> تاريخ التحديث</th>
             <th> الحالة</th>
             <th> الاجراءات</th>
         </thead>
         <tbody>
            @foreach ( $data as $info )
            <tr>
               <td> {{ $info->employees_code }}
               </td>
               <td> {{ $info->emp_name }}
                   @if(!empty($info->notes))
                  <br> <span style="color: brown">ملاحظة</span>{{ $info->notes }}
            @endif
               </td>
                <td> {{ $info->value*1}} </td>
               <td> {{ $info->total*1}} </td>
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
               <td> @if($info->is_archived==1) مؤرشف @else   مفتوح  @endif
               </td>
                  <td>
                     @if($info->is_archived==0)
                     <button data-id="{{ $info->id }}" data-main_salary_employee_id="{{ $info->main_salary_employee_id }}" class="btn  load_edit_this_row  btn-primary btn-sm">تعديل</button>
                     <button data-id="{{ $info->id }}" data-main_salary_employee_id="{{ $info->main_salary_employee_id }}" class="btn  delete_this_row  btn-danger btn-sm">حذف</button>
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
         <h4 class="modal-title">أضافة إضافى أيام للموظفين بالشهر المالى </h4>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times;</span></button>
       </div>
       <div class="modal-body" id="AddModalBody" style="background-color: white; color:black;">
         <div class="row">
           <div class="col-md-3">
                  <div class="form-group">
                     <label>  بيانات الموظفين </label>
                     <select name="employees_code_Add" id="employees_code_Add" class="form-control select2 ">
                        <option value="">اختر  الموظف </option>
                        @if (@isset($employess) && !@empty($employess))
                        @foreach ($employess as $info )
                        <option value="{{$info->EmployeeData['employees_code']}}" data-s=" {{ $info->EmployeeData['emp_sal']}}" data-db=" {{  $info->EmployeeData['day_price']}}"> {{ $info->EmployeeData['emp_name']}} ({{$info->EmployeeData['employees_code']}}) </option>
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
          <div class="col-md-3">
              <div class="form-group">
                  <label>عدد ايام الاضافى</label>
                  <input  type="text" name="value_Add" id="value_Add" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" class="form-control" value="" >
               </div>
          </div>   
            
              <div class="col-md-3">
              <div class="form-group">
                  <label>أجمالى قيمة الاضافى</label>
                  <input readonly  type="text" name="total_Add" id="total_Add" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" class="form-control" value="" >
               </div>
          </div>  

          <div class="col-md-3">
              <div class="form-group">
                  <label> ملاحظة</label>
                  <input  type="text" name="notes_Add" id="notes_Add"  class="form-control" value="" >
               </div>
          </div> 
              <div class="col-md-12">
               <div class="form-group text-center">
                  <hr>
                  <button id="do_add_now" style="margin-top:33px; " class="btn btn-sm btn-danger" type="submit" name="submit">أضف الاضافى </button>
               </div>
            </div> 
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

 <div class="modal fade " id="EditModal" >
   <div class="modal-dialog modal-xl">
     <div class="modal-content bg-info">
       <div class="modal-header">
         <h4 class="modal-title">تحديث إضافى أيام للموظفين بالشهر المالى </h4>
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
        var value_Add=$("#value_Add").val();
        var total_Add=$("#total_Add").val();
        var notes_Add=$("#notes_Add").val();
        var day_price_add=$("#day_price_add").val();
        var the_finance_cln_periods_id=$("#the_finance_cln_periods_id").val();

        if(employees_code_Add==""){
         alert("من فضلك اختر الموظف");
         $("#employees_code_Add").focus();
         return false;
        }
       if(value_Add==""){
         alert("من فضلك أدخل عدد ايام الاضافى");
         $("#value_Add").focus();
         return false;
        }
   

         if(total_Add==""||total_Add==0){
         alert("من فضلك أدخل اجمالى  الاضافى");
         $("#total_Add").focus();
         return false;
        }
   
  
   jQuery.ajax({
   url:'{{ route('Main_salary_employees_addtion.checkExsistsBefore') }}',
   type:'post',
   'dataType':'json',
   cache:false,
   data:{"_token":'{{ csrf_token() }}',employees_code_Add:employees_code_Add,the_finance_cln_periods_id:the_finance_cln_periods_id},
   success: function(data){ 
   if(data=='exsists_before'){
   var res=confirm("يوجد اضافى سابق مسجل للموظف  هل تريد الاستمرار");
   if(res==true){
      var flagRes=true;
   }else{
       var flagRes=false;
   }
   }else{
    var flagRes=true;
   }
   if(flagRes==true)
    $('#backup_freeze_modal').modal('show'); 
  
   jQuery.ajax({
   url:'{{ route('Main_salary_employees_addtion.store') }}',
   type:'post',
   'dataType':'html',
   cache:false,
   data:{"_token":'{{ csrf_token() }}',employees_code_Add:employees_code_Add,the_finance_cln_periods_id:the_finance_cln_periods_id,value_Add:value_Add,total_Add:total_Add,notes_Add:notes_Add,day_price_add:day_price_add},
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



   },
   error:function(){
   alert("عفوا لقد حدث خطأ ");
   }
   
   });




      });

    $(document).on('change','#employees_code_search',function(e){
        ajax_search(); 
      }); 
      


       $(document).on('change','#is_archived_search',function(e){
        ajax_search(); 
      }); 
   

   function ajax_search(){
   var employees_code_search=$("#employees_code_search").val();
   var is_archived_search=$("#is_archived_search").val();
   var the_finance_cln_periods_id=$("#the_finance_cln_periods_id").val();
   jQuery.ajax({
   url:'{{ route('Main_salary_employees_addtion.ajax_search') }}',
   type:'post',
   'dataType':'html',
   cache:false,
   data:{"_token":'{{ csrf_token() }}',employees_code_search:employees_code_search,is_archived_search:is_archived_search,the_finance_cln_periods_id:the_finance_cln_periods_id},
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
   var is_archived_search=$("#is_archived_search").val();
   var the_finance_cln_periods_id=$("#the_finance_cln_periods_id").val();
   var linkurl=$(this).attr("href");
   jQuery.ajax({
   url:linkurl,
   type:'post',
   'dataType':'html',
   cache:false,
   data:{"_token":'{{ csrf_token() }}',employees_code_search:employees_code_search,is_archived_search:is_archived_search,the_finance_cln_periods_id:the_finance_cln_periods_id},
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
        
        var main_salary_employee_id=$(this).data("main_salary_employee_id");
        var id=$(this).data("id");
        var the_finance_cln_periods_id=$("#the_finance_cln_periods_id").val();
        $('#backup_freeze_modal').modal('show'); 
        
      jQuery.ajax({
      url:'{{ route('Main_salary_employees_addtion.delete_row') }}',
      type:'post',
      'dataType':'json',
      cache:false,
      data:{"_token":'{{ csrf_token() }}',id:id,the_finance_cln_periods_id:the_finance_cln_periods_id,main_salary_employee_id:main_salary_employee_id},
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
    var main_salary_employee_id=$(this).data("main_salary_employee_id");
        
      jQuery.ajax({
      url:'{{ route('Main_salary_employees_addtion.load_edit_row') }}',
      type:'post',
      'dataType':'html',
      cache:false,
      data:{"_token":'{{ csrf_token() }}',id:id,the_finance_cln_periods_id:the_finance_cln_periods_id,main_salary_employee_id:main_salary_employee_id},
      success: function(data){
      $("#EditModalBody").html(data);
      $("#EditModal").modal("show");
      $('.select2').select2();
      },
      error:function(){
      alert("عفوا لقد حدث خطأ ");
      }
      
      });


 });

    $(document).on('click','#do_edit_now',function(e){
        var employees_code_edit=$("#employees_code_edit").val();
        var value_edit=$("#value_edit").val();
        var total_edit=$("#total_edit").val();
        var notes_edit=$("#notes_edit").val();
        var day_price_edit=$("#day_price_edit").val();
        var the_finance_cln_periods_id=$("#the_finance_cln_periods_id").val();
        var main_salary_employee_id=$(this).data("main_salary_employee_id");
        var id=$(this).data("id");

        if(employees_code_edit==""){
         alert("من فضلك اختر الموظف");
         $("#employees_code_edit").focus();
         return false;
        }
         if(value_edit==""){
         alert("من فضلك أدخل عدد ايام الاضافى");
         $("#employees_code_edit").focus();
         return false;
        }
         if(total_edit==""||total_edit==0){
         alert("من فضلك أدخل اجمالى  الاضافى");
         $("#employees_code_edit").focus();
         return false;
        }
   
  
       $('#backup_freeze_modal').modal('show'); 
  
   jQuery.ajax({
   url:'{{ route('Main_salary_employees_addtion.do_edit_row') }}',
   type:'post',
   'dataType':'html',
   cache:false,
   data:{"_token":'{{ csrf_token() }}',employees_code_edit:employees_code_edit,the_finance_cln_periods_id:the_finance_cln_periods_id,main_salary_employee_id:main_salary_employee_id,id:id,value_edit:value_edit,total_edit:total_edit,notes_edit:notes_edit,day_price_edit:day_price_edit},
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
   
   
   
   });
   
</script>

@endsection