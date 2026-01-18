@if(isset($permission_roles_sub_menu) && !empty($permission_roles_sub_menu))

<form action="{{ route('admin.permission_roles.add_roles_actions',$permission_roles_sub_menu['id']) }}"
      method="post">
    @csrf

    <div class="form-group">
        <label>الصلاحيات المتاحة</label>
        <select name="permission_sub_menues_actions_id[]"
                class="form-control select2"
                multiple>
            <option value="">اختر الصلاحيات</option>

            @if(isset($permission_sub_menues_actions) && count($permission_sub_menues_actions) > 0)
                @foreach($permission_sub_menues_actions as $action)
                    <option value="{{ $action->id }}">
                        {{ $action->name }}
                    </option>
                @endforeach
            @endif
        </select>
    </div>

    <div class="form-group text-center">
        <button type="submit" class="btn btn-success btn-sm">
            إضافة الصلاحيات
        </button>
    </div>
</form>

<script>
    $('.select2').select2({
        theme: 'bootstrap4'
    });
</script>

@else
<div class="alert alert-danger text-center">
    عفوا لا توجد صلاحيات للعرض
</div>
@endif
