
@if(@isset($parent) and !@empty($parent) )

@if($parent['is_archived']==0)
<div class="row">
       <div class="col-md-4">
               <div class="form-group">
                  <label> <button  class="btn btn-xs btn-danger pull-left" id="zeroresetdatetime_In">reset</button>
                    <button  data-old="{{ $parent['datetime_In']}}"  class="btn btn-xs btn-info pull-left" style="display: none" id="undozeroresetdatetime_In">undo</button>
                     بصمه الحضور المفعله
                  </label>
                 <input data-old="{{ $parent['datetime_In']}}" type="datetime-local"  id="datetime_In" class="form-control" value="{{ $parent['datetime_In']}}" >
               </div>
       </div>
        <div class="col-md-4">
               <div class="form-group">
                  <label>  <button  class="btn btn-xs btn-danger pull-left" id="zeroresetdatetime_out">reset</button>
                     <button  data-old="{{ $parent['datetime_out']}}"  class="btn btn-xs btn-info pull-left" style="display: none" id="undozeroresetdatetime_out">undo</button>
                     بصمه الانصراف المفعله           
                  </label>
                 <input data-old="{{ $parent['datetime_out']}}" type="datetime-local"  id="datetime_out" class="form-control" value="{{ $parent['datetime_out']}}" >
               </div>
       </div>
       <div class="col-md-2">
               <div class="form-group text-center">
                  <button style="margin-top: 48px;" class="btn btn-sm btn-success pull-left" data-id="{{ $parent['id']}}" id="redo_update">تحديث</button>
                 
               </div>
            </div>
</div>
@endif
@if(@isset($attenance_departure_actions) and !@empty($attenance_departure_actions) )
<table id="example2" class="table table-bordered table-hover">
   <thead class="custom_thead">
      <th>التاريخ</th>
      <th>نوع البصمه</th>
      <th> توقيت البصمة من الجهاز الخاص بالصمه</th>
      <th>هل البصمه مفعله </th>
      <th>طريقه الاضافه</th>
       <th>السحب بواسطه</th>
     
   </thead>
   <tbody>
      @foreach ( $attenance_departure_actions as $info )
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
           <td>@if ($info->action_type==1) حضور @else انصراف @endif</td>
         <td>
                     {{ $time }}
                     {{ $newDateTimeType }}  <br>
                    
         </td>
            <td>@if ($info->it_is_active_with_parent==1) نعم @else لا @endif
             @if($info->it_is_active_with_parent==1)
             @if($info->datetimeAction==$parent['datetime_In'])
             <br>واخذت كحضور
             @endif
            @if($info->datetimeAction==$parent['datetime_out'])
             <br>واخذت كانصراف
            @endif
            @endif
            </td>

       <td>@if ($info->added_method==1) ألى @else يدوى @endif</td>
          
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

@else
<p class="bg-danger text-center"> عفوا لاتوجد بيانات لعرضها</p>
@endif
