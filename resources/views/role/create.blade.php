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
                        <form method="POST" action="{{route('roles.store')}}" enctype="multipart/form-data">
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
                                    @foreach($permissions as $permission)
                                        @if($group->group == $permission->group)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="permission[]" value="{{$permission->id}}">
                                            <label class="form-check-label mr-4">{{ $permission->label }}</label>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                                @endforeach
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-success">Create</button>
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