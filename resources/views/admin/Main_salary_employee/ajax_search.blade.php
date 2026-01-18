@if(@isset($data) and !@empty($data) and count($data)>0 )
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
<div class="col-md-12 text-center" id="ajax_pagination_in_search">
{{ $data->links('pagination::bootstrap-5') }}
</div>
@else
<p class="bg-danger text-center"> عفوا لاتوجد بيانات لعرضها</p>
@endif
