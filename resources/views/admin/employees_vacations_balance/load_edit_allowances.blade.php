@if(!@empty($data))
   
     <form action="{{ route('Employees.do_edit_allowances',$data['id']) }}" method="post">
            @csrf
            <div class="row">
          <div class="col-md-6">
              <div class="form-group">
                  <label>قيمة البدل</label>
                  <input  type="text" name="allowances_value_edit" id="allowances_value_edit" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" class="form-control" value="{{ $data['value']*1 }}" >
               </div>
          </div>  
           
          
              <div class="col-md-6">
               <div class="form-group">
                  <button id="do_edit_allowances"  style="margin-top:33px; " class="btn btn-sm btn-danger" type="submit" name="submit">تعديل البدل </button>
               </div>
            </div> 
     </div>    
      </form>    
 
@else
<p class="bg-danger text-center"> عفوا لاتوجد بيانات لعرضها</p>
@endif
