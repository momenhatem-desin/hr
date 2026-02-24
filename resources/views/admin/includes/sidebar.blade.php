<div class="sidebar">
  <!-- Sidebar user panel (optional) -->
  <div class="user-panel mt-3 pb-3 mb-3 d-flex">
    <div class="image">
      @if(!empty(auth()->user()->image))
      <img src="{{ asset('assets/admin/uploads').'/' . auth()->user()->image  }}" border-raduis: 50%; width="80px" ; height="80px" ; class="img-circle elevation-2" alt="user_image">
      @else
      <img src="{{ asset('assets/admin/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
      @endif
    </div>
    <div class="info">
      <a href="#" class="d-block">{{ auth()->user()->name; }}</a>
      @if(auth()->user()->is_master_admin==0 and auth()->user()->usertype!=2 )
      <span style="color: lightgoldenrodyellow">
        {!! $permission_roles_name=App\Models\Permission_rols::where('id','=',auth()->user()->permission_roles_id)->value("name") !!}
      </span>
      @elseif(auth()->user()->usertype==2)
      <span style="color: lightgoldenrodyellow">
        موظف
      </span>
      @else
      <span style="color: lightgoldenrodyellow">
        أدمن رئيسى
      </span>
      @endif
    </div>
  </div>

  <!-- Sidebar Menu -->
  <nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
      <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
      @if(auth()->user()->is_master_admin==1 or check_permission_main_menue(1)==true)
      <li class="nav-item has-treeview    {{ ( request()->is('admin/generalSettings*') || request()->is('admin/finance_calender*') || request()->is('admin/branches*') || request()->is('admin/ShiftsTypes*') || request()->is('admin/departements*')  || request()->is('admin/jobs_categories*') || request()->is('admin/Qualifications*') || request()->is('admin/occasions*') || request()->is('admin/Resignations*') || request()->is('admin/Nationalities*') || request()->is('admin/Religions*')) ? 'menu-open':'' }} ">
        <a href="#" class="nav-link {{ ( request()->is('admin/generalSettings*') || request()->is('admin/finance_calender*') || request()->is('admin/branches*') || request()->is('admin/ShiftsTypes*') || request()->is('admin/departements*') || request()->is('admin/jobs_categories*')  || request()->is('admin/Qualifications*') ||request()->is('admin/occasions*') || request()->is('admin/Resignations*') || request()->is('admin/Nationalities*') || request()->is('admin/Religions*') ) ? 'active':'' }} ">
          <i class="nav-icon fas fa-tachometer-alt"></i>
          <p>
            قائمة الضبط
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          @if(auth()->user()->is_master_admin==1 or check_permission_sub_menue(1)==true)
          <li class="nav-item">
            <a href="{{ route('admin_panel_settings.index') }}" class="nav-link {{ (request()->is('admin/generalSettings*'))?'active':'' }}">
              <i class="far fa-circle nav-icon"></i>
              <p>الضبط العام</p>
            </a>
          </li>
          @endif
          @if(auth()->user()->is_master_admin==1 or check_permission_sub_menue(2)==true)
          <li class="nav-item">
            <a href="{{ route('finance_calender.index') }}" class="nav-link  {{ (request()->is('admin/finance_calender*'))?'active':'' }}">
              <i class="far fa-circle nav-icon"></i>
              <p> السنوات المالية</p>
            </a>
          </li>
          @endif
          @if(auth()->user()->is_master_admin==1 or check_permission_sub_menue(3)==true)
          <li class="nav-item">
            <a href="{{ route('branches.index') }}" class="nav-link  {{ (request()->is('admin/branches*'))?'active':'' }}">
              <i class="far fa-circle nav-icon"></i>
              <p> الفروع</p>
            </a>
          </li>
          @endif
          @if(auth()->user()->is_master_admin==1 or check_permission_sub_menue(4)==true)
          <li class="nav-item">
            <a href="{{ route('ShiftsTypes.index') }}" class="nav-link  {{ (request()->is('admin/ShiftsTypes*'))?'active':'' }}">
              <i class="far fa-circle nav-icon"></i>
              <p> انواع الشفتات</p>
            </a>
          </li>
          @endif
          @if(auth()->user()->is_master_admin==1 or check_permission_sub_menue(5)==true)
          <li class="nav-item">
            <a href="{{ route('departements.index') }}" class="nav-link  {{ (request()->is('admin/departements*'))?'active':'' }}">
              <i class="far fa-circle nav-icon"></i>
              <p> ادارات الموظفين</p>
            </a>
          </li>
          @endif
          @if(auth()->user()->is_master_admin==1 or check_permission_sub_menue(6)==true)
          <li class="nav-item">
            <a href="{{ route('jobs_categories.index') }}" class="nav-link  {{ (request()->is('admin/jobs_categories*'))?'active':'' }}">
              <i class="far fa-circle nav-icon"></i>
              <p> وظائف الموظفين</p>
            </a>
          </li>
          @endif
          @if(auth()->user()->is_master_admin==1 or check_permission_sub_menue(27)==true)
          <li class="nav-item">
            <a href="{{ route('Qualifications.index') }}" class="nav-link  {{ (request()->is('admin/Qualifications*'))?'active':'' }}">
              <i class="far fa-circle nav-icon"></i>
              <p> مؤهلات الموظفين</p>
            </a>
          </li>
          @endif
          @if(auth()->user()->is_master_admin==1 or check_permission_sub_menue(28)==true)
          <li class="nav-item">
            <a href="{{ route('occasions.index') }}" class="nav-link  {{ (request()->is('admin/occasions*'))?'active':'' }}">
              <i class="far fa-circle nav-icon"></i>
              <p> المناسبات الرسمية</p>
            </a>
          </li>
          @endif
          @if(auth()->user()->is_master_admin==1 or check_permission_sub_menue(29)==true)
          <li class="nav-item">
            <a href="{{ route('Resignations.index') }}" class="nav-link  {{ (request()->is('admin/Resignations*'))?'active':'' }}">
              <i class="far fa-circle nav-icon"></i>
              <p> أنواع ترك العمل</p>
            </a>
          </li>
          @endif
          @if(auth()->user()->is_master_admin==1 or check_permission_sub_menue(30)==true)
          <li class="nav-item">
            <a href="{{ route('Nationalities.index') }}" class="nav-link  {{ (request()->is('admin/Nationalities*'))?'active':'' }}">
              <i class="far fa-circle nav-icon"></i>
              <p> أنواع الجنسيات</p>
            </a>
          </li>
          @endif
          @if(auth()->user()->is_master_admin==1 or check_permission_sub_menue(31)==true)
          <li class="nav-item">
            <a href="{{ route('Religions.index') }}" class="nav-link  {{ (request()->is('admin/Religions*'))?'active':'' }}">
              <i class="far fa-circle nav-icon"></i>
              <p> أنواع الديانات</p>
            </a>
          </li>
          @endif
        </ul>
      </li>
      @endif
      @if(auth()->user()->is_master_admin==1 or check_permission_main_menue(2)==true)
      <li class="nav-item has-treeview    {{ ( request()->is('admin/Employees*')|| request()->is('admin/AdditionalTypes*')||request()->is('admin/Allowances*')||request()->is('admin/DiscountTypes*')||request()->is('admin/grants*')) ? 'menu-open':'' }} ">
        <a href="#" class="nav-link {{ ( request()->is('admin/Employees*') || request()->is('admin/AdditionalTypes*') ||  request()->is('admin/Allowances*') ||request()->is('admin/DiscountTypes*')||request()->is('admin/grants*')) ? 'active':'' }} ">
          <i class="nav-icon fas fa-tachometer-alt"></i>
          <p>
            قائمة شئون الموظفين
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          @if(auth()->user()->is_master_admin==1 or check_permission_sub_menue(7)==true)
          <li class="nav-item">
            <a href="{{ route('Employees.index') }}" class="nav-link {{ (request()->is('admin/Employees*'))?'active':'' }}">
              <i class="far fa-circle nav-icon"></i>
              <p> بيانات الموظفين</p>
            </a>
          </li>
          @endif
          @if(auth()->user()->is_master_admin==1 or check_permission_sub_menue(8)==true)
          <li class="nav-item">
            <a href="{{ route('AdditionalTypes.index') }}" class="nav-link {{ (request()->is('admin/AdditionalTypes*'))?'active':'' }}">
              <i class="far fa-circle nav-icon"></i>
              <p> انواع الاضافي للراتب</p>
            </a>
          </li>
          @endif
          @if(auth()->user()->is_master_admin==1 or check_permission_sub_menue(9)==true)
          <li class="nav-item">
            <a href="{{ route('DiscountTypes.index') }}" class="nav-link {{ (request()->is('admin/DiscountTypes*'))?'active':'' }}">
              <i class="far fa-circle nav-icon"></i>
              <p> انواع الخصم للراتب</p>
            </a>
          </li>
          @endif
          @if(auth()->user()->is_master_admin==1 or check_permission_sub_menue(10)==true)
          <li class="nav-item">
            <a href="{{ route('Allowances.index') }}" class="nav-link {{ (request()->is('admin/Allowances*'))?'active':'' }}">
              <i class="far fa-circle nav-icon"></i>
              <p> انواع البدلات للراتب</p>
            </a>
          </li>
          @endif
          @if(auth()->user()->is_master_admin==1 or check_permission_sub_menue(34)==true)
          <li class="nav-item">
            <a href="{{ route('grants.index') }}" class="nav-link {{ (request()->is('admin/grants*'))?'active':'' }}">
              <i class="far fa-circle nav-icon"></i>
              <p> انواع المنح </p>
            </a>
          </li>
          @endif
        </ul>
      </li>

      @endif
      @if(auth()->user()->is_master_admin==1 or check_permission_main_menue(3)==true)
      <li class="nav-item has-treeview    {{ ( request()->is('admin/MainSalaryRecord*')|| request()->is('admin/MainSalarySanctions*')||request()->is('admin/Main_salary_employee_absence*')||request()->is('admin/Main_salary_employees_addtion*')||request()->is('admin/Main_salary_employees_discound*')||request()->is('admin/Main_salary_employees_rewards*')||request()->is('admin/Main_salary_employees_allowances*')||request()->is('admin/Main_salary_employees_loans*')||request()->is('admin/Main_salary_employees_P_loans*')||request()->is('admin/Main_salary_employee/*') ) ? 'menu-open':'' }} ">
        <a href="#" class="nav-link {{ ( request()->is('admin/MainSalaryRecord*')||request()->is('admin/MainSalarySanctions*')||request()->is('admin/Main_salary_employee_absence*')||request()->is('admin/Main_salary_employees_addtion*')||request()->is('admin/Main_salary_employees_discound*')||request()->is('admin/Main_salary_employees_rewards*')||request()->is('admin/Main_salary_employees_allowances*')||request()->is('admin/Main_salary_employees_loans*')||request()->is('admin/Main_salary_employees_P_loans*')||request()->is('admin/Main_salary_employee/*') ) ? 'active':'' }} ">
          <i class="nav-icon fas fa-tachometer-alt"></i>
          <p>
            قائمة الاجور
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          @if(auth()->user()->is_master_admin==1 or check_permission_sub_menue(17)==true)
          <li class="nav-item">
            <a href="{{ route('MainSalaryRecord.index') }}" class="nav-link {{ (request()->is('admin/MainSalaryRecord*'))?'active':'' }}">
              <i class="far fa-circle nav-icon"></i>
              <p>السجلات الرئيسية للرواتب</p>
            </a>
          </li>
          @endif
          @if(auth()->user()->is_master_admin==1 or check_permission_sub_menue(18)==true)
          <li class="nav-item">
            <a href="{{ route('MainSalarySanctions.index') }}" class="nav-link {{ (request()->is('admin/MainSalarySanctions*'))?'active':'' }} ">
              <i class="far fa-circle nav-icon"></i>
              <p>جزاءات الايام</p>
            </a>
          </li>
          @endif
          @if(auth()->user()->is_master_admin==1 or check_permission_sub_menue(19)==true)
          <li class="nav-item">
            <a href="{{ route('Main_salary_employee_absence.index') }}" class="nav-link {{ (request()->is('admin/Main_salary_employee_absence*'))?'active':'' }}">
              <i class="far fa-circle nav-icon"></i>
              <p>غياب الايام</p>
            </a>
          </li>
          @endif
          @if(auth()->user()->is_master_admin==1 or check_permission_sub_menue(20)==true)
          <li class="nav-item">
            <a href="{{ route('Main_salary_employees_discound.index') }}" class="nav-link {{ (request()->is('admin/Main_salary_employees_discound*'))?'active':'' }}">
              <i class="far fa-circle nav-icon"></i>
              <p>الخصومات المالية</p>
            </a>
          </li>
          @endif
          @if(auth()->user()->is_master_admin==1 or check_permission_sub_menue(21)==true)
          <li class="nav-item">
            <a href="{{ route('Main_salary_employees_loans.index') }}" class="nav-link {{ (request()->is('admin/Main_salary_employees_loans*'))?'active':'' }}">
              <i class="far fa-circle nav-icon"></i>
              <p>السلف الشهرية</p>
            </a>
          </li>
          @endif
          @if(auth()->user()->is_master_admin==1 or check_permission_sub_menue(22)==true)
          <li class="nav-item">
            <a href="{{ route('Main_salary_employees_P_loans.index') }}" class="nav-link {{ (request()->is('admin/Main_salary_employees_P_loans*'))?'active':'' }}">
              <i class="far fa-circle nav-icon"></i>
              <p>السلف المستديمة</p>
            </a>
          </li>
          @endif
          @if(auth()->user()->is_master_admin==1 or check_permission_sub_menue(23)==true)
          <li class="nav-item">
            <a href="{{ route('Main_salary_employees_addtion.index') }}" class="nav-link {{ (request()->is('admin/Main_salary_employees_addtion*'))?'active':'' }}">
              <i class="far fa-circle nav-icon"></i>
              <p>إضافى ايام</p>
            </a>
          </li>
          @endif
          @if(auth()->user()->is_master_admin==1 or check_permission_sub_menue(24)==true)
          <li class="nav-item">
            <a href="{{ route('Main_salary_employees_rewards.index') }}" class="nav-link {{ (request()->is('admin/Main_salary_employees_rewards*'))?'active':'' }}">
              <i class="far fa-circle nav-icon"></i>
              <p>المكافئات المالية</p>
            </a>
          </li>
          @endif
          @if(auth()->user()->is_master_admin==1 or check_permission_sub_menue(25)==true)
          <li class="nav-item">
            <a href="{{ route('Main_salary_employees_allowances.index') }}" class="nav-link {{ (request()->is('admin/Main_salary_employees_allowances*'))?'active':'' }}">
              <i class="far fa-circle nav-icon"></i>
              <p>البدلات المتغيرة</p>
            </a>
          </li>
          @endif
          @if(auth()->user()->is_master_admin==1 or check_permission_sub_menue(26)==true)
          <li class="nav-item">
            <a href="{{ route('Main_salary_employee.index') }}" class="nav-link {{ (request()->is('admin/Main_salary_employee/*'))?'active':'' }}">
              <i class="far fa-circle nav-icon"></i>
              <p>رواتب الموظفين المفصلة</p>
            </a>
          </li>
          @endif
        </ul>
      </li>
      @endif

      @if(auth()->user()->is_master_admin==1 or check_permission_main_menue(4)==true)
      <li class="nav-item has-treeview    {{ ( request()->is('admin/attenance_departure*')) ? 'menu-open':'' }} ">
        <a href="#" class="nav-link {{ ( request()->is('admin/attenance_departure*')) ? 'active':'' }} ">
          <i class="nav-icon fas fa-tachometer-alt"></i>
          <p>
            قائمة سجل الموظفين
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          @if(auth()->user()->is_master_admin==1 or check_permission_sub_menue(11)==true)
          <li class="nav-item">
            <a href="{{ route('attenance_departure.index') }}" class="nav-link {{ (request()->is('admin/attenance_departure*'))?'active':'' }}">
              <i class="far fa-circle nav-icon"></i>
              <p> سجلات الموظفين</p>
            </a>
          </li>
          @endif

        </ul>
      </li>
      @endif
      @if(auth()->user()->is_master_admin==1 or check_permission_main_menue(5)==true)
      <li class="nav-item has-treeview    {{ ( request()->is('admin/employees_vacations_balance*')) ? 'menu-open':'' }} ">
        <a href="#" class="nav-link {{ ( request()->is('admin/employees_vacations_balance*')) ? 'active':'' }} ">
          <i class="nav-icon fas fa-tachometer-alt"></i>
          <p>
            قائمة رصيد الاجازات
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          @if(auth()->user()->is_master_admin==1 or check_permission_sub_menue(12)==true)
          <li class="nav-item">
            <a href="{{ route('employees_vacations_balance.index') }}" class="nav-link {{ (request()->is('admin/employees_vacations_balance*'))?'active':'' }}">
              <i class="far fa-circle nav-icon"></i>
              <p>رصيد الاجازات</p>
            </a>
          </li>
          @endif

        </ul>
      </li>
      @endif
      @if(auth()->user()->is_master_admin==1 or check_permission_main_menue(7)==true)
      <li class="nav-item has-treeview    {{ ( request()->is('admin/main_EmployessInvestigations*')) ? 'menu-open':'' }} ">
        <a href="#" class="nav-link {{ ( request()->is('admin/main_EmployessInvestigations*')) ? 'active':'' }} ">
          <i class="nav-icon fas fa-tachometer-alt"></i>
          <p>
            قائمة التحقيقات الاداريه
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          @if(auth()->user()->is_master_admin==1 or check_permission_sub_menue(13)==true)
          <li class="nav-item">
            <a href="{{ route('main_EmployessInvestigations.index') }}" class="nav-link {{ (request()->is('admin/main_EmployessInvestigations*'))?'active':'' }}">
              <i class="far fa-circle nav-icon"></i>
              <p>التحقيقات</p>
            </a>
          </li>

          @endif
        </ul>
      </li>
      @endif

      @if(auth()->user()->is_master_admin==1 or check_permission_main_menue(9)==true)
      <li class="nav-item has-treeview    {{ ( request()->is('admin/MainEmployeeSettlements*')|| request()->is('admin/Main_salary_Directrewards*')|| request()->is('admin/Main_direct_grants*')) ? 'menu-open':'' }} ">
        <a href="#" class="nav-link {{ ( request()->is('admin/MainEmployeeSettlements*')|| request()->is('admin/Main_salary_Directrewards*')|| request()->is('admin/Main_direct_grants*')) ? 'active':'' }} ">
          <i class="nav-icon fas fa-tachometer-alt"></i>
          <p>
            قائمة التسويات
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          @if(auth()->user()->is_master_admin==1 or check_permission_sub_menue(32)==true)
          <li class="nav-item">
            <a href="{{ route('MainEmployeeSettlements.index') }}" class="nav-link {{ (request()->is('admin/MainEmployeeSettlements*'))?'active':'' }}">
              <i class="far fa-circle nav-icon"></i>
              <p>تسويات الرواتب</p>
            </a>
          </li>

          @endif
          @if(auth()->user()->is_master_admin==1 or check_permission_sub_menue(33)==true)
          <li class="nav-item">
            <a href="{{ route('Main_salary_Directrewards.index') }}" class="nav-link {{ (request()->is('admin/Main_salary_Directrewards*'))?'active':'' }}">
              <i class="far fa-circle nav-icon"></i>
              <p>المكافئات المباشرة</p>
            </a>
          </li>

          @endif
          @if(auth()->user()->is_master_admin==1 or check_permission_sub_menue(35)==true)
          <li class="nav-item">
            <a href="{{ route('Main_direct_grants.index') }}" class="nav-link {{ (request()->is('admin/Main_direct_grants*'))?'active':'' }}">
              <i class="far fa-circle nav-icon"></i>
              <p>المنح المباشرة</p>
            </a>
          </li>

          @endif
        </ul>
      </li>
      @endif

      @if(auth()->user()->is_master_admin==1 or check_permission_main_menue(6)==true)
      <li class="nav-item has-treeview    {{ ( request()->is('admin/system_monitoring*')) ? 'menu-open':'' }} ">
        <a href="#" class="nav-link {{ ( request()->is('admin/system_monitoring*')) ? 'active':'' }} ">
          <i class="nav-icon fas fa-tachometer-alt"></i>
          <p>
            قائمة المراقبه
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          @if(auth()->user()->is_master_admin==1 or check_permission_sub_menue(14)==true)
          <li class="nav-item">
            <a href="{{ route('system_monitoring.index') }}" class="nav-link {{ (request()->is('admin/system_monitoring*'))?'active':'' }}">
              <i class="far fa-circle nav-icon"></i>
              <p>سجل حركه النظام</p>
            </a>
          </li>
          @endif

        </ul>
      </li>
      @endif
      @if(auth()->user()->is_master_admin==1 or check_permission_main_menue(8)==true)
      <li class="nav-item has-treeview {{ ( request()->is('admin/admins_accounts*') || request()->is('admin/permission_roles*') ||request()->is('admin/permission_main_menues*') ||request()->is('admin/permission_sub_menues*') )?'menu-open':'' }}     ">
        <a href="#" class="nav-link {{ (request()->is('admin/admins_accounts*') || request()->is('admin/permission_roles*') ||request()->is('admin/permission_main_menues*') ||request()->is('admin/permission_sub_menues*') )?'active':'' }}">


          <i class="nav-icon fas fa-tachometer-alt"></i>
          <p>
            قائمة الصلاحيات
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          @if(auth()->user()->is_master_admin==1 or check_permission_sub_menue(16)==true)
          <li class="nav-item">
            <a href="{{ route('admin.permission_roles.index') }}" class="nav-link {{ (request()->is('admin/permission_roles*') )?'active':'' }}">
              <p>
                أدوار المستخدمين
              </p>
            </a>
          </li>
          @endif
          @if(auth()->user()->is_master_admin==1 or check_permission_sub_menue(15)==true)
          <li class="nav-item">
            <a href="{{ route('admin.admins_accounts.index') }}" class="nav-link {{ (request()->is('admin/admins_accounts*') )?'active':'' }}">
              <p>
                المستخدمين
              </p>
            </a>
          </li>
          @endif
          @if(auth()->user()->is_programer==1)
          <li class="nav-item">
            <a href="{{ route('admin.permission_main_menues.index') }}" class="nav-link {{ (request()->is('admin/permission_main_menues*') )?'active':'' }}">
              <p>
                القوائم الرئيسية للصلاحيات
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('admin.permission_sub_menues.index') }}" class="nav-link {{ (request()->is('admin/permission_sub_menues*') )?'active':'' }}">
              <p>
                القوائم الفرعية للصلاحيات
              </p>
            </a>
          </li>
          @endif
        </ul>
      </li>
      @endif

