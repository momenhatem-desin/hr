@extends('layouts.admin')

@section('title', 'تسجيل الساعات الإضافية')
@section('contentheader', 'تسجيل الساعات الإضافية')

@section('content')
<style>

    /* أضف هذا في قسم CSS */
/* إزالة الأسهم من جميع حقول number */
input[type="number"] {
    -moz-appearance: textfield;
}

input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* تحسين مظهر الحقول بدون الأسهم */
.additional-input {
    padding-right: 8px !important; /* تعويض مكان الأسهم */
}

/* لتحسين العرض على جميع المتصفحات */
.additional-input::-webkit-inner-spin-button,
.additional-input::-webkit-outer-spin-button,
.additional-input::-ms-clear,
.additional-input::-ms-reveal {
    display: none;
}
   /* ========== الأنماط الجديدة ========== */
input.additional-input:disabled {
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

.disabled-cell .additional-input {
    background-color: #f8f9fa !important;
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

/* تنسيق Select الفرع */
.branch-select-container {
    background: white;
    padding: 8px 15px;
    border-radius: 6px;
    border: 1px solid #dee2e6;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    display: inline-flex;
    align-items: center;
    margin-top: 10px;
}

.branch-select-container i {
    color: #4d90fe;
    margin-left: 8px;
}

.branch-select-container label {
    margin: 0;
    font-size: 14px;
    color: #495057;
    margin-left: 5px;
}

#branch_id {
    border: 1px solid #ced4da;
    border-radius: 4px;
    padding: 5px 10px;
    font-size: 14px;
    background: white;
    min-width: 180px;
    color: #495057;
    transition: all 0.3s ease;
}

#branch_id:focus {
    border-color: #4d90fe;
    box-shadow: 0 0 0 0.2rem rgba(77, 144, 254, 0.25);
    outline: none;
}

/* تحسين التنسيق */
.alert-period {
    border-right: 4px solid #4d90fe;
}

.period-dates {
    font-size: 15px;
}

.branch-info {
    border-top: 1px dashed #dee2e6;
    padding-top: 10px;
    margin-top: 10px;
} 
   .additional-input {
    width: 60px;
    text-align: center;
    font-weight: bold;
    border: 2px solid #dee2e6;
    border-radius: 4px;
    font-size: 16px;
    transition: all 0.2s;
}

