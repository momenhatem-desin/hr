<style>
    /* تصميم ملاحظة التواريخ */
    .date-notice {
        background: linear-gradient(135deg, #d9edf7 0%, #b8dff0 100%);
        border: 2px solid #7ac1e4;
        border-radius: 12px;
        padding: 15px;
        margin: 20px 0;
        text-align: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
    }
    
    .date-notice::before {
        content: "ℹ️";
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 24px;
        opacity: 0.8;
    }
    
    .date-notice p {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
        color: #0c5460;
        line-height: 1.6;
    }
    
    /* تصميم الجدول */
    .custom_thead {
        background: linear-gradient(135deg, #2c3e50 0%, #4a6491 100%);
        color: white;
    }
    
    .custom_thead th {
        padding: 15px 10px;
        font-weight: 700;
        font-size: 15px;
        border: none;
        text-align: center;
        vertical-align: middle;
        position: relative;
    }
    
    .custom_thead th::after {
        content: '';
        position: absolute;
        right: 0;
        top: 20%;
        height: 60%;
        width: 1px;
        background: rgba(255, 255, 255, 0.3);
    }
    
    .custom_thead th:last-child::after {
        display: none;
    }
    
    /* تصميم صفوف الجدول */
    .table-bordered {
        border: 1px solid #dee2e6;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    }
    
    .table-bordered td {
        padding: 12px 8px;
        vertical-align: middle;
        border: 1px solid #eaeaea;
        font-size: 14px;
        transition: all 0.3s ease;
    }
    
    .table-bordered tr:hover td {
        background-color: #f8f9fa;
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    
    /* تصميم الصف مع خلفية حمراء */
    tr[style*="background-color:#f2dede"] {
        background: linear-gradient(135deg, #f2dede 0%, #ebcccc 100%) !important;
    }
    
    tr[style*="background-color:#f2dede"]:hover td {
        background: linear-gradient(135deg, #f7e6e6 0%, #f2d6d6 100%) !important;
    }
    
    /* تصميم الخلايا */
    .table-bordered td > div {
        margin: 0 auto;
    }
    
    /* تصميم الحقول */
    .form-control {
        border: 2px solid #ced4da;
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 14px;
        transition: all 0.3s;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .form-control:focus {
        border-color: #4d94ff;
        box-shadow: 0 0 0 0.2rem rgba(77, 148, 255, 0.25);
        transform: translateY(-1px);
    }
    
    /* تصميم الأزرار */
    .btn {
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s;
        border: none;
        padding: 8px 16px;
        font-size: 14px;
    }
    
    .btn-danger {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        box-shadow: 0 4px 8px rgba(220, 53, 69, 0.2);
    }
    
    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(220, 53, 69, 0.3);
        background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
    }
    
    .load_my_actions {
        width: 100%;
        white-space: nowrap;
    }
    
    .btn-sm {
        padding: 6px 12px;
        font-size: 13px;
    }
    
    /* تصميم الصف الأخير (الإجماليات) */
    tr[style*="background-color: green"] {
        background: linear-gradient(135deg, #28a745 0%, #218838 100%) !important;
        color: white;
        font-weight: 700;
        text-align: center;
    }
    
    tr[style*="background-color: green"] td {
        color: white;
        border-color: rgba(255, 255, 255, 0.2);
        font-size: 15px;
        padding: 15px 8px;
    }
    
    /* تصميم الأيقونات */
    .fa-hand-point-right, .fa-hand-point-left {
        color: #4d94ff;
        font-size: 16px;
        margin: 0 5px;
        cursor: pointer;
        transition: all 0.3s;
        padding: 5px;
        border-radius: 50%;
        background: rgba(77, 148, 255, 0.1);
    }
    
    .fa-hand-point-right:hover, .fa-hand-point-left:hover {
        background: rgba(77, 148, 255, 0.2);
        transform: scale(1.2);
        color: #0056b3;
    }
    
    /* تصميم الخلايا ذات العرض المحدد */
    [style*="width: 10vw"], 
    [style*="width: 15vw"], 
    [style*="width: 7vw"], 
    [style*="width: 4vw"] {
        min-width: 80px;
    }
    
    /* تصميم الحقول المعطلة */
    .form-control:disabled {
        background-color: #f8f9fa;
        color: #6c757d;
        cursor: not-allowed;
    }
    
    /* رسالة عدم وجود بيانات */
    .bg-danger.text-center {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
        padding: 20px;
        border-radius: 10px;
        font-size: 18px;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.2);
        margin: 20px 0;
        border: 2px solid #bd2130;
        text-align: center;
    }
    
    /* تحسين عرض التاريخ واليوم */
    #the_day_date td {
        text-align: center;
        font-weight: 600;
    }
    
    #the_day_date td br {
        display: block;
        margin: 5px 0;
        content: "";
    }
    
    /* تحسين الـ select */
    .vacations_types {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 16px 12px;
        padding-right: 2.5rem;
    }
    
    /* تصميم العناوين داخل الجدول */
    td:first-child {
        font-weight: 600;
        color: #2c3e50;
    }
    
    /* تحسين استجابة الجدول */
    @media (max-width: 768px) {
        .table-responsive {
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .custom_thead th {
            font-size: 13px;
            padding: 10px 5px;
        }
        
        .table-bordered td {
            font-size: 12px;
            padding: 8px 5px;
        }
        
        .btn {
            padding: 6px 10px;
            font-size: 12px;
        }
        
        .date-notice {
            padding: 12px;
            font-size: 14px;
        }
        
        .date-notice::before {
            display: none;
        }
    }
    
    /* تصميم صف الإجماليات للجوال */
    @media (max-width: 768px) {
        tr[style*="background-color: green"] td {
            font-size: 13px;
            padding: 10px 5px;
            word-break: break-word;
        }
    }
    
    /* تأثيرات إضافية */
    .table-bordered tr {
        transition: all 0.3s ease;
    }
    
    .table-bordered tr:not(:last-child) {
        border-bottom: 1px solid #f0f0f0;
    }
    
    /* تنسيق الروابط */
    .move_to {
        display: inline-block;
        margin: 0 3px;
    }
</style>

<div class="date-notice">
    <p>
        <a target="_blank"  class="btn btn-sm btn-info" href="{{ route('attenance_departure.print_one_passma',['employees_code'=>$other['Employee_Date']['employees_code'],'finance_cln_periods_id'=>$other['finance_cln_periods_data']['id']]) }}">طباعه</a>
        @if($setting['is_outo_offect_passmaV']==3 and $other['finance_cln_periods_data']['is_open']==1 )
     <button id="do_is_outo_offect_passmaV" class="btn btn-sm btn-danger">اسقاط المتغيرات بالاجور</button>
        
        @endif
   
        ملاحظة: آخر تاريخ ملف بصمة مسحوب بالنظام هو
        <span style="font-weight: 700; color: #004085; background: rgba(0, 64, 133, 0.1); padding: 3px 10px; border-radius: 5px; margin: 0 5px;">
            {{ $max_attend_date }}
        </span>
    </p>
</div>

@if(@isset($other['data']) and !@empty($other['data']) and count($other['data'])>0 )
    <div class="table-responsive">
        <table id="example2" class="table table-bordered table-hover">
            <thead class="custom_thead">
                <tr>
                    <th>التاريخ</th>
                    <th>الحضور</th>
                    <th>الانصراف</th>
                    <th>البصمات</th>
                    <th>متغيرات</th>
                    <th>خصم أيام</th>
                    <th>هل إجازة</th>
                    <th>حضور متأخر</th>
                    <th>انصراف مبكر</th>
                    <th>إذن ساعات</th>
                    <th>ساعات عمل</th>
                    <th>غياب ساعات</th>
                    <th>إضافي ساعات</th>
                    <th>إجراء</th>
                </tr>
            </thead>
            <tbody>
                @foreach ( $other['data'] as $info )
                    <tr @if($info->datetime_In==null and $info->datetime_out==null) style="background-color:#f2dede" @endif>
                        <td id="the_day_date{{ $info->id }}">
                            <div style="text-align: center; padding: 5px;">
                                <div style="font-weight: 700; color: #2c3e50; margin-bottom: 3px;">
                                    {{ $info->the_day_date }}
                                </div>
                                <div style="color: #6c757d; font-size: 13px; background: rgba(108, 117, 125, 0.1); padding: 2px 8px; border-radius: 4px; display: inline-block;">
                                    {{ $info->week_day_name_arbic }}
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($info->timeIn != null)
                                <div style="background: rgba(40, 167, 69, 0.1); padding: 5px 10px; border-radius: 5px; display: inline-block; font-weight: 600; color: #28a745;">
                                    @php echo date('H:i', strtotime($info->timeIn)) @endphp
                                </div>
                            @else
                                <span style="color: #6c757d; font-style: italic;">-</span>
                            @endif
                        </td>
                        <td>
                            @if($info->timeOut != null)
                                <div style="background: rgba(0, 123, 255, 0.1); padding: 5px 10px; border-radius: 5px; display: inline-block; font-weight: 600; color: #007bff;">
                                    @php echo date('H:i', strtotime($info->timeOut)) @endphp
                                </div>
                            @else
                                <span style="color: #6c757d; font-style: italic;">-</span>
                            @endif
                        </td>
                        <td>
                            <div style="min-width: 120px;">
                                @if($info->attendance_type == 1) 
                                    <button data-id="{{ $info->id }}" class="btn load_my_actions btn-sm btn-danger">
                                        <i class="fas fa-eye mr-1"></i>
                                        عرض الحركات  
                                        ({{ $info->attendance_dep_action_counter*1 }})
                                    </button>
                                @else
                                    <span style="color: #6c757d; font-style: italic;">يدوي</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div style="min-width: 150px;">
                                <input type="text" class="form-control variables" @if($info->is_archived==1) readonly @endif  name="variables{{ $info->id }}" id="variables{{ $info->id }}" value="{{ $info->variables }}">
                            </div>
                        </td>
                        <td>
                            <div style="min-width: 80px;">
                                <input type="text" class="form-control cut" @if($info->is_archived==1) readonly @endif name="cut{{ $info->id }}" id="cut{{ $info->id }}" value="{{ $info->cut*1 }}" oninput="this.value=this.value.replace(/[^0-9.]/g,'');">
                            </div>
                        </td>
                        <td>
                            <div style="min-width: 120px; text-align: center;">
                                <select name="vacations_types_id{{ $info->id }}" @if($info->is_archived==1) disabled @endif id="vacations_types_id{{ $info->id }}" class="form-control vacations_types">
                                    <option value="">لا</option>
                                    @if (@isset($other['vactions_types']) && !@empty($other['vactions_types']))
                                        @foreach ($other['vactions_types'] as $vac )
                                            <option @if($info->vacations_types_id==$vac->id) selected="selected"@endif value="{{ $vac->id }}" @if(($other['Employee_Date']['is_active_for_Vaccation']==0) and ($vac->id ==3 or $vac->id==15 or $vac->id==14))disabled @endif>
                                                {{ $vac->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <div style="margin-top: 8px;">
                                    <a class="move_to" data-dest="variables{{ $info->id }}" href="#" title="نسخ للإجازة">
                                        <i class="far fa-hand-point-right"></i>
                                    </a>
                                    <a class="move_to" data-dest="additional_hours{{ $info->id }}" href="#" title="نسخ للإضافي">
                                        <i class="far fa-hand-point-left"></i>
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="min-width: 80px;">
                                <input type="text" class="form-control attendance_dely" @if($info->is_archived==1) readonly @endif name="attendance_dely{{ $info->id }}" id="attendance_dely{{ $info->id }}" value="{{ $info->attendance_dely*1 }}" oninput="this.value=this.value.replace(/[^0-9.]/g,'');">
                            </div>
                        </td>
                        <td>
                            <div style="min-width: 80px;">
                                <input type="text" class="form-control early_departure" @if($info->is_archived==1) readonly @endif name="early_departure{{ $info->id }}" id="early_departure{{ $info->id }}" value="{{ $info->early_departure*1 }}" oninput="this.value=this.value.replace(/[^0-9.]/g,'');">
                            </div>
                        </td>
                        <td>
                            <div style="min-width: 80px;">
                                <input type="text" class="form-control azn_houres" @if($info->is_archived==1) readonly @endif name="azn_houres{{ $info->id }}" id="azn_houres{{ $info->id }}" value="{{ $info->azn_houres }}">
                            </div>
                        </td>
                        <td>
                            <div style="min-width: 80px;">
                                <input disabled type="text" class="form-control total_hours" @if($info->is_archived==1) readonly @endif name="total_hours{{ $info->id }}" id="total_hours{{ $info->id }}" value="{{ $info->total_hours*1 }}" oninput="this.value=this.value.replace(/[^0-9.]/g,'');">
                            </div>
                        </td>
                        <td>
                            <div style="min-width: 80px;">
                                <input type="text" class="form-control absen_hours" @if($info->is_archived==1) readonly @endif name="absen_hours{{ $info->id }}" id="absen_hours{{ $info->id }}" value="{{ $info->absen_hours*1 }}" oninput="this.value=this.value.replace(/[^0-9.]/g,'');">
                            </div>
                        </td>
                        <td>
                            <div style="min-width: 80px;">
                                <input type="text" class="form-control additional_hours" @if($info->is_archived==1) readonly @endif name="additional_hours{{ $info->id }}" id="additional_hours{{ $info->id }}" value="{{ $info->additional_hours*1 }}" oninput="this.value=this.value.replace(/[^0-9.]/g,'');">
                            </div>
                        </td>
                        <td id="make_save_changes{{ $info->id }}">
                            <div style="min-width: 90px;">
                                @if($info->is_archived==0) 
                                <button class="btn btn-danger make_save_changes_row btn-sm" data-id="{{ $info->id }}">
                                    <i class="fas fa-save mr-1"></i>
                                    حفظ
                                </button>
                                @else
                                مؤرشف
                             @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
                <!-- صف الإجماليات -->
                <tr style="background-color:green; text-align: center;">
                    <td colspan="5" style="font-weight: 800; font-size: 16px;">الإجماليات</td>
                    <td style= "border-radius:5px; padding: 10px;">
                        {{ $other['cut_total']*1 }} يوم
                    </td>
                    <td style=" border-radius: 5px; padding: 10px;">
                        {{ $other['vacations_types_id_total']*1 }} يوم
                        @if(@isset($other['Vactions_types_distinct']) and !@empty($other['Vactions_types_distinct']))
                            <div style="margin-top: 8px; text-align: left; font-size: 13px;">
                                @foreach($other['Vactions_types_distinct'] as $vac) 
                                    <div style="margin-bottom: 3px; padding: 3px 8px; background: rgba(255, 255, 255, 0.1); border-radius: 3px;">
                                        {{ $vac->counter*1 }} {{ $vac->name }}
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </td>
                    <td style=" border-radius: 5px; padding: 10px;">
                        {{ $other['attendance_dely_total']*1 }} دقيقة
                    </td>
                    <td style=" border-radius: 5px; padding: 10px;">
                        {{ $other['early_departure_total']*1 }} دقيقة
                    </td>
                    <td style=" border-radius: 5px; padding: 10px;">
                        {{ $other['total_hours_total']*1 }} ساعة
                    </td>
                    <td style=" border-radius: 5px; padding: 10px;">
                        {{ $other['absen_hours_total']*1 }} ساعة
                    </td>
                    <td colspan="2" style=" border-radius: 5px; padding: 10px;">
                        {{ $other['additional_hours_total']*1 }} ساعة
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
@else
    <div class="no-data-message">
        <p class="bg-danger text-center">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            عذراً، لا توجد بيانات لعرضها
        </p>
    </div>
@endif