@if (@isset($data) && !@empty($data) && count($data) >0)
@php
$i=1;   
@endphp
<table id="example2" class="table table-bordered table-hover">
   <thead class="custom_thead">
      <th>مسلسل</th>
      <th>اسم المستخدم</th>
      <th>دور صلاحية المستخدم </th>
      <th>حالة التفعيل</th>
       <th>  الاضافة بواسطة</th>
      <th>  التحديث بواسطة</th>
      <th></th>
   </thead>
   <tbody>
      @foreach ($data as $info )
      <tr>
         <td>{{ $i }}</td>
         <td>{{ $info->name }}</td>
         <td>{{ $info->permission_roles_name }}</td>
         <td>@if($info->active==1) مفعل @else معطل @endif</td>
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
            @if( auth()->user()->is_master_admin==1 or check_permission_sub_menue_actions(157)) 
            <a href="{{ route('admin.admins_accounts.edit',$info->id) }}" class="btn btn-sm  btn-primary">تعديل</a>   
            @endif
         </td>
      </tr>
      @php
      $i++; 
      @endphp
      @endforeach
   </tbody>
</table>
<br>
<div class="col-md-12" id="ajax_pagination_in_search">
   {{ $data->links() }}
</div>
@else
<div class="clearfix"></div>
<div class="alert alert-danger">
   عفوا لاتوجد بيانات لعرضها !!
</div>
@endif