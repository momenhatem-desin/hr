@extends('layouts.admin')
@section('title')
<i class="fas fa-cog text-primary"></i> الضبط العام للنظام
@endsection
@section('contentheader')
<i class="fas fa-list text-info"></i> قائمة الضبط
@endsection
@section('contentheaderactivelink')
<a href="{{ route('admin_panel_settings.index') }}"><i class="fas fa-cogs text-warning"></i> الضبط العام</a>
@endsection
@section('contentheaderactive')
<i class="fas fa-eye text-success"></i> عرض
@endsection
@section('content')
<div class="col-12">
   <div class="card">
      <div class="card-header">
         <h3 class="card-title card_title_center"><i class="fas fa-info-circle text-primary"></i> بيانات الضبط العام للنظام</h3>
      </div>
      <div class="card-body">
         @if(@isset($data) and !@empty($data))
         <table id="example2" class="table table-bordered table-hover">
            <tr class="bg-light">
               <td class="width30"><i class="fas fa-building text-info"></i> اسم الشركة</td>
               <td>{{ $data['company_name'] }}</td>
            </tr>
            <tr>
               <td class="width30"><i class="fas fa-power-off text-dark"></i> حالة التفعيل</td>
               <td>@if($data['saysem_status']==1) <i class="fas fa-check-circle text-success"></i> مفعل @else <i class="fas fa-times-circle text-danger"></i> معطل @endif</td>
            </tr>
            <tr class="bg-light">
               <td class="width30"><i class="fas fa-phone text-primary"></i> هاتف الشركة</td>
               <td>{{ $data['phones'] }}</td>
            </tr>
            <tr>
               <td class="width30"><i class="fas fa-map-marker-alt text-danger"></i> عنوان الشركة</td>
               <td>{{ $data['address'] }}</td>
            </tr>
            <tr class="bg-light">
               <td class="width30"><i class="fas fa-envelope text-warning"></i> بريد الشركة</td>
               <td>{{ $data['email'] }}</td>
            </tr>
            <tr>
               <td class="width30"><i class="fas fa-clock text-secondary"></i> بعد كم دقيقة نحسب تاخير حضور</td>
               <td>{{ $data['after_miniute_calculate_delay'] }}</td>
            </tr>
            <tr class="bg-light">
               <td class="width30"><i class="fas fa-clock text-secondary"></i> بعد كم دقيقة نحسب انصراف مبكر</td>
               <td>{{ $data['after_miniute_calculate_delay'] }}</td>
            </tr>
            <tr>
               <td class="width30"><i class="fas fa-stopwatch text-info"></i> بعد كم مرة تأخير او انصارف مبكر نخصم ربع يوم</td>
               <td>{{ $data['after_miniute_quarterday'] }}</td>
            </tr>
            <tr class="bg-light">
               <td class="width30"><i class="fas fa-hourglass-half text-warning"></i> بعد كم مرة تأخير او انصارف مبكر نخصم نص يوم</td>
               <td>{{ $data['after_time_half_daycut'] }}</td>
            </tr>
            <tr>
               <td class="width30"><i class="fas fa-calendar-minus text-danger"></i> نخصم بعد كم مره تاخير او انصارف مبكر يوم كامل</td>
               <td>{{ $data['after_time_allday_daycut'] }}</td>
            </tr>
               <tr>
               <td class="width30"><i class="fas fa-calendar-minus text-danger"></i> يتم حساب ساعه الاضافى ب </td>
               <td>{{ $data['number_addinal_get'] }}</td>
            </tr>
              <tr>
               <td class="width30"><i class="fas fa-calendar-minus text-danger"></i>اقل من كام دقيقه فرق بين البصمه الاولى والثانيه يتم اهمال البصمه</td>
               <td>{{ $data['less_than_miniute_neglecting_passmaa'] }}</td>
            </tr>
                 <tr>
               <td class="width30"><i class="fas fa-calendar-minus text-danger"></i>الحد الاقصى لاحتساب عدد ساعات عمل اضافيه عند انصراف الموظف واحتساب بصمه الانصراف والا ستحتسب على أنها بصمه حضور شفت جديد	</td>
               <td>{{ $data['max_hours_take_pasma_as_addtional'] }}</td>
            </tr>
            <tr class="bg-light">
               <td class="width30"><i class="fas fa-umbrella-beach text-success"></i> رصيد اجازات الموظف الشهري</td>
               <td>{{ $data['monthly_vacation_balance'] }}</td>
            </tr>
             <tr class="bg-light">
               <td class="width30"><i class="fas fa-umbrella-beach text-success"></i> هل يوجد اجازات تحسب على عدد ايام الحضور فقط </td>
               <td> @if($data['type_vacation']==1) له حساب اخر حسب الحضور @else سنوى فقط @endif </td>
            </tr>
            
             <tr class="bg-light">
               <td class="width30"><i class="fas fa-umbrella-beach text-success"></i> بعد كام يوم حضور يحسب يوم أجازة</td>
               <td>{{ $data['quinty_vacstion'] }}</td>
            </tr>
            <tr>
               <td class="width30"><i class="fas fa-calendar-day text-primary"></i> بعد كم يوم ينزل للموظف رصيد اجازات</td>
               <td>{{ $data['after_days_begin_vacation'] }}</td>
            </tr>
             <tr>
               <td class="width30"><i class="fas fa-power-off text-dark"></i>هل يتم ترحيل الاجازات بعد انتهاء السنه الماليه </td>
               <td>@if($data['is_transfer_vaccation']==1) <i class="fas fa-check-circle text-success"></i> مفعل @else <i class="fas fa-times-circle text-danger"></i> معطل @endif</td>
            </tr>
             <tr>
               <td class="width30"><i class="fas fa-power-off text-dark"></i>هل يتم سحب ايام غياب السنوى تلقائى من تقفيل البصمه</td>
               <td>@if($data['is_pull_anuall_day_from_passma']==1) <i class="fas fa-check-circle text-success"></i> مفعل @else <i class="fas fa-times-circle text-danger"></i> معطل @endif</td>
            </tr>
           <tr>
               <td class="width30"><i class="fas fa-power-off text-dark"></i>هل يتم اسقاط متغيرات البصمه او سجل الحضور على المرتبات</td>
               <td>@if($data['is_outo_offect_passmaV']==1) <i class="fas fa-check-circle text-success"></i> توثر عند سحب البصمه فقط  @elseif($data['is_outo_offect_passmaV']==2) توثر تلقائى @elseif($data['is_outo_offect_passmaV']==3)توثر عند لطلب فقط@else <i class="fas fa-times-circle text-danger"></i> معطل @endif</td>
            </tr>
            <tr class="bg-light">
               <td class="width30"><i class="fas fa-calendar-plus text-info"></i> الرصيد الاولي المرحل عند تفعيل الاجازات للموظف</td>
               <td>{{ $data['first_balance_begin_vacation'] }}</td>
            </tr>
            <tr>
               <td class="width30"><i class="fas fa-exclamation-triangle text-warning"></i> قيمة خصم الايام بعد اول مرة غياب بدون اذن</td>
               <td>{{ $data['sanctions_value_first_abcence'] }}</td>
            </tr>
            <tr class="bg-light">
               <td class="width30"><i class="fas fa-exclamation-circle text-warning"></i> قيمة خصم الايام بعد ثاني مرة غياب بدون اذن</td>
               <td>{{ $data['sanctions_value_second_abcence'] }}</td>
            </tr>
            <tr>
               <td class="width30"><i class="fas fa-ban text-danger"></i> قيمة خصم الايام بعد ثالث مرة غياب بدون اذن</td>
               <td>{{ $data['sanctions_value_thaird_abcence'] }}</td>
            </tr>
            <tr class="bg-light">
               <td class="width30"><i class="fas fa-skull-crossbones text-danger"></i> قيمة خصم الايام بعد رابع مرة غياب بدون اذن</td>
               <td>{{ $data['sanctions_value_forth_abcence'] }}</td>
            </tr>
              <tr class="bg-light">
               <td class="width30"><i class="fas fa-skull-crossbones text-danger"></i>شعار الشركة</td>
             <td>  <img src="{{ asset('assets/admin/uploads').'/' . $data['image'] }}" border-raduis: 50%; width="80px"; height="80px"; class="rounded-circle" alt=" شعار الشركة"> </td>
            </tr>
            @if(auth()->user()->is_master_admin==1 or check_permission_sub_menue_actions(2)==true)
            <tr class="text-center">
               <td colspan="2">
                  <a href="{{ route('admin_panel_settings.edit') }}" class="btn btn-sm btn-info">
                     <i class="fas fa-edit text-white"></i> تعديل
                  </a>
               </td>
            </tr>
          @endif
         </table>
         @else
         <div class="alert alert-danger text-center">
            <i class="fas fa-exclamation-triangle text-white"></i> عفوا لاتوجد بيانات لعرضها
         </div>
         @endif
      </div>
   </div>
</div>
@endsection