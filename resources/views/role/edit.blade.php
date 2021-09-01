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
                    <h1>Add Role</h1>
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('roles.index')}}">Role</a> / Add Role</li>
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
                        <form method="POST" action="{{route('roles.update', $id)}}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="name">Name *</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{old('name', $role->name)}}" required>
                                @error('name')
                                <span class="text-danger"><i>{{ $message }}</i></span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <br/>
                                <strong>Permission:</strong>
                                <hr/>
                                @error('permission')
                                <span class="text-danger"><i>{{ $message }}</i></span>
                                @enderror
                                <br />

                                @foreach($groups as $group)
                                <p class="mb-2">{{$group->group}}</p>
                                <div class="row border-bottom justify-content-center mb-3">
                                    @foreach($permission as $value)
                                        @if($group->group == $value->group)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="permission[]" value="{{$value->id}}" {{in_array($value->id, $rolePermissions) ? ' checked="checked"' : ''}}>
                                            <label class="form-check-label mr-4">{{ $value->label }}</label>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                                @endforeach
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-success">Update</button>
                                <a href="{{ route('roles.index') }}" class="btn btn-outline-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection