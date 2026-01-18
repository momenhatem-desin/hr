<!DOCTYPE html>
<html>

<head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <title>حضور وانصراف الموظف</title>
   <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
   <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap_rtl-v4.2.1/bootstrap.min.css')}}">
   <style>
      @media print {
         .hidden-print {
            display: none;
         }
      }

      @media print {
         #printButton {
            display: none;
         }
      }

      td {
         font-size: 15px !important;
         text-align: center;
      }
      th{
         text-align: center;
      }
   </style>

<body style="padding-top: 10px;font-family: tahoma;">


   <p style="text-align: center;">
               سجل تقفيل بصمه الموظف({{ $finance_cln_periods_data['month']->name }}لسنة {{ $finance_cln_periods_data['FINANCE_YR'] }})
   </p>
 

   <br>

    <table dir="rtl" collspacing="1" collpadding="3" border="1" style="text-align:right;width:95%; margin:0 auto;">
      <tbody>
         <tr>
            <td style="font-weight:bold;">الفرع</td>
            <td style="font-weight:bold;"> كود الموظف</td>
            <td style="font-weight:bold;"> اسم الموظف</td>
            <td style="font-weight:bold;">اسم الوظيفه</td>
            <td style="font-weight:bold;"> الحاله الوظفيه</td>
            <td style="font-weight:bold;">عدد ساعات العمل</td>
            <td style="font-weight:bold;">تاريخ التعين</td>
         </tr>  
         <tr>
            <td>{{  $other['Employee_Date']['branch_name'] }}</td>
            <td>{{ $other['Employee_Date']['employees_code'] }}</td>
            <td>{{ $other['Employee_Date']['emp_name'] }}</td>
            <td>{{ $other['Employee_Date']['jop_name'] }}</td>
            <td>
              @if( $other['Employee_Date']['Functiona_status']==1) بالخدمه@else خارج الخدمه
              @endif
            </td>
            <td>{{ $other['Employee_Date']['daily_work_hour']*1 }}</td>
            <td>{{ $other['Employee_Date']['emp_start_date'] }}</td> 
         </tr>
      </tbody>
    </table>
<br>
 @if(@isset($other['data']) and !@empty($other['data']) and count($other['data'])>0 )
        <table dir="rtl" collspacing="1" collpadding="3" border="1" style="text-align:right;width:95%; margin:0 auto;">
            <thead class="custom_thead">
                <tr>
                    <th>التاريخ</th>
                    <th>الحضور</th>
                    <th>الانصراف</th>
                    <th>متغيرات</th>
                    <th>نوع الحركه</th>
                    <th>حضور متأخر</th>
                    <th>انصراف مبكر</th>
                    <th>إذن ساعات</th>
                    <th>ساعات عمل</th>
                    <th>غياب ساعات</th>
                    <th>إضافي ساعات</th>
                  
                </tr>
            </thead>
            <tbody>
                @foreach ( $other['data'] as $info )
                    <tr @if($info->datetime_In==null and $info->datetime_out==null) style="background-color:#f2dede" @endif>
                        <td>
                                    {{ $info->the_day_date }}
                                
                                    {{ $info->week_day_name_arbic }}
                  
                        </td>
                        <td>
                         
                              
                                 {{ $info->timeIn }}  
      
                        </td>
                      <td>{{ $info->timeOut }}</td>
                        
                        <td>
                           
                               {{ $info->variables }}
                           
                        </td>
                        <td>
    
                         {{ $info->name_vac }}     
                        </td>

                        <td>
                           
                               {{ $info->attendance_dely*1 }}
                           
                        </td>
                       
                        <td>
                           
                               {{ $info->early_departure*1 }}
                         
                        </td>
                        <td>
                         
                            {{ $info->azn_houres }} 
                            
                        </td>
                        <td>
                           
                               {{ $info->total_hours*1 }}
                            
                        </td>
                        <td>
                          
                             {{ $info->absen_hours*1 }}
                          
                        </td>
                        <td>
                          
                               {{ $info->additional_hours*1 }}
                          
                        </td>
                       
                    </tr>
                @endforeach
                <!-- صف الإجماليات -->
                <tr>
                    <td colspan="4" style="font-weight: 800; font-size: 16px;">الإجماليات</td>
                   
                   
                    <td style=" border-radius: 5px; padding: 10px;">
                        {{ $other['attendance_dely_total']*1 }} دقيقة
                    </td>
                    <td style=" border-radius: 5px; padding: 10px;">
                        {{ $other['early_departure_total']*1 }} دقيقة
                    </td>
                    <td></td>
                    <td style=" border-radius: 5px; padding: 10px;">
                        {{ $other['total_hours_total']*1 }} ساعة
                    </td>
                    <td style=" border-radius: 5px; padding: 10px;">
                        {{ $other['absen_hours_total']*1 }} ساعة
                    </td>
                    <td colspan="2" style=" border-radius: 5px; padding: 10px;">
                        {{ $other['additional_hours_total']*1 }} ساعة
                    </td>
                </tr>
            </tbody>
        </table>
 
@else
    <div class="no-data-message">
        <p class="bg-danger text-center">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            عذراً، لا توجد بيانات لعرضها
        </p>
    </div>
@endif


  
   <div class="clearfix"></div> <br>
   <p class="text-center">
      <button onclick="window.print()" class="btn btn-success btn-sm" id="printButton">طباعة</button>
   </p>
</body>

</html>