@if(!@empty($finance_cln_periods_data) and !@empty($Main_salary_employee_data) and !@empty($data_row))
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
                  <label> نوع الجزاء </label>
                  <select  name="sanctions_type_edit" id="sanctions_type_edit" class="form-control">

                  <option  @if ($data_row['sanctions_type']==1)selected @endif  value="1">جزاء ايام</option>
                  <option  @if ($data_row['sanctions_type']==2)selected @endif  value="2">جزاء بصمة</option>
                   <option  @if ($data_row['sanctions_type']==3)selected @endif  value="3">جزاء تحقيق</option>
               </select>
                  @error('MotivationType')
                  <span class="text-danger">{{ $message }}</span> 
                  @enderror
               </div>
            </div>
          <div class="col-md-3">
              <div class="form-group">
                  <label>عدد ايام الجزاء</label>
                  <input  type="text" name="value_edit" id="value_edit" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" class="form-control" value="{{ $data_row['value']*1 }}" >
               </div>
          </div>  
              <div class="col-md-3">
              <div class="form-group">
                  <label>أجمالى قيمة الجزاء</label>
                  <input readonly type="text" name="total_edit" id="total_edit" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" class="form-control" value="{{ $data_row['total']*1 }}" >
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
                  <button id="do_edit_now" data-id="{{ $data_row['id'] }}" data-main_salary_employee_id="{{ $data_row['main_salary_employee_id'] }}"style="margin-top:33px; " class="btn btn-sm btn-danger" type="submit" name="submit">تعديل الجزاء </button>
               </div>
            </div> 
       </div>
     </div>        
@else
<p class="bg-danger text-center"> عفوا لاتوجد بيانات لعرضها</p>
@endif
