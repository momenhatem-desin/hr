@extends('layouts.admin')

@section('title')
سجل مراقبه النظام 
@endsection

@section("css")
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('contentheader')
قائمة الضبط
@endsection

@section('contentheaderactivelink')
<a href="{{ route('system_monitoring.index') }}">المراقبه</a>
@endsection

@section('contentheaderactive')
عرض
@endsection

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center">بيانات سجب حركه النظام</h3>
        </div>

        <div class="row" style="padding: 5px;">
            <div class="col-md-4">
                <div class="form-group">
                    <label>بحث بالاقسام</label>
                    <select name="alert_modules_id" id="alert_modules_id" class="form-control select2">
                        <option value="all">بحث بالكل</option>
                        @if (@isset($other['alert_modules']) && !@empty($other['alert_modules']))
                            @foreach ($other['alert_modules'] as $info )
                                <option value="{{ $info->id }}">{{ $info->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>بحث بالحركات</label>
                    <select name="alert_movetype_id" id="alert_movetype_id" class="form-control select2">
                        <option value="all">بحث بالكل</option>
                        @if (@isset($other['alert_movetype']) && !@empty($other['alert_movetype']))
                            @foreach ($other['alert_movetype'] as $info )
                                <option value="{{ $info->id }}">{{ $info->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>بحث بالموظفين</label>
                    <select name="employees_code" id="employees_code" class="form-control select2">
                        <option value="all">بحث بالكل</option>
                        @if (@isset($other['employess']) && !@empty($other['employess']))
                            @foreach ($other['employess'] as $info )
                                <option value="{{ $info->employees_code }}">{{ $info->emp_name }} ({{ $info->employees_code }})</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>بحث بالمستخدمين</label>
                    <select name="admin_id" id="admin_id" class="form-control select2">
                        <option value="all">بحث بالكل</option>
                        @if (@isset($other['admins']) && !@empty($other['admins']))
                            @foreach ($other['admins'] as $info )
                                <option value="{{ $info->id }}">{{ $info->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>بحث بحاله التميز</label>
                    <select name="is_marked" id="is_marked" class="form-control">
                        <option value="all">بحث بالكل</option>  
                        <option value="1">مميز</option>
                        <option value="0">غير مميز</option>
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>بحث من تاريخ</label>
                    <input type="date" id="from_date" class="form-control">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>بحث الى تاريخ</label>
                    <input type="date" id="to_date" class="form-control">
                </div>
            </div>
        </div>

        <div class="card-body" id="ajax_responce_serachDiv">
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
                                    <button data-id="{{ $info->id }}" class="btn are_you_shur do_undo_mark btn-danger btn-sm">الغاء </button>
                                @else
                                    لا<br>
                                    <button data-id="{{ $info->id }}" class="btn are_you_shur do_undo_mark btn-info btn-sm">تميز</button>
                                @endif
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
                <p class="bg-danger text-center">عفوا لاتوجد بيانات لعرضها</p>
            @endif
        </div>
    </div>
</div>
@endsection

@section("script")
<script src="{{ asset('assets/admin/plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    // Initialize Select2 Elements
    $('.select2').select2({ theme: 'bootstrap4' });

    $(document).ready(function() {
        $('#alert_modules_id, #alert_movetype_id, #employees_code, #admin_id, #is_marked, #from_date, #to_date').change(ajax_search);

        function ajax_search() {
            var data = {
                "_token": '{{ csrf_token() }}',
                alert_modules_id: $("#alert_modules_id").val(),
                alert_movetype_id: $("#alert_movetype_id").val(),
                employees_code: $("#employees_code").val(),
                admin_id: $("#admin_id").val(),
                is_marked: $("#is_marked").val(),
                from_date: $("#from_date").val(),
                to_date: $("#to_date").val()
            };
            $.ajax({
                url: '{{ route('system_monitoring.ajax_search') }}',
                type: 'post',
                dataType: 'html',
                cache: false,
                data: data,
                success: function(response) {
                    $("#ajax_responce_serachDiv").html(response);
                },
                error: function() {
                    alert("عفوا لقد حدث خطأ");
                }
            });
        }

        $(document).on('click', '#ajax_pagination_in_search a', function(e) {
            e.preventDefault();
            var linkurl = $(this).attr("href");
            var data = {
                "_token": '{{ csrf_token() }}',
                alert_modules_id: $("#alert_modules_id").val(),
                alert_movetype_id: $("#alert_movetype_id").val(),
                employees_code: $("#employees_code").val(),
                admin_id: $("#admin_id").val(),
                is_marked: $("#is_marked").val(),
                from_date: $("#from_date").val(),
                to_date: $("#to_date").val()
            };
            $.ajax({
                url: linkurl,
                type: 'post',
                dataType: 'html',
                cache: false,
                data: data,
                success: function(response) {
                    $("#ajax_responce_serachDiv").html(response);
                },
                error: function() {
                    alert("عفوا لقد حدث خطأ");
                }
            });
        });

        $(document).on('click', '.do_undo_mark ', function(e) {
            e.preventDefault();
            var id = $(this).data("id");
            $.ajax({
                url: '{{ route('system_monitoring.do_undo_mark') }}',
                type: 'post',
                dataType: 'html',
                cache: false,
                data: {"_token": '{{ csrf_token() }}',id:id},
                success: function() {
                    ajax_search();
                },
                error: function() {
                    alert("عفوا لقد حدث خطأ");
                }
            });
        });
    });
</script>
@endsection
