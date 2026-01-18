@extends('layouts.admin')
@section('title')
الاجور
@endsection
@section('contentheader')
قائمة الاجور
@endsection
@section("css")
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection
@section('contentheaderactivelink')
<a href="{{ route('Main_salary_employee.index') }}">   عرض الرواتب المفصله  </a>
@endsection
@section('contentheaderactive')
عرض
@endsection
@section('content')
<style>
.modal-xl{
   max-width: 100%;
   margin: 0 auto;
   padding:0 !important;
}
</style>
<div class="col-12">
   <div class="card">
      <div class="card-header">
         <h3 class="card-title card_title_center"><i class="fas fa-money-bill-wave"></i>  تفاصيل مرتب موظف للشهر المالى({{ $finance_cln_periods_data['month']->name }}لسنة {{ $finance_cln_periods_data['FINANCE_YR'] }})
          <a class="btn btn-info btn-sm" style="color: white" href="{{ route("Main_salary_employee.show",$finance_cln_periods_data['id']) }}">عودة لأجور الشهر  <i class="fas fa-arrow-right"></i></a>
            <a target="_blank" class="btn btn-success btn-sm" style="color: white" href="{{ route("Main_salary_employee.print_salary",$Main_salary_employee_Data['id']) }}">   طباعة مفردات المرتب </a>
         </h3>
      </div>
   
<div class="card-body">
    @if(isset($Main_salary_employee_Data) && !empty($Main_salary_employee_Data))

    <style>
        body {
            font-family: "Cairo", Tahoma, sans-serif;
            background: #f5f5f5;
        }

        table.custom-table {
            border-collapse: collapse;
            width: 97%;
            margin: 15px auto;
            background: #fff;
            font-size: 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            overflow: hidden;
        }

        table.custom-table th,
        table.custom-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            color: #222;
        }

        /* أول صف */
        table.custom-table tr:first-child th,
        table.custom-table tr:first-child td {
            background: #f2f2f2;
            font-weight: bold;
        }

        /* آخر صف */
        table.custom-table tr:last-child th,
        table.custom-table tr:last-child td {
            background: #f2f2f2;
            font-weight: bold;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin: 25px 0 15px;
            text-align: right;
            color: #333;
            border-right: 4px solid #666;
            padding-right: 8px;
        }

        .note-row {
            background-color: #f9f9f9 !important;
            color: #000 !important;
            font-weight: bold;
        }

        .rotate-text {
            writing-mode: vertical-rl;
            transform: rotate(180deg);
            font-weight: bold;
            background: #fafafa;
            color: #333;
            min-width: 30px;
        }

        /* صافي المرتب */
        .net-salary {
            background-color: #e8f7e1;
            font-weight: bold;
            color: #1a4d1a;
        }

        .net-salary.negative {
            background-color: #fdeaea;
            color: #a11;
        }

        /* للطباعة */
        @media print {
            body {
                background: #fff;
            }
            table.custom-table {
                box-shadow: none;
                width: 100%;
                font-size: 14px;
            }
        }
    </style>
   @if($finance_cln_periods_data['is_open'] == 1 && $Main_salary_employee_Data['is_archived'] == 0)
    @if($Main_salary_employee_Data['is_stoped'] == 0)
    <p>
        <a id="DoStopSalary" href="{{ route('Main_salary_employee.DoStopSalary', $Main_salary_employee_Data['id']) }}" 
           class="btn btn-danger btn-sm are_you_shur">إيقاف المرتب مؤقتًا <i class="fas fa-pause-circle"></i></a>
    <a  href="{{ route('Main_salary_employee.delete_salaryInternal', $Main_salary_employee_Data['id']) }}" 
           class="btn btn-danger btn-sm are_you_shur"> حذف المرتب  <i class="fas fa-trash-alt"></i></a>
           <button class="btn btn-sm btn-success load_archive_salary" data-id="{{  $Main_salary_employee_Data['id']}}">ارشفة المرتب<i class="fas fa-archive"></i></button>
    </p>      
    @else
        هذا المرتب موقوف حاليا
        <a href="{{ route('Main_salary_employee.UnStopSalary', $Main_salary_employee_Data['id']) }}" 
           class="btn btn-success btn-sm">إلغاء إيقاف المرتب<i class="fas fa-play-circle"></i></a>
    @endif
@endif
    <!-- بيانات الموظف -->
    <table class="custom-table" dir="rtl">
        <tr>
            <th style="width: 20%">اسم الموظف<i class="fas fa-user"></i></th>
            <td>
                {{ $Main_salary_employee_Data['emp_name'] }}
                (كود {{ $Main_salary_employee_Data['employees_code'] }})
            </td>
        </tr>
        <tr>
            <th>الوظيفة<i class="fas fa-briefcase"></i></th>
            <td>{{ $Main_salary_employee_Data['job_name'] }}</td>
        </tr>
        <tr class="note-row">
            <td colspan="2">
                <i class="fas fa-info-circle"></i>
                ملحوظة: الرصيد المرحل من الشهر السابق =
                {{ $Main_salary_employee_Data['last_salary_remain_blance'] * 1 }} جنيه
            </td>
        </tr>
    </table>

    <!-- الاستحقاقات -->
    <p class="section-title">أولاً: الاستحقاقات<i class="fas fa-plus-circle text-success"></i></p>
    <table class="custom-table" dir="rtl">
        <tr>
         <td rowspan="8" class="rotate-text normal-cell">الاستحقاقات <i class="fas fa-hand-holding-usd"></i></td>
            <th colspan="2">الراتب الأساسي<i class="fas fa-money-check"></i></th>
            <td>{{ $Main_salary_employee_Data['emp_sal'] * 1 }}</td>
        </tr>
        <tr>
            <th colspan="2">حافز ثابت<i class="fas fa-gift"></i></th>
            <td>{{ $Main_salary_employee_Data['motivation']*1 }}</td>
        </tr>
        <tr>
            <th rowspan="2">مكافآت<i class="fas fa-award"></i></th>
            <td>مكافأة مالية</td>
            <td>{{ $Main_salary_employee_Data['additions']*1 }}</td>
        </tr>
        <tr></tr>
        <tr>
            <th rowspan="2">بدلات<i class="fas fa-hand-holding-heart"></i></th>
            <td>بدلات ثابتة</td>
            <td>{{ $Main_salary_employee_Data['fixed_suits']*1 }}</td>
        </tr>
        <tr>
            <td>بدلات متغيرة</td>
            <td>{{ $Main_salary_employee_Data['changable_suits']*1 }}</td>
        </tr>
        <tr>
            <th rowspan="2">الإضافي<i class="fas fa-clock"></i></th>
            <td>عدد أيام الإضافي</td>
            <td>{{ $Main_salary_employee_Data['addtional_days_counter']*1 }}</td>
        </tr>
        <tr>
            <td>قيمة الإضافي</td>
            <td>{{ $Main_salary_employee_Data['addtional_days'] *1}}</td>
        </tr>
        <tr>
            <td colspan="3">إجمالي الاستحقاقات<i class="fas fa-calculator"></i></td>
            <td>{{ $Main_salary_employee_Data['total_benefits']*1}} جنيه</td>
        </tr>
    </table>

    <!-- الاستقطاعات -->
    <p class="section-title">ثانيا: الاستقطاعات<i class="fas fa-minus-circle text-danger"></i></p>
    <table class="custom-table" dir="rtl">
        <tr>
            <td rowspan="8" class="rotate-text normal-cell">الاستقطاعات<i class="fas fa-money-bill-alt"></i></td>
            <th>خصم تأمين اجتماعي<i class="fas fa-shield-alt"></i></th>
            <td>{{ $Main_salary_employee_Data['socialinsurancecutmonthely'] * 1 }}</td>
        </tr>
        <tr>
            <th>خصم تأمين طبي<i class="fas fa-heartbeat"></i></th>
            <td>{{ $Main_salary_employee_Data['medicalinsurancecutmonthely'] * 1 }}</td>
        </tr>
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
        <tr>
            <th>خصومات مالية<i class="fas fa-money-bill-wave"></i></th>
            <td>{{ $Main_salary_employee_Data['discount'] * 1 }}</td>
        </tr>
        <tr>
            <th>سلف شهرية<i class="fas fa-hand-holding-usd"></i></th>
            <td>{{ $Main_salary_employee_Data['monthly_loan'] * 1 }}</td>
        </tr>
        <tr>
            <th>سلف مستديمة<i class="fas fa-hand-holding-usd"></i></th>
            <td>{{ $Main_salary_employee_Data['permanent_loan'] * 1 }}</td>
        </tr>
        <tr>
            <th>خصومات تليفونات<i class="fas fa-phone"></i></th>
            <td>{{ $Main_salary_employee_Data['phones'] * 1 }}</td>
        </tr>
        <tr>
            <th colspan="2">إجمالي الاستقطاعات<i class="fas fa-calculator"></i> </th>
            <td>{{ $Main_salary_employee_Data['total_deductions'] * 1 }} جنيه</td>
        </tr>
    </table>

    <!-- صافي المرتب -->
    <p class="section-title">ثالثا: صافي المرتب</p>
    <table class="custom-table" dir="rtl">
        <tr class="@if($Main_salary_employee_Data['final_the_net'] < 0) net-salary negative @else net-salary @endif">
            <th style="width: 20%">صافي المرتب<i class="fas fa-calculator"></i></th>
            <td>
                @if($Main_salary_employee_Data['final_the_net']>0)
                    مبلغ مستحق للموظف بقيمة ({{ $Main_salary_employee_Data['final_the_net']*1 }})
                @elseif($Main_salary_employee_Data['final_the_net']<0)
                    مبلغ مستحق على الموظف بقيمة ({{ $Main_salary_employee_Data['final_the_net']*1 }})
                @else
                    لا يوجد رصيد مستحق
                @endif
            </td>
        </tr>
        @if($Main_salary_employee_Data['is_archived']==1)
         <td style="width: 20%">تم ارشفة المرتب</td>
                  <td>
                     @php
                     $dt=new DateTime($Main_salary_employee_Data['archived_date']);
                     $date=$dt->format("Y-m-d");
                     $time=$dt->format("h:i");
                     $newDateTime=date("a",strtotime($Main_salary_employee_Data['archived_date']));
                     $newDateTimeType= (($newDateTime=='am'||$newDateTime=='AM')?'صباحا ':'مساء'); 
                     @endphp
                     {{ $date }}  <br>
                     {{ $time }}
                     {{ $newDateTimeType }}  <br>
                     {{ $Main_salary_employee_Data['arcivedBy']->name }} 
                  </td>
        @endif

    </table>

    <!-- التاريخ -->
    <p style="text-align: left; padding-left: 20%; font-weight: bold; font-size: 14px; color: black;">
        {{ date('d-m-Y') }}
    </p>

    @else
        <p class="bg-danger text-center text-white p-2">عفوا لا توجد بيانات لعرضها</p>
    @endif
