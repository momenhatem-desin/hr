
  @if(@isset($data) and !@empty($data) )
  @if($data['is_open']==0)
         <form action="{{ route('MainSalaryRecord.do_open_month',$data['id'])  }}" method="post">
            @csrf
          <div class="row">
               <div class="col-md-6">
                  <div class="form-group">
                     <label> تاريخ بداية البصمة للشهر</label>
                     @if($data && $data->is_open == 0)
                     <input type="date" name="start_date_for_pasma" id="start_date_for_pasma" class="form-control" value="{{$data['start_date_for_pasma'] }}" >
                   @endif
                  </div>
               </div>
           
          
               <div class="col-md-6">
                  <div class="form-group">
                     <label> تاريخ نهاية البصمة للشهر </label>
                      @if($data && $data->is_open == 0)
                     <input type="date" name="end_date_for_pasma" id="end_date_for_pasma" class="form-control" value="{{ $data['end_date_for_pasma'] }}" >
                      @endif
                  </div>
               </div>
           
            <div class="col-md-12">
               <div class="form-group text-center">
                  <button class="btn btn-sm btn-danger are_you_shur" type="submit" name="submit">فتح الشهر المالى الأن  </button>
                  
               </div>
            </div>
          </div>
         </form>
         @endif
         @else
         <p class="bg-danger text-center"> عفوا لاتوجد بيانات لعرضها</p>
         @endif