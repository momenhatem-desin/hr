@if(!@empty($finance_cln_periods_data)  and !@empty($data_row))
    <div class="row">
           <div class="col-md-12">
                  <div class="form-group">
                     <label>  بيانات الموظفين </label>
                     <select name="employees_code_edit" id="employees_code_edit" class="form-control select2 ">
                       
                        @if (@isset($employess) && !@empty($employess))
                        @foreach ($employess as $info )
                        <option disabled @if ($info->EmployeeData['employees_code'] ==$data_row['employees_code'])selected @endif value="{{$info->EmployeeData['employees_code'] }}" data-s=" {{$info->EmployeeData['emp_sal']}}" data-db=" {{ $info->EmployeeData['day_price']}}"> {{ $info->EmployeeData['emp_name'] }} ({{ $info->EmployeeData['employees_code'] }}) </option>
                        @endforeach
                        @endif
                     </select>
               
                  </div>
               </div>
               <div class="col-md-12 " >
               <div class="form-group">
                  <label>محتوى التحقيق</label>
                  <textarea rows="6"  type="text" name="content_edit" id="content_edit"  class="form-control" >{{ $data_row['content'] }}</textarea>
               </div>
            </div>   
  
        
          <div class="col-md-12">
              <div class="form-group">
                  <label> ملاحظات</label>
                  <input  type="text" name="notes_edit" id="notes_edit"  class="form-control" value="{{ $data_row['notes'] }}" >
               </div>
          </div> 
              <div class="col-md-3">
               <div class="form-group text-left">
                  <button id="do_edit_now" data-id="{{ $data_row['id'] }}" style="margin-top:33px; " class="btn btn-sm btn-danger" type="submit" name="submit">تعديل التحقيق </button>
               </div>
            </div> 
       </div>
     </div>        
@else
<p class="bg-danger text-center"> عفوا لاتوجد بيانات لعرضها</p>
@endif
