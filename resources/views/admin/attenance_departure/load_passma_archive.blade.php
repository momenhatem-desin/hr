
@if(@isset($attenance_departure_actions_excel) and !@empty($attenance_departure_actions_excel) )
<table id="example2" class="table table-bordered table-hover">
   <thead class="custom_thead">
      <th>التاريخ</th>
      <th> التوقيت بنظام 12ساعه</th>
       <th> التوقيت بنظام 24ساعه</th>
      <th>نوع البصمه</th>
      <th>السحب بواسطه</th>
     
   </thead>
   <tbody>
      @foreach ( $attenance_departure_actions_excel as $info )
      <tr>
         <td>
                     @php
                     $dt=new DateTime($info->datetimeAction);
                     $date=$dt->format("Y-m-d");
                     $time=$dt->format("h:i");
                     $newDateTime=date("a",strtotime($info->datetimeAction));
                     $newDateTimeType= (($newDateTime=='am'||$newDateTime=='AM')?'صباحا ':'مساء'); 
                     @endphp
                     {{$info->week_day_name_arbic}}
                     {{ $date }} <br>
         </td>
         <td>
                
                    
                     <span class=""></span>
                        {{ $time }}
                        {{ $newDateTimeType }}  <br>
                    
         </td>
         <td> @php echo date('H:i',strtotime($info->datetimeAction)) @endphp</td>
         <td>@if ($info->action_type==1) حضور @else انصراف @endif</td>
    
        <td> 
                  @php
                     $dt=new DateTime($info->created_at);
                     $date=$dt->format("Y-m-d");
                     $time=$dt->format("h:i");
                     $newDateTime=date("a",strtotime($info->created_at));
                     $newDateTimeType= (($newDateTime=='am'||$newDateTime=='AM')?'صباحا ':'مساء'); 
                     @endphp
                     {{ $date }} 
                       {{ $time }}
                     {{ $newDateTimeType }}  
                     {{ $info->added->name }} 
        </td>
      
      </tr>
      @endforeach
   </tbody>
</table>
@else
<p class="bg-danger text-center"> عفوا لاتوجد بيانات لعرضها</p>
@endif
