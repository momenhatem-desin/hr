<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
   <meta charset="utf-8">
   <title>مفردات مرتب</title>
   <style>
      body {
         font-family: "Tahoma", Arial, sans-serif;
         font-size: 14px;
         color: #000;
         margin: 20px;
         background: #fff;
      }

      /* زر الطباعة */
      @media print {
         #printButton {display: none;}
         body {margin: 0;}
      }

      .header {
         text-align: center;
         margin-bottom: 20px;
      }

      .header h2 {
         margin: 5px 0;
         font-size: 18px;
         font-weight: bold;
      }

      .header p {
         margin: 0;
         font-size: 14px;
      }
      .company-logo {
         position: absolute;  /* الصورة تبقى ثابتة فوق */
         top: 10px;           /* مسافة من فوق */
         left: 20px;          /* مسافة من الشمال */
      }

      .company-logo img {
          width: 206px;
      
      }

   
      table {
         width: 100%;
         border-collapse: collapse;
         margin-bottom: 20px;
      }

      th, td {
         border: 1px solid #000;
         padding: 6px 10px;
         text-align: center;
      }

      th {
         background: #f2f2f2;
         font-weight: bold;
      }

      .section-title {
         text-align: right;
         font-weight: bold;
         margin: 10px 0 5px;
         border-right: 4px solid #000;
         padding-right: 6px;
         font-size: 15px;
      }

      .note {
         background: #f9f9f9;
         font-weight: bold;
         text-align: center;
      }

      .net-salary {
         background: #eaeaea;
         font-weight: bold;
         font-size: 15px;
      }

      .footer {
         text-align: center;
         font-size: 13px;
         font-weight: bold;
         margin-top: 25px;
         border-top: 2px solid #000;
         padding-top: 10px;
      }
   </style>
</head>

<body>
    <!-- بيانات الموظف -->
@if(@isset($data) and !@empty($data) )
@foreach($data as $info)
<div class="company-logo">
   <img src="{{ asset('assets/admin/uploads').'/'.$systemData['image'] }}" alt="Logo">
