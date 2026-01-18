@if(!@empty($finance_cln_periods_data)  and !@empty($data_row))
            <form  action="{{ route('MainEmployeeSettlements.update',$data_row['id']) }}" method="POST" >
                @csrf
           <div class="row">
           <div class="col-md-3">
                  <div class="form-group">
                     <label>  بيانات الموظفين </label>
                     <select name="employees_code_edit" id="employees_code_edit" class="form-control select2 ">
                        <option value="">اختر  الموظف </option>
                        @if (@isset($employess) && !@empty($employess))
                        @foreach ($employess as $info )
                        <option @if($data_row['employees_code']==$info->employees_code) selected @endif value="{{ $info->employees_code}}" data-s=" {{   $info->emp_sal}}" data-db=" {{   $info->day_price}}"> {{   $info->emp_name}} ({{  $info->employees_code}}) </option>
                        @endforeach
                        @endif
                     </select>
               
                  </div>
               </div>
            <div class="col-md-3 relatet_employees_edit"  >
               <div class="form-group">
                  <label>   راتب الموظف الشهري</label>
                  <input readonly type="text" name="emp_sal_edit" id="emp_sal_edit"  class="form-control" value="{{ $data_row['emp_sal'] }}" >
               </div>
            </div>   
                <div class="col-md-3 relatet_employees_edit"  >
               <div class="form-group">
                  <label>أجر اليوم الواحد</label>
                  <input readonly type="text" name="day_price_edit" id="day_price_edit"  class="form-control" value="{{ $data_row['emp_day_price'] }}" >
               </div>
            </div>  
           
     
            <div class="row">
          
            <div class=" col-md-6" style="border: 1px solid lightslategray;
               border-radius: 10px;
               
               ">
               <p class="text-center" style="color: brown;font-size: 16px;">مستحقات</p>
               <hr>
               <div class="row">
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="work_days_forEdit">      عدد ايام عمل  </label>
                     <input name="work_days_forEdit" required  id="work_days_forEdit" value="{{ $data_row['work_days_for'] }}" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"    class="form-control caluclateChangeEdit" >
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="work_days_total_forEdit">       اجمالي  </label>
                     <input readonly="" required name="work_days_for_totalEdit"  id="work_days_for_totalEdit" value="{{ $data_row['work_days_for_total'] }}" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"   class="form-control" >
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="extra_days_forEdit">      عدد ايام اضافي  </label>
                     <input name="extra_days_forEdit" required  id="extra_days_forEdit" required value="{{ $data_row['extra_days_for'] }}" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"    class="form-control caluclateChangeEdit" >
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="extra_days_total_forEdit">       اجمالي  </label>
                     <input readonly="" name="extra_days_total_for" required  id="extra_days_total_for" value="{{ $data_row['extra_days_for_total'] }}" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"   class="form-control" >
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="absence_back_forEdit">       عدد ايام رد غياب  </label>
                     <input name="absence_back_forEdit"  id="absence_back_forEdit" value="{{ $data_row['absence_back_for'] }}" required oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"    class="form-control caluclateChangeEdit" >
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="absence_back_total_forEdit">       اجمالي  </label>
                     <input readonly="" name="absence_back_total_forEdit" required id="absence_back_total_forEdit" value="{{ $data_row['absence_back_total_for'] }}" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"   class="form-control" >
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="sanctions_back_forEdit">       عدد ايام رد جزاء  </label>
                     <input name="sanctions_back_forEdit" required  id="sanctions_back_forEdit" value="{{ $data_row['sanctions_back_for'] }}" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"    class="form-control caluclateChangeEdit" >
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="sanctions_back_total_forEdit">       اجمالي  </label>
                     <input readonly="" required name="sanctions_back_total_forEdit"  id="sanctions_back_total_forEdit" value="{{ $data_row['sanctions_back_total_for'] }}" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"   class="form-control " >
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="salary_difference_forEdit">        فرق راتب  </label>
                     <input name="salary_difference_forEdit" required id="salary_difference_forEdit" value="{{ $data_row['salary_difference_for'] }}" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"    class="form-control caluclateChangeEdit" >
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="award_forEdit">         مكافئة  </label>
                     <input name="award_forEdit"  required id="award_forEdit" value="{{ $data_row['award_for'] }}" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"    class="form-control caluclateChangeEdit" >
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="allowance_forEdit">         بدل  </label>
                     <input name="allowance_forEdit" required  id="allowance_forEdit" value="{{ $data_row['allowance_for'] }}" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"    class="form-control caluclateChangeEdit" >
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="total_forEdit">       اجمالي الاستحقاقات  </label>
                     <input style="background-color: lightgoldenrodyellow" readonly="" required  name="total_forEdit"  id="total_forEdit" value="{{ $data_row['total_for'] }}" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"   class="form-control" >
                  </div>
               </div>
            </div>
            </div>
            <div class=" col-md-6" style="border: 1px solid lightslategray;
               border-radius: 10px;
           
               ">
               <p class="text-center" style="color: brown;font-size: 16px;">استقطاعات</p>
               <hr>
               <div class="row">
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="absence_onEdit">         عدد ايام غياب  </label>
                     <input name="absence_onEdit" required  id="absence_onEdit" value="{{ $data_row['absence_on'] }}" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"    class="form-control caluclateChangeEdit" >
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="absence_total_onEdit">       اجمالي  </label>
                     <input readonly="" name="absence_total_onEdit" required  id="absence_total_onEdit" value="{{ $data_row['absence_total_on'] }}" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"   class="form-control" >
                  </div>
               </div>
               </div>
               <div class="row">
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="sanctions_onEdit">         عدد ايام جزاء  </label>
                     <input name="sanctions_onEdit" required  id="sanctions_onEdit" value="{{ $data_row['sanctions_on'] }}" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"    class="form-control caluclateChangeEdit" >
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="sanctions_total_onEdit">       اجمالي  </label>
                     <input readonly=""  required name="sanctions_total_onEdit"  id="sanctions_total_onEdit" value="{{ $data_row['sanctions_total_on'] }}" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"   class="form-control" >
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="cash_discount_onEdit">         خصم نقدي  </label>
                     <input name="cash_discount_onEdit" required  id="cash_discount_onEdit" value="{{ $data_row['cash_discound_on'] }}" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"    class="form-control caluclateChangeEdit" >
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="allowance_on">            زي   </label>
                     <input name="allowance_onEdit"  required id="allowance_onEdit" value="{{ $data_row['allowance_on'] }}" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"    class="form-control caluclateChangeEdit" >
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="medical_insurance_onEdit">          تأمين طبي  </label>
                     <input name="medical_insurance_onEdit" required  id="medical_insurance_onEdit" value="{{ $data_row['midical_insurance_on'] }}" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"    class="form-control caluclateChangeEdit"  >
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="social_insurance_onEdit">          تأمين اجتماعي  </label>
                     <input name="social_insurance_onEdit" required id="social_insurance_onEdit" value="{{ $data_row['social_insurance_on'] }}" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"    class="form-control caluclateChangeEdit" >
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="monthly_loan_onEdit">           سلف شهرية  </label>
                     <input name="monthly_loan_onEdit"  required id="monthly_loan_onEdit" value="{{ $data_row['monthly_loan_on'] }}" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"    class="form-control caluclateChangeEdit" >
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="permanetn_monthly_loan_onEdit">           سلف مستديمة  </label>
                     <input name="permanetn_monthly_loan_onEdit"  required id="permanetn_monthly_loan_onEdit" value="{{ $data_row['permaneten_monthly_loan_on'] }}" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"    class="form-control caluclateChangeEdit" >
                  </div>
               </div>
            </div>
               <div class="col-md-6">
                  <div class="form-group  " >
                     <label for="total_onEdit">       اجمالي استقطاعات  </label>
                     <input style="background-color: lightgoldenrodyellow" readonly="" required name="total_onEdit"  id="total_onEdit" value="{{ $data_row['total_on'] }}" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"   class="form-control" >
                  </div>
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group  " >
                  <label for="final_totalEdit">       صافي اجمالي التسوية  </label>
                  <input style="background-color: lavenderblush" readonly="" required name="final_totalEdit"  id="final_totalEdit" value="{{ $data_row['final_total'] }}" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" oninvalid="setCustomValidity('من فضلك أدخل  هذا الحقل ')" onchange="try {setCustomValidity('')} catch (e) {}"   class="form-control" >
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group  " >
                  <label for="notes">      ملاحظات</label>
                  <textarea name="notes"   id="notes" class="form-control" ></textarea>
               </div>
            </div>
         
            <</div>
            <div class="col-md-3">
               <div class="form-group text-left">
                  <button  style="margin-top:33px; " class="btn btn-sm btn-danger" type="submit" name="submit">تعديل  </button>
               </div>
            </div> 
         </div>
         </form>
@else
<p class="bg-danger text-center"> عفوا لاتوجد بيانات   لعرضها</p>
@endif
