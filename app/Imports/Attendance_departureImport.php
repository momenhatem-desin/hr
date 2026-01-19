<?php

namespace App\Imports;

use App\Models\Admin_panel_setting;
use App\Models\Attenance_actions;
use App\Models\Attenance_departure;
use App\Models\attenance_departure_actions_excel;
use App\Models\Employee;
use App\Models\Main_salary_employee;
use App\Models\Shifts_type;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class Attendance_departureImport implements ToCollection
{
  private $finance_cln_periods_data;
  private $branch_id;
  public function __construct($finance_cln_periods_data,$branch_id)
  {
    $this->finance_cln_periods_data = $finance_cln_periods_data;
      $this->branch_id = $branch_id; // ✅ هنا
  }
  
 
  public function collection(Collection $rows)
  {

   

    $com_code = auth('admin')->user()->com_code;
    $dataSetting = get_cols_where_row(new Admin_panel_setting(), array("*"), array('com_code' => $com_code));
    foreach ($rows as $row) {

      $dateUpdate = array();
      $dataToInsert = array();

      // تحويل التاريخ من Excel ل DateTime Object
      if (is_numeric($row[1])) {
        $excelDate = Date::excelToDateTimeObject($row[1]);
        $dateTime = $excelDate->format('Y-m-d H:i:s');
        $date = $excelDate->format('Y-m-d');
        $time = $excelDate->format('H:i:s');
      } else {
        $parsedDate = strtotime($row[1]);
        if ($parsedDate !== false) {
          $dateTime = date('Y-m-d H:i:s', $parsedDate);
          $date = date('Y-m-d', $parsedDate);
          $time = date('H:i:s', $parsedDate);
        } else {
          // إذا التاريخ غير صالح، تخطي السطر
          continue;
        }
      }

      //نشوف لو التاريخ داخل فتره الشهر المالى او لا
      if ($date < $this->finance_cln_periods_data['start_date_for_pasma'] || $date > $this->finance_cln_periods_data['end_date_for_pasma']) {
        continue;
      }


      if ($row[2] == 'o' || $row[2] == 'O') {
        $action_type = 2;
      } else {
        $action_type = 1;
      }


      $employeeData = get_cols_where_row(new Employee(), array("employees_code", "is_has_fixced_shift", "shift_type_id", "daily_work_hour", "branch_id", "Functiona_status"), array("com_code" => $com_code, "zketo_code" => $row[0]));
      if (!empty($employeeData)) {
          
     $branch_get_id = !empty($this->branch_id) ? $this->branch_id : $employeeData['branch_id'];

     
        $checkExsistsBefor = get_cols_where_row(new attenance_departure_actions_excel(), array("id"), array("com_code" => $com_code, 'finance_cln_periods_id' => $this->finance_cln_periods_data['id'], 'employees_code' => $employeeData['employees_code'], 'datetimeAction' => $dateTime, 'action_type' => $action_type));
        if (empty($checkExsistsBefor)) {
          $checkExsistSalary = get_cols_where_row(new Main_salary_employee(), array("id"), array("com_code" => $com_code, 'finance_cln_periods_id' => $this->finance_cln_periods_data['id'], 'employees_code' => $employeeData['employees_code']));
          if (!empty($checkExsistSalary)) {
            $dataToInsert['main_salary_employee_id'] = $checkExsistSalary['id'];
          }
          $dataToInsert['finance_cln_periods_id'] = $this->finance_cln_periods_data['id'];
          $dataToInsert['employees_code'] = $employeeData['employees_code'];
          $dataToInsert['datetimeAction'] = $dateTime;
          $dataToInsert['action_type'] = $action_type;
          $dataToInsert['created_at'] = date("Y-m-d H:i:s");
          $dataToInsert['added_by'] = auth('admin')->user()->id;
          $dataToInsert['com_code'] = $com_code;

          $Attendance_departure_excel_data = insert(new attenance_departure_actions_excel(), $dataToInsert, true);

          //بشكل موقت حنحط الكود هنا وهيتم لاحقا فصلهم
          //التاكد من مبيانات الموظف تم فوق
          //التاكد من حاله الشفت للموظف
          if ($employeeData['is_has_fixced_shift'] == 1) {
            $shiftdata = get_cols_where_row(new Shifts_type(), array("from_time", "to_time", "total_hour"), array("com_code" => $com_code, "id" => $employeeData['shift_type_id']));
            if (empty($shiftdata)) {
              continue;
            } else {
              $shiftHour = $shiftdata['total_hour'];
            }
          } else {
            if ($employeeData['daily_work_hour'] == 0 or $employeeData['daily_work_hour'] == NULL) {
              continue;
            } else {
              $shiftHour = $employeeData['daily_work_hour'];
            }
          }

          //ثانيا نشوف هل يوجد يوم فاضى مسجل مطابق لهذا التاريخ
          $emptyRecords = Attenance_departure::where("employees_code", $employeeData['employees_code'])
            ->where("finance_cln_periods_id", $this->finance_cln_periods_data['id'])
            ->whereNull('datetime_In')
            ->where("the_day_date", $date)
            ->where("com_code", $com_code)
            ->get();

          foreach ($emptyRecords as $emptyRecord) {
            if ($action_type == 1) {
              destroy(new Attenance_departure(), ["id" => $emptyRecord->id]);
            }
          }

          //ثالثا هنجيب اخر سجل بصمه مسجل  
          //cheak_for_last_record
          $last = Attenance_departure::select("*")->where("employees_code", "=", $employeeData['employees_code'])->where("finance_cln_periods_id", "=", $this->finance_cln_periods_data['id'])->where("com_code", "=", $com_code)->where("dateIn", "!=", null)->where("dateIn", "<=", $date)->orderby("dateIn", "DESC")->first();

          if (!empty($last)) {
            // حنشوف فرق الدقئق بين اخر بصمه والبصمه الحاليه
            $lastAttendance = $last['datetime_In'];
            $seconds = strtotime($dateTime) - strtotime($last['datetime_In']);
            $hourdiff = $seconds / 60 / 60;
            $hourdiff = number_format((float)$hourdiff, 2, '.', '');
            $minutesiff = $seconds / 60;
            $minutesiff = number_format((float)$minutesiff, 2, '.', '');
            if ($hourdiff < 0) $hourdiff = $hourdiff * (-1);
            if ($minutesiff < 0) $minutesiff = $minutesiff * (-1);
            //اذا كان تاريخ اخر بصمه مسجله هو نفسه تاريخ البصمه الحاليه يعنى نفس اليوم
            if ($last['dateIn'] == $date) {
              // نشوف البصمه دى تاكديه ولا بصمه اخرى
              if ($minutesiff > $dataSetting['less_than_miniute_neglecting_passmaa']) {
                // نشتغل على متغيرات اقفال البصمه الحاليه
                $dateUpdate['datetime_out'] = $dateTime;
                $dateUpdate['dateOut'] = $date;
                $dateUpdate['timeOut'] = $time;
                $dateUpdate['total_hours'] = $hourdiff;
                if ($hourdiff < $shiftHour) {
                  $dateUpdate['additional_hours'] = 0;
                  $dateUpdate['absen_hours'] = $shiftHour - $hourdiff;
                }
                if ($hourdiff > $shiftHour) {
                  $dateUpdate['additional_hours'] = $hourdiff - $shiftHour;
                  $dateUpdate['absen_hours'] = 0;
                }
              

                //هنا نشوف لو الموظف له شفت ثابت نشوف موضوع الانصراف المبكر والجزاء عليه
                $timeexit = $time;
                if ($employeeData['is_has_fixced_shift'] == 1) {
                  if ($shiftdata['to_time'] >= $timeexit) {
                    $secondsNow = strtotime($shiftdata['to_time']) - strtotime($timeexit);
                    $minutesifNow = $secondsNow / 60;
                    $minutesifNow = number_format((float)$minutesifNow, 2, '.', '');
                    //نشوف هل الموظف اجتاز عدد دقائق الانصراف المبكر المسموح بيها ننزل عليه متغيرات
                    if ($minutesifNow > $dataSetting['after_miniute_calculate_early_departure']) {
                      $dateUpdate['early_departure'] = $minutesifNow;
                      $countercutQuarterday = get_count_where(new Attenance_departure(), array("com_code" => $com_code, "employees_code" => $employeeData['employees_code'], "finance_cln_periods_id" => $this->finance_cln_periods_data['id'], "cut" => .25));
                      $countercuthelfday = get_count_where(new Attenance_departure(), array("com_code" => $com_code, "employees_code" => $employeeData['employees_code'], "finance_cln_periods_id" => $this->finance_cln_periods_data['id'], "cut" => .5));
                      $countercutoneday = get_count_where(new Attenance_departure(), array("com_code" => $com_code, "employees_code" => $employeeData['employees_code'], "finance_cln_periods_id" => $this->finance_cln_periods_data['id'], "cut" => 1));
                      if (($countercutoneday) >= $dataSetting['after_time_allday_daycut']) {
                        $dateUpdate['cut'] = 1 + ($countercutoneday);
                      } elseif (($countercuthelfday) >= $dataSetting['after_time_half_daycut']) {
                        $dateUpdate['cut'] = .5 + ($countercuthelfday * .5);
                      } elseif (($countercutQuarterday) >= $dataSetting['after_miniute_quarterday']) {
                        $dateUpdate['cut'] = .25 + ($countercutQuarterday * 0.25);
                      } else {
                        //$dateUpdate['cut'] = 0;
                      }
                    }elseif($minutesifNow < $dataSetting['after_miniute_calculate_early_departure']){
                          $dateUpdate['early_departure'] = 0;
                        }
                  }
                }
             /*   dd([
    'shift_data' => $shiftdata,
    'time_exit' => $timeexit,
    'comparison' => [
        'shift_end' => strtotime($shiftdata['to_time']),
        'exit_time' => strtotime($timeexit),
        'shift_end_formatted' => date('H:i:s', strtotime($shiftdata['to_time'])),
        'exit_formatted' => date('H:i:s', strtotime($timeexit))
    ]
]);*/
                $dateUpdate['vacations_types_id'] = 1;
                $flagupdateperent = update(new Attenance_departure(), $dateUpdate, array("id" => $last['id'], "com_code" => $com_code));
                if ($flagupdateperent) {

                  $dataToInsertAction['attendance_departure_id'] = $last['id'];
                  $dataToInsertAction['finance_cln_periods_id'] = $this->finance_cln_periods_data['id'];
                  $dataToInsertAction['employees_code'] = $employeeData['employees_code'];
                  $dataToInsertAction['datetimeAction'] = $dateTime;
                  $dataToInsertAction['action_type'] = $action_type;
                  $dataToInsertAction['it_is_active_with_parent'] = 0;
                  $dataToInsertAction['added_method'] = 1;
                  $dataToInsertAction['is_made_action_on_emp'] = 0;
                  $dataToInsertAction['com_code'] = $com_code;
                  $dataToInsertAction['added_by'] = auth('admin')->user()->id;
                  $dataToInsertAction['attenance_departure_actions_excel_id'] = $Attendance_departure_excel_data['id'];
                  $dataToUpdateAction['it_is_active_with_parent'] = 1;
                  insert(new Attenance_actions(), $dataToInsertAction);
                  update(new Attenance_actions(), $dataToUpdateAction, array("com_code" => $com_code, "action_type" => $action_type, "attendance_departure_id" => $last['id'], "datetimeAction" => $dateUpdate['datetime_out']));
                }
              }
            } else {
              //تواريخ مختلفه هنا معناها آخر بصمة مش لنفس اليوم - مفيش أكشن، نكمل
              // if hour 
              //هنا لو عدد سعات فرق بين بصمه الحضور والانصراف  تساوى او اقل من عدد ساعات شفت الموظف
              if ($hourdiff <= $shiftHour) {
                //دى تقفيله الشفت
                // نشوف البصمه دى تاكديه ولا بصمه اخرى
                if ($minutesiff > $dataSetting['less_than_miniute_neglecting_passmaa']) {
                  // نشتغل على متغيرات اقفال البصمه الحاليه
                  $dateUpdate['datetime_out'] = $dateTime;
                  $dateUpdate['dateOut'] = $date;
                  $dateUpdate['timeOut'] = $time;
                  $dateUpdate['total_hours'] = $hourdiff;
                  if ($hourdiff < $shiftHour) {
                    $dateUpdate['additional_hours'] = 0;
                    $dateUpdate['absen_hours'] = $shiftHour - $hourdiff;
                  }
                  if ($hourdiff > $shiftHour) {
                    $dateUpdate['additional_hours'] = $hourdiff - $shiftHour;
                    $dateUpdate['absen_hours'] = 0;
                  }
              

                  //هنا نشوف لو الموظف له شفت ثابت نشوف موضوع الانصراف المبكر والجزاء عليه
                  $timeexit = $time;
                  if ($employeeData['is_has_fixced_shift'] == 1) {
                    if (strtotime($shiftdata['to_time']) > strtotime($timeexit)) {
                      $secondsNow = strtotime($shiftdata['to_time']) - strtotime($timeexit);
                      $minutesifNow = $secondsNow / 60;
                      $minutesifNow = number_format((float)$minutesifNow, 2, '.', '');
                      //نشوف هل الموظف اجتاز عدد دقائق الانصراف المبكر المسموح بيها ننزل عليه متغيرات
                      if ($minutesifNow > $dataSetting['after_miniute_calculate_early_departure']) {
                        $dateUpdate['early_departure'] = $minutesifNow;
                        $countercutQuarterday = get_count_where(new Attenance_departure(), array("com_code" => $com_code, "employees_code" => $employeeData['employees_code'], "finance_cln_periods_id" => $this->finance_cln_periods_data['id'], "cut" => .25));
                        $countercuthelfday = get_count_where(new Attenance_departure(), array("com_code" => $com_code, "employees_code" => $employeeData['employees_code'], "finance_cln_periods_id" => $this->finance_cln_periods_data['id'], "cut" => .5));
                        $countercutoneday = get_count_where(new Attenance_departure(), array("com_code" => $com_code, "employees_code" => $employeeData['employees_code'], "finance_cln_periods_id" => $this->finance_cln_periods_data['id'], "cut" => 1));
                        if (($countercutoneday) >= $dataSetting['after_time_allday_daycut']) {
                          $dateUpdate['cut'] = 1 + ($countercutoneday);
                        } elseif (($countercuthelfday) >= $dataSetting['after_time_half_daycut']) {
                          $dateUpdate['cut'] = .5 + ($countercuthelfday * .5);
                        } elseif (($countercutQuarterday) >= $dataSetting['after_miniute_quarterday']) {
                          $dateUpdate['cut'] = .25 + ($countercutQuarterday * 0.25);
                        } else {
                          //$dateUpdate['cut'] = 0;
                        }
                      }elseif($minutesifNow < $dataSetting['after_miniute_calculate_early_departure']){
                          $dateUpdate['early_departure'] = 0;
                        }
                    }
                  }
                  $dateUpdate['vacations_types_id'] = 1;
                  $flagupdateperent = update(new Attenance_departure(), $dateUpdate, array("id" => $last['id'], "com_code" => $com_code));
                  if ($flagupdateperent) {

                    $dataToInsertAction['attendance_departure_id'] = $last['id'];
                    $dataToInsertAction['finance_cln_periods_id'] = $this->finance_cln_periods_data['id'];
                    $dataToInsertAction['employees_code'] = $employeeData['employees_code'];
                    $dataToInsertAction['datetimeAction'] = $dateTime;
                    $dataToInsertAction['action_type'] = $action_type;
                    $dataToInsertAction['it_is_active_with_parent'] = 0;
                    $dataToInsertAction['added_method'] = 1;
                    $dataToInsertAction['is_made_action_on_emp'] = 0;
                    $dataToInsertAction['com_code'] = $com_code;
                    $dataToInsertAction['added_by'] = auth('admin')->user()->id;
                    $dataToInsertAction['attenance_departure_actions_excel_id'] = $Attendance_departure_excel_data['id'];
                    $dataToUpdateAction['it_is_active_with_parent'] = 1;

                    insert(new Attenance_actions(), $dataToInsertAction);
                    update(new Attenance_actions(), $dataToUpdateAction, array("com_code" => $com_code, "action_type" => $action_type, "attendance_departure_id" => $last['id'], "datetimeAction" => $dateUpdate['datetime_out']));
                  }
                }

                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              } else {
                //$hourdiff geaether then shift hour
                // هنا نشوف الضبط العام والحدالاقصى لاحتساب عدد ساعات الاضافى والا سيكون شفت جديد
                if (($hourdiff - $shiftHour) <= $dataSetting['max_hours_take_pasma_as_addtional']) {
                  // نشوف البصمه دى تاكديه ولا بصمه اخرى
                  if ($minutesiff > $dataSetting['less_than_miniute_neglecting_passmaa']) {
                    // نشتغل على متغيرات اقفال البصمه الحاليه
                    $dateUpdate['datetime_out'] = $dateTime;
                    $dateUpdate['dateOut'] = $date;
                    $dateUpdate['timeOut'] = $time;
                    $dateUpdate['total_hours'] = $hourdiff;
                    if ($hourdiff < $shiftHour) {
                      $dateUpdate['additional_hours'] = 0;
                      $dateUpdate['absen_hours'] = $shiftHour - $hourdiff;
                    }
                    if ($hourdiff > $shiftHour) {
                      $dateUpdate['additional_hours'] = $hourdiff - $shiftHour;
                      $dateUpdate['absen_hours'] = 0;
                    }
                       
                    //هنا نشوف لو الموظف له شفت ثابت نشوف موضوع الانصراف المبكر والجزاء عليه
                    $timeexit = $time;
                    if ($employeeData['is_has_fixced_shift'] == 1) {
                      if ($shiftdata['to_time'] > $timeexit) {
                        $secondsNow = strtotime($shiftdata['to_time']) - strtotime($timeexit);
                        $minutesifNow = $secondsNow / 60;
                        $minutesifNow = number_format((float)$minutesifNow, 2, '.', '');
                        //نشوف هل الموظف اجتاز عدد دقائق الانصراف المبكر المسموح بيها ننزل عليه متغيرات
                        //هذه الحاله لان تتطبق
                        if ($minutesifNow > $dataSetting['after_miniute_calculate_early_departure']) {
                          $dateUpdate['early_departure'] = $minutesifNow;
                          $countercutQuarterday = get_count_where(new Attenance_departure(), array("com_code" => $com_code, "employees_code" => $employeeData['employees_code'], "finance_cln_periods_id" => $this->finance_cln_periods_data['id'], "cut" => .25));
                          $countercuthelfday = get_count_where(new Attenance_departure(), array("com_code" => $com_code, "employees_code" => $employeeData['employees_code'], "finance_cln_periods_id" => $this->finance_cln_periods_data['id'], "cut" => .5));
                          $countercutoneday = get_count_where(new Attenance_departure(), array("com_code" => $com_code, "employees_code" => $employeeData['employees_code'], "finance_cln_periods_id" => $this->finance_cln_periods_data['id'], "cut" => 1));
                          if (($countercutoneday) >= $dataSetting['after_time_allday_daycut']) {
                            $dateUpdate['cut'] = 1 + ($countercutoneday);
                          } elseif (($countercuthelfday) >= $dataSetting['after_time_half_daycut']) {
                            $dateUpdate['cut'] = .5 + ($countercuthelfday * .5);
                          } elseif (($countercutQuarterday) >= $dataSetting['after_miniute_quarterday']) {
                            $dateUpdate['cut'] = .25 + ($countercutQuarterday * 0.25);
                          } else {
                            //$dateUpdate['cut'] = 0;
                          }
                        }elseif($minutesifNow < $dataSetting['after_miniute_calculate_early_departure']){
                          $dateUpdate['early_departure'] = 0;
                        }
                      }
                    }

                    $dateUpdate['vacations_types_id'] = 1;
                    $flagupdateperent = update(new Attenance_departure(), $dateUpdate, array("id" => $last['id'], "com_code" => $com_code));
                    if ($flagupdateperent) {

                      $dataToInsertAction['attendance_departure_id'] = $last['id'];
                      $dataToInsertAction['finance_cln_periods_id'] = $this->finance_cln_periods_data['id'];
                      $dataToInsertAction['employees_code'] = $employeeData['employees_code'];
                      $dataToInsertAction['datetimeAction'] = $dateTime;
                      $dataToInsertAction['action_type'] = $action_type;
                      $dataToInsertAction['it_is_active_with_parent'] = 0;
                      $dataToInsertAction['added_method'] = 1;
                      $dataToInsertAction['is_made_action_on_emp'] = 0;
                      $dataToInsertAction['com_code'] = $com_code;
                      $dataToInsertAction['added_by'] = auth('admin')->user()->id;
                      $dataToInsertAction['attenance_departure_actions_excel_id'] = $Attendance_departure_excel_data['id'];
                      $dataToUpdateAction['it_is_active_with_parent'] = 1;

                      insert(new Attenance_actions(), $dataToInsertAction);
                      update(new Attenance_actions(), $dataToUpdateAction, array("com_code" => $com_code, "action_type" => $action_type, "attendance_departure_id" => $last['id'], "datetimeAction" => $dateUpdate['datetime_out']));
                    }
                  }
                } else {
                  // هنا هيبقى عنى تسليم شفت جديد 
                  $dataToInsert_departure['status_move'] = 1;
                  $dataToInsert_departure['shift_hour_contract'] = $shiftHour;
                  $dataToInsert_departure['employees_code'] = $employeeData['employees_code'];
                  $dataToInsert_departure['finance_cln_periods_id'] = $this->finance_cln_periods_data['id'];
                  $dataToInsert_departure['dateIn'] = $date;
                  $dataToInsert_departure['timeIn'] = $time;
                  $timeenter = $dataToInsert_departure['timeIn'];
                  $dataToInsert_departure['datetime_In'] = $dateTime;
                  $dataToInsert_departure['com_code'] = $com_code;
                  $dataToInsert_departure['added_by'] = auth('admin')->user()->id;
                  //هنا هنشوف  لو الموظف ليه شفت ثابت هنطبق عليه بعض المتغيرات 
                  if ($employeeData['is_has_fixced_shift'] == 1) {
                    if ($shiftdata['from_time'] < $timeenter) {
                      $secondsNow = strtotime($timeenter) - strtotime($shiftdata['from_time']);
                      $minutesifNow = $secondsNow / 60;
                      $minutesifNow = number_format((float)$minutesifNow, 2, '.', '');
                      //نشوف هل الموظف اجتاز عدد دقائق التاخير المسموح بيها ننزل عليه متغيرات
                      if ($minutesifNow >= $dataSetting['after_miniute_calculate_delay']) {
                        $dataToInsert_departure['attendance_dely'] = $minutesifNow;
                        $countercutQuarterday = get_count_where(new Attenance_departure(), array("com_code" => $com_code, "employees_code" => $employeeData['employees_code'], "finance_cln_periods_id" => $this->finance_cln_periods_data['id'], "cut" => .25));
                        $countercuthelfday = get_count_where(new Attenance_departure(), array("com_code" => $com_code, "employees_code" => $employeeData['employees_code'], "finance_cln_periods_id" => $this->finance_cln_periods_data['id'], "cut" => .5));
                        $countercutoneday = get_count_where(new Attenance_departure(), array("com_code" => $com_code, "employees_code" => $employeeData['employees_code'], "finance_cln_periods_id" => $this->finance_cln_periods_data['id'], "cut" => 1));
                        if (($countercutoneday) >= $dataSetting['after_time_allday_daycut']) {
                          $dataToInsert_departure['cut'] = 1;
                        } elseif (($countercuthelfday) >= $dataSetting['after_time_half_daycut']) {
                          $dataToInsert_departure['cut'] = .5;
                        } elseif (($countercutQuarterday) >= $dataSetting['after_miniute_quarterday']) {
                          $dataToInsert_departure['cut'] = .25;
                        } else {
                          $dataToInsert_departure['cut'] = 0;
                        }
                      }elseif($minutesifNow < $dataSetting['after_miniute_calculate_delay']){
                       $dataToInsert_departure['attendance_dely'] = 0;
                     }
                    }
                  }

                  $dataToInsert_departure['year_and_month'] = $this->finance_cln_periods_data['year_and_month'];
                  $dataToInsert_departure['branch_id'] = $branch_get_id;
                  $dataToInsert_departure['Functiona_status'] = $employeeData['Functiona_status'];
                  $dataToInsert_departure['vacations_types_id'] = 1;
                  $main_salary_employee = get_cols_where_row(new Main_salary_employee(), array("id"), array("com_code" => $com_code, "employees_code" => $employeeData['employees_code'], "is_archived" => 0));
                  if (!empty($main_salary_employee)) {
                    $dataToInsert_departure['main_salary_employee_id'] = $main_salary_employee['id'];
                  }
                  $dataToInsert_departure['the_day_date'] = $date;
                  $flaginsertprent = insert(new Attenance_departure(), $dataToInsert_departure, true);
                  if ($flaginsertprent) {
                    //هنسجل البصمات الفعليه للموظف 
                    $dataToInsertAction['attendance_departure_id'] = $flaginsertprent['id'];
                    $dataToInsertAction['finance_cln_periods_id'] = $this->finance_cln_periods_data['id'];
                    $dataToInsertAction['employees_code'] = $employeeData['employees_code'];
                    $dataToInsertAction['datetimeAction'] = $dateTime;
                    $dataToInsertAction['action_type'] = $action_type;
                    $dataToInsertAction['it_is_active_with_parent'] = 0;
                    $dataToInsertAction['added_method'] = 1;
                    $dataToInsertAction['is_made_action_on_emp'] = 0;
                    $dataToInsertAction['com_code'] = $com_code;
                    $dataToInsertAction['added_by'] = auth('admin')->user()->id;
                    $dataToInsertAction['attenance_departure_actions_excel_id'] = $Attendance_departure_excel_data['id'];

                    insert(new Attenance_actions(), $dataToInsertAction);
                    $dataToUpdateAction['it_is_active_with_parent'] = 1;
                    update(new Attenance_actions(), $dataToUpdateAction, array("com_code" => $com_code, "action_type" => $action_type, "attendance_departure_id" => $flaginsertprent['id'], "datetimeAction" => $dataToInsert_departure['datetime_In']));
                  }
                }
              }
            }
          } else {
            // تعتبر اول بصمه للموظف خلال  الشهر المالى المفتوح حاليا 
            //هنبدا نجهز الادخال وهنعتبر ان البصمه  دى حضور بغض النظر عن نوع البصمه 
            //after cheak !empty last

            $dataToInsert_departure['status_move'] = 1;
            $dataToInsert_departure['shift_hour_contract'] = $shiftHour;
            $dataToInsert_departure['employees_code'] = $employeeData['employees_code'];
            $dataToInsert_departure['finance_cln_periods_id'] = $this->finance_cln_periods_data['id'];
            $dataToInsert_departure['dateIn'] = $date;
            $dataToInsert_departure['timeIn'] = $time;
            $timeenter = $dataToInsert_departure['timeIn'];
            $dataToInsert_departure['datetime_In'] = $dateTime;
            $dataToInsert_departure['com_code'] = $com_code;
            $dataToInsert_departure['added_by'] = auth('admin')->user()->id;
            //هنا هنشوف  لو الموظف ليه شفت ثابت هنطبق عليه بعض المتغيرات 
            if ($employeeData['is_has_fixced_shift'] == 1) {
              if ($shiftdata['from_time'] < $timeenter) {
                $secondsNow = strtotime($timeenter) - strtotime($shiftdata['from_time']);
                $minutesifNow = $secondsNow / 60;
                $minutesifNow = number_format((float)$minutesifNow, 2, '.', '');
                //نشوف هل الموظف اجتاز عدد دقائق التاخير المسموح بيها ننزل عليه متغيرات
                if ($minutesifNow >= $dataSetting['after_miniute_calculate_delay']) {
                  $dataToInsert_departure['attendance_dely'] = $minutesifNow;
                  $countercutQuarterday = get_count_where(new Attenance_departure(), array("com_code" => $com_code, "employees_code" => $employeeData['employees_code'], "finance_cln_periods_id" => $this->finance_cln_periods_data['id'], "cut" => .25));
                  $countercuthelfday = get_count_where(new Attenance_departure(), array("com_code" => $com_code, "employees_code" => $employeeData['employees_code'], "finance_cln_periods_id" => $this->finance_cln_periods_data['id'], "cut" => .5));
                  $countercutoneday = get_count_where(new Attenance_departure(), array("com_code" => $com_code, "employees_code" => $employeeData['employees_code'], "finance_cln_periods_id" => $this->finance_cln_periods_data['id'], "cut" => 1));
                  if (($countercutoneday) >= $dataSetting['after_time_allday_daycut']) {
                    $dataToInsert_departure['cut'] = 1;
                  } elseif (($countercuthelfday) >= $dataSetting['after_time_half_daycut']) {
                    $dataToInsert_departure['cut'] = .5;
                  } elseif (($countercutQuarterday) >= $dataSetting['after_miniute_quarterday']) {
                    $dataToInsert_departure['cut'] = .25;
                  } else {
                    $dataToInsert_departure['cut'] = 0;
                  }
                }elseif($minutesifNow < $dataSetting['after_miniute_calculate_delay']){
                 $dataToInsert_departure['attendance_dely'] = 0;
                }
              }
            }

            $dataToInsert_departure['year_and_month'] = $this->finance_cln_periods_data['year_and_month'];
            $dataToInsert_departure['branch_id'] = $branch_get_id;
            $dataToInsert_departure['Functiona_status'] = $employeeData['Functiona_status'];
            $dataToInsert_departure['vacations_types_id'] = 1;
            $main_salary_employee = get_cols_where_row(new Main_salary_employee(), array("id"), array("com_code" => $com_code, "employees_code" => $employeeData['employees_code'], "is_archived" => 0));
            if (!empty($main_salary_employee)) {
              $dataToInsert_departure['main_salary_employee_id'] = $main_salary_employee['id'];
            }
            $dataToInsert_departure['the_day_date'] = $date;
            $flaginsertprent = insert(new Attenance_departure(), $dataToInsert_departure, true);
            if ($flaginsertprent) {
              //هنسجل البصمات الفعليه للموظف 
              $dataToInsertAction['attendance_departure_id'] = $flaginsertprent['id'];
              $dataToInsertAction['finance_cln_periods_id'] = $this->finance_cln_periods_data['id'];
              $dataToInsertAction['employees_code'] = $employeeData['employees_code'];
              $dataToInsertAction['datetimeAction'] = $dateTime;
              $dataToInsertAction['action_type'] = $action_type;
              $dataToInsertAction['it_is_active_with_parent'] = 0;
              $dataToInsertAction['added_method'] = 1;
              $dataToInsertAction['is_made_action_on_emp'] = 0;
              $dataToInsertAction['com_code'] = $com_code;
              $dataToInsertAction['added_by'] = auth('admin')->user()->id;
              $dataToInsertAction['attenance_departure_actions_excel_id'] = $Attendance_departure_excel_data['id'];
              insert(new Attenance_actions(), $dataToInsertAction);
              $dataToUpdateAction['it_is_active_with_parent'] = 1;
              update(new Attenance_actions(), $dataToUpdateAction, array("com_code" => $com_code, "action_type" => $action_type, "attendance_departure_id" => $flaginsertprent['id'], "datetimeAction" => $dataToInsert_departure['datetime_In']));
            }
          }
        }
      }
    }
  } // ← قوس إغلاق function collection
} // ← قوس إغلاق class