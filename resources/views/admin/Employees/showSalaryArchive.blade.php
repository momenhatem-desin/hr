
@if(@isset($data) and !@empty($data) )
<table id="example2" class="table table-bordered table-hover">
   <thead class="custom_thead">
      <th>قيمة الراتب</th>
      <th>  تاريخ التغير</th>
      <th>  التحديث بواسطة</th>
   </thead>
   <tbody>
      @foreach ( $data as $info )
      <tr>
         <td> {{ $info->value*1 }} </td>
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
          <td>{{ $info->added->name }} </td>      
      </tr>
      @endforeach
   </tbody>
</table>
@else
<p class="bg-danger text-center"> عفوا لاتوجد بيانات لعرضها</p>
@endif