.additional-input:focus {
    border-color: #4d90fe;
    background-color: #f0f7ff;
    outline: none;
    box-shadow: 0 0 0 3px rgba(77, 144, 254, 0.1);
}
    .bg-has-hours { background:#d4edda !important; color:#155724 !important; }
    .bg-no-hours { background:#e2e3e5 !important; color:#383d41 !important; }

    table { font-size: 14px; }
    table th { background-color: #f8f9fa; font-weight: bold; color: #495057; padding: 10px 5px; border-bottom: 2px solid #dee2e6; }
    table td { padding: 8px 5px; vertical-align: middle; }
    .employee-name { font-weight: 600; color: #2c3e50; white-space: nowrap; }

    .day-header { cursor: help; position: relative; }
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

    #saveAllBtn { font-size: 16px; font-weight: bold; padding: 12px 30px; border-radius: 6px; transition: all 0.3s; }
    #saveAllBtn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3); }

    .table-responsive { overflow-x: auto; }

    @media (max-width: 768px) {
        input.day-input { width: 45px; height: 35px; font-size: 14px; }
        table { font-size: 12px; }
        .employee-name { max-width: 120px; overflow: hidden; text-overflow: ellipsis; }
    }
</style>

<div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-calendar-check mr-2"></i>تسجيل الساعات الإضافية</h5>
    </div>

    <div class="card-body">
       
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
         <div class="text-center alert alert-info mb-4 " >
                 تسجيل الحضور يدوى فرع 
            (
           <select name="branch_id" id="branch_id">
                @foreach ($branches as $branch)
                    <option 
                        value="{{ $branch->id }}"
                        {{ old('branch_id') == $branch->id ? 'selected' : '' }}
                    >
                        {{ $branch->name }}
                    </option>
                @endforeach
            </select>
            )  
            </div>
                    
                </div>
            </div>
        </div>
            <input type="hidden" name="finance_id" value="{{ $finance->id }}">

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm">
                    <thead class="thead-light">
                        <tr>
                            <th style="min-width: 180px;" class="text-right">اسم الموظف</th>
                            @foreach($attendance_days as $date)
                                <th class="day-header" 
                                    title="{{ \Carbon\Carbon::parse($date)->translatedFormat('l Y-m-d') }}"
                                    style="min-width: 60px;">
                                    {{ \Carbon\Carbon::parse($date)->format('d') }}
                                    <div style="font-size: 11px; color: #6c757d;">
                                        {{ \Carbon\Carbon::parse($date)->translatedFormat('D') }}
                                    </div>
                                </th>
                            @endforeach
                             <th class="text-center">إجمالي الساعات</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($employees as $emp)
                        <tr>
                            <td class="employee-name text-right">
                                {{ $emp->emp_name }}
                                <div class="text-muted small">{{ $emp->employees_code }}</div>
                            </td>
                        @foreach($attendance_days as $date)
                                @php 
                                    $hours = $additional_data[$emp->employees_code][$date] ?? "0";
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
                                
                                <td class="{{ $is_disabled ? 'disabled-cell' : '' }}" style="position: relative;">
                                    @if($is_disabled && $branch_id)
                                        <span class="disabled-indicator" 
                                            title="تم التسجيل في فرع ليس لديك صلاحية عليه"></span>
                                    @endif
                                    
                                    <input type="number"
                                        class="additional-input form-control"
                                        name="additional[{{ $emp->employees_code }}][{{ $date }}]"
                                        value="{{ old('additional.' . $emp->employees_code . '.' . $date, $hours) }}"
                                        min="0"
                                        max="16"
                                        data-emp="{{ $emp->employees_code }}"
                                        data-date="{{ $date }}"
                                        data-branch="{{ $branch_id }}"
                                        {{ $is_disabled ? 'disabled' : '' }}
                                        placeholder="0"
                                        title="{{ $tooltip }}">
                                </td>
                            @endforeach
                         
                          <td class="text-center font-weight-bold">
                           {{ $additional_total[$emp->employees_code] }}
                         </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
                <div class="text-muted small"><i class="fas fa-mouse-pointer mr-1"></i>انقر على أي خلية لتعديلها</div>
                <button type="button" id="saveAllBtn" class="btn btn-primary btn-lg px-5">
                    <i class="fas fa-save mr-2"></i>حفظ جميع البيانات
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {

    function applyColor(input) {
        let val = parseFloat(input.val());
        input.removeClass('bg-has-hours bg-no-hours');
        if (val > 0) input.addClass('bg-has-hours');
        else input.addClass('bg-no-hours');
    }

    $('.additional-input').each(function(){ applyColor($(this)); });

    $('.additional-input').on('focus', function(){ $(this).select(); });

    $('.additional-input').on('keyup change', function(){ applyColor($(this)); });

    // التنقل بالسهم
    $('.additional-input').on('keydown', function(e){
        let $inputs = $('.additional-input');
        let index = $inputs.index(this);

        switch(e.key) {
            case "ArrowRight":
                e.preventDefault();
                if(index - 1 < $inputs.length) $inputs.eq(index - 1).focus();
                break;
            case "ArrowLeft":
                e.preventDefault();
                if(index + 1 >= 0) $inputs.eq(index + 1).focus();
                break;
            case "ArrowDown":
                e.preventDefault();
                // نفترض نفس العمود في الصف التالي
                let colIndex = $(this).closest('td').index();
                let nextRow = $(this).closest('tr').next();
                if(nextRow.length){
                    nextRow.find('td').eq(colIndex).find('input').focus();
                }
                break;
            case "ArrowUp":
                e.preventDefault();
                let prevRow = $(this).closest('tr').prev();
                let colIndexUp = $(this).closest('td').index();
                if(prevRow.length){
                    prevRow.find('td').eq(colIndexUp).find('input').focus();
                }
                break;
        }
    });

    $('#saveAllBtn').click(function(e){
        e.preventDefault();
        let btn = $(this);
        btn.prop('disabled', true);
        $('#backup_freeze_modal').modal('show');
        let formData = $('#saveAllForm').serialize();

        $.ajax({
            url: '{{ route('attenance_departure.saveManualAdditional') }}',
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
                // 3. إضافة tooltip للخلايا المعطلة (في نهاية document.ready)
            $('.disabled-cell').hover(function() {
                let input = $(this).find('.additional-input');
                let branchId = input.data('branch');
                if(branchId) {
                    input.attr('title', 'تم التسجيل في فرع (ID: ' + branchId + ') ليس لديك صلاحية عليه');
                }
            }, function() {
                let input = $(this).find('.additional-input');
                input.attr('title', 'غير مسموح بالتعديل - الفرع خارج صلاحياتك');
            });

    $(document).keydown(function(e){
        if (e.ctrlKey && e.keyCode == 83) {
            e.preventDefault();
            $("#saveAllBtn").click();
        }
    });
});

</script>
@endsection
