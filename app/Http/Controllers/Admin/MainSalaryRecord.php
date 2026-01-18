<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attenance_actions;
use App\Models\Attenance_departure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Finance_calender;
use App\Models\Finance_cln_periods;
use App\Models\Employee;
use App\Models\Main_salary_employee_absence;
use App\Models\Main_salary_employees_allowances;
use App\Models\Main_salary_employees_sanctions;
use App\Models\Main_salary_employees_discound;
use App\Models\Main_salary_employees_rewards;
use App\Models\Main_salary_employees_loans;
use App\Models\Main_salary_P_loans_akast;
use App\Models\Main_salary_employees_addtion;

use App\Models\Main_salary_employee;
use App\Traits\GeneralTrait;


class MainSalaryRecord extends Controller
{
     use GeneralTrait;
     public function index()
     {
          $com_code = auth('admin')->user()->com_code;
          $data = get_cols_where_p_order2(new Finance_cln_periods(), array("*"), array("com_code" => $com_code), 'FINANCE_YR', 'DESC', 'MONTH_ID', 'ASC', 12);
          if (!empty($data))
               foreach ($data as $info) {
                    // cheak status to open
                    $info->currnetYear = get_cols_where_row(new Finance_calender(), array("is_open"), array("com_code" => $com_code, "FINANCE_YR" => $info->FINANCE_YR));
                    $info->counterOpenMonth = get_count_where(new Finance_cln_periods(), array("com_code" => $com_code, "is_open" => 1));
                    $info->counterPreviousMonthWatingOpen = Finance_cln_periods::where("com_code", "=", $com_code)
                         ->where("FINANCE_YR", "=", $info->FINANCE_YR)
                         ->where("MONTH_ID", "<", $info->MONTH_ID)
                         ->where("is_open", "=", 0)->count();
                    // get_count_where(new Finance_cln_periods(),array("com_code" => $com_code,"is_open"=>0,"FINANCE_YR"=>$info->FINANCE_YR,"MONTH_ID"));
               }
          return view('admin.MainSalaryRecord.index', ['data' => $data]);
     }
     public function do_open_month($id, Request $request)
     {
          try {
               $com_code = auth('admin')->user()->com_code;
               $data = get_cols_where_row(new Finance_cln_periods(), array("*"), array("com_code" => $com_code, "id" => $id));
               if (empty($data)) {

                    return redirect()->route('MainSalaryRecord.index')->with(['error' => 'عفوا غير قادر للوصل الى البيانات المطلوبة ! ']);
               }
               $currnetYear = get_cols_where_row(new Finance_calender(), array("is_open"), array("com_code" => $com_code, "FINANCE_YR" => $data['FINANCE_YR']));
               if (empty($currnetYear)) {

                    return redirect()->route('MainSalaryRecord.index')->with(['error' => 'عفوا غير قادر للوصل الى بيانات السنه المالية المطلوبة ! ']);
               }
               if ($currnetYear['is_open'] != 1) {

                    return redirect()->route('MainSalaryRecord.index')->with(['error' => 'عفوا السنه الماليه التابع ليها هذا الشهر غير مفتوحة الان ! ']);
               }

               if ($data['is_open'] == 1) {
                    return redirect()->route('MainSalaryRecord.index')->with(['error' => 'عفو هذا الشهر مفتوح حاليا! ']);
               }
               if ($data['is_open'] == 2) {
                    return redirect()->route('MainSalaryRecord.index')->with(['error' => 'عفو هذا الشهر مؤرشف من قبل ! ']);
               }
               $counterOpenMonth = get_count_where(new Finance_cln_periods(), array("com_code" => $com_code, "is_open" => 1));
               if ($counterOpenMonth > 0) {

                    return redirect()->route('MainSalaryRecord.index')->with(['error' => 'عفوا لايمكن  فتح هذا الشهر الان لوجود شهر مالى اخر مفتوح حاليا ! ']);
               }

               $counterPreviousMonthWatingOpen = Finance_cln_periods::where("com_code", "=", $com_code)
                    ->where("FINANCE_YR", "=", $data['FINANCE_YR'])
                    ->where("MONTH_ID", "<", $data['MONTH_ID'])
                    ->where("is_open", "=", 0)->count();

               if ($counterPreviousMonthWatingOpen > 0) {

                    return redirect()->route('MainSalaryRecord.index')->with(['error' => 'عفوا لايمكن فتح هذا الشهر لوجود شهر مستحق الفتح اولا ! ']);
               }
               DB::beginTransaction();
               $dataToUpdate['start_date_for_pasma'] = $request->start_date_for_pasma;
               $dataToUpdate['end_date_for_pasma'] = $request->end_date_for_pasma;
               $dataToUpdate['is_open'] = 1;
               $dataToUpdate['updated_by'] = auth('admin')->user()->id;
               $dataToUpdate['updated_at'] = date("Y-m-d H:i:s");
               $flag = update(new Finance_cln_periods(), $dataToUpdate, array("com_code" => $com_code, "id" => $id, 'is_open' => 0));


               //كو فتح المرتبات للموظف
               if ($flag) {
                    $all_employees = get_cols_where(new Employee(), array("*"), array("com_code" => $com_code, "Functiona_status" => 1), 'employees_code', 'ASC');
                    if (!empty($all_employees)) {
                         foreach ($all_employees as $info) {
                              $dataSalaryToInser = array();
                              $dataSalaryToInsert['Finance_cln_periods_id'] = $id;
                              $dataSalaryToInsert['employees_code'] = $info->employees_code;
                              $dataSalaryToInsert['com_code'] = $info->com_code;
                              $checkExsistsCounter = get_count_where(new Main_salary_employee(), $dataSalaryToInsert);
                              if ($checkExsistsCounter == 0) {

                                   $dataSalaryToInsert['emp_name'] = $info->emp_name;
                                   $dataSalaryToInsert['day_price'] = $info->day_price;
                                   $dataSalaryToInsert['is_Sensitive_manger_data'] = $info->is_Sensitive_manger_data;
                                   $dataSalaryToInsert['branch_id'] = $info->branch_id;
                                   $dataSalaryToInsert['Functiona_status'] = $info->Functiona_status;
                                   $dataSalaryToInsert['emp_Departments_code'] = $info->emp_Departments_code;
                                   $dataSalaryToInsert['emp_jobs_id'] = $info->emp_jobs_id;
                                   $dataSalaryToInsert['emp_sal'] = $info->emp_sal;
                                   $LastSalaryData = get_cols_where_row_orderby(new Main_salary_employee(), array("final_the_net_after_colse"), array("com_code" => $com_code, "employees_code" => $info->employees_code, "is_archived" => 1), 'id', 'DESC');
                                   if (!empty($LastSalaryData)) {
                                        $dataSalaryToInsert['last_salary_remain_blance'] = $LastSalaryData['final_the_net_after_colse'];
                                   } else {
                                        $dataSalaryToInsert['last_salary_remain_blance'] = 0;
                                   }

                                   $dataSalaryToInsert['year_and_month'] = $data['year_and_month'];
                                   $dataSalaryToInsert['FINANCE_YR'] = $data['FINANCE_YR'];
                                   $dataSalaryToInsert['sal_cach_or_visa'] = $info->sal_cach_or_visa;
                                   $dataSalaryToInsert['added_by'] = auth('admin')->user()->id;
                                   $dataSalaryToInsert['archived_by'] = auth('admin')->user()->id;
                                   $flagInsert = insert(new Main_salary_employee, $dataSalaryToInsert, true);
                                   if (!empty($flagInsert)) {
                                        $this->Recalculate_main_salary_employee($flagInsert['id']);
                                   }
                              }
                         }
                    }
               }

               DB::commit();
               return  redirect()->route('MainSalaryRecord.index')->with(['success' => 'تم فتح الشهر المالى بنجاح']);
          } catch (\Exception $ex) {
               DB::rollBack();
               return redirect()->route('MainSalaryRecord.index')->with(['error' => 'عفوا حدث خطا ' . $ex->getMessage()]);
          }
     }

     public function load_open_monthModel(Request $request)
     {
          if ($request->ajax()) {
               $id = $request->id;
               $com_code = auth('admin')->user()->com_code;
               $data = get_cols_where_row(new Finance_cln_periods(), array("*"), array("com_code" => $com_code, "id" => $id));
               return view('admin.MainSalaryRecord.load_open_monthModel', ['data' => $data]);
          }
     }


     public function do_close_month($id, Request $request)
     {
          try {
               $com_code = auth('admin')->user()->com_code;
               $data = get_cols_where_row(new Finance_cln_periods(), array("*"), array("com_code" => $com_code, "id" => $id));
               if (empty($data)) {

                    return redirect()->route('MainSalaryRecord.index')->with(['error' => 'عفوا غير قادر للوصل الى البيانات المطلوبة ! ']);
               }
               $currnetYear = get_cols_where_row(new Finance_calender(), array("is_open"), array("com_code" => $com_code, "FINANCE_YR" => $data['FINANCE_YR']));
               if (empty($currnetYear)) {

                    return redirect()->route('MainSalaryRecord.index')->with(['error' => 'عفوا غير قادر للوصل الى بيانات السنه المالية المطلوبة ! ']);
               }
               if ($currnetYear['is_open'] != 1) {

                    return redirect()->route('MainSalaryRecord.index')->with(['error' => 'عفوا السنه الماليه التابع ليها هذا الشهر غير مفتوحة الان ! ']);
               }

               if ($data['is_open'] == 0) {
                    return redirect()->route('MainSalaryRecord.index')->with(['error' => ' عفو هذا الشهر غير مفتوح حاليا!']);
               }
               if ($data['is_open'] == 2) {
                    return redirect()->route('MainSalaryRecord.index')->with(['error' => 'عفو هذا الشهر مؤرشف من قبل ! ']);
               }
               $counterstop = get_count_where(new Main_salary_employee(), array("com_code" => $com_code, "Finance_cln_periods_id" => $id, "is_stoped" => 1));
               if ($counterstop == 1) {
                    return redirect()->route('MainSalaryRecord.index')->with(['error' => 'عفو توجد مرتبات موقوفه بهذا الشهر المالى من فضلك خذ لها اجراء اولا للتتمكن من ارشفه الشهر المالى  ! ']);
               }

               DB::beginTransaction();
               $dataToUpdatef['is_open'] = 2;
               $dataToUpdatef['updated_by'] = auth('admin')->user()->id;
               $dataToUpdatef['updated_at'] = date("Y-m-d H:i:s");
               $flag = update(new Finance_cln_periods(), $dataToUpdatef, array("com_code" => $com_code, "id" => $id, 'is_open' => 1));


               //كو ارشفة المرتبات للموظف
               if ($flag) {
                    $all_Main_salary_employee = get_cols_where(new Main_salary_employee(), array("*"), array("com_code" => $com_code, "Finance_cln_periods_id" => $id), 'id', 'ASC');
                    if (!empty($all_Main_salary_employee)) {
                         foreach ($all_Main_salary_employee as $info) {
                              $dataToUpdate['is_archived'] = 1;
                              $dataToUpdate['archived_date'] = date("Y-m-d H:i:s");
                              $dataToUpdate['archived_by'] = auth('admin')->user()->id;




                              if ($info->final_the_net < 0) {
                                   $dataToUpdate['final_the_net_after_colse'] = $info->final_the_net;
                              } else {
                                   $dataToUpdate['final_the_net_after_colse'] = 0;
                                   $info->final_the_net_after_colse = 0;
                              }

                              $flagUpdate = update(new Main_salary_employee(), $dataToUpdate, array("com_code" => $com_code, "id" => $info->id, "is_archived" => 0, "is_stoped" => 0));
                              if ($flagUpdate) {
                                   $dataToUpdate_variables['is_archived'] = 1;
                                   $dataToUpdate_variables['archived_at'] = date("Y-m-d H:i:s");
                                   $dataToUpdate_variables['archived_by'] = auth('admin')->user()->id;
                                   update(new Main_salary_employees_sanctions(), $dataToUpdate_variables, array("com_code" => $com_code, "main_salary_employee_id" => $info->id, "finance_cln_periods_id" => $id));
                                   update(new Main_salary_employee_absence(), $dataToUpdate_variables, array("com_code" => $com_code, "main_salary_employee_id" => $info->id, "finance_cln_periods_id" => $id));
                                   update(new Main_salary_employees_rewards(), $dataToUpdate_variables, array("com_code" => $com_code, "main_salary_employee_id" => $info->id, "finance_cln_periods_id" => $id));
                                   update(new Main_salary_employees_allowances(), $dataToUpdate_variables, array("com_code" => $com_code, "main_salary_employee_id" => $info->id, "finance_cln_periods_id" => $id));
                                   update(new Main_salary_employees_loans(), $dataToUpdate_variables, array("com_code" => $com_code, "main_salary_employee_id" => $info->id, "finance_cln_periods_id" => $id));
                                   update(new Main_salary_employees_addtion(), $dataToUpdate_variables, array("com_code" => $com_code, "main_salary_employee_id" => $info->id, "finance_cln_periods_id" => $id));
                                   update(new Main_salary_employees_discound(), $dataToUpdate_variables, array("com_code" => $com_code, "main_salary_employee_id" => $info->id, "finance_cln_periods_id" => $id));
                                   Main_salary_P_loans_akast::where("com_code", "=", $com_code)->where("year_and_month", "=", $data['year_and_month'])->where("is_parent_dismissail_done", "=", 1)->where("is_archived", "=", 0)->where("state", "!=", "2")->where("employees_code", "=", $info->employees_code)->where("main_salary_employee_id", "=", $info->id)->update($dataToUpdate_variables);
                              }
                         }
                    }
                    // نأرشف المقابل له بموديول لبصمه والسنوى
                    $dataToUpdate2['is_archived'] = 1;
                    $dataToUpdate2['archived_at'] = date("Y-m-d H:i:s");
                    $dataToUpdate2['archived_by'] = auth('admin')->user()->id;
                    $dataToUpdate3['is_archived'] = 1;
                 update(new Attenance_departure(),$dataToUpdate2,array("com_code"=>$com_code,"finance_cln_periods_id"=>$id));
                 update(new Attenance_actions(),$dataToUpdate3,array("com_code"=>$com_code,"finance_cln_periods_id"=>$id));    
             //هنا نتحقق ان كان اخر شهر فى السنه الماليه 
    $data = get_cols_where_row(new Finance_cln_periods(), array("*"), array("com_code" => $com_code, "id" => $id));
                        $counterNextMonthWatingOpen = Finance_cln_periods::where("com_code", "=", $com_code)
                         ->where("FINANCE_YR", "=", $data['FINANCE_YR'])
                         ->where("MONTH_ID", ">", $data['MONTH_ID'])
                         ->where("is_open", "=", 0)->count();
        if($counterNextMonthWatingOpen==0){
   //ده اخر شهر مالى مضاف للسنه الماليه المفتوحه
   //هنا نأرشف السنة الماليه
   $dataToUpdateYear['is_open'] =2;
   $dataToUpdateYear['updated_at'] = date("Y-m-d H:i:s");
   $dataToUpdateYear['updated_by'] = auth('admin')->user()->id;
   update(new Finance_calender(),$dataToUpdateYear,array("com_code"=>$com_code,"FINANCE_YR"=>$data['FINANCE_YR'],"is_open"=>1));
   

        }                 




               }

               DB::commit();
               return  redirect()->route('MainSalaryRecord.index')->with(['success' => 'تم ارشفة  الشهر المالى وارشفه جميع المرتبات الخاصه بالشهر المالى بنجاح']);
          } catch (\Exception $ex) {
               DB::rollBack();
               return redirect()->route('MainSalaryRecord.index')->with(['error' => 'عفوا حدث خطا ' . $ex->getMessage()]);
          }
     }



     public function open_month_admin($id, Request $request)
     {
          try {
               $com_code = auth('admin')->user()->com_code;
               $data = get_cols_where_row(new Finance_cln_periods(), array("*"), array("com_code" => $com_code, "id" => $id));
               if (empty($data)) {

                    return redirect()->route('MainSalaryRecord.index')->with(['error' => 'عفوا غير قادر للوصل الى البيانات المطلوبة ! ']);
               }
               $currnetYear = get_cols_where_row(new Finance_calender(), array("is_open"), array("com_code" => $com_code, "FINANCE_YR" => $data['FINANCE_YR']));
               if (empty($currnetYear)) {

                    return redirect()->route('MainSalaryRecord.index')->with(['error' => 'عفوا غير قادر للوصل الى بيانات السنه المالية المطلوبة ! ']);
               }
               if ($currnetYear['is_open'] != 1) {

                    return redirect()->route('MainSalaryRecord.index')->with(['error' => 'عفوا السنه الماليه التابع ليها هذا الشهر غير مفتوحة الان ! ']);
               }

               if ($data['is_open'] == 0) {
                    return redirect()->route('MainSalaryRecord.index')->with(['error' => ' عفو هذا الشهر غير مفتوح حاليا!']);
               }



               DB::beginTransaction();
               $dataToUpdatef['is_open'] = 1;
               $dataToUpdatef['updated_by'] = auth('admin')->user()->id;
               $dataToUpdatef['updated_at'] = date("Y-m-d H:i:s");
               $flag = update(new Finance_cln_periods(), $dataToUpdatef, array("com_code" => $com_code, "id" => $id, 'is_open' => 2));


               //كو ارشفة المرتبات للموظف
               if ($flag) {
                    $all_Main_salary_employee = get_cols_where(new Main_salary_employee(), array("*"), array("com_code" => $com_code, "Finance_cln_periods_id" => $id), 'id', 'ASC');
                    if (!empty($all_Main_salary_employee)) {
                         foreach ($all_Main_salary_employee as $info) {
                              $dataToUpdate['is_archived'] = 0;
                              $dataToUpdate['archived_date'] = date("Y-m-d H:i:s");
                              $dataToUpdate['archived_by'] = auth('admin')->user()->id;




                              $flagUpdate = update(new Main_salary_employee(), $dataToUpdate, array("com_code" => $com_code, "id" => $info->id, "is_archived" => 1, "is_stoped" => 0));
                              if ($flagUpdate) {
                                   $dataToUpdate_variables['is_archived'] = 0;
                                   $dataToUpdate_variables['archived_at'] = date("Y-m-d H:i:s");
                                   $dataToUpdate_variables['archived_by'] = auth('admin')->user()->id;
                                   update(new Main_salary_employees_sanctions(), $dataToUpdate_variables, array("com_code" => $com_code, "main_salary_employee_id" => $info->id, "finance_cln_periods_id" => $id));
                                   update(new Main_salary_employee_absence(), $dataToUpdate_variables, array("com_code" => $com_code, "main_salary_employee_id" => $info->id, "finance_cln_periods_id" => $id));
                                   update(new Main_salary_employees_rewards(), $dataToUpdate_variables, array("com_code" => $com_code, "main_salary_employee_id" => $info->id, "finance_cln_periods_id" => $id));
                                   update(new Main_salary_employees_allowances(), $dataToUpdate_variables, array("com_code" => $com_code, "main_salary_employee_id" => $info->id, "finance_cln_periods_id" => $id));
                                   update(new Main_salary_employees_loans(), $dataToUpdate_variables, array("com_code" => $com_code, "main_salary_employee_id" => $info->id, "finance_cln_periods_id" => $id));
                                   update(new Main_salary_employees_addtion(), $dataToUpdate_variables, array("com_code" => $com_code, "main_salary_employee_id" => $info->id, "finance_cln_periods_id" => $id));
                                   update(new Main_salary_employees_discound(), $dataToUpdate_variables, array("com_code" => $com_code, "main_salary_employee_id" => $info->id, "finance_cln_periods_id" => $id));
                                   Main_salary_P_loans_akast::where("com_code", "=", $com_code)->where("year_and_month", "=", $data['year_and_month'])
                                        ->where("is_archived", "=", 1)->where("state", "=", "2")->where("employees_code", "=", $info->employees_code)->where("main_salary_employee_id", "=", $info->id)->update($dataToUpdate_variables);
                              }
                         }
                    }
               }

               DB::commit();
               return  redirect()->route('MainSalaryRecord.index')->with(['success' => 'تم ارشفة  الشهر المالى وارشفه جميع المرتبات الخاصه بالشهر المالى بنجاح']);
          } catch (\Exception $ex) {
               DB::rollBack();
               return redirect()->route('MainSalaryRecord.index')->with(['error' => 'عفوا حدث خطا ' . $ex->getMessage()]);
          }
     }
}