</div>


 

<div class="modal fade" id="load_archive_salaryModal">
  <div class="modal-dialog modal-xl" style="max-width:70%;">
    <div class="modal-content bg-info">    

      <div class="modal-header">
        <h4 class="modal-title">أرشفة راتب موظف</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body" id="load_archive_salaryModalBody" style="background-color: white; color:black;">
      </div>

      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
      </div>

    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

@endsection
@section('script')
<script src="{{ asset('assets/admin/plugins/select2/js/select2.full.min.js') }}"> </script>
<script>
     //Initialize Select2 Elements
   $('.select2').select2({
     theme: 'bootstrap4'
   });

   $(document).ready(function(){

    $(document).on('click','.load_archive_salary',function(e){
   
    var id=$(this).data("id");
      jQuery.ajax({
      url:'{{ route('Main_salary_employee.load_archive_salary') }}',
      type:'post',
      'dataType':'html',
      cache:false,
      data:{"_token":'{{ csrf_token() }}',id:id},
      success: function(data){
      $("#load_archive_salaryModalBody").html(data);
      $("#load_archive_salaryModal").modal("show");
      $('.select2').select2();
      },
      error:function(){
      alert("عفوا لقد حدث خطأ ");
      }
      
      });


 });

   });
   
</script>
@endsection