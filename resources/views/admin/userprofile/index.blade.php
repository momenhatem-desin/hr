@extends('layouts.admin')
@section('title')
البيانات الشخصيه
@endsection
@section('contentheader')
 البروفيل
@endsection
@section('contentheaderactivelink')
<a href="{{ route('userprofile.index') }}">   بيانتى</a>
@endsection
@section('contentheaderactive')
عرض
@endsection
@section('content')
<div class="col-12">
   <div class="card">
      <div class="card-header">
         <h3 class="card-title card_title_center">تفاصيل البروفيل الشخصي   </h3>
      </h3>
      </div>
   
      <div class="card-body" id="ajax_responce_serachDiv">
         <div class="card-body">
            @if (@isset($data) && !@empty($data))
            <table id="example2" class="table table-bordered table-hover">
               <tr>
                  <td class="width30">اسم المستخدم كاملاً</td>
                  <td > {{ $data['name'] }}</td>
               </tr>
             
               <tr>
                  <td class="width30">اسم المستخدم للدخول للنظام</td>
                  <td > {{ $data['username'] }}</td>
               </tr>

               <tr>
                  <td class="width30">البريد الالكتروني</td>
                  <td > {{ $data['email'] }}</td>
               </tr>
               <tr>
                  <td class="width30">حالة تفعيل المستخدم</td>
                  <td > @if($data['active']==1) مفعل  @else معطل @endif</td>
               </tr>
               <tr>
                  <td class="width30">  تاريخ  الاضافة</td>
                  <td > 
                     @php
                     $dt=new DateTime($data['created_at']);
                     $date=$dt->format("Y-m-d");
                     $time=$dt->format("h:i");
                     $newDateTime=date("A",strtotime($time));
                     $newDateTimeType= (($newDateTime=='AM')?'صباحا ':'مساء'); 
                     @endphp
                     {{ $date }}
                     {{ $time }}
                     {{ $newDateTimeType }}
                     بواسطة 
                     {{ $data['added_by_admin'] }}
                  </td>
               </tr>
               <tr>
                  <td class="width30">  تاريخ اخر تحديث</td>
                  <td > 
                     @if($data['updated_by']>0 and $data['updated_by']!=null )
                     @php
                     $dt=new DateTime($data['updated_at']);
                     $date=$dt->format("Y-m-d");
                     $time=$dt->format("h:i");
                     $newDateTime=date("A",strtotime($time));
                     $newDateTimeType= (($newDateTime=='AM')?'صباحا ':'مساء'); 
                     @endphp
                     {{ $date }}
                     {{ $time }}
                     {{ $newDateTimeType }}
                     بواسطة 
                     {{ $data['updated_by_admin'] }}
                     @else
                     لايوجد تحديث
                     @endif
                     <a href="{{ route('userprofile.edit') }}" class="btn btn-sm btn-success">تعديل</a>
                    
                  </td>

                  <tr>
                     <td class="width30"> الصورة الشخصية 	</td>
                     <td> 
                        
                        <img  src="{{ asset('assets/admin/uploads').'/'.$data['image'] }}"  style=" border-radius: 50%; width:80px; height:80px;" class="rounded-circle" alt=" صورة البروفيل">       
                     
                     </td>
                  </tr>
               </tr>
            </table>
            @else
            <div class="alert alert-danger">
               عفوا لاتوجد بيانات لعرضها !!
            </div>
            @endif
         


         </div>
		 
      </div>
   </div>
</div>
@endsection