<!-- employess only-->
         @if(auth()->user()->usertype==2)
      <li class="nav-item has-treeview {{ ( request()->is('admin/EmployeeTasks*'))?'menu-open':'' }}     ">
        <a href="#" class="nav-link {{ (request()->is('admin/EmployeeTasks*'))?'active':'' }}">


          <i class="nav-icon fas fa-tachometer-alt"></i>
          <p>
             طلبات ومهام الموظفين
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
       
          <li class="nav-item">
            <a href="{{ route('EmployeeTasks.index') }}" class="nav-link {{ (request()->is('admin/EmployeeTasks*') )?'active':'' }}">
              <p>
                 مهام الموظفين
              </p>
            </a>
          </li>
        
    
          <li class="nav-item">
            <a href="{{ route('admin.admins_accounts.index') }}" class="nav-link {{ (request()->is('admin/admins_accounts*') )?'active':'' }}">
              <p>
                المستخدمين
              </p>
            </a>
          </li>
        
         
          <li class="nav-item">
            <a href="{{ route('admin.permission_main_menues.index') }}" class="nav-link {{ (request()->is('admin/permission_main_menues*') )?'active':'' }}">
              <p>
                القوائم الرئيسية للصلاحيات
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('admin.permission_sub_menues.index') }}" class="nav-link {{ (request()->is('admin/permission_sub_menues*') )?'active':'' }}">
              <p>
                القوائم الفرعية للصلاحيات
              </p>
            </a>
          </li>
        
        </ul>
      </li>
      @endif
    



      <li class="nav-item has-treeview ">
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-tachometer-alt"></i>
          <p>
            قائمة الدعم الفني
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <a href="#">
              <i class="far fa-circle nav-icon"></i>
              <p> احصائية عامة</p>
            </a>
          </li>

        </ul>
      </li>



    </ul>
  </nav>
  <!-- /.sidebar-menu -->
</div>