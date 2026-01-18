@if(!@empty($other['data_row']) and !@empty($other['cheack_currentOpenMonth']))
    <div class="row">

         <div class="col-md-3 relatet_employees_edit">
           <div class="form-group">
                  <label>  الشهر</label>
                  <input readonly type="text" name="year_and_month" id="year_and_month"  class="form-control" value="{{$other['data_row']['year_and_month'] }}" >
               </div>
            </div>  
           
          <div class="col-md-3">
              <div class="form-group">
                  <label>الرصيد المرحل </label>
                  <input  type="text" name="carryover_from_previous_month" id="carryover_from_previous_month" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" class="form-control" value="{{$other['data_row']['carryover_from_previous_month']*1 }}" >
               </div>
          </div>  
         <div class="col-md-3">
              <div class="form-group">
                  <label>رصيد الشهر  </label>
                  <input readonly  type="text" name="current_month_balance" id="current_month_balance" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" class="form-control" value="{{$other['data_row']['current_month_balance']*1 }}" >
               </div>
          </div> 
           <div class="col-md-3">
              <div class="form-group">
                  <label>الرصيد المتاح </label>
                  <input readonly type="text" name="total_available_balance" id="total_available_balance" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" class="form-control" value="{{ $other['data_row']['total_available_balance']*1 }}" >
               </div>
          </div> 
           <div class="col-md-3">
              <div class="form-group">
                  <label>الرصيد المستهلك </label>
                  <input  type="text" name="spent_balance" id="spent_balance" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" class="form-control" value="{{$other['data_row']['spent_balance']*1 }}" >
               </div>
          </div> 
           <div class="col-md-3">
              <div class="form-group">
                  <label> صافى  الرصيد </label>
                  <input readonly type="text" name="net_balance" id="net_balance" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" class="form-control" value="{{$other['data_row']['net_balance']*1 }}" >
               </div>
          </div>  

          <div class="col-md-3">
              <div class="form-group">
                  <label> ملاحظة</label>
                  <input  type="text" name="notes" id="notes"  class="form-control" value="{{ $other['data_row']['notes'] }}" >
               </div>
          </div> 
              <div class="col-md-3">
               <div class="form-group text-left">
                  <button type="button" id="do_edit_now" data-id="{{ $other['data_row']['id'] }}" style="margin-top:33px; " class="btn btn-sm btn-danger">تعديل الرصيد </button>
               </div>
            </div> 
       </div>
     </div>        
@else
<p class="bg-danger text-center"> عفوا لاتوجد بيانات لعرضها</p>
@endif
