@if(@isset($data) and !@empty($data) and count($data)>0 )
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
                     <a href="{{ route('Main_salary_employees_P_loans.do_dismissal_done_now',$info->id) }}"  class="btn  are_you_shur  btn-dark btn-sm">صرف الان</a>
                     @endif
                  </td>
                   <td> @if($info->is_archived==1) نعم @else   لا  @endif </td>
                  <td>
                     @if($info->is_archived==0 && $info->is_dismissail_done==0)
                     <button data-id="{{ $info->id }}"  class="btn  load_edit_this_row  btn-primary btn-sm">تعديل</button>
                     <a href="{{ route('Main_salary_employees_P_loans.delete',$info->id) }}"  class="btn  are_you_shur  btn-danger btn-sm">حذف</a>
                     @endif
                             <button data-id="{{ $info->id }}"  class="btn  load_akast_details  btn-info btn-sm">الاقساط</button>
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
