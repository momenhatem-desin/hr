<?php

namespace App\Traits;

use App\Models\Admin_panel_setting;
use App\Models\Attenance_departure;
use App\Models\Employee;
use App\Models\Main_salary_employee;
use App\Models\Main_salary_employee_absence;
use App\Models\Main_salary_employees_allowances;
use App\Models\Main_salary_employees_addtion;
use App\Models\Main_salary_employees_discound;
use App\Models\Main_salary_employees_loans;
use App\Models\Main_salary_employees_P_loans;
use App\Models\Main_salary_P_loans_akast;
use App\Models\Main_salary_employees_rewards;
use App\Models\Main_salary_employees_sanctions;
use App\Models\Finance_cln_periods;
use App\Models\Employees_fixed_suits;
use App\Models\MainVacationsBalance;

trait GeneralTrait
{

    public function Recalculate_main_salary_employee($main_salary_employee_id)
    {
        $com_code = auth('admin')->user()->com_code;
        $Main_salary_employee_data = get_cols_where_row(new Main_salary_employee, array("*"), array("com_code" => $com_code, "id" => $main_salary_employee_id, "is_archived" => 0));
        $Main_salary_employee_data_get_last_salary = get_cols_where_row(new Main_salary_employee, array("final_the_net_after_colse", "id"), array("com_code" => $com_code, "is_archived" => 1, "employees_code" => $Main_salary_employee_data['employees_code'], "Finance_cln_periods_id" => $Main_salary_employee_data['Finance_cln_periods_id'] - 1));
        if (!empty($Main_salary_employee_data)) {
            $employee_data = get_cols_where_row(new Employee(), array("employees_code", "motivation", "Socialnsurancecutmonthely", "medicalinsurancecutmonthely", "emp_sal", "day_price", "id"), array("com_code" => $com_code, "employees_code" => $Main_salary_employee_data['employees_code']));
            $Finance_cln_periods_data = get_cols_where_row(new Finance_cln_periods, array("year_and_month"), array("com_code" => $com_code, "is_open" => 1, "id" => $Main_salary_employee_data['Finance_cln_periods_id']));
            if (!empty($employee_data) and !empty($Finance_cln_periods_data)) {
                //اولا المستحق للموظف
                $dataToUpdate['day_price'] = $employee_data['day_price'];
                $dataToUpdate['emp_sal'] = $employee_data['emp_sal'];
                $dataToUpdate['motivation'] = $employee_data['motivation'];
                $dataToUpdate['fixed_suits'] = get_sum_where(new Employees_fixed_suits(), "value", array("com_code" => $com_code, "employees_id" => $employee_data['id']));
                if (empty($Main_salary_employee_data_get_last_salary['final_the_net_after_colse'])) {
                    $dataToUpdate['last_salary_remain_blance'] = 0;
                } else {
                    $dataToUpdate['last_salary_remain_blance'] = $Main_salary_employee_data_get_last_salary['final_the_net_after_colse'];
                }

                // البدالات المتغيرة
                $dataToUpdate['changable_suits'] = get_sum_where(new Main_salary_employees_allowances(), "total", array("com_code" => $com_code, "main_salary_employee_id" => $main_salary_employee_id));
                // المكافات المالية
                $dataToUpdate['additions'] = get_sum_where(new Main_salary_employees_rewards(), "total", array("com_code" => $com_code, "main_salary_employee_id" => $main_salary_employee_id));
                //اضافى الايام
                $dataToUpdate['addtional_days_counter'] = get_sum_where(new Main_salary_employees_addtion(), "value", array("com_code" => $com_code, "main_salary_employee_id" => $main_salary_employee_id));
                $dataToUpdate['addtional_days'] = get_sum_where(new Main_salary_employees_addtion(), "total", array("com_code" => $com_code, "main_salary_employee_id" => $main_salary_employee_id));

                //مجموع اضافه للراتب
                $dataToUpdate['total_benefits'] =  $dataToUpdate['emp_sal'] + $dataToUpdate['motivation'] + $dataToUpdate['changable_suits']
                    + $dataToUpdate['additions'] + $dataToUpdate['addtional_days'] + $dataToUpdate['fixed_suits'];
                //ثانيا الاستقاعات  للموظف
                $dataToUpdate['socialinsurancecutmonthely'] = $employee_data['Socialnsurancecutmonthely'];
                $dataToUpdate['medicalinsurancecutmonthely'] = $employee_data['medicalinsurancecutmonthely'];
                //الجزاءات
                $dataToUpdate['sanctions_days_counter_type1'] = get_sum_where(new Main_salary_employees_sanctions(), "value", array("com_code" => $com_code, "main_salary_employee_id" => $main_salary_employee_id));
                $dataToUpdate['sanctions_days_total_type1'] = get_sum_where(new Main_salary_employees_sanctions(), "total", array("com_code" => $com_code, "main_salary_employee_id" => $main_salary_employee_id));
                //الغيابات
                $dataToUpdate['absence_days_counter'] = get_sum_where(new Main_salary_employee_absence(), "value", array("com_code" => $com_code, "main_salary_employee_id" => $main_salary_employee_id));
                $dataToUpdate['absence_days'] = get_sum_where(new Main_salary_employee_absence(), "total", array("com_code" => $com_code, "main_salary_employee_id" => $main_salary_employee_id));
                // الخصومات المالية
                $dataToUpdate['discount'] = get_sum_where(new Main_salary_employees_discound(), "total", array("com_code" => $com_code, "main_salary_employee_id" => $main_salary_employee_id));
                // السلف الشهرية
                $dataToUpdate['monthly_loan'] = get_sum_where(new Main_salary_employees_loans(), "total", array("com_code" => $com_code, "main_salary_employee_id" => $main_salary_employee_id));
                //  تسحب قسط  السلفه المستديمة المطابقة  للشهر الحالى
                $dataToUpdate['permanent_loan'] = Main_salary_P_loans_akast::where("com_code", "=", $com_code)->where("year_and_month", "=", $Finance_cln_periods_data['year_and_month'])->where("is_parent_dismissail_done", "=", 1)->where("is_archived", "=", 0)->where("state", "!=", "2")->where("employees_code", "=", $Main_salary_employee_data['employees_code'])->where("is_parent_dismissail_done", "=", 1)->sum('month_kast_value');
                $dataToUpdateAksat['state'] = 1;
                $dataToUpdateAksat['main_salary_employee_id'] = $main_salary_employee_id;

                Main_salary_P_loans_akast::where("com_code", "=", $com_code)->where("year_and_month", "=", $Finance_cln_periods_data['year_and_month'])->where("is_parent_dismissail_done", "=", 1)->where("is_archived", "=", 0)->where("state", "!=", "2")->where("employees_code", "=", $Main_salary_employee_data['employees_code'])->update($dataToUpdateAksat);

                $dataToUpdate['total_deductions'] = $dataToUpdate['socialinsurancecutmonthely'] + $dataToUpdate['medicalinsurancecutmonthely'] +  $dataToUpdate['sanctions_days_total_type1'] +
                    $dataToUpdate['absence_days'] + $dataToUpdate['discount'] + $dataToUpdate['monthly_loan'] + $dataToUpdate['permanent_loan'];
                //صافى المرتب
                $dataToUpdate['final_the_net'] = $dataToUpdate['last_salary_remain_blance'] + ($dataToUpdate['total_benefits'] - $dataToUpdate['total_deductions']);


                update(new Main_salary_employee(), $dataToUpdate, array("com_code" => $com_code, "id" => $main_salary_employee_id, "is_archived" => 0));
            }
        }
    }



    public function calculate_employess_vactions_blance($employees_code)
    {
        $com_code = auth("admin")->user()->com_code;
        $employees_data = get_cols_where_row(new Employee(), array("*"), array('com_code' => $com_code, "employees_code" => $employees_code, 'Functiona_status' => 1));
        $admin_panel_settingData = get_cols_where_row(new Admin_panel_setting(), array("*"), array('com_code' => $com_code));


        if (!empty($employees_data) and !empty($admin_panel_settingData)) {

            //التحقق  من وجود شهر مالى مفتوح
            $cheack_currentOpenMonth = get_cols_where_row(new Finance_cln_periods(), array("*"), array("com_code" => $com_code, "is_open" => 1));
            if (!empty($cheack_currentOpenMonth)) {

                if ($employees_data['is_active_for_Vaccation'] == 1) {
                    if ($employees_data['is_done_Vaccation_formula'] == 0) {

                        // اول مره ينزل له رصيد
                        //or your date as well
                        $now =  strtotime($cheack_currentOpenMonth['end_date_for_pasma']);
                        $your_date = strtotime($employees_data['emp_start_date']);
                        $datadiff = $now - $your_date;
                        $diffrance_days = round($datadiff / (60 * 60 * 24));
                        //لو عدد الايام يساوى او اكبر من الضبط العام
                        if ($diffrance_days >= $admin_panel_settingData['after_days_begin_vacation']) {
                            $activeDay = number_format($admin_panel_settingData['after_days_begin_vacation'] * 1);
                            $current_year = $cheack_currentOpenMonth['FINANCE_YR'];
                            $work_year = date('Y', strtotime($employees_data['emp_start_date']));
                            $dateActiveFormal = date('Y-m-d', strtotime($employees_data['emp_start_date'] . ' + ' . $activeDay . ' days '));

                            if ($work_year == $current_year) {
                                $dataInsert['current_month_balance'] = $admin_panel_settingData['first_balance_begin_vacation'];
                                $dataInsert['total_available_balance'] = $admin_panel_settingData['first_balance_begin_vacation'];
                                $dataInsert['net_balance'] = $admin_panel_settingData['first_balance_begin_vacation'];
                            } else {
                                $dataInsert['current_month_balance'] = $admin_panel_settingData['monthly_vacation_balance'];
                                $dataInsert['total_available_balance'] = $admin_panel_settingData['monthly_vacation_balance'];
                                $dataInsert['net_balance'] = $admin_panel_settingData['monthly_vacation_balance'];
                            }
                            //نقطه خلاف
                            if ($diffrance_days <= 360) {
                                $dataInsert['year_and_month'] = date('Y-m', strtotime($dateActiveFormal));
                            } else {
                                $dataInsert['year_and_month'] = $current_year . '-01';
                            }

                            $dataInsert['FINANCE_YR'] = $current_year;
                            $dataInsert['employees_code'] = $employees_code;
                            $dataInsert['created_at'] = date('Y-m-d H:i:s');
                            $dataInsert['added_by'] = auth("admin")->user()->id;
                            $dataInsert['com_code'] = $com_code;

                            $checkExsitsMinV = get_cols_where_row(new MainVacationsBalance(), array("id"), array("com_code" => $com_code, "employees_code" => $employees_code, "FINANCE_YR" => $current_year, "year_and_month" => $dataInsert['year_and_month']));
                            if (empty($checkExsitsMinV)) {
                                $flag = insert(new MainVacationsBalance(), $dataInsert);
                                if ($flag) {
                                    $data_to_update['is_done_Vaccation_formula'] = 1;
                                    $data_to_update['updated_at'] = date('Y-m-d H:i:s');
                                    $data_to_update['updated_by'] = auth("admin")->user()->id;
                                    update(new Employee(), $data_to_update, array("com_code" => $com_code, "employees_code" => $employees_code));
                                }
                            }
                        }
                    } else {
                        // نزل له رصيد سابق
                        $last_Added = get_cols_where_row_orderby(new MainVacationsBalance(), array("year_and_month"), array("com_code" => $com_code, "employees_code" => $employees_code, "FINANCE_YR" => $cheack_currentOpenMonth['FINANCE_YR']), "id", "DESC");
                        $current_month = date('m', strtotime($cheack_currentOpenMonth['year_and_month']));
                        if (!empty($last_Added)) {
                            if ($last_Added['year_and_month'] != $cheack_currentOpenMonth['year_and_month']) {
                                $i = intval(date('m', strtotime($last_Added['year_and_month'])));
                                $i += 1;
                                while ($i <= $current_month) {
                                    if ($i < 10) {
                                        $dataInsert['year_and_month'] = $cheack_currentOpenMonth['FINANCE_YR'] . '-0' . $i;
                                    } else {
                                        $dataInsert['year_and_month'] = $cheack_currentOpenMonth['FINANCE_YR'] . '-' . $i;
                                    }

                                    $dataInsert['FINANCE_YR'] = $cheack_currentOpenMonth['FINANCE_YR'];
                                    $dataInsert['current_month_balance'] = $admin_panel_settingData['monthly_vacation_balance'];
                                    $dataInsert['total_available_balance'] = $admin_panel_settingData['monthly_vacation_balance'];
                                    $dataInsert['net_balance'] = $admin_panel_settingData['monthly_vacation_balance'];
                                    $dataInsert['employees_code'] = $employees_code;
                                    $dataInsert['created_at'] = date('Y-m-d H:i:s');
                                    $dataInsert['added_by'] = auth("admin")->user()->id;
                                    $dataInsert['com_code'] = $com_code;

                                    $checkExsitsMinV = get_cols_where_row(new MainVacationsBalance(), array("id"), array("com_code" => $com_code, "employees_code" => $employees_code, "FINANCE_YR" => $cheack_currentOpenMonth['FINANCE_YR'], "year_and_month" => $dataInsert['year_and_month']));
                                    if (empty($checkExsitsMinV)) {
                                        insert(new MainVacationsBalance(), $dataInsert);
                                    }

                                    $i++;
                                }
                            }
                        } else {

                            //هنا عندى احتمالات انه الموظف كان بالخدمه وخرج  من الخدمه ورجع مره اخرى
                            //ان الموظف كان بالخدمه والاداره علت  له لارصده
                            //هنا وارد ان يكون صفر الرصيدله او طعطيله
                            //او اول شهر عندى بالسنه الماليه الجديده
                            $current_month = date('m', strtotime($cheack_currentOpenMonth['year_and_month']));
                            if (!empty($cheack_currentOpenMonth['year_and_month'])) {
                                // $first_monthIn_OpenYear = get_cols_where_row_orderby(new Finance_cln_periods(), array("year_and_month"), array("com_code" => $com_code, "FINANCE_YR" => $cheack_currentOpenMonth['FINANCE_YR'], "is_open" => 2), "id", "ASC");
                                $first_monthIn_OpenYear = Finance_cln_periods::select("year_and_month")->where("com_code", "=", $com_code)->where("FINANCE_YR", "=", $cheack_currentOpenMonth['FINANCE_YR'])->where("is_open", ">", 0)->orderby('id', 'ASC')->first();
                                if (!empty($first_monthIn_OpenYear)) {

                                    $i = intval(date('m', strtotime($first_monthIn_OpenYear['year_and_month'])));
                                    while ($i <= $current_month) {
                                        if ($i < 10) {
                                            $dataInsert['year_and_month'] = $cheack_currentOpenMonth['FINANCE_YR'] . '-0' . $i;
                                        } else {
                                            $dataInsert['year_and_month'] = $cheack_currentOpenMonth['FINANCE_YR'] . '-' . $i;
                                        }

                                        $dataInsert['FINANCE_YR'] = $cheack_currentOpenMonth['FINANCE_YR'];
                                        $dataInsert['current_month_balance'] = $admin_panel_settingData['monthly_vacation_balance'];
                                        $dataInsert['total_available_balance'] = $admin_panel_settingData['monthly_vacation_balance'];
                                        $dataInsert['net_balance'] = $admin_panel_settingData['monthly_vacation_balance'];
                                        $dataInsert['employees_code'] = $employees_code;
                                        $dataInsert['created_at'] = date('Y-m-d H:i:s');
                                        $dataInsert['added_by'] = auth("admin")->user()->id;
                                        $dataInsert['com_code'] = $com_code;

                                        $checkExsitsMinV = get_cols_where_row(new MainVacationsBalance(), array("id"), array("com_code" => $com_code, "employees_code" => $employees_code, "FINANCE_YR" => $cheack_currentOpenMonth['FINANCE_YR'], "year_and_month" => $dataInsert['year_and_month']));
                                        if (empty($checkExsitsMinV)) {
                                            insert(new MainVacationsBalance(), $dataInsert);
                                        }

                                        $i++;
                                    }
                                }
                            }
                        }
                    }
                } elseif ($admin_panel_settingData['type_vacation'] == 1) {
                    //داله احتساب الاجازات عن طريق الحضور
                    if ($employees_data['is_active_for_Vaccation'] == 2) {

                        if ($employees_data['is_done_Vaccation_formula'] == 0) {

                            // اول مره ينزل له رصيد
                            $your_date = date('Y-m-d', strtotime($employees_data['emp_start_date']));
                            $now = date('Y-m-d', strtotime($cheack_currentOpenMonth['end_date_for_pasma']));

                            $current_year = $cheack_currentOpenMonth['FINANCE_YR'];

                            $vaction_attend = Attenance_departure::where('com_code', $com_code)
                                ->where('vacations_types_id', 1)
                                ->where('employees_code', $employees_code)
                                ->whereBetween('the_day_date', [$your_date, $now])
                                ->count();

                            $vaction_normal = Attenance_departure::where('com_code', $com_code)
                                ->where('vacations_types_id', 15)
                                ->where('employees_code', $employees_code)
                                ->whereBetween('the_day_date', [$your_date, $now])
                                ->count();

                            $dayes_vac = $admin_panel_settingData['quinty_vacstion'];
                            $curentmonthblance = $vaction_attend /  $dayes_vac;
                            $totalavailablebalancetoget =  $curentmonthblance - $vaction_normal;


                            $dataInsert['attend_count'] = $vaction_attend;
                            //المرحل قبل هذه الفتره
                            $dataInsert['carryover_from_previous_month'] = $totalavailablebalancetoget;
                            $dataInsert['year_and_month'] = $cheack_currentOpenMonth['year_and_month'];
                            $dataInsert['FINANCE_YR'] = $current_year;
                            $dataInsert['employees_code'] = $employees_code;
                            $dataInsert['created_at'] = date('Y-m-d H:i:s');
                            $dataInsert['added_by'] = auth("admin")->user()->id;
                            $dataInsert['com_code'] = $com_code;

                            $checkExsitsMinV = get_cols_where_row(new MainVacationsBalance(), array("id"), array("com_code" => $com_code, "employees_code" => $employees_code, "FINANCE_YR" => $current_year, "year_and_month" => $dataInsert['year_and_month']));
                            if (empty($checkExsitsMinV)) {
                                $flag = insert(new MainVacationsBalance(), $dataInsert);
                                if ($flag) {
                                    $data_to_update['is_done_Vaccation_formula'] = 1;
                                    $data_to_update['updated_at'] = date('Y-m-d H:i:s');
                                    $data_to_update['updated_by'] = auth("admin")->user()->id;
                                    update(new Employee(), $data_to_update, array("com_code" => $com_code, "employees_code" => $employees_code));
                                }
                            }
                        } else {
                            // ✅ نزل له رصيد سابق

                            $last_Added = get_cols_where_row_orderby(
                                new MainVacationsBalance(),
                                array("*"),
                                array(
                                    "com_code" => $com_code,
                                    "employees_code" => $employees_code,
                                    "FINANCE_YR" => $cheack_currentOpenMonth['FINANCE_YR']
                                ),
                                "id",
                                "DESC"
                            );

                            if (!empty($last_Added)) {

                                $currentYM = $cheack_currentOpenMonth['year_and_month'];

                                /* =====================================================
                                    الحالة 1 : نفس الشهر (إعادة تقفيل)
                                    ====================================================== */
                                if ($last_Added['year_and_month'] == $currentYM) {

                                    $from = $cheack_currentOpenMonth['start_date_for_pasma'];
                                    $to   = $cheack_currentOpenMonth['end_date_for_pasma'];

                                    $attend = Attenance_departure::where('com_code', $com_code)
                                        ->where('employees_code', $employees_code)
                                        ->where('vacations_types_id', 1)
                                        ->whereBetween('the_day_date', [$from, $to])
                                        ->count();

                                    $vacations = Attenance_departure::where('com_code', $com_code)
                                        ->where('employees_code', $employees_code)
                                        ->where('vacations_types_id', 15)
                                        ->whereBetween('the_day_date', [$from, $to])
                                        ->count();
                                    $carryover_from_month = $last_Added['carryover_from_previous_month'];
                                    $month_balance = $attend / $admin_panel_settingData['quinty_vacstion'];
                                    $net_total   = $month_balance - $vacations;
                                    $net_balance   = $carryover_from_month + $net_total;


                                    update(new MainVacationsBalance(), [
                                        'attend_count' => $attend,
                                        'current_month_balance' => $month_balance,
                                        'spent_balance' => $vacations,
                                        'net_balance' => $net_balance,
                                        'total_available_balance' => $net_total,
                                        'updated_at' => date('Y-m-d H:i:s'),
                                        'updated_by' => auth("admin")->user()->id,
                                    ], [
                                        'com_code' => $com_code,
                                        'employees_code' => $employees_code,
                                        'FINANCE_YR' => $cheack_currentOpenMonth['FINANCE_YR'],
                                        'year_and_month' => $currentYM
                                    ]);
                                } else {
                                    /* ==========================================
            الحالة 2 : شهر جديد (تقفيل شهر واحد فقط)
          ========================================== */

                                    $lastYM = $last_Added['year_and_month'];
                                    $currentYM = $cheack_currentOpenMonth['year_and_month'];

                                    // حدد الشهر اللي بعد آخر شهر مقفول
                                    $nextMonth = date('Y-m', strtotime($lastYM . ' +1 month'));

                                    // لو الشهر الجديد هو فعلًا الشهر الحالي
                                    if ($nextMonth == $currentYM) {

                                        $from = $cheack_currentOpenMonth['start_date_for_pasma'];
                                        $to   = $cheack_currentOpenMonth['end_date_for_pasma'];

                                        $attend = Attenance_departure::where('com_code', $com_code)
                                            ->where('employees_code', $employees_code)
                                            ->where('vacations_types_id', 1)
                                            ->whereBetween('the_day_date', [$from, $to])
                                            ->count();

                                        $vacations = Attenance_departure::where('com_code', $com_code)
                                            ->where('employees_code', $employees_code)
                                            ->where('vacations_types_id', 15)
                                            ->whereBetween('the_day_date', [$from, $to])
                                            ->count();

                                        $month_balance = $attend / $admin_panel_settingData['quinty_vacstion'];
                                        $net_month     = $month_balance - $vacations;

                                        $carry_balance = $last_Added['net_balance'] + $net_month;

                                        insert(new MainVacationsBalance(), [
                                            'com_code' => $com_code,
                                            'employees_code' => $employees_code,
                                            'FINANCE_YR' => $cheack_currentOpenMonth['FINANCE_YR'],
                                            'year_and_month' => $currentYM,
                                            'attend_count' => $attend,
                                            'current_month_balance' => $month_balance,
                                            'spent_balance' => $vacations,
                                            'carryover_from_previous_month' => $last_Added['net_balance'],
                                            'net_balance' => $carry_balance,
                                            'total_available_balance' => $net_month,
                                            'created_at' => date('Y-m-d H:i:s'),
                                            'added_by' => auth("admin")->user()->id,
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
                if ($admin_panel_settingData['type_vacation'] == 1) {
                    $this->transfer_attendance_vacation_to_new_year($employees_code);
                }

                $this->reupdate_vactions_blance($employees_code);
            }
        }
    }
    //داله تحديث وتجميع الارصده من شهر الى اخر
    public function reupdate_vactions_blance($employees_code)
    {

        $com_code = auth("admin")->user()->com_code;
        $employees_data = get_cols_where_row(new Employee(), array("*"), array('com_code' => $com_code, "employees_code" => $employees_code, 'Functiona_status' => 1));
        $admin_panel_settingData = get_cols_where_row(new Admin_panel_setting(), array("*"), array('com_code' => $com_code));


        if (!empty($employees_data) and !empty($admin_panel_settingData)) {
            //التحقق  من وجود شهر مالى مفتوح
            $cheack_currentOpenMonth = get_cols_where_row(new Finance_cln_periods(), array("id", "FINANCE_YR", "year_and_month"), array("com_code" => $com_code, "is_open" => 1));
            if (!empty($cheack_currentOpenMonth)) {
                if ($employees_data['is_active_for_Vaccation'] == 1) {
                    if ($employees_data['is_done_Vaccation_formula'] == 1) {
                        if ($admin_panel_settingData['is_transfer_vaccation'] == 1) {
                            //يرحل كل الشهور لكل السنوات
                            $vacations_blance = get_cols_where(new MainVacationsBalance(), array("*"), array('com_code' => $com_code, "employees_code" => $employees_code), "id", "ASC");
                        } else {
                            //لا يرحل ويتعامل مع السنه الماليه هذه فقط
                            $vacations_blance = get_cols_where(new MainVacationsBalance(), array("*"), array('com_code' => $com_code, "employees_code" => $employees_code, 'FINANCE_YR' => $cheack_currentOpenMonth['FINANCE_YR']), "id", "ASC");
                        }

                        if (!empty($vacations_blance)) {
                            foreach ($vacations_blance as $info) {
                                $getPrevious = MainVacationsBalance::select("net_balance")->where("com_code", "=", $com_code)->where("employees_code", "=", $employees_code)->where("id", "<", $info->id)->orderby("id", "DESC")->first();
                                if (!empty($getPrevious)) {
                                    $data_to_update_vaction['carryover_from_previous_month'] = $getPrevious['net_balance'];
                                    $data_to_update_vaction['total_available_balance'] =  $data_to_update_vaction['carryover_from_previous_month'] + $info->current_month_balance;
                                    $data_to_update_vaction['net_balance'] =  $data_to_update_vaction['total_available_balance'] - $info->spent_balance;
                                    update(new MainVacationsBalance, $data_to_update_vaction, array('com_code' => $com_code, "employees_code" => $employees_code, "id" => $info->id));
                                }
                            }
                        }
                    }
                }
            }
        }
    }


    public function transfer_attendance_vacation_to_new_year($employees_code)
    {
        $com_code = auth('admin')->user()->com_code;

        $employee = get_cols_where_row(
            new Employee(),
            ['is_active_for_Vaccation'],
            ['com_code' => $com_code, 'employees_code' => $employees_code]
        );

        $settings = get_cols_where_row(
            new Admin_panel_setting(),
            ['is_transfer_vaccation'],
            ['com_code' => $com_code]
        );

        // ✔ نظام حضور فقط + السماح بالترحيل
        if (
            empty($employee) ||
            $employee['is_active_for_Vaccation'] != 2 ||
            $settings['is_transfer_vaccation'] != 1
        ) {
            return;
        }

        // الشهر المفتوح
        $openMonth = get_cols_where_row(
            new Finance_cln_periods(),
            ['FINANCE_YR', 'year_and_month'],
            ['com_code' => $com_code, 'is_open' => 1]
        );

        if (empty($openMonth)) {
            return;
        }

        // ✔ أول شهر في السنة؟
        if (date('m', strtotime($openMonth['year_and_month'])) != '01') {
            return;
        }

        // ✔ آخر رصيد من السنة السابقة
        $lastYearBalance = MainVacationsBalance::where('com_code', $com_code)
            ->where('employees_code', $employees_code)
            ->where('FINANCE_YR', '<', $openMonth['FINANCE_YR'])
            ->orderBy('id', 'DESC')
            ->first();

        if (empty($lastYearBalance)) {
            return;
        }

        // ✔ هل السنة الجديدة نزل لها رصيد قبل كده؟
        $exists = MainVacationsBalance::where('com_code', $com_code)
            ->where('employees_code', $employees_code)
            ->where('FINANCE_YR', $openMonth['FINANCE_YR'])
            ->exists();

        if ($exists) {
            return;
        }

        // ✔ إنشاء أول شهر في السنة الجديدة بالترحيل
        insert(new MainVacationsBalance(), [
            'com_code' => $com_code,
            'employees_code' => $employees_code,
            'FINANCE_YR' => $openMonth['FINANCE_YR'],
            'year_and_month' => $openMonth['year_and_month'],
            'carryover_from_previous_month' => $lastYearBalance->net_balance,
            'current_month_balance' => 0,
            'spent_balance' => 0,
            'total_available_balance' => 0,
            'net_balance' => $lastYearBalance->net_balance,
            'created_at' => now(),
            'added_by' => auth('admin')->user()->id,
        ]);
    }




    // داله اعاده اسقاط متغيرات البصمه تبقائيا على متغيرات الراتب 
    public function Recalculate_auto_passma_variables($main_salary_employee_id)
    {
        $com_code = auth('admin')->user()->com_code;
        $Main_salary_employee_data = get_cols_where_row(new Main_salary_employee, array("*"), array("com_code" => $com_code, "id" => $main_salary_employee_id, "is_archived" => 0));
        if (!empty($Main_salary_employee_data)) {
            $employee_data = get_cols_where_row(new Employee(), array("employees_code", "day_price", "id"), array("com_code" => $com_code, "employees_code" => $Main_salary_employee_data['employees_code']));
            $Finance_cln_periods_data = get_cols_where_row(new Finance_cln_periods, array("year_and_month"), array("com_code" => $com_code, "is_open" => 1, "id" => $Main_salary_employee_data['Finance_cln_periods_id']));
            if (!empty($employee_data) and !empty($Finance_cln_periods_data)) {
                // اولا نجيب ايام لغياب بدون اذن 
                $absence_passma = get_count_where(new Attenance_departure(), array("Finance_cln_periods_id" => $Main_salary_employee_data['Finance_cln_periods_id'], "employees_code" => $Main_salary_employee_data['employees_code'], "com_code" => $com_code, "vacations_types_id" => 6));
                $absence_passma_not_salary = get_count_where(new Attenance_departure(), array("Finance_cln_periods_id" => $Main_salary_employee_data['Finance_cln_periods_id'], "employees_code" => $Main_salary_employee_data['employees_code'], "com_code" => $com_code, "vacations_types_id" => 7));
                $absence_passma_midcal = get_count_where(new Attenance_departure(), array("Finance_cln_periods_id" => $Main_salary_employee_data['Finance_cln_periods_id'], "employees_code" => $Main_salary_employee_data['employees_code'], "com_code" => $com_code, "vacations_types_id" => 11));
                // نشوف اولا لو يوجد سجل نحدثو ولو لا يوجد نعمل ادخال
                $get_exsistes_absence_passma = get_cols_where_row(new Main_salary_employee_absence(), array("id"), array("Finance_cln_periods_id" => $Main_salary_employee_data['Finance_cln_periods_id'], "employees_code" => $Main_salary_employee_data['employees_code'], "com_code" => $com_code, "is_auto" => 1, "kind_auto" => 1));
                $get_exsistes_absence_passma_not_salary = get_cols_where_row(new Main_salary_employee_absence(), array("id"), array("Finance_cln_periods_id" => $Main_salary_employee_data['Finance_cln_periods_id'], "employees_code" => $Main_salary_employee_data['employees_code'], "com_code" => $com_code, "is_auto" => 1, "kind_auto" => 2));
                $get_exsistes_absence_passma_midcal = get_cols_where_row(new Main_salary_employee_absence(), array("id"), array("Finance_cln_periods_id" => $Main_salary_employee_data['Finance_cln_periods_id'], "employees_code" => $Main_salary_employee_data['employees_code'], "com_code" => $com_code, "is_auto" => 1, "kind_auto" => 3));

                if (empty($get_exsistes_absence_passma)) {
                    //ادخال
                    $dataToInsert['main_salary_employee_id'] = $main_salary_employee_id;
                    $dataToInsert['finance_cln_periods_id'] = $Main_salary_employee_data['Finance_cln_periods_id'];
                    $dataToInsert['is_auto'] = 1;
                    $dataToInsert['kind_auto'] = 1;
                    $dataToInsert['employees_code'] = $Main_salary_employee_data['employees_code'];
                    $dataToInsert['emp_day_price'] = $employee_data['day_price'];
                    $dataToInsert['value'] = $absence_passma;
                    $dataToInsert['total'] = ($employee_data['day_price'] * 2) *  $absence_passma;
                    $dataToInsert['is_archived'] = 0;
                    $dataToInsert['notes'] = "خصم ايام بدون أذن تلقائى";
                    $dataToInsert['added_by'] = auth('admin')->user()->id;
                    $dataToInsert['com_code'] = $com_code;
                    $flag = insert(new Main_salary_employee_absence(), $dataToInsert);
                } else {
                    //تحديث
                    $data_to_updateAbsence['emp_day_price'] = $employee_data['day_price'];
                    $data_to_updateAbsence['value'] = $absence_passma;
                    $data_to_updateAbsence['total'] = ($employee_data['day_price'] * 2) *  $absence_passma;
                    $data_to_updateAbsence['updated_by'] = auth('admin')->user()->id;
                    $data_to_updateAbsence['com_code'] = $com_code;
                    $flag = update(new Main_salary_employee_absence(), $data_to_updateAbsence, array("id" => $get_exsistes_absence_passma['id']));
                }
                if (empty($get_exsistes_absence_passma_not_salary)) {
                    //ادخال
                    $dataToInsert['main_salary_employee_id'] = $main_salary_employee_id;
                    $dataToInsert['finance_cln_periods_id'] = $Main_salary_employee_data['Finance_cln_periods_id'];
                    $dataToInsert['is_auto'] = 1;
                    $dataToInsert['kind_auto'] = 2;
                    $dataToInsert['employees_code'] = $Main_salary_employee_data['employees_code'];
                    $dataToInsert['emp_day_price'] = $employee_data['day_price'];
                    $dataToInsert['value'] = $absence_passma_not_salary;
                    $dataToInsert['total'] = $employee_data['day_price'] *  $absence_passma_not_salary;
                    $dataToInsert['is_archived'] = 0;
                    $dataToInsert['notes'] = "خصم ايام بدون اجر  تلقائى";
                    $dataToInsert['added_by'] = auth('admin')->user()->id;
                    $dataToInsert['com_code'] = $com_code;
                    $flag = insert(new Main_salary_employee_absence(), $dataToInsert);
                } else {
                    //تحديث
                    $data_to_updateAbsence['emp_day_price'] = $employee_data['day_price'];
                    $data_to_updateAbsence['value'] = $absence_passma_not_salary;
                    $data_to_updateAbsence['total'] = $employee_data['day_price'] *  $absence_passma_not_salary;
                    $data_to_updateAbsence['updated_by'] = auth('admin')->user()->id;
                    $data_to_updateAbsence['com_code'] = $com_code;
                    $flag = update(new Main_salary_employee_absence(), $data_to_updateAbsence, array("id" => $get_exsistes_absence_passma_not_salary['id']));
                }
                if (empty($get_exsistes_absence_passma_midcal)) {
                    //ادخال
                    $dataToInsert['main_salary_employee_id'] = $main_salary_employee_id;
                    $dataToInsert['finance_cln_periods_id'] = $Main_salary_employee_data['Finance_cln_periods_id'];
                    $dataToInsert['is_auto'] = 1;
                    $dataToInsert['kind_auto'] = 3;
                    $dataToInsert['employees_code'] = $Main_salary_employee_data['employees_code'];
                    $dataToInsert['emp_day_price'] = $employee_data['day_price'];
                    $dataToInsert['value'] = $absence_passma_midcal;
                    $dataToInsert['total'] = ($employee_data['day_price'] * 0.25) *  $absence_passma_midcal;
                    $dataToInsert['is_archived'] = 0;
                    $dataToInsert['notes'] = "خصم ايام مرضى  تلقائى";
                    $dataToInsert['added_by'] = auth('admin')->user()->id;
                    $dataToInsert['com_code'] = $com_code;
                    $flag = insert(new Main_salary_employee_absence(), $dataToInsert);
                } else {
                    //تحديث
                    $data_to_updateAbsence['emp_day_price'] = $employee_data['day_price'];
                    $data_to_updateAbsence['value'] = $absence_passma_midcal;
                    $data_to_updateAbsence['total'] = ($employee_data['day_price'] * 0.25) *  $absence_passma_midcal;
                    $data_to_updateAbsence['updated_by'] = auth('admin')->user()->id;
                    $data_to_updateAbsence['com_code'] = $com_code;
                    $flag = update(new Main_salary_employee_absence(), $data_to_updateAbsence, array("id" => $get_exsistes_absence_passma_midcal['id']));
                }
            }
        }
    }




    // داله اعاده اسقاط متغيرات البصمه تبقائيا على متغيرات الراتب 
    public function Recalculate_auto_passma_variables_all($finance_cln_periods_id)
    {
        $com_code = auth('admin')->user()->com_code;

        // نجيب كل سجلات الرواتب للموظفين في الفترة
        $main_salary_employees = get_cols_where(
            new Main_salary_employee(),
            ['id'],
            [
                'com_code' => $com_code,
                'Finance_cln_periods_id' => $finance_cln_periods_id,
                'is_archived' => 0
            ]
        );

        if (!empty($main_salary_employees)) {
            foreach ($main_salary_employees as $row) {
                // نشغل نفس الدالة القديمة
                $this->Recalculate_auto_passma_variables($row['id']);
            }
        }
    }
}
