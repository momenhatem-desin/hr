@extends('layouts.admin')

@section('title', 'تسجيل الحضور اليدوي')
@section('contentheader', 'تسجيل الحضور اليدوي')

@section('content')
<style>
    /* ========== أضف هذه الأنماط الجديدة فقط ========== */
    input.day-input:disabled {
        background-color: #f8f9fa !important;
        color: #adb5bd !important;
        cursor: not-allowed !important;
        opacity: 0.6 !important;
        border-color: #dee2e6 !important;
    }

    .disabled-cell {
        position: relative;
        background-color: #f8f9fa;
    }

    .disabled-cell .day-input {
        background-color: #f8f9fa !important;
    }

    .disabled-cell:hover::after {
        content: "غير مسموح بالتعديل - الفرع خارج صلاحياتك";
        position: absolute;
        top: -30px;
        left: 50%;
        transform: translateX(-50%);
        background: #333;
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 12px;
        white-space: nowrap;
        z-index: 100;
    }

    /* أيقونة صغيرة للخلايا المعطلة */
    .disabled-indicator {
        position: absolute;
        top: 2px;
        left: 2px;
        width: 8px;
        height: 8px;
        background-color: #dc3545;
        border-radius: 50%;
        z-index: 5;
    }

    /* تصميم الحقول */
    input.day-input {
        width: 45px;
        height: 45px;
        text-align: center;
        font-weight: bold;
        border: 2px solid #dee2e6;
        border-radius: 4px;
        font-size: 16px;
        transition: all 0.2s;
    }

    input.day-input:focus {
        border-color: #4d90fe;
        background-color: #f0f7ff;
        outline: none;
        box-shadow: 0 0 0 3px rgba(77, 144, 254, 0.1);
    }

    .bg-present {
        background: #d4edda !important;
        color: #155724 !important;
    }

    .bg-absent {
        background: #f8d7da !important;
        color: #721c24 !important;
    }

    .bg-leave {
        background: #fff3cd !important;
        color: #856404 !important;
    }

    .bg-special {
        background: #d1ecf1 !important;
        color: #0c5460 !important;
    }

    /* تصميم الجدول */
    table {
        font-size: 14px;
    }

    table th {
        background-color: #f8f9fa;
        font-weight: bold;
        color: #495057;
        padding: 10px 5px;
        border-bottom: 2px solid #dee2e6;
    }

    table td {
        padding: 8px 5px;
        vertical-align: middle;
    }

    .employee-name {
        font-weight: 600;
        color: #2c3e50;
        white-space: nowrap;
    }

    /* رأس الأيام */
    .day-header {
        cursor: help;
        position: relative;
    }

    .day-header:hover::after {
        content: attr(title);
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        background: #333;
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 12px;
        white-space: nowrap;
        z-index: 100;
    }

    /* زر الحفظ */
    #saveAllBtn {
        font-size: 16px;
        font-weight: bold;
        padding: 12px 30px;
        border-radius: 6px;
        transition: all 0.3s;
    }

    #saveAllBtn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
    }

    /* تلميحات */
    .legend {
        background: #f8f9fa;
        border-radius: 6px;
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid #dee2e6;
    }

    .legend-item {
        display: inline-block;
        margin-right: 20px;
        font-size: 14px;
    }

    .legend-color {
        display: inline-block;
        width: 20px;
        height: 20px;
        border-radius: 4px;
        margin-right: 8px;
        vertical-align: middle;
    }

    /* تجميد عمود الأسماء */
    .table-responsive {
        overflow-x: auto;
    }

    /* تحسين الظهور على الجوال */
    @media (max-width: 768px) {
        input.day-input {
            width: 35px;
            height: 35px;
            font-size: 14px;
        }

        table {
            font-size: 12px;
        }

        .employee-name {
            max-width: 120px;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    }

    /* === Freeze Header & Employee Column === */
    .table-responsive {
        overflow: auto;
    }

    /* تثبيت صف الهيدر */
    .freeze-table {
        max-height: 70vh;
        /* أو رقم ثابت زي 500px */
        overflow: auto;
        position: relative;
    }

    .freeze-table thead th {
        position: sticky;
        top: 0;
        z-index: 5;
        background: #f8f9fa;
    }

    /* تثبيت عمود اسم الموظف */
    .freeze-table td.employee-name,
    .freeze-table th.employee-name {
        position: sticky;
        right: 0;
        /* لأن الاتجاه RTL */
        z-index: 4;
        background: #ffffff;
    }

    /* أول خلية (اسم الموظف في الهيدر) */
    .freeze-table thead th:first-child {
        right: 0;
        z-index: 6;
        background: #e9ecef;
    }

    /* تحسين المظهر */
    .freeze-table td.employee-name {
        box-shadow: -2px 0 5px rgba(0, 0, 0, 0.05);
    }
</style>

<div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="fas fa-calendar-check mr-2"></i>
            تسجيل الحضور يدوى

        </h5>
        @if($finance->is_open == 1)
        <a href="{{ route('attenance_departure.show_Manual_additional_hours',$finance->id) }}"
            class="btn btn-outline-light btn-sm" style="float: left">
            تسجيل الاضافى
        </a>
        @endif
        @if($setting['is_outo_offect_passmaV']==3 and $finance['is_open']==1 )
        <button id="do_is_outo_offect_passmaV_all" class="btn btn-sm btn-danger">اسقاط المتغيرات بالاجور</button>

        @endif
    </div>

    <div class="card-body">
        <!-- تلميحات الرموز -->
        <div class="legend">
            <div class="legend-item">
                @foreach($vactions as $vac)
                <span class="legend-color" style="background-color: #d4edda;"></span>
                <strong>{{ $vac->variables }}</strong> - {{ $vac->name }}
                @endforeach
            </div>


        </div>

        <form id="saveAllForm">
            @csrf
            <!-- معلومات الفترة -->
            <div class="alert alert-info mb-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-info-circle fa-lg mr-3"></i>
                    <div>
                        <h6 class="alert-heading mb-1">الفترة المحددة</h6>
                        من <strong>{{ $finance->start_date_for_pasma }}</strong>
                        إلى <strong>{{ $finance->end_date_for_pasma }}</strong>
                        <br>
                        <div class="text-center alert alert-info mb-4 ">
                            تسجيل الحضور يدوى فرع
                            (
                            <select name="branch_id_up" id="branch_id_up">
                                @foreach ($branches as $branch)
                                <option
                                    value="{{ $branch->id }}"
                                    {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                                @endforeach
                            </select>
                            )
                        </div>

                    </div>
                </div>
            </div>
            <input type="hidden" id="finance_id" name="finance_id" value="{{ $finance->id }}">
            <input type="hidden" name="year_and_month" value="{{ $finance->year_and_month }}">

            <div class="table-responsive freeze-table">
                <table class="table table-bordered table-hover table-sm">
                    <thead class="thead-light">
                        <tr>
                            <th style="min-width: 180px;" class="text-right">اسم الموظف</th>
                            @foreach($attendance_days as $date)
                            <th class="day-header"
                                title="{{ \Carbon\Carbon::parse($date)->translatedFormat('l Y-m-d') }}"
                                style="min-width: 50px;">
                                {{ \Carbon\Carbon::parse($date)->format('d') }}
                                <div style="font-size: 11px; color: #6c757d;">
                                    {{ \Carbon\Carbon::parse($date)->translatedFormat('D') }}
                                </div>
                            </th>
                            @endforeach

                            @foreach($vactions as $vac)
                            <th class="day-header"
                                title=""
                                style="min-width: 50px;">
                                {{ $vac->name }}
                            </th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($employees as $emp)
                        <tr>
                            <td class="employee-name text-right">
                                {{ $emp->emp_name }}
                                <div class="text-muted small">
                                    {{ $emp->employees_code }}
                                </div>
                            </td>

                            @foreach($attendance_days as $date)
                            @php
                            $rec = $attendance_data[$emp->employees_code][$date] ?? '';
                            $branch_id = $branch_data[$emp->employees_code][$date] ?? null;
                            $is_disabled = false;
                            $tooltip = '';

                            if ($branch_id !== null) {
                            if (!in_array($branch_id, $allowed_branch_ids)) {
                            $is_disabled = true;
                            $tooltip = 'لا تملك صلاحية على الفرع المسجل فيه';
                            } else {
                            $tooltip = 'مسموح بالتعديل';
                            }
                            }
                            @endphp

                            <td class="{{ $is_disabled ? 'cell-disabled' : '' }}" style="position: relative;">
                                @if($is_disabled && $branch_id)
                                <span class="branch-tooltip"
                                    title="تم التسجيل في فرع ليس لديك صلاحية عليه - ID: {{ $branch_id }}"></span>
                                @endif

                                <input type="text"
                                    maxlength="1"
                                    class="day-input form-control"
                                    name="cells[{{ $emp->employees_code }}][{{ $date }}]"
                                    value="{{ old('cells.' . $emp->employees_code . '.' . $date, $rec) }}"
                                    data-emp="{{ $emp->employees_code }}"
                                    data-date="{{ $date }}"
                                    data-branch="{{ $branch_id }}"
                                    {{ $is_disabled ? 'disabled' : '' }}
                                    placeholder="-"
                                    title="{{ $tooltip }}">
                            </td>
                            @endforeach

                            {{-- إجماليات أنواع الإجازات لكل موظف --}}
                            @foreach($vactions as $vac)
                            @php
                            $var = $vac->variables;
                            $var_name = $vac->name;
                            $count = $attendance_count[$emp->employees_code][$var] ?? 0;
                            @endphp
                            <td class="text-center font-weight-bold" title="نوع الاجازه: {{ $var_name }} - موظف: {{ $emp->emp_name }}">
                                {{ $count }}
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4 pt-3 border-top">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        <i class="fas fa-mouse-pointer mr-1"></i>
                        الخلايا المعتمدة غير قابلة للتعديل
                    </div>
                    <button type="button" id="saveAllBtn" class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-save mr-2"></i>
                        حفظ جميع البيانات
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@section('script')
<script>
    // تجميع كل الاختصارات المسموح بها من المتغيرات
    let allowedVariables = [@foreach($vactions as $vac)
        "{{ $vac->variables }}",
        @endforeach
    ];

    $(document).ready(function() {
        // تحسين تجربة المستخدم في الكتابة
        $('.day-input').on('focus', function() {
            $(this).select();
        });


        // تحويل الحروف تلقائياً لحالة كبيرة
        $('.day-input').on('keyup', function() {
            let value = $(this).val().toUpperCase();
            $(this).val(value);

            // السماح فقط بالحروف المطلوبة
            if (!allowedVariables.includes(value) && value !== '') {
                $(this).val('');
            }
        });

        function applyColor(input) {
            let val = input.val().toUpperCase();

            input.removeClass('bg-present bg-absent bg-leave bg-special');

            if (['H', 'B'].includes(val)) {
                input.addClass('bg-present');
            } else if (['U', 'D'].includes(val)) {
                input.addClass('bg-absent');
            } else if (['R', 'F', 'A', 'S', 'N', 'C'].includes(val)) {
                input.addClass('bg-leave');
            } else if (['M', 'L', 'V'].includes(val)) {
                input.addClass('bg-special');
            }
        }

        // عند تحميل الصفحة
        $('.day-input').each(function() {
            applyColor($(this));
        });

        // عند التعديل
        $('.day-input').on('keyup change', function() {
            let value = $(this).val().toUpperCase();
            $(this).val(value);
            applyColor($(this));
        });

        // التنقل بالسهم
        $('.day-input').on('keydown', function(e) {
            let $inputs = $('.day-input');
            let index = $inputs.index(this);

            switch (e.key) {
                case "ArrowRight":
                    e.preventDefault();
                    if (index - 1 < $inputs.length) $inputs.eq(index - 1).focus();
                    break;
                case "ArrowLeft":
                    e.preventDefault();
                    if (index + 1 >= 0) $inputs.eq(index + 1).focus();
                    break;
                case "ArrowDown":
                    e.preventDefault();
                    // نفترض نفس العمود في الصف التالي
                    let colIndex = $(this).closest('td').index();
                    let nextRow = $(this).closest('tr').next();
                    if (nextRow.length) {
                        nextRow.find('td').eq(colIndex).find('input').focus();
                    }
                    break;
                case "ArrowUp":
                    e.preventDefault();
                    let prevRow = $(this).closest('tr').prev();
                    let colIndexUp = $(this).closest('td').index();
                    if (prevRow.length) {
                        prevRow.find('td').eq(colIndexUp).find('input').focus();
                    }
                    break;
            }
        });


        $(document).on('click', '#saveAllBtn', function(e) {
            e.preventDefault();

            let btn = $(this);
            btn.prop('disabled', true);
            $('#backup_freeze_modal').modal('show');

            let formData = $('#saveAllForm').serialize();

            $.ajax({
                url: @json(route('attenance_departure.saveManual')),
                type: 'POST',
                data: formData,
                success: function() {
                    setTimeout(function() {
                        $("#backup_freeze_modal").modal("hide");
                        btn.prop('disabled', false);
                    }, 800);

                },
                error: function() {
                    setTimeout(function() {
                        $("#backup_freeze_modal").modal("hide");
                        btn.prop('disabled', false);
                    }, 800);
                    alert('حدث خطأ أثناء الحفظ ❌');
                }
            });
        });


        $(document).on('click', '#do_is_outo_offect_passmaV_all', function(e) {

            var finance_cln_periods_id = $("#finance_id").val();
            $("#backup_freeze_modal").modal("show");

            jQuery.ajax({
                url: @json(route('attenance_departure.do_is_outo_offect_passmaV_all')),
                type: 'post',
                'dataType': 'json',
                cache: false,
                data: {
                    "_token": '{{ csrf_token() }}',
                    finance_cln_periods_id: finance_cln_periods_id
                },
                success: function(data) {
                    setTimeout(function() {
                        $("#backup_freeze_modal").modal("hide");
                    }, 1000);

                },
                error: function() {
                    setTimeout(function() {
                        $("#backup_freeze_modal").modal("hide");
                    }, 1000);
                    alert("عفوا لقد حدث خطأ ");
                }

            });


        });

        // وظيفة لعرض الإشعارات
        function showAlert(type, title, message) {
            // يمكنك استخدام مكتبة مثل SweetAlert أو كتابة إشعار مخصص
            alert(title + ': ' + message); // مؤقتاً

            // مثال باستخدام SweetAlert:
            /*
            Swal.fire({
                icon: type,
                title: title,
                text: message,
                timer: 3000,
                showConfirmButton: false
            });
            */
        }

        // 1. غير هذه الدوال لتتجاهل المدخلات المعطلة
        $('.day-input:not(:disabled)').on('focus', function() { // أضف :not(:disabled)
            $(this).select();
        });

        $('.day-input:not(:disabled)').on('keyup', function() { // أضف :not(:disabled)
            let value = $(this).val().toUpperCase();
            $(this).val(value);

            if (!allowedVariables.includes(value) && value !== '') {
                $(this).val('');
            }
        });

        $('.day-input:not(:disabled)').on('keyup change', function() { // أضف :not(:disabled)
            let value = $(this).val().toUpperCase();
            $(this).val(value);
            applyColor($(this));
        });
        // إضافة tooltip للخلايا المعطلة
        $('.disabled-cell').hover(function() {
            let input = $(this).find('.day-input');
            let branchId = input.data('branch');
            if (branchId) {
                input.attr('title', 'تم التسجيل في فرع (ID: ' + branchId + ') ليس لديك صلاحية عليه');
            }
        }, function() {
            let input = $(this).find('.day-input');
            input.attr('title', 'غير مسموح بالتعديل - الفرع خارج صلاحياتك');
        });

        // اختصارات لوحة المفاتيح
        $(document).keydown(function(e) {
            // Ctrl + S للحفظ
            if (e.ctrlKey && e.keyCode == 83) {
                e.preventDefault();
                $("#saveAllBtn").click();
            }
        });
    });
</script>

@endsection