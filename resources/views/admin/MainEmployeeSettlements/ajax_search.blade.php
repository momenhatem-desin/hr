@if(@isset($data) and !@empty($data) and count($data)>0 )
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
<div class="col-md-12 text-center" id="ajax_pagination_in_search">
{{ $data->links('pagination::bootstrap-5') }}
</div>
@else
<p class="bg-danger text-center"> عفوا لاتوجد بيانات لعرضها</p>
@endif
