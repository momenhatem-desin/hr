
@if(@isset($dataParentloan) and !@empty($dataParentloan) )
@if(@isset($aksatDetails['aksatDetalis']) and !@empty($aksatDetails['aksatDetalis']) )
 <table id="example2" class="table table-bordered table-hover">
            <tr class="bg-light">
               <td class="width30">تاريخ الاضافة</td>
                  <td>
                     @php
                     $dt=new DateTime($dataParentloan['created_at']);
                     $date=$dt->format("Y-m-d");
                     $time=$dt->format("h:i");
                     $newDateTime=date("a",strtotime($dataParentloan['created_at']));
                     $newDateTimeType= (($newDateTime=='am'||$newDateTime=='AM')?'صباحا ':'مساء'); 
                     @endphp
                     ({{ $date }})
                      ({{ $time }})
                     ({{ $newDateTimeType }} )
                      ( {{ $dataParentloan->added->name }} )
                  </td>

                @if ($dataParentloan['updated_by']>0)
                <td class="width30">تاريخ التحديث</td>
                  <td>
                     @php
                     $dt=new DateTime($dataParentloan['updated_at']);
                     $date=$dt->format("Y-m-d");
                     $time=$dt->format("h:i");
                     $newDateTime=date("a",strtotime($dataParentloan['updated_by']));
                     $newDateTimeType= (($newDateTime=='am'||$newDateTime=='AM')?'صباحا ':'مساء'); 
                     @endphp
                     ({{ $date }})
                      ({{ $time }})
                     ({{ $newDateTimeType }} )
                      ( {{ $dataParentloan->updatedby->name }} )
                  </td>  
               @endif   
               
                @if ($dataParentloan['is_dismissail_done']==1)
                <td class="width30">تاريخ الصرف</td>
                <td>
                     @php
                     $dt=new DateTime($dataParentloan['dismissail_at']);
                     $date=$dt->format("Y-m-d");
                     $time=$dt->format("h:i");
                     $newDateTime=date("a",strtotime($dataParentloan['dismissail_at']));
                     $newDateTimeType= (($newDateTime=='am'||$newDateTime=='AM')?'صباحا ':'مساء'); 
                     @endphp
                     ({{ $date }})
                      ({{ $time }})
                     ({{ $newDateTimeType }} )
                      ( {{ $dataParentloan->dismissailby->name }} )
                  </td>
                 @endif    
            </tr>

 </table>      


<p class="text-center">تفاصيل الاقساط على السلفة المستديمة للموظف</p>
<table id="example2" class="table table-bordered table-hover">
   <thead class="custom_thead">
      <th> تاريخ شهر الاستحقاق </th>
      <th>قيمة القسط الشهرى</th>
      <th>  حالة الدفع </th>
      <th>  حالة الارشفة</th>
      <th> ملاحظة</th>
   </thead>
   <tbody>
      @foreach ( $aksatDetails['aksatDetalis'] as $info )
      <tr>
         <td> {{ $info->year_and_month }} </td>
         <td> {{ $info->month_kast_value*1 }} </td>
          <td> @if($info->state==1) تم الدفع على المرتب @elseif ($info->state==2) تم الدفع كاش @else  بأنتظار الدفع @endif
           @if($info->state==0 && $info->counterBeforeNotPaid==0 && $info->is_archived==0 && $dataParentloan['is_dismissail_done']==1 )
          <button data-id="{{ $info->id }}"  data-idparent="{{ $dataParentloan['id'] }}" class="btn btn-sm btn-danger DoCachpayNow">الدفع كاش</button>
          @endif
          </td>
         <td> @if($info->is_archived==1) مؤرشف @else   لا  @endif</td>
         <td>{{ $info->notes }} </td>
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