</div>
    <table style="width: 60%;float: right;  margin-right: 5px;" dir="rtl">
 
      <tr>
         <td style="text-align: center;padding: 5px;font-weight: bold; border:none;"> <span style=" display: inline-block;
               width: 500px;
               height: 30px;
               text-align: center;
               color: red;
               border: 1px solid black;border-radius:10px !important;border:none; ">
               بحث   بمفردات المرتب    ({{ $finance_cln_periods_data['month']->name }}لسنة {{ $finance_cln_periods_data['FINANCE_YR'] }})
            </span>
         </td>
      </tr>
      <tr>
         <td style="text-align: center;padding: 5px;font-weight:bold;border:none;">
            <span style=" display: inline-block;
                  width: 400px;
                  height: 30px;
                  text-align: center;
                  color: blue;
                  border: 1px solid black;border-radius:10px !important ">
               طبع بتاريخ @php echo date('Y-m-d'); @endphp
            </span>
         </td>
      </tr>
      <tr>
         <td style="text-align: center;padding: 5px;font-weight: bold;border:none;">
            <span style=" display: inline-block;
                  width: 400px;
                  height: 30px;
                  text-align: center;
                  color: blue;
                  border: 1px solid black;border-radius:10px !important ">
               طبع بواسطة {{ auth()->user()->name }}
            </span>
         </td>
      </tr>
   </table>
   <table>

       <tr>
         <th style="width: 25%">اسم الموظف</th>
         <td>{{ $info->emp_name }} (كود {{ $info->employees_code }})</td>
      </tr>
      <tr>
         <th>الوظيفة</th>
         <td>{{ $info->job_name }}</td>
      </tr>
      <tr class="note">
         <td colspan="2">الرصيد المرحل من الشهر السابق: {{ $info->last_salary_remain_blance * 1 }} جنيه
            @if($info->is_stoped==1)
            (هذا المرتب موقوف)
            @endif
            
    </td>
      </tr>
   </table>

   <!-- الاستحقاقات -->
   <p class="section-title">أولاً: الاستحقاقات</p>
   <table>
      <tr><th>الراتب الأساسي</th><td>{{ $info->emp_sal * 1 }}</td></tr>
      <tr><th>حافز ثابت</th><td>{{ $info->motivation *1 }}</td></tr>
      <tr><th>مكافآت</th><td>{{ $info->additions*1 }}</td></tr>
      <tr><th>بدلات ثابتة</th><td>{{ $info->fixed_suits *1 }}</td></tr>
      <tr><th>بدلات متغيرة</th><td>{{ $info->changable_suits*1 }}</td></tr>
            <tr>
            <th>إضافي أيام<i class="fas fa-clock"></i></th>
            <td>
                @if( $info->addtional_days_counter > 0) 
                    {{ $info->addtional_days * 1 }} <br>
                    {{  $info->addtional_days_counter * 1 }} يوم
                @else
                    0
                @endif
            </td>
        </tr>
      <tr><th>إجمالي الاستحقاقات</th><td><b>{{ $info->total_benefits*1 }} جنيه</b></td></tr>
   </table>

   <!-- الاستقطاعات -->
   <p class="section-title">ثانياً: الاستقطاعات</p>
   <table>
      <tr><th>تأمين اجتماعي</th><td>{{ $info->socialinsurancecutmonthely * 1 }}</td></tr>
      <tr><th>تأمين طبي</th><td>{{ $info->medicalinsurancecutmonthely * 1 }}</td></tr>
      <tr>
            <th>جزاء أيام<i class="fas fa-exclamation-triangle"></i></th>
            <td>
                @if($info->sanctions_days_counter_type1 > 0) 
                    {{$info->sanctions_days_total_type1 * 1 }} <br>
                    {{ $info->sanctions_days_counter_type1 * 1 }} يوم
                @else
                    0
                @endif
            </td>
        </tr>
     <tr>
            <th>غياب أيام<i class="fas fa-calendar-times"></i></th>
            <td>
                @if($info->absence_days_counter > 0) 
                    {{$info->absence_days * 1 }} <br>
                    {{ $info->absence_days_counter * 1 }} يوم
                @else
                    0
                @endif
            </td>
        </tr>
      <tr><th>خصومات مالية</th><td>{{ $info->discount * 1 }}</td></tr>
      <tr><th>سلف شهرية</th><td>{{ $info->monthly_loan * 1 }}</td></tr>
      <tr><th>سلف مستديمة</th><td>{{$info->permanent_loan * 1 }}</td></tr>
      <tr><th>خصومات تليفونات</th><td>{{ $info->phones * 1 }}</td></tr>
      <tr><th>إجمالي الاستقطاعات</th><td><b>{{ $info->total_deductions * 1 }} جنيه</b></td></tr>
   </table>

   <!-- صافي المرتب -->
   <p class="section-title">ثالثاً: صافي المرتب</p>
   <table>
      <tr class="net-salary">
         <th>صافي المرتب</th>
         <td>
            @if($info->final_the_net>0)
               مستحق للموظف: {{ $info->final_the_net*1 }} جنيه
            @elseif($info->final_the_net<0)
               مستحق على الموظف: {{ $info->final_the_net*1 }} جنيه
            @else
               لا يوجد رصيد مستحق
            @endif
         </td>
      </tr>
   </table>

   <!-- الفوتر -->
   <div class="footer">
      {{ $systemData['address'] }} - {{ $systemData['phones'] }}
   </div>
 @if(!$loop->last)
      <div style="page-break-after: always;"></div>
   @endif

@endforeach
@endif
   <p class="text-center">
      <button onclick="window.print()" id="printButton">طباعة</button>
   </p>
</body>
</html>
