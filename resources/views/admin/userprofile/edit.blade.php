@extends('layouts.admin')

@section('title', 'المستخدمين')
@section('contentheader', 'البروفيل')
@section('contentheaderlink')
    <a href="#">الصفحة الشخصية</a>
@endsection
@section('contentheaderactive', 'تعديل')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white text-center">
                <h3 class="card-title text-center">تعديل بيانات البروفيل الشخصي</h3>
            </div>
            
            <div class="card-body">
                @if(isset($data) && !empty($data))
                    <form action="{{ route('userprofile.update') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- الصف الأول: الاسم والبريد -->
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">اسم المستخدم كاملا</label>
                                <input 
                                    readonly 
                                    name="name" 
                                    id="name" 
                                    class="form-control" 
                                    value="{{ old('name', $data['name']) }}"
                                >
                                @error('name')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">البريد الالكتروني</label>
                                <input 
                                    name="email" 
                                    id="email" 
                                    class="form-control" 
                                    value="{{ old('email', $data['email']) }}"
                                >
                                @error('email')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- الصف الثاني: اسم المستخدم وتحديث كلمة المرور -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">اسم المستخدم للدخول للنظام</label>
                                <input 
                                    name="username" 
                                    id="username" 
                                    class="form-control" 
                                    value="{{ old('username', $data['username']) }}"
                                >
                                @error('username')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">هل تريد تحديث كلمة المرور؟</label>
                                <select 
                                    name="checkForupdatePassword" 
                                    id="checkForupdatePassword" 
                                    class="form-select"
                                >
                                    <option 
                                        value="0" 
                                        {{ old('checkForupdatePassword', $data['checkForupdatePassword']) == 0 ? 'selected' : '' }}
                                    >
                                        لا
                                    </option>
                                    <option 
                                        value="1" 
                                        {{ old('checkForupdatePassword', $data['checkForupdatePassword']) == 1 ? 'selected' : '' }}
                                    >
                                        نعم
                                    </option>
                                </select>
                                @error('checkForupdatePassword')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- الصورة الشخصية -->
                        <div class="row mb-4">
                            <div class="col-12 text-center">
                                <label class="form-label fw-bold d-block mb-3">الصورة الشخصية</label>
                                @if(!empty($data['image']))
                                    <img 
                                        src="{{ asset('assets/admin/uploads/' . $data['image']) }}" 
                                        class="rounded-circle border border-3 border-primary" 
                                        width="120" 
                                        height="120" 
                                        alt="الصورة الشخصية"
                                    >
                                @else
                                    <img 
                                        src="{{ asset('assets/admin/imgs/male.png') }}" 
                                        class="rounded-circle border border-3 border-secondary" 
                                        width="120" 
                                        height="120" 
                                        alt="صورة الموظف"
                                    >
                                @endif
                            </div>
                               <input type="file" name="image_edit" id="image_edit" class="form-control" >
                        </div>
              
                        <!-- حقل كلمة المرور -->
                        <div 
                            class="form-group mb-4" 
                            id="PasswordDIV" 
                            style="{{ old('checkForupdatePassword', $data['checkForupdatePassword']) == 0 ? 'display:none;' : '' }}"
                        >
                            <label class="form-label fw-bold">كلمة المرور الجديدة</label>
                            <input 
                                name="password" 
                                type="password" 
                                id="password" 
                                class="form-control"
                                placeholder="أدخل كلمة المرور الجديدة"
                            >
                            @error('password')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- أزرار التحكم -->
                        <div class="d-flex justify-content-center gap-3 mt-4">
                            <button type="submit" class="btn btn-success px-4">
                                <i class="fas fa-save me-1"></i> حفظ التعديلات
                            </button>
                            <a href="{{ route('userprofile.index') }}" class="btn btn-outline-danger px-4">
                                <i class="fas fa-times me-1"></i> إلغاء
                            </a>
                        </div>
                    </form>
                @else
                    <div class="alert alert-danger text-center py-4">
                        <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                        <h5 class="mb-0">عفواً، لا توجد بيانات لعرضها!</h5>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/admin/js/admins.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#checkForupdatePassword').change(function() {
            const passDiv = $('#PasswordDIV');
            if ($(this).val() === '1') {
                passDiv.slideDown(300);
                $('#password').focus();
            } else {
                passDiv.slideUp(300);
            }
        });
    });
</script>
@endsection
