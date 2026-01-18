@if(!@empty($data_row))
    <div class="row">
           <div class="col-md-4">
                  <div class="form-group">
                     <label>  بيانات الموظفين </label>
                     <select name="employees_code_edit" id="employees_code_edit" class="form-control select2 ">
                        <option value="">اختر  الموظف </option>
                        @if (@isset($employess) && !@empty($employess))
                        @foreach ($employess as $info )
                       <option @if ($info->employees_code ==$data_row['employees_code'])selected @endif value="{{$info->employees_code}}" data-s=" {{$info->emp_sal}}" data-db=" {{ $info->day_price}}"> {{ $info->emp_name }} ({{ $info->employees_code }}) </option>
                        @endforeach
                        @endif
                     </select>
               
                  </div>
               </div>
            <div class="col-md-4 relatet_employees_edit" style="" >
               <div class="form-group">
                  <label>   راتب الموظف الشهري</label>
                  <input readonly type="text" name="emp_sal_edit" id="emp_sal_edit"  class="form-control" value="{{ $data_row['emp_sal']*1 }}" >
               </div>
            </div>   
          
              <div class="col-md-4">
              <div class="form-group">
                  <label>أجمالى قيمة السلفه المستديمة</label>
                  <input  type="text" name="total_edit" id="total_edit" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" class="form-control" value="{{ $data_row['total']*1 }}" >
             </div>
             </div>  

              <div class="col-md-4">
              <div class="form-group">
                  <label> عدد الشهور للاقساط</label>
                  <input  type="text" name="month_number_edit" id="month_number_edit" oninput="this.value=this.value.replace(/[^0-9]/g,'');" class="form-control" value="{{ $data_row['month_number']*1 }}" >
             </div>
             </div>  

            <div class="col-md-4">
            <div class="form-group">
                  <label> قيمة القسط الشهرى</label>
                  <input readonly  type="text" name="month_kast_value_edit" id="month_kast_value_edit" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" class="form-control" value="{{ $data_row['month_kast_value']*1 }}" >
             </div>
             </div>  

            <div class="col-md-4">
            <div class="form-group">
                  <label>يبداء سداد اول قسط فى تاريخ</label>
                  <input  type="date" name="year_and_month_start_data_edit" id="year_and_month_start_data_edit"  class="form-control" value="{{$data_row['year_and_month_start_date']}}" >
             </div>
             </div>  

          <div class="col-md-8">
              <div class="form-group">
                  <label> ملاحظة</label>
                  <input  type="text" name="notes_edit" id="notes_edit"  class="form-control" value="{{ $data_row['notes'] }}" >
               </div>
          </div> 
              <div class="col-md-2">
               <div class="form-group">
                  <button id="do_edit_now" data-id="{{ $data_row['id'] }}" data-main_salary_employee_id="{{ $data_row['main_salary_employee_id'] }}"style="margin-top:33px; " class="btn btn-sm btn-danger" type="submit" name="submit">تعديل السلفة المستديمة</button>
               </div>
            </div> 
       </div>
     </div>        
@else
<p class="bg-danger text-center"> عفوا لاتوجد بيانات لعرضها</p>
@endif
