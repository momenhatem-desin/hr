@if(!@empty($finance_cln_periods_data) and !@empty($data_row))
    <div class="row">
           <div class="col-md-3">
                  <div class="form-group">
                     <label>  بيانات الموظفين </label>
                     <select name="employees_code_edit" id="employees_code_edit" class="form-control select2 ">
                        <option value="">اختر  الموظف </option>
                        @if (@isset($employess) && !@empty($employess))
                        @foreach ($employess as $info )
                        <option @if ($info->EmployeeData['employees_code'] ==$data_row['employees_code'])selected @endif value="{{$info->EmployeeData['employees_code'] }}" data-s=" {{$info->EmployeeData['emp_sal']}}" data-db=" {{ $info->EmployeeData['day_price']}}"> {{ $info->EmployeeData['emp_name'] }} ({{ $info->EmployeeData['employees_code'] }}) </option>
                        @endforeach
                        @endif
                     </select>
               
                  </div>
               </div>
                <div class="col-md-3 relatet_employees_edit">
               <div class="form-group">
                  <label>أجر اليوم الواحد</label>
                  <input readonly type="text" name="day_price_edit" id="day_price_edit"  class="form-control" value="{{ $data_row['emp_day_price']*1 }}" >
               </div>
            </div>  
             <div class="col-md-3">
               <div class="form-group">
                  <label> نوع المكافئات </label>
                  <select  name="grant_types_edit" id="grant_types_edit" class="form-control">
                   @if (@isset($grant_types) && !@empty($grant_types))
                        @foreach ($grant_types as $info )
                        <option @if ($data_row['grants_id'] ==$info->id)selected @endif value="{{ $info->id }}"> {{ $info->name }}  </option>
                        @endforeach
                        @endif
               </select>
                  @error('MotivationType')
                  <span class="text-danger">{{ $message }}</span> 
                  @enderror
               </div>
            </div>
              <div class="col-md-3">
              <div class="form-group">
                  <label>أجمالى قيمة المكافئات</label>
                  <input  type="text" name="total_edit" id="total_edit" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" class="form-control" value="{{ $data_row['total']*1 }}" >
               </div>
          </div>  

          <div class="col-md-3">
              <div class="form-group">
                  <label> ملاحظة</label>
                  <input  type="text" name="notes_edit" id="notes_edit"  class="form-control" value="{{ $data_row['notes'] }}" >
               </div>
          </div> 
              <div class="col-md-3">
               <div class="form-group text-left">
                  <button id="do_edit_now" data-id="{{ $data_row['id'] }}" style="margin-top:33px; " class="btn btn-sm btn-danger" type="submit" name="submit">تعديل المكافئات </button>
               </div>
            </div> 
       </div>
     </div>        
@else
<p class="bg-danger text-center"> عفوا لاتوجد بيانات لعرضها</p>
@endif
