@extends('layouts.admin')
@section('title')
تعديل الفروع للمستخدم
@endsection

@section('contentheader')
المستخدمين
@endsection

@section('contentheaderlink')
<a href="{{ route('admin.admins_accounts.index') }}"> المستخدمين </a>
@endsection

@section('contentheaderactive')
 ربط الفروع بالمستخدم
@endsection

@section('content')
<div class="container-fluid" style="margin-top:10px;">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">تعديل الفروع للمستخدم: {{ $admin->name }}</h3>
            </div>
            <div class="card-body">
                @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('admin.admins_accounts.branches_update', $admin->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>اختر الفروع:</label>
                        <div class="row">
                            @foreach($branches as $branch)
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input 
                                            class="form-check-input" 
                                            type="checkbox" 
                                            name="branches[]" 
                                            value="{{ $branch->id }}" 
                                            id="branch_{{ $branch->id }}"
                                            {{ $admin->branches->contains($branch->id) ? 'checked' : '' }}
                                        >
                                        <label class="form-check-label" for="branch_{{ $branch->id }}">
                                            {{ $branch->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success mt-3">تحديث الفروع</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
