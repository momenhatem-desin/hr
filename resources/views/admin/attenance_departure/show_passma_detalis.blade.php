@extends('layouts.admin')
@section('title')
البصمة
@endsection
@section('contentheader')
قائمة البصمة
@endsection
@section("css")
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection
@section('contentheaderactivelink')
<a href="{{ route('MainSalarySanctions.index') }}"> سجل الموظف </a>
@endsection
@section('contentheaderactive')
عرض
@endsection
@section('content')
<style>
  .modal-xl {
    max-width: 100%;
    margin: 0 auto;
    padding: 0 !important;
  }
</style>
<div class="col-12">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title card_title_center">
        بيانات سجل الموظف ({{ $Employee_Date['emp_name'] }}بالشهر المالى {{$finance_cln_periods_data['month']->name}})
      </h3>
      <input type="hidden" id="the_finance_cln_periods_id" value="{{$finance_cln_periods_data['id']}}">
      <input type="hidden" id="the_employees_code" value="{{ $Employee_Date['employees_code']}}">
      <input type="hidden" id="is_done_Vaccation_formula" value="{{ $Employee_Date['is_done_Vaccation_formula']}}">
      <input type="hidden" id="is_active_for_Vaccation" value="{{ $Employee_Date['is_active_for_Vaccation']}}">
      <button class="btn btn-sm btn_yahoo" style="float: right;" data-empcode="{{ $Employee_Date['employees_code'] }}" data-finicinid="{{ $finance_cln_periods_data['id'] }}" id="ShowArchivepassmabtun">عرض سجل أرشيف البصمه</button>
      <button class="btn btn-sm btn-success" style="float: right;margin-right:6px;" data-empcode="{{ $Employee_Date['employees_code'] }}" data-finicinid="{{ $finance_cln_periods_data['id'] }}" id="load_active_Attendance_departure">تحميل سجل الشهر</button>
    </div>


    <div class="card-body" id="ajax_responce_serachDiv" style="padding: 0px 5px;overflow-x:scroll">
      @if(@isset($data) and !@empty($data))

      @endif
    </div>
  </div>
</div>
<div class="modal fade " id="attenance_departure_actions_excel_Modal">
  <div class="modal-dialog modal-xl">
    <div class="modal-content bg-info">
      <div class="modal-header">
        <h4 class="modal-title">عرض ارشيف سجلات بصمه الموظف كما هى بدون اى تعديلات </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body" style="background-color: white !important;color:black;" id="attenance_departure_actions_excel_ModalBody">

      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>

      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>


<div class="modal fade " id="load_my_actionsModal">
  <div class="modal-dialog modal-xl">
    <div class="modal-content bg-info">
      <div class="modal-header">
        <h4 class="modal-title">عرض سجل حركات البصمه لتاريخ يوم محدد </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body" style="background-color: white !important;color:black;" id="load_my_actionsModalBody">

      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>

      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

@endsection

