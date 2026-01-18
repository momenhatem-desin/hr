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
<a href="{{ route('Main_salary_employees_p_loans.index') }}">  السلف المستديمة  </a>
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
         <h3 class="card-title card_title_center">   بيانات  السلف المستديمة للموظفين
         </h3>
         <button  href="" class="btn btn-sm btn-success" data-toggle="modal" data-target="#AddModal">اضافة جديد</button>
       
      </div>
      <form method="POST" action="{{ route('Main_salary_employees_p_loans.print_search') }}" target="_blank">
         @csrf
     
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
                  <label> بحث  بحالة الصرف </label>
                  <select  name="is_dismissail_done_search" id="is_dismissail_done_search" class="form-control select2">
                  <option  value="all">بحث بالحاله</option>
                  <option  value="1">تم الصرف</option>
                  <option  value="0"> فى انتظار الصرف</option>

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
            <th> اجمالى السلفه</th>
            <th> عدد الشهور</th>
            <th>  قيمة القسط </th>
             <th>هل صرفت</th>
              <th>هل انتهت</th>
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
               <td> {{ $info->total*1}} </td>
               <td> {{ $info->month_number*1}} </td>
                <td> {{ $info->month_kast_value*1}} </td>
                   <td> @if($info->is_dismissail_done==1) نعم @else   لا  @endif
                     @if($info->is_dismissail_done==0 and $info->is_archived==0)
                     <a href="{{ route('Main_salary_employees_p_loans.do_dismissal_done_now',$info->id) }}"  class="btn  are_you_shur  btn-dark btn-sm">صرف الان</a>
                     @endif
                  </td>
                   <td> @if($info->is_archived==1) نعم @else   لا  @endif </td>
                  <td>
                     @if($info->is_archived==0 && $info->is_dismissail_done==0)
                     <button data-id="{{ $info->id }}"  class="btn  load_edit_this_row  btn-primary btn-sm">تعديل</button>
                     <a href="{{ route('Main_salary_employees_p_loans.delete',$info->id) }}"  class="btn  are_you_shur  btn-danger btn-sm">حذف</a>
                     @endif
                             <button data-id="{{ $info->id }}"  class="btn  load_akast_details  btn-info btn-sm">الاقساط</button>
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
<input type="hidden" value="@php echo date('Y-m-d'); @endphp" id="the_today_day">
<div class="modal fade " id="AddModal" >
   <div class="modal-dialog modal-xl">
     <div class="modal-content bg-info">
       <div class="modal-header">
         <h4 class="modal-title">أضافة السلف المستديمة للموظفين   </h4>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times;</span></button>
       </div>
       <div class="modal-body" id="AddModalBody" style="background-color: white; color:black;">
         <div class="row">
           <div class="col-md-4">
                  <div class="form-group">
                     <label>  بيانات الموظفين </label>
                     <select name="employees_code_Add" id="employees_code_Add" class="form-control select2 ">
                        <option value="">اختر  الموظف </option>
                        @if (@isset($other['employess']) && !@empty($other['employess']))
                        @foreach ($other['employess'] as $info )
                        <option value="{{ $info->employees_code }}" data-s=" {{ $info->emp_sal }}" data-db=" {{ $info->day_price }}"> {{ $info->emp_name}} ({{ $info->employees_code }}) </option>
                        @endforeach
                        @endif
                     </select>
               
                  </div>
               </div>
            <div class="col-md-4 relatet_employees_add" style="display: none;" >
               <div class="form-group">
                  <label>   راتب الموظف الشهري</label>
                  <input readonly type="text" name="emp_sal_add" id="emp_sal_add"  class="form-control" value="0" >
               </div>
            </div>   
            <div class="col-md-4 relatet_employees_add" style="display: none;" >
               <div class="form-group">
                  <label>أجر اليوم الواحد</label>
                  <input readonly type="text" name="day_price_add" id="day_price_add"  class="form-control" value="0" >
               </div>
            </div>  

              <div class="col-md-4">
              <div class="form-group">
                  <label>أجمالى قيمة السلفه المستديمة</label>
                  <input  type="text" name="total_Add" id="total_Add" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" class="form-control" value="0" >
             </div>
             </div>  

              <div class="col-md-4">
              <div class="form-group">
                  <label> عدد الشهور للاقساط</label>
                  <input  type="text" name="month_number_Add" id="month_number_Add" oninput="this.value=this.value.replace(/[^0-9]/g,'');" class="form-control" value="0" >
             </div>
             </div>  

            <div class="col-md-4">
            <div class="form-group">
                  <label> قيمة القسط الشهرى</label>
                  <input readonly  type="text" name="month_kast_value_Add" id="month_kast_value_Add" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" class="form-control" value="0" >
             </div>
             </div>  

            <div class="col-md-4">
            <div class="form-group">
                  <label>يبداء سداد اول قسط فى تاريخ</label>
                  <input  type="date" name="year_and_month_start_data_Add" id="year_and_month_start_data_Add"  class="form-control" value="" >
             </div>
             </div>  

          <div class="col-md-8">
              <div class="form-group">
                  <label> ملاحظة</label>
                  <input  type="text" name="notes_Add" id="notes_Add"  class="form-control" value="" >
               </div>
          </div> 
              <div class="col-md-12">
               <div class="form-group text-center">
                  <hr>
                  <button id="do_add_now" style="margin-top:33px; " class="btn btn-sm btn-danger" type="submit" name="submit">أضف السلف المستديمة </button>
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

<div class="modal fade" id="AksatDetailsModal">
  <div class="modal-dialog modal-xl">
    <div class="modal-content bg-info">

      <div class="modal-header">
        <h4 class="modal-title">عرض تفاصيل اقساط سلفة مستديمة لموظف</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body" id="AksatDetailsModalBody" style="background-color: white; color:black;">
        <!-- هنا هيتحمل المحتوى -->
      </div>

      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-outline-light" data-dismiss="modal">إغلاق</button>
      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

 <div class="modal fade " id="EditModal" >
   <div class="modal-dialog modal-xl">
     <div class="modal-content bg-info">
       <div class="modal-header">
         <h4 class="modal-title"> تعديل  سلفة مستديمة لموظف</h4>
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

        

   
function recalute_add_p_row(){
  var total_Add = $("#total_Add").val();
  var month_number_Add = $("#month_number_Add").val();

  total_Add = total_Add === "" ? 0 : parseFloat(total_Add);
  month_number_Add = month_number_Add === "" ? 0 : parseFloat(month_number_Add);

  if (month_number_Add === 0 || total_Add === 0) {
    $("#month_kast_value_Add").val('');
    return;
  }

  var month_kast_value = total_Add / month_number_Add;

  // تنسيق الإخراج: بدون فاصلة إذا كان عدد صحيح، وبفاصلة إذا كان فيه كسور
  var displayValue = Number.isInteger(month_kast_value)
    ? month_kast_value
    : month_kast_value.toFixed(2);

  $("#month_kast_value_Add").val(displayValue);
}

$(document).on('input', '#total_Add, #month_number_Add', function(e) {
  recalute_add_p_row();
});

function recalute_add_p_row_edit(){
  var total_edit = $("#total_edit").val();
  var month_number_edit = $("#month_number_edit").val();

  total_edi = total_edit === "" ? 0 : parseFloat(total_edit);
  month_number_edit = month_number_edit === "" ? 0 : parseFloat(month_number_edit);

  if (month_number_edit=== 0 || total_edit === 0) {
    $("#month_kast_value_edit").val('');
    return;
  }

  var month_kast_value = total_edit / month_number_edit;

  // تنسيق الإخراج: بدون فاصلة إذا كان عدد صحيح، وبفاصلة إذا كان فيه كسور
  var displayValue = Number.isInteger(month_kast_value)
    ? month_kast_value
    : month_kast_value.toFixed(2);

  $("#month_kast_value_edit").val(displayValue);
}

