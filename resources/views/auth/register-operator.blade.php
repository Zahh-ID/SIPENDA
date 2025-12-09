@extends('layouts.main')
@section('title', 'Register New Operator')

@section('content')
<section class="page-content container">
    <div class="form-container small">
        <h2>Register New Operator</h2>
        <p class="subtitle">Create an account for a new school operator.</p>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.register.operator.submit') }}" method="POST">
            @csrf
            <div>
                <label for="nama_operator">Operator Name</label>
                <input type="text" id="nama_operator" name="nama_operator" value="{{ old('nama_operator') }}" required>
            </div>
            <div>
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="{{ old('username') }}" required>
            </div>
            <div>
                <label for="sekolah_tujuan">School</label>
                <input type="text" id="sekolah_tujuan" name="sekolah_tujuan" value="{{ old('sekolah_tujuan') }}" required placeholder="e.g., SMAN 1 Nganjuk">
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div>
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>
            <button type="submit" class="btn-primary" style="width: 100%; margin-top: 15px;">Register Operator</button>
        </form>
    </div>
</section>
@endsection