@section('script')
<script src="{{ asset('assets/admin/plugins/select2/js/select2.full.min.js') }}"> </script>
<script>
  //Initialize Select2 Elements
  $('.select2').select2({
    theme: 'bootstrap4'
  });

  $(document).ready(function() {



    $(document).on('click', '#ShowArchivepassmabtun', function(e) {

      var employees_code = $(this).data("empcode");
      var the_finance_cln_periods_id = $(this).data("finicinid");

      jQuery.ajax({
       
        url:'{{ route('attenance_departure.load_passma_archive') }}',
        type: 'post',
        'dataType': 'html',
        cache: false,
        data: { "_token": '{{ csrf_token() }}',
          employees_code: employees_code,
          the_finance_cln_periods_id: the_finance_cln_periods_id
        },
        success: function(data) {
          $("#attenance_departure_actions_excel_ModalBody").html(data);
          $("#attenance_departure_actions_excel_Modal").modal("show");
          $('.select2').select2();
        },
        error: function() {
          alert("عفوا لقد حدث خطأ ");
        }

      });


    });

    function load_active_Attendance_departure() {
      var finance_cln_periods_id = $("#the_finance_cln_periods_id").val();
      var employees_code = $("#the_employees_code").val();
      $('#backup_freeze_modal').modal('show');

      jQuery.ajax({
        url: '{{ route('attenance_departure.load_active_Attendance_departure') }}',
        type: 'post',
        'dataType': 'html',
        cache: false,
        data: {
          "_token": '{{ csrf_token() }}',
          employees_code: employees_code,
          finance_cln_periods_id: finance_cln_periods_id
        },
        success: function(data) {
          $("#ajax_responce_serachDiv").html(data);
          setTimeout(function() {
            $("#backup_freeze_modal").modal("hide");
          }, 1000);
        },
        error: function() {
          alert("عفوا لقد حدث خطأ ");
          setTimeout(function() {
            $("#backup_freeze_modal").modal("hide");
          }, 1000);
        }

      });
    }

    $(document).on('click', '#load_active_Attendance_departure', function(e) {

      load_active_Attendance_departure();


    });


    $(document).on('click', '.load_my_actions', function(e) {

      var id = $(this).data("id");
      var finance_cln_periods_id = $("#the_finance_cln_periods_id").val();
      var employees_code = $("#the_employees_code").val();

      jQuery.ajax({

        url:'{{ route('attenance_departure.load_my_actions') }}',
        type: 'post',
        'dataType': 'html',
        cache: false,
        data: {
          "_token": '{{ csrf_token() }}',
          id: id,
          employees_code: employees_code,
          finance_cln_periods_id: finance_cln_periods_id
        },
        success: function(data) {
          $("#load_my_actionsModalBody").html(data);
          $("#load_my_actionsModal").modal("show");
        },
        error: function() {
          alert("عفوا لقد حدث خطأ ");
        }

      });


    });




    $(document).on('click', '.make_save_changes_row', function(e) {
      var id = $(this).data("id");
      var variables = $("#variables" + id).val();
      var cut = $("#cut" + id).val();
      var vacations_types_id = $("#vacations_types_id" + id).val();
      var attendance_dely = $("#attendance_dely" + id).val();
      var early_departure = $("#early_departure" + id).val();
      var azn_houres = $("#azn_houres" + id).val();
      var total_hours = $("#total_hours" + id).val();
      var absen_hours = $("#absen_hours" + id).val();
      var additional_hours = $("#additional_hours" + id).val();
      var finance_cln_periods_id = $("#the_finance_cln_periods_id").val();
      var employees_code = $("#the_employees_code").val();
      $('#backup_freeze_modal').modal('show');
      jQuery.ajax({
        url: '{{ route('attenance_departure.save_active_Attendance_departure') }}',
        type: 'post',
        'dataType': 'html',
        cache: false,
        data: {
          "_token": '{{ csrf_token() }}',
          id: id,
          variables: variables,
          cut: cut,
          vacations_types_id: vacations_types_id,
          attendance_dely: attendance_dely,
          early_departure: early_departure,
          azn_houres: azn_houres,
          total_hours: total_hours,
          absen_hours: absen_hours,
          additional_hours: additional_hours,
          employees_code: employees_code,
          finance_cln_periods_id: finance_cln_periods_id
        },
        success: function(data) {
          load_active_Attendance_departure();
          setTimeout(function() {
            $("#backup_freeze_modal").modal("hide");
          }, 1000);

        },
        error: function() {
          load_active_Attendance_departure();
          setTimeout(function() {
            $("#backup_freeze_modal").modal("hide");
          }, 1000);
          alert("عفوا لقد حدث خطأ ");

        }

      });



    });

    $(document).on('click', '.move_to', function(e) {
      e.preventDefault();
      var dest = $(this).attr('data-dest');
      $("#" + dest).focus();

    });

    $(document).on('click', '#zeroresetdatetime_In', function(e) {
      e.preventDefault();
      $("#datetime_In").val("");
      $("#undozeroresetdatetime_In").show();

    });

    $(document).on('click', '#undozeroresetdatetime_In', function(e) {
      e.preventDefault();
      $("#datetime_In").val($(this).data('old'));
    });

    $(document).on('click', '#zeroresetdatetime_out', function(e) {
      e.preventDefault();
      $("#datetime_out").val("");
      $("#undozeroresetdatetime_out").show();

    });
    $(document).on('click', '#undozeroresetdatetime_out', function(e) {
      e.preventDefault();
      $("#datetime_out").val($(this).data('old'));
    });

    $(document).on('click', '#redo_update', function(e) {
      e.preventDefault();
      var datetime_in = $("#datetime_In").val(); // يجب أن تكون # داخل علامات الاقتباس
      var datetime_out = $("#datetime_out").val(); // يجب أن تكون # داخل علامات الاقتباس

      if ((datetime_in !== "" && datetime_out !== "") && datetime_out < datetime_in) {
        alert("لايمكن ان يكون توقيت الانصراف اقل من توقيت الحضور");
        return false;
      }
      var finance_cln_periods_id = $("#the_finance_cln_periods_id").val();
      var employees_code = $("#the_employees_code").val();
      var id = $(this).data("id");
      $('#backup_freeze_modal').modal('show');
      jQuery.ajax({
        url: '{{ route('attenance_departure.redo_update_action') }}',
        type: 'post',
        'dataType': 'json',
        cache: false,
        data: {
          "_token": '{{ csrf_token() }}',
          id: id,
          employees_code: employees_code,
          finance_cln_periods_id: finance_cln_periods_id,
          datetime_in: datetime_in,
          datetime_out: datetime_out
        },
        success: function(data) {
          setTimeout(function() {
            load_active_Attendance_departure();
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


    $(document).on('click', '#do_is_outo_offect_passmaV', function(e) {


      var finance_cln_periods_id = $("#the_finance_cln_periods_id").val();
      var employees_code = $("#the_employees_code").val();
      $("#backup_freeze_modal").modal("show");

      jQuery.ajax({
        url: '{{ route('attenance_departure.do_is_outo_offect_passmaV') }}',
        type: 'post',
        'dataType': 'json',
        cache: false,
        data: {
          "_token": '{{ csrf_token() }}',
          employees_code: employees_code,
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



  });
</script>

@endsection