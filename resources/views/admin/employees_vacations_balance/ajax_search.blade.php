     @if(@isset($data) and !@empty($data) and count($data)>0 )
         <table id="example2" class="table table-bordered table-hover">
            <thead class="custom_thead">
               <th> كود </th>
               <th style="width: 22%">الاسم</th>
               <th style="width: 13%"> الفرع</th>
               <th> الادارة</th> 
               <th>الوظيفة</th>
               <th>حاله الرصيد</th>
               <th> الصورة</th>
                <th> الرصيد </th>
                <th> الاجراءات </th>
            </thead>
            <tbody>
               @foreach ( $data as $info )
               <tr>
                  <td>{{ $info->employees_code}}</td>
                  <td>{{ $info->emp_name}}
                     <br>
                <span style="color: red">@if($info->Functiona_status==1) بالخدمة @else خارج الخدمة@endif</span>
                  </td>
                  <td>{{ $info->Branch->name}}</td>
                  <td>
                  @if(!empty($info->Departement))
                  {{ $info->Departement->name}}
                   @endif
                  </td>
                  <td>{{ $info->jobs_categorie->name}}</td>
                  <td>@if($info->is_active_for_Vaccation==1) سنوى @elseif($info->is_active_for_Vaccation==2)حسب الحضور @else  غير مفعل@endif</td>
                  <td>
                     @if(!empty($info->emp_photo))
                     <img src="{{ asset('assets/admin/uploads').'/' . $info->emp_photo }}" border-raduis: 50%; width="80px"; height="80px"; class="rounded-circle" alt="صورة الموظف">
                     @else
                     @if($info->emp_gender==1)
                    <img src="{{ asset('assets/admin/imgs/male.png') }}" border-raduis: 50%; width="80px"; height="80px"; class="rounded-circle" alt="صورة الموظف">
                     @else
                  <img src="{{ asset('assets/admin/imgs/female.png')}}" border-raduis: 50%; width="80px"; height="80px"; class="rounded-circle" alt="صورة الموظف">
                     @endif
                     @endif
                  </td>
              <td>{{ $info->balance_vac }}</td>
                  <td>
                     <a  href="{{ route('employees_vacations_balance.show',$info->id) }}" class="btn btn-info btn-sm">عرض المزيد</a>
                  </td>
               </tr>
               @endforeach
            </tbody>
         </table>
         <br>
         <div class="col-md-12 text-center">
            {{ $data->links('pagination::bootstrap-5') }}
         </div>
         @else
         <p class="bg-danger text-center"> عفوا لاتوجد بيانات لعرضها</p>
         @endif
