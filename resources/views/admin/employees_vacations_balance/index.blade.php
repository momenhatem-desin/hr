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
         <h3 class="card-title card_title_center">  بيانات  رصيد الاجازات للموظفين 
         </h3>
      </div>
         <div class="row" style="padding: 5px;">
         <div class="col-md-3">
                  <div class="form-group">
                     <label>   
                         <input checked name="searchbyradiocode" type="radio" value="zketo_code">كود بصمة
                         <input type="radio" name="searchbyradiocode" value="employee_code">كود موظف
                        </label>
                     <input autofocus type="text" name="searchbycode" id="searchbycode" class="form-control" value="" >
                  </div>
               </div>
        <div class="col-md-3">
                  <div class="form-group">
                     <label>    اسم الموظف</label>
                     <input type="text" name="emp_name_search" id="emp_name_search" class="form-control" value="" >
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
                  <label>بحث بحاله  الرصيد</label>
                  <select  name="is_active_for_Vaccation_search" id="is_active_for_Vaccation_search" class="form-control">
                     <option value="all">بحث بالكل </option>
              <option  value="1">نعم له سنوى</option>
                  <option  value="2">نعم له رصيد عن طريق ايام الحصور</option>
                  <option  value="0"> غير مفعل رصيد</option>
               </select>
               </div>
            </div>
              <div class="col-md-3">
                  <div class="form-group">
                     <label> بحث بالنوع</label>
                     <select  name="emp_gender_search" id="emp_gender_search" class="form-control">
                       <option value="all">بحث بالكل</option> 
                     <option  value="1">ذكر</option>
                     <option  value="2">انثي</option>
                     </select>
                  </div>
               </div>
      <div class="card-body" id="ajax_responce_serachDiv">
         @if(@isset($data) and !@empty($data) and count($data)>0 )
         <table id="example2" class="table table-bordered table-hover">
            <thead class="custom_thead">
               <th> كود </th>
               <th style="width: 22%">الاسم</th>
               <th style="width: 13%"> الفرع</th>
               <th> الادارة</th> 
               <th>الوظيفة</th>
               <th>حاله الرصيد</th>
               <th> الصورة</th>
                <th> الرصيد </th>
                <th> الاجراءات </th>
            </thead>
            <tbody>
               @foreach ( $data as $info )
               <tr>
                  <td>{{ $info->employees_code}}</td>
                  <td>{{ $info->emp_name}}
                     <br>
                <span style="color: red">@if($info->Functiona_status==1) بالخدمة @else خارج الخدمة@endif</span>
                  </td>
                  <td>{{ $info->Branch->name}}</td>
                  <td>
                  @if(!empty($info->Departement))
                  {{ $info->Departement->name}}
                   @endif
                  </td>
                  <td>{{ $info->jobs_categorie->name}}</td>
                  <td>@if($info->is_active_for_Vaccation==1) سنوى @elseif($info->is_active_for_Vaccation==2)حسب الحضور @else  غير مفعل@endif</td>
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
             {{ $info->balance_vac }}
             
               
            </td>
                  <td>
                     <a  href="{{ route('employees_vacations_balance.show',$info->id) }}" class="btn btn-info btn-sm">عرض المزيد</a>
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
@endsection
@section("script")
<script src="{{ asset('assets/admin/plugins/select2/js/select2.full.min.js') }}"> </script>
<script>
   //Initialize Select2 Elements
   $('.select2').select2({
     theme: 'bootstrap4'
   });




   $(document).ready(function(){
   
      $(document).on('input','#searchbycode',function(e){
         ajax_search();
      });
      $(document).on('input','#emp_name_search',function(e){
         ajax_search();
      });
   
      $(document).on('change','#branch_id_search',function(e){
         ajax_search();
      });

        $(document).on('change','#emp_Departments_code_search',function(e){
         ajax_search();
      });
       $(document).on('change','#Functiona_status_search',function(e){
         ajax_search();
      });

      $(document).on('change','#emp_jobs_id_search',function(e){
         ajax_search();
      });

     $(document).on('change','#is_active_for_Vaccation_search',function(e){
         ajax_search();
      });

         $(document).on('change','#emp_gender_search',function(e){
         ajax_search();
      });
     
      $('input[type=radio][name=searchbyradiocode]').change(function(){
        ajax_search();
      });



   function ajax_search(){
   var searchbycode=$("#searchbycode").val();
   var emp_name_search=$("#emp_name_search").val();
   var emp_Departments_code_search=$("#emp_Departments_code_search").val();
   var emp_jobs_id_search=$("#emp_jobs_id_search").val();
   var is_active_for_Vaccation_search=$("#is_active_for_Vaccation_search").val();
   var emp_gender_search=$("#emp_gender_search").val();
   var branch_id_search=$("#branch_id_search").val();
   var Functiona_status_search=$("#Functiona_status_search").val();
   var searchbyradiocode=$("input[type=radio][name=searchbyradiocode]:checked").val();
   jQuery.ajax({
   url:'{{ route('employees_vacations_balance.ajax_search') }}',
   type:'post',
   'dataType':'html',
   cache:false,
   data:{"_token":'{{ csrf_token() }}',searchbycode:searchbycode,emp_name_search:emp_name_search,emp_Departments_code_search:emp_Departments_code_search,emp_jobs_id_search:emp_jobs_id_search,is_active_for_Vaccation_search:is_active_for_Vaccation_search,emp_gender_search:emp_gender_search,branch_id_search:branch_id_search,Functiona_status_search:Functiona_status_search,searchbyradiocode:searchbyradiocode},
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
   var searchbycode=$("#searchbycode").val();
   var emp_name_search=$("#emp_name_search").val();
   var emp_Departments_code_search=$("#emp_Departments_code_search").val();
   var emp_jobs_id_search=$("#emp_jobs_id_search").val();
   var is_active_for_Vaccation_search=$("#is_active_for_Vaccation_search").val();
   var emp_gender_search=$("#emp_gender_search").val();
   var branch_id_search=$("#branch_id_search").val();
   var Functiona_status_search=$("#Functiona_status_search").val();
   var searchbyradiocode=$("input[type=radio][name=searchbyradiocode]:checked").val();
   jQuery.ajax({
   url:linkurl,
   type:'post',
   'dataType':'html',
   cache:false,
   data:{"_token":'{{ csrf_token() }}',searchbycode:searchbycode,emp_name_search:emp_name_search,emp_Departments_code_search:emp_Departments_code_search,emp_jobs_id_search:emp_jobs_id_search,is_active_for_Vaccation_search:is_active_for_Vaccation_search,emp_gender_search:emp_gender_search,branch_id_search:branch_id_search,Functiona_status_search:Functiona_status_search,searchbyradiocode:searchbyradiocode},
   success: function(data){
   $("#ajax_responce_serachDiv").html(data);
   },
   error:function(){
   alert("عفوا لقد حدث خطأ ");
   }
   
   });
   
   });
   
   
   
   
   
   });
   
   

  </script> 

@endsection
