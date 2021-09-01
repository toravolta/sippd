@extends('layouts.master')

@section('content')

@include('layouts.navbar')

@include('layouts.sidebar')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Add User</h1>
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('users.index')}}">User Management</a> / Add User</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="card card-solid">
            <div class="card-body pb-0">
                <div class="row d-flex align-items-stretch">
                    <div class="col-md-6 col-sm-12 mx-auto">
                        <form method="POST" action="{{route('users.store')}}" enctype="multipart/form-data">
                            @csrf
                            @method('POST')
                            <div class="form-group">
                                <label for="name">Name *</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{old('name')}}" required>
                                @error('name')
                                <span class="text-danger"><i>{{ $message }}</i></span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="email">Email *</label>
                                <input type="text" class="form-control" id="email" name="email" value="{{old('email')}}" required>
                                @error('email')
                                <span class="text-danger"><i>{{ $message }}</i></span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="email">Role *</label>
                                <select class="form-control" name="roles" id="roles" required>
                                    @foreach($roles as $role)
                                    <option value="{{$role}}">{{$role}}</option>
                                    @endforeach
                                </select>
                                @error('role')
                                <span class="text-danger"><i>{{ $message }}</i></span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password">Password *</label>
                                <input type="password" class="form-control" id="currentPassword" name="password" autocomplete="off" required>
                                @error('password')
                                <span class="text-danger"><i>{{ $message }}</i></span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="confirm-password">Confirm Password *</label>
                                <input type="password" class="form-control" id="confirm-password" name="confirm-password" required>
                                @error('confirm-password')
                                <span class="text-danger"><i>{{ $message }}</i></span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">Create</button>
                                <a href="{{ route('users.index') }}" class="btn btn-outline-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection