<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Models\Alert_modules;
use App\Models\Alert_movetype;
use App\Models\Alerts_system_monitoring;
use App\Models\Employee;

class Alerts_system_monitoringController extends Controller
{
    public function index()
    {
        $com_code = auth("admin")->user()->com_code;
        $data = get_cols_where_p(new Alerts_system_monitoring(), array("*"), array("com_code" => $com_code), 'id', 'ASC', PC);
        if (!empty($data)) {
            foreach ($data as $info) {
                $info->alert_modules_name = get_field_value(new Alert_modules(), "name", array("id" => $info->alert_modules_id));
                $info->alert_movetype_name = get_field_value(new Alert_movetype(), "name", array("id" => $info->alert_movetype_id));
            }
        }
        $other['alert_modules'] = get_cols_where(new Alert_modules(), array("*"), array("active" => 1),"id","ASC");
        $other['alert_movetype'] = get_cols_where(new Alert_movetype(), array("*"), array("active" => 1),"id","ASC");
        $other['employess'] = get_cols_where(new Employee(), array("employees_code", "emp_name"), array("com_code" => $com_code));
        $other['admins'] = get_cols_where(new Admin(), array("id", "name"), array("com_code" => $com_code));
        return view('admin.system_monitoring.index', ['data' => $data, 'other' => $other]);
    }


    public function ajax_search(Request $request)
    {
        if ($request->ajax()) {
            $com_code = auth('admin')->user()->com_code;
            $alert_modules_id = $request->alert_modules_id;
            $alert_movetype_id = $request->alert_movetype_id;
            $employees_code = $request->employees_code;
            $admin_id = $request->admin_id;
            $is_marked = $request->is_marked;
            $from_date = $request->from_date;
            $to_date = $request->to_date;


            if ($alert_modules_id == 'all') {
                //هنا نعمل شرط دائم التحقق
                $field1 = "id";
                $operator1 = ">";
                $value1 = 0;
            } else {
                $field1 = "alert_modules_id";
                $operator1 = "=";
                $value1 = $alert_modules_id;
            }

            if ($alert_movetype_id == 'all') {
                //هنا نعمل شرط دائم التحقق
                $field2 = "id";
                $operator2 = ">";
                $value2 = 0;
            } else {
                $field2 = "alert_movetype_id";
                $operator2 = "=";
                $value2 = $alert_movetype_id;
            }

            if ($employees_code == 'all') {
                //هنا نعمل شرط دائم التحقق
                $field3 = "id";
                $operator3 = ">";
                $value3 = 0;
            } else {
                $field3 = "employees_code";
                $operator3 = "=";
                $value3 = $employees_code;
            }

            if ($admin_id == 'all') {
                //هنا نعمل شرط دائم التحقق
                $field5 = "id";
                $operator5 = ">";
                $value5 = 0;
            } else {
                $field5 = "added_by";
                $operator5 = "=";
                $value5 = $admin_id;
            }

            if ($is_marked == 'all') {
                //هنا نعمل شرط دائم التحقق
                $field6 = "id";
                $operator6 = ">";
                $value6 = 0;
            } else {
                $field6 = "is_marked";
                $operator6 = "=";
                $value6 = $is_marked;
            }

            if ($from_date == '') {
                //هنا نعمل شرط دائم التحقق
                $field7 = "id";
                $operator7 = ">";
                $value7 = 0;
            } else {
                $field7 = "date";
                $operator7 = ">=";
                $value7 = $from_date;
            }

            if ($to_date == '') {
                //هنا نعمل شرط دائم التحقق
                $field8 = "id";
                $operator8 = ">";
                $value8 = 0;
            } else {
                $field8 = "date";
                $operator8 = "<=";
                $value8 = $to_date;
            }


            $data = Alerts_system_monitoring::select("*")->where($field1, $operator1, $value1)->where($field2, $operator2, $value2)->where($field3, $operator3, $value3)->where($field5, $operator5, $value5)->where($field6, $operator6, $value6)->where($field7, $operator7, $value7)->where($field8, $operator8, $value8)->where('com_code', '=', $com_code)->orderby('id', 'DESC')->paginate(PC);
            if (!empty($data)) {
                foreach ($data as $info) {
                    $info->alert_modules_name = get_field_value(new Alert_modules(), "name", array( "id" => $info->alert_modules_id));
                    $info->alert_movetype_name = get_field_value(new Alert_movetype(), "name", array( "id" => $info->alert_movetype_id));
                }
            }

            return view('admin.system_monitoring.ajax_search', ['data' => $data]);
        }
    }

    public function do_undo_mark(Request $request)
    {
        if ($request->ajax()) {
            $com_code = auth('admin')->user()->com_code;
            $data = get_cols_where_row(new Alerts_system_monitoring(), array("is_marked"), array("com_code" => $com_code,"id" => $request->id));
            if (!empty($data)) {
                if ($data['is_marked'] == 1) {
                    $data_to_update['is_marked'] = 0;
                    $data_to_update['updated_by'] = auth('admin')->user()->id;
                } else {
                    $data_to_update['is_marked'] = 1;
                    $data_to_update['updated_by'] = auth('admin')->user()->id;
                }
                update(new Alerts_system_monitoring(),$data_to_update,array("com_code" => $com_code,"id" => $request->id));
            }
        }
    }
}
