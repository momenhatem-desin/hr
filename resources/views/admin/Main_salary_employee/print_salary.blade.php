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

 <table style="width: 60%;float: right;  margin-right: 5px;" dir="rtl">
 
      <tr>
         <td style="text-align: center;padding: 5px;font-weight: bold; border:none;"> <span style=" display: inline-block;
               width: 500px;
               height: 30px;
               text-align: center;
               color: red;
               border: 1px solid black;border-radius:10px !important;border:none; ">
               بحث  بجزاءات ايام الرواتب بالشهر المالى ({{ $finance_cln_periods_data['month']->name }}لسنة {{ $finance_cln_periods_data['FINANCE_YR'] }})
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
<div class="company-logo">
   <img src="{{ asset('assets/admin/uploads').'/'.$systemData['image'] }}" alt="Logo">
</div>



   <!-- بيانات الموظف -->
   <table>
      <tr>
         <th style="width: 25%">اسم الموظف</th>
         <td>{{ $Main_salary_employee_Data['emp_name'] }} (كود {{ $Main_salary_employee_Data['employees_code'] }})</td>
      </tr>
      <tr>
         <th>الوظيفة</th>
         <td>{{ $Main_salary_employee_Data['job_name'] }}</td>
      </tr>
      <tr class="note">
         <td colspan="2">الرصيد المرحل من الشهر السابق: {{ $Main_salary_employee_Data['last_salary_remain_blance'] * 1 }} جنيه
            @if($Main_salary_employee_Data['is_stoped']==1)
            (هذا المرتب موقوف)
            @endif
            
    </td>
      </tr>
   </table>

   <!-- الاستحقاقات -->
   <p class="section-title">أولاً: الاستحقاقات</p>
   <table>
      <tr><th>الراتب الأساسي</th><td>{{ $Main_salary_employee_Data['emp_sal'] * 1 }}</td></tr>
      <tr><th>حافز ثابت</th><td>{{ $Main_salary_employee_Data['motivation']*1 }}</td></tr>
      <tr><th>مكافآت</th><td>{{ $Main_salary_employee_Data['additions']*1 }}</td></tr>
      <tr><th>بدلات ثابتة</th><td>{{ $Main_salary_employee_Data['fixed_suits']*1 }}</td></tr>
      <tr><th>بدلات متغيرة</th><td>{{ $Main_salary_employee_Data['changable_suits']*1 }}</td></tr>
     
       <tr>
            <th>إضافي أيام<i class="fas fa-clock"></i></th>
            <td>
                @if($Main_salary_employee_Data['addtional_days_counter'] > 0) 
                    {{ $Main_salary_employee_Data['addtional_days'] * 1 }} <br>
                    {{ $Main_salary_employee_Data['addtional_days_counter'] * 1 }} يوم
                @else
                    0
                @endif
            </td>
        </tr>
      <tr><th>إجمالي الاستحقاقات</th><td><b>{{ $Main_salary_employee_Data['total_benefits']*1 }} جنيه</b></td></tr>
   </table>

   <!-- الاستقطاعات -->
   <p class="section-title">ثانياً: الاستقطاعات</p>
   <table>
      <tr><th>تأمين اجتماعي</th><td>{{ $Main_salary_employee_Data['socialinsurancecutmonthely'] * 1 }}</td></tr>
      <tr><th>تأمين طبي</th><td>{{ $Main_salary_employee_Data['medicalinsurancecutmonthely'] * 1 }}</td></tr>
      <tr>
            <th>جزاء أيام<i class="fas fa-exclamation-triangle"></i></th>
            <td>
                @if($Main_salary_employee_Data['sanctions_days_counter_type1'] > 0) 
                    {{ $Main_salary_employee_Data['sanctions_days_total_type1'] * 1 }} <br>
                    {{ $Main_salary_employee_Data['sanctions_days_counter_type1'] * 1 }} يوم
                @else
                    0
                @endif
            </td>
        </tr>
     <tr>
            <th>غياب أيام<i class="fas fa-calendar-times"></i></th>
            <td>
                @if($Main_salary_employee_Data['absence_days_counter'] > 0) 
                    {{ $Main_salary_employee_Data['absence_days'] * 1 }} <br>
                    {{ $Main_salary_employee_Data['absence_days_counter'] * 1 }} يوم
                @else
                    0
                @endif
            </td>
        </tr>
      <tr><th>خصومات مالية</th><td>{{ $Main_salary_employee_Data['discount'] * 1 }}</td></tr>
      <tr><th>سلف شهرية</th><td>{{ $Main_salary_employee_Data['monthly_loan'] * 1 }}</td></tr>
      <tr><th>سلف مستديمة</th><td>{{ $Main_salary_employee_Data['permanent_loan'] * 1 }}</td></tr>
      <tr><th>خصومات تليفونات</th><td>{{ $Main_salary_employee_Data['phones'] * 1 }}</td></tr>
      <tr><th>إجمالي الاستقطاعات</th><td><b>{{ $Main_salary_employee_Data['total_deductions'] * 1 }} جنيه</b></td></tr>
   </table>

   <!-- صافي المرتب -->
   <p class="section-title">ثالثاً: صافي المرتب</p>
   <table>
      <tr class="net-salary">
         <th>صافي المرتب</th>
         <td>
            @if($Main_salary_employee_Data['final_the_net']>0)
               مستحق للموظف: {{ $Main_salary_employee_Data['final_the_net']*1 }} جنيه
            @elseif($Main_salary_employee_Data['final_the_net']<0)
               مستحق على الموظف: {{ $Main_salary_employee_Data['final_the_net']*1 }} جنيه
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

   <p class="text-center">
      <button onclick="window.print()" id="printButton">طباعة</button>
   </p>
</body>
</html>
