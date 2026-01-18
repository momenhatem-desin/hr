@if(@isset($data) and !@empty($data) and count($data)>0 )
     <table id="example2" class="table table-bordered table-hover">
            <thead class="custom_thead">
               <th> كود </th>
               <th>الاسم</th>
               <th> الفرع</th>
               <th> الادارة</th>
               <th>الوظيفة</th>
               <th>الحالة الوظيفية</th>
               <th> الصورة</th>
                <th> الاجراءات </th>
            </thead>
            <tbody>
               @foreach ( $data as $info )
               <tr>
                  <td>{{ $info->employees_code}}</td>
                  <td>{{ $info->emp_name}}</td>
                  <td>{{ $info->Branch->name}}</td>
                  <td>
                  @if(!empty($info->Departement))
                  {{ $info->Departement->name}}
                   @endif
                  </td>
                  <td>{{ $info->jobs_categorie->name}}</td>
                  <td>@if($info->Functiona_status==1) بالخدمة @else خارج الخدمة@endif</td>
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
              
                  <td>
                     <a  href="{{ route('Employees.edit',$info->id) }}" class="btn btn-success btn-sm">تعديل</a>
                      @if($info->counterUserBefor==0)
                     <a  href="{{ route('Employees.destroy',$info->id) }}" class="btn are_you_shur  btn-danger btn-sm">حذف</a>
                     @endif
                     <a  href="{{ route('Employees.show',$info->id) }}" class="btn btn-info btn-sm">عرض المزيد</a>
                  </td>
               </tr>
               @endforeach
            </tbody>
         </table>
<br>
<div class="col-md-12 text-center" id="ajax_pagination_in_search">
{{ $data->links('pagination::bootstrap-5') }}
</div>
@else
<p class="bg-danger text-center"> عفوا لاتوجد بيانات لعرضها</p>
@endif