$(document).on('input', '#total_edit, #month_number_edit', function(e) {
  recalute_add_p_row_edit();
});





 

     $(document).on('click','#do_add_now',function(e){
         var employees_code_Add=$("#employees_code_Add").val();
         var notes_Add=$("#notes_Add").val();
         var day_price_add=$("#day_price_add").val();
         var emp_sal_add=$("#emp_sal_add").val();

         if(employees_code_Add==""){
            alert("من فضلك اختر الموظف");
            $("#employees_code_Add").focus();
            return false;
         }
         
      
            var total_Add=$("#total_Add").val();
            if(total_Add==""||total_Add==0){
            alert("من فضلك أدخل اجمالى  السلف المستديمة");
            $("#total_Add").focus();
            return false;
         }

            var month_number_Add=$("#month_number_Add").val();
            if(month_number_Add==""||month_number_Add==0){
            alert("من فضلك أدخل عدد شهور الاقساط");
            $("#month_number_Add").focus();
            return false;
         }

            var month_kast_value_Add=$("#month_kast_value_Add").val();
            if(month_kast_value_Add==""||month_kast_value_Add==0){
            alert("من فضلك أدخل اجمالى قسط الشهر");
            $("#month_kast_value_Add").focus();
            return false;
         }
         
            var year_and_month_start_data_Add=$("#year_and_month_start_data_Add").val();
            if(year_and_month_start_data_Add==""||year_and_month_start_data_Add==0){
            alert("من فضلك أدخل تاريخ اول قسط  ");
            $("#year_and_month_start_data_Add").focus();
            return false;
         }

            var the_today_day=$("#the_today_day").val();
            if(year_and_month_start_data_Add<the_today_day){
            alert("من فضلك أدخل تاريخ صحيح يكون اكبر او يساوى من تاريخ اليوم  ");
            $("#year_and_month_start_data_Add").focus();
            return false;
         }

         

      
      
         jQuery.ajax({
         url:'{{ route('Main_salary_employees_p_loans.checkExsistsBefore') }}',
         type:'post',
         'dataType':'json',
         cache:false,
         data:{"_token":'{{ csrf_token() }}',employees_code_Add:employees_code_Add},
         success: function(data){  
         if(data=='exsists_before'){
         var res=confirm("يوجد سلفة مستديمة سابقه مسجله للموظف  هل تريد الاستمرار");
         if(res==true){
            var flagRes=true;
         }else{
            var flagRes=false;
         }
         }else{
         var flagRes=true;
         }
         if(flagRes==true){
         $('#backup_freeze_modal').modal('show'); 
         jQuery.ajax({
         url:'{{ route('Main_salary_employees_p_loans.store') }}',
         type:'post',
         'dataType':'html',
         cache:false,
         data:{"_token":'{{ csrf_token() }}',employees_code_Add:employees_code_Add,
         total_Add:total_Add,
         notes_Add:notes_Add,
         year_and_month_start_data_Add:year_and_month_start_data_Add,
         month_kast_value_Add:month_kast_value_Add,
         month_number_Add:month_number_Add,
         day_price_add:day_price_add,
         emp_sal_add:emp_sal_add,
            },
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

      };
         
         },
         error:function(){
         alert("عفوا لقد حدث خطأ ");
         }
         
         });




      });

    $(document).on('click','#do_edit_now',function(e){
         var employees_code_edit=$("#employees_code_edit").val();
         var notes_edit=$("#notes_edit").val();
         var day_price_edit=$("#day_price_edit").val();
         var emp_sal_edit=$("#emp_sal_edit").val();

         if(employees_code_edit==""){
            alert("من فضلك اختر الموظف");
            $("#employees_code_edit").focus();
            return false;
         }
         
      
            var total_edit=$("#total_edit").val();
            if(total_edit==""||total_edit==0){
            alert("من فضلك أدخل اجمالى  السلف المستديمة");
            $("#total_edit").focus();
            return false;
         }

            var month_number_edit=$("#month_number_edit").val();
            if(month_number_edit==""||month_number_edit==0){
            alert("من فضلك أدخل عدد شهور الاقساط");
            $("#month_number_edit").focus();
            return false;
         }

            var month_kast_value_edit=$("#month_kast_value_edit").val();
            if(month_kast_value_edit==""||month_kast_value_edit==0){
            alert("من فضلك أدخل اجمالى قسط الشهر");
            $("#month_kast_value_edit").focus();
            return false;
         }
         
            var year_and_month_start_data_edit=$("#year_and_month_start_data_edit").val();
            if(year_and_month_start_data_edit==""||year_and_month_start_data_edit==0){
            alert("من فضلك أدخل تاريخ اول قسط  ");
            $("#year_and_month_start_data_edit").focus();
            return false;
         }

            var the_today_day=$("#the_today_day").val();
            if(year_and_month_start_data_edit<the_today_day){
            alert("من فضلك أدخل تاريخ صحيح يكون اكبر او يساوى من تاريخ اليوم  ");
            $("#year_and_month_start_data_edit").focus();
            return false;
         }

       var id=$(this).data("id");
      $('#backup_freeze_modal').modal('show'); 

      jQuery.ajax({
         url:'{{ route('Main_salary_employees_p_loans.do_edit_row') }}',
         type:'post',
         'dataType':'html',
         cache:false,
         data:{"_token":'{{ csrf_token() }}',employees_code_edit:employees_code_edit,
         total_edit:total_edit,
         notes_edit:notes_edit,
         year_and_month_start_data_edit:year_and_month_start_data_edit,
         month_kast_value_edit:month_kast_value_edit,
         month_number_edit:month_number_edit,
         day_price_edit:day_price_edit,
         emp_sal_edit:emp_sal_edit,
         id:id,
            },
         success: function(data){
         ajax_search();
         setTimeout(function () {
         $("#backup_freeze_modal").modal("hide");
      }, 1000);

         
         },
         error:function(){
         alert("عفوا لقد حدث خطأ ");
         }
         
         });




      });



      

    $(document).on('change','#employees_code_search',function(e){
        ajax_search(); 
      }); 
    $(document).on('change','#is_dismissail_done_search',function(e){
        ajax_search(); 
      }); 
      
       $(document).on('change','#is_archived_search',function(e){
        ajax_search(); 
      }); 
   

   function ajax_search(){
   var employees_code_search=$("#employees_code_search").val();
   var is_archived_search=$("#is_archived_search").val();
   var is_dismissail_done_search=$("#is_dismissail_done_search").val();
   jQuery.ajax({
   url:'{{ route('Main_salary_employees_p_loans.ajax_search') }}',
   type:'post',
   'dataType':'html',
   cache:false,
   data:{"_token":'{{ csrf_token() }}',employees_code_search:employees_code_search,is_archived_search:is_archived_search,is_dismissail_done_search:is_dismissail_done_search},
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
   var is_dismissail_done_search=$("#is_dismissail_done_search").val();
   var linkurl=$(this).attr("href");
   jQuery.ajax({
   url:linkurl,
   type:'post',
   'dataType':'html',
   cache:false,
   data:{"_token":'{{ csrf_token() }}',employees_code_search:employees_code_search,is_archived_search:is_archived_search,is_dismissail_done_search:is_dismissail_done_search},
   success: function(data){
   $("#ajax_responce_serachDiv").html(data);
   },
   error:function(){
   alert("عفوا لقد حدث خطأ ");
   }
   
   });
   
   });

 
   
    $(document).on('click','.load_edit_this_row',function(e){
   
    var id=$(this).data("id");
        
      jQuery.ajax({
      url:'{{ route('Main_salary_employees_p_loans.load_edit_row') }}',
      type:'post',
      'dataType':'html',
      cache:false,
      data:{"_token":'{{ csrf_token() }}',id:id},
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

    


       
   $(document).on('click','.load_akast_details',function(e){
        var id=$(this).data("id");
        
      jQuery.ajax({
      url:'{{ route('Main_salary_employees_p_loans.load_akast_details') }}',
      type:'post',
      'dataType':'html',
      cache:false,
      data:{"_token":'{{ csrf_token() }}',id:id},
      success: function(data){
      $("#AksatDetailsModalBody").html(data);
      $("#AksatDetailsModal").modal("show");
      },
      error:function(){
      alert("عفوا لقد حدث خطأ ");
      }
      
      });


 });


$(document).on('click', '.DoCachpayNow', function(e){
    e.preventDefault();

    var id = $(this).data("id");
    var idparent = $(this).data("idparent"); 

    $.ajax({
        url: '{{ route('Main_salary_employees_p_loans.DoCachpayNow') }}',
        type: 'POST',
        dataType: 'json',
        cache: false,
        data: {
            "_token": '{{ csrf_token() }}',
            id: id,
            idparent: idparent
        },
        success: function(data){
            // بعد الدفع الكاش حمّل تاني تفاصيل الأقساط
            $.ajax({
                url: '{{ route('Main_salary_employees_p_loans.load_akast_details') }}',
                type: 'POST',
                dataType: 'html',
                cache: false,
                data: {
                    "_token": '{{ csrf_token() }}',
                    id: idparent
                },
                success: function(data){
                    $("#AksatDetailsModalBody").html(data);
                    $("#AksatDetailsModal").modal("show");
                },
                error:function(){
                    alert("عفوا لقد حدث خطأ عند إعادة تحميل التفاصيل");
                }
            });
        },
        error:function(){
            alert("عفوا لقد حدث خطأ عند تنفيذ الدفع");
        }
    });
});


   });


   
</script>

@endsection