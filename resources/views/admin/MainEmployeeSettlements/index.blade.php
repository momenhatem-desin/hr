@extends('layouts.admin')
@section('title')
التسويات 
@endsection
@section('contentheader')
قائمة التسويات
@endsection
@section('contentheaderactivelink')
<a href="{{ route('MainEmployeeSettlements.index') }}">تسويات المرتبات</a>
@endsection
@section('contentheaderactive')
عرض
@endsection
@section('content')
<div class="col-12">
   <div class="card">
      <div class="card-header">
         <h3 class="card-title card_title_center">  بيانات  التسويات للموظفين 
        
         </h3>
      </div>
      <div class="row" style="padding: 5px;">  
      </div>
      <div class="card-body" id="ajax_responce_serachDiv" style="padding: 0px 5px">
         @if(@isset($data) and !@empty($data) and count($data)>0 )
            <table id="example2" class="table table-bordered table-hover">
         <thead class="custom_thead">
            <th>  اسم الشهر </th>
            <th> تاريخ البداية</th>
            <th> تاريخ النهاية</th>
            <th>بداية البصمة</th>
            <th>نهاية البصمة</th>
            <th> عدد الايام</th>
            <th> حالة الشهر</th>
       
         
         </thead>
         <tbody>
            @foreach ( $data as $info )
            <tr>
               <td> {{ $info->Month->name }} </td>
               <td> {{ $info->START_DATE_M }} </td>
               <td> {{ $info->END_DATE_M }} </td>
               <td> {{ $info->start_date_for_pasma }} </td>
               <td> {{ $info->end_date_for_pasma }} </td>
               <td> {{ $info->number_of_days }} </td>
               <td> @if($info->is_open==1) مفتوح @elseif ($info->is_open==2)  مغلق و مؤرشف @else  بأنتظار الفتح 
                 @endif
                  @if($info->is_open !=0)
                 <a href="{{ route('MainEmployeeSettlements.show',$info->id) }}"  class="btn  btn-success btn-sm"> عرض </a>
                  
                 
                 @endif
               </td>
            </tr>
            @endforeach
         </tbody>
      </table>
         <br>
         <div class="col-md-12 text-center">
            {{ $data->links('pagination::bootstrap-5') }}
         </div>
         @else
         <p class="bg-danger text-center"> عفوا لاتوجد بيانات لعرضها</p>
         @endif
      </div>
   </div>
</div>

@endsection
@section('script')
<script>
   $(document).ready(function(){
   
      $(document).on('change','#type_search',function(e){
         ajax_search();
      });
      $(document).on('input','#hour_from_range',function(e){
         ajax_search();
      });
   
      $(document).on('input','#hour_to_range',function(e){
         ajax_search();
      });
   function ajax_search(){
   var type_search=$("#type_search").val();
   var hour_from_range=$("#hour_from_range").val();
   var hour_to_range=$("#hour_to_range").val();
   jQuery.ajax({
   url:'{{ route('MainEmployeeSettlements.ajax_search') }}',
   type:'post',
   'dataType':'html',
   cache:false,
   data:{"_token":'{{ csrf_token() }}',type_search:type_search,hour_from_range:hour_from_range,hour_to_range:hour_to_range},
   success: function(data){
   $("#ajax_responce_serachDiv").html(data);
   },
   error:function(){
   alert("عفوا لقد حدث خطأ ");
   }
   
   });
}
   $(document).on('click','#ajax_pagination_in_search a',function(e){
   e.preventDefault();
   var type_search=$("#type_search").val();
   var hour_from_range=$("#hour_from_range").val();
   var hour_to_range=$("#hour_to_range").val();
   var linkurl=$(this).attr("href");
   jQuery.ajax({
   url:linkurl,
   type:'post',
   'dataType':'html',
   cache:false,
   data:{"_token":'{{ csrf_token() }}',type_search:type_search,hour_from_range:hour_from_range,hour_to_range:hour_to_range},
   success: function(data){
   $("#ajax_responce_serachDiv").html(data);
   },
   error:function(){
   alert("عفوا لقد حدث خطأ ");
   }
   
   });
   
   });
   
   
   $(document).on('click','.load_open_monthModel',function(e){
        var id=$(this).data("id");
        
      jQuery.ajax({
      url:'{{ route('MainSalaryRecord.load_open_monthModel') }}',
      type:'post',
      'dataType':'html',
      cache:false,
      data:{"_token":'{{ csrf_token() }}',id:id},
      success: function(data){
      $("#load_open_monthModelBody").html(data);
      $("#load_open_monthModel").modal("show");
      },
      error:function(){
      alert("عفوا لقد حدث خطأ ");
      }
      
      });


 });
   
   
   });
   
</script>

@endsection