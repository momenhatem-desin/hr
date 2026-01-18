@extends('layouts.admin')
@section('title')
بيانات الاجازات
@endsection
@section("css")
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection
@section('contentheader')
قائمة الاجازات
@endsection
@section('contentheaderactivelink')
<a href="{{ route('Employees.index') }}">  رصيد الاجازات</a>
@endsection
@section('contentheaderactive')
عرض
@endsection
@section('content')
<div class="col-12">
   <div class="card">
      <div class="card-header">
         <h3 class="card-title card_title_center">  عرض تفاصيل رصيد الاجازات للموظف
              <a  href="{{ route('employees_vacations_balance.index')}}" class="btn btn-warning btn-sm">عودة</a>
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
           
            <div class="col-md-4 " >
               <div class="form-group">
                  <label>   تاريخ التعيين </label>
                  <input disabled type="date" name="emp_start_date" id="emp_start_date" class="form-control" value="{{ old('emp_start_date',$data['emp_start_date']) }}" >
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
                  <label>    الحالة الوظيفية </label>
                  <select disabled   name="Functiona_status" id="Functiona_status" class="form-control">
                   <option value="">اختر الحاله</option>  
                  <option   @if(old('Functiona_status',$data['Functiona_status'])==1) selected @endif  value="1">يعمل</option>
                  <option @if(old('Functiona_status',$data['Functiona_status'])==0 and old('Functiona_status')!="" ) selected @endif value="0">خارج الخدمة</option>
             
               </select>
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
               </div>
            </div>

            <div class="clearfix"></div>
     
             <div class="col-md-3">
               <div class="form-group">
                  <label> بحث بالسنوات الماليه </label>
                  <select  name="finance_calender_search" id="finance_calender_search" class="form-control">
                   <option value="">اختر السنه الماليه</option>  
            @if(@isset($other['finance_calender']) && !@empty( $other['finance_calender']))
            @foreach ($other['finance_calender'] as $info )
                     <option  value="{{ $info->FINANCE_YR }}" @if(!@empty($other['finance_calender_open_year']) and $info->FINANCE_YR==$other['finance_calender_open_year']['FINANCE_YR']) selected @endif> {{ $info->FINANCE_YR }} </option>
                     @endforeach
             @endif        
               </select>
               </div>
            </div>
          <div class="card-body col-md-12" id="ajax_responce_serachDiv">   
         @if(@isset($other['finance_calender_open_year']) and !@empty($other['finance_calender_open_year']))
         @if(@isset($other['main_employees_vacations_balance']) and !@empty( $other['main_employees_vacations_balance']) and count( $other['main_employees_vacations_balance'])>0 )

         <table id="example2" class="table table-bordered table-hover">
            <thead class="custom_thead">
               <th> الشهر </th>
              <th> الرصيد المرحل </th>
              <th> رصيد الشهر   </th>
              <th> الرصيد المتاح </th>
               <th> الرصيد المستهلك </th>
              <th> صافى  الرصيد </th>
               <th> بواسطه </th>
               <th> التحديث </th>
            </thead>
            <tbody>
               @foreach ($other['main_employees_vacations_balance'] as $info )
               <tr>
                  <td>{{ $info->year_and_month}}
                     @if($admin_panel_settingData['is_pull_anuall_day_from_passma']==0)
                     <br>
                     <button data-id="{{ $info->id }}" class="btn btn-sm btn-danger load_edit_this_row">تعديل</button>
                     @endif
                  </td>
                  <td>{{ $info->carryover_from_previous_month*1}}
                  <td>{{ $info->current_month_balance*1}}</td>
                   <td>{{ $info->total_available_balance*1}}</td>
                  <td>{{ $info->spent_balance*1}}</td>
                  <td>{{ $info->net_balance*1}}</td>
                    <td>
                     @php
                     $dt=new DateTime($info->created_at);
                     $date=$dt->format("Y-m-d");
                     $time=$dt->format("h:i");
                     $newDateTime=date("A",strtotime($info->created_at));
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
                     $newDateTime=date("A",strtotime($info->updated_at));
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
               </tr>
               @endforeach
            </tbody>
         </table>
    <br>

         @else
         <p class="bg-warning text-center">
            لا يوجد رصيد إجازات مسجل لهذه السنة المالية
        </p>
      @endif

  @else
      <p class="bg-danger text-center">
        لا توجد سنة مالية مفتوحة
      </p>
   @endif

          
            

          </div>

                </div>
              </div>
            </div>
      </div>
   </div>
         


 <div class="modal fade" id="EditModal">
  <div class="modal-dialog modal-xl">
    <div class="modal-content bg-info">

      <div class="modal-header">
        <h4 class="modal-title">تحديث الرصيد لهذا الشهر يدويا</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body" id="EditModalBody" style="background-color: white; color:black;">
      </div>

      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-outline-light" data-dismiss="modal">
          Close
        </button>
      </div>

    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
         


@endsection
@section("script")
<script src="{{ asset('assets/admin/plugins/select2/js/select2.full.min.js') }}"> </script>
<script>
   //Initialize Select2 Elements
   $('.select2').select2({
     theme: 'bootstrap4'
   });

   $(document).ready(function(){

   
$(document).on('click','.load_edit_this_row',function(e){
   
    var id=$(this).data("id");
  
        
      jQuery.ajax({
      url:'{{ route('employees_vacations_balance.load_edit_row') }}',
      type:'post',
      'dataType':'html',
      cache:false,
      data:{"_token":'{{ csrf_token() }}',id:id},
      success: function(data){
      $("#EditModalBody").html(data);
      $("#EditModal").modal("show");
      },
      error:function(){
      alert("عفوا لقد حدث خطأ ");
      }
      
      });


 });

  $(document).on('click','#do_edit_now',function(e){
   var carryover_from_previous_month=$("#carryover_from_previous_month").val(); 
   var spent_balance=$("#spent_balance").val();
   var notes=$("#notes").val();
   var id = $(this).data("id");


    $('#backup_freeze_modal').modal('show');

    $.ajax({
        url: '{{ route('employees_vacations_balance.do_edit_row') }}',
        type: 'POST',
        dataType: 'json',
        cache: false,
        data: {
            _token: '{{ csrf_token() }}',
            id: id,
            carryover_from_previous_month:carryover_from_previous_month,
            spent_balance:spent_balance,
            notes:notes
        },
        success: function (data) {
         $("#backup_freeze_modal").modal("hide");
          console.log(data);
            },
        error: function () {
          $("#backup_freeze_modal").modal("hide");   
           alert("عفوا لقد حدث خطأ ");
            }
    });
});

       });
 

</script>
@endsection

 
