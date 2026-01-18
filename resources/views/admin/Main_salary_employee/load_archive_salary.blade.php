@if(!@empty($finance_cln_periods_data) and !@empty($Main_salary_employee_Data))
<form action="{{ route('Main_salary_employee.do_archive_salary', $Main_salary_employee_Data['id']) }}" method="post">
    @csrf      

    <!-- حالة مرتب الموظف -->
    <div class="form-group">
        <label>حالة مرتب الموظف الآن</label>
        <select name="SalaryStatus" id="SalaryStatus" class="form-control">
            @if ($Main_salary_employee_Data['final_the_net'] > 0)
                <option value="1">دائن مستحق له</option>
            @elseif($Main_salary_employee_Data['final_the_net'] < 0)
                <option value="2">مدين مستحق عليه</option>
            @else
                <option value="3">متزن ليس له أو عليه</option>  
            @endif
        </select>
    </div>

    <!-- صافي المرتب -->
    <div class="form-group">
        @if($Main_salary_employee_Data['final_the_net'] > 0) 
            <label>صافي المرتب المستحق له</label>
            <input readonly type="text" name="final_the_net" id="final_the_net" 
                   value="{{ $Main_salary_employee_Data['final_the_net'] * 1 }}">
        
        @elseif($Main_salary_employee_Data['final_the_net'] < 0) 
            <label>صافي المرتب المستحق عليه</label>
            <input readonly type="text" name="final_the_net" id="final_the_net" 
                   value="{{ $Main_salary_employee_Data['final_the_net'] * -1 }}">
        
        @else
            <label>قيمة المبلغ متزن</label>
            <input readonly type="text" name="final_the_net" id="final_the_net" 
                   value="{{ $Main_salary_employee_Data['final_the_net'] * 1 }}">
        @endif
    </div>

      <!-- صافي المرتب المصروف-->
    <div class="form-group">
        @if($Main_salary_employee_Data['final_the_net'] > 0) 
            <label>صافي المبلغ  المصروف  له</label>
            <input readonly type="text" name="action_money_value_now" id="action_money_value_now" 
                   value="{{ $Main_salary_employee_Data['final_the_net'] * 1 }}" data-max="{{ $Main_salary_employee_Data['final_the_net'] * 1 }}">
        
        @elseif($Main_salary_employee_Data['final_the_net'] < 0) 
            <label>صافي المبلغ المدين به الموظف وسيرحل للشهر القادم   </label>
            <input readonly  type="text" name="action_money_value_now" id="action_money_value_now" 
                   value="{{ $Main_salary_employee_Data['final_the_net'] * -1 }}"  data-max="{{ $Main_salary_employee_Data['final_the_net'] * 1 }}">
        
        @else
            <label>قيمة المبلغ متزن</label>
            <input readonly type="text" name="action_money_value_now" id="action_money_value_now" 
                   value="{{ $Main_salary_employee_Data['final_the_net'] * 1 }}"  data-max="{{ $Main_salary_employee_Data['final_the_net'] * 1 }}">
        @endif
    </div>

    <!-- زر الأرشفة -->
    <div class="form-group text-center">
        <button id="do_archive_salary" class="btn btn-sm btn-danger" type="submit" name="submit">
            أرشفة المرتب الآن
        </button>
    </div>
</form>     
@else
<p class="bg-danger "> عفوا لاتوجد بيانات لعرضها</p>
@endif
