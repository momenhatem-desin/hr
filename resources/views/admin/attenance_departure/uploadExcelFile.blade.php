@extends('layouts.admin')
@section('title')
البصمة
@endsection
@section('contentheader')
قائمة البصمة
@endsection
@section('contentheaderactivelink')
<a href="{{ route('attenance_departure.index') }}">  بصمة الموظفين   </a>
@endsection
@section('contentheaderactive')
عرض
@endsection
@section('content')
<div class="col-12">
   <div class="card">
      <div class="card-header">
         <h3 class="card-title card_title_center">
               ارفاق ملف  بصمات الموظفين   الشهر المالى({{ $finance_cln_periods_data['month']->name }}لسنة {{ $finance_cln_periods_data['FINANCE_YR'] }})
               بفترة بصمة (من {{ $finance_cln_periods_data['start_date_for_pasma'] }} الى فتره بصمة
               {{ $finance_cln_periods_data['end_date_for_pasma'] }})
         </h3>
      </div>
      <div class="card-body">
         <form action="{{ route('attenance_departure.DoUploadExcelFile',$finance_cln_periods_data['id']) }}"  method="post" enctype="multipart/form-data">
            @csrf
      
               <div class="col-md-12">
               <div class="form-group">
                  <label> اختر ملف البصمة  </label>
                  <span style="color: brown">ملاحظه:سيتم اهمال اى بصمه خارج نطاق فترة الشهر المالى وفى حاله عدم تحديد الفرع سيعتبر بصمه مركزيه</span>
                  <input required type="file" name="excel_file" id="excel_file" class="form-control"  >
                   @error('excel_file')
                  <span class="text-danger">{{ $message }}</span> 
                  @enderror
               </div>
                 <div class="text-center alert alert-info mb-4 ">
                               فرع
                            (
                            <select name="branch_id_up" id="branch_id_up">
                                @foreach ($branches as $branch)
                                <option
                                    value="{{ $branch->id }}"
                                    {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                                @endforeach
                            </select>
                            )
                        </div>
            </div>
            <div class="col-md-12">
               <div class="form-group text-center">
                  <button class="btn btn-sm btn-success" type="submit" name="submit"> ارفاق الملف </button>
                  <a href="{{ route('attenance_departure.show',$finance_cln_periods_data['id']) }}" class="btn btn-danger btn-sm">الغاء</a>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>
@endsection