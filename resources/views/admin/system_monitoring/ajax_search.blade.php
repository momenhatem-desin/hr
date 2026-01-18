   @if(@isset($data) && !@empty($data) && count($data) > 0)
                <table id="example2" class="table table-bordered table-hover">
                    <thead class="custom_thead">
                        <th>مسلسل</th>
                        <th>قسم</th>
                        <th>نوع الحركه</th>
                        <th style="width: 30%">البيان</th>
                        <th>التاريخ</th>
                        <th>هل مميز</th>
                    </thead>
                    <tbody>
                        @foreach ($data as $info)
                        <tr @if($info->is_marked == 1) style="background-color:lightgoldenrodyellow;" @endif>
                            <td>{{ $info->id }}</td>
                            <td>{{ $info->alert_modules_name }}</td>
                            <td>{{ $info->alert_movetype_name }}</td>
                            <td>{{ $info->content }}</td>
                            <td>
                                @php
                                    $dt = new DateTime($info->created_at);
                                    $date = $dt->format("Y-m-d");
                                    $time = $dt->format("h:i");
                                    $newDateTime = date("a", strtotime($info->created_at));
                                    $newDateTimeType = ($newDateTime == 'am' || $newDateTime == 'AM') ? 'صباحا ' : 'مساء'; 
                                @endphp
                                {{ $date }} <br>
                                {{ $time }} {{ $newDateTimeType }} <br>
                                {{ $info->added->name }}
                            </td>
                            <td>
                                @if($info->is_marked == 1)
                                    نعم<br>
                                    <button data-id="{{ $info->id }}" class="btn are_you_shur do_undo_mark btn-danger btn-sm">الغاء التميز</button>
                                @else
                                    لا<br>
                                    <button data-id="{{ $info->id }}" class="btn are_you_shur do_undo_mark btn-danger btn-sm">تميز</button>
                                @endif
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
