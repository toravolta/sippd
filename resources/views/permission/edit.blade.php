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
                    <h1>Edit Permission</h1>
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('permission.index')}}">Permission</a> / Edit Permission</li>
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
                        <form method="POST" action="{{route('permission.update', $id)}}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="name">Name *</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{old('name', $permission->name)}}" required>
                                <small class="text-muted">ex: product-list, product-edit, product-delete</small>
                                @error('name')
                                <span class="text-danger"><i>{{ $message }}</i></span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="email">Label *</label>
                                <input type="text" class="form-control" id="label" name="label" value="{{old('label', $permission->label)}}" required>
                                @error('label')
                                <span class="text-danger"><i>{{ $message }}</i></span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="email">Group *</label>
                                <select class="form-control group" name="group">
                                </select>
                                @error('group')
                                <span class="text-danger"><i>{{ $message }}</i></span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">Update</button>
                                <a href="{{ route('permission.index') }}" class="btn btn-outline-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('register-scriptcode')
<script>
    $(document).ready(function () {

        $('.group').select2({
            width: "100%",
            tags: true,
            ajax: {
                url: '/getGroup',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.group,
                                id: item.group
                            }
                        })
                    };
                },
                cache: true
            }
        });

        var id = '<?php echo $id ?>';

        // Fetch the preselected item, and add to the control
        var groupSelect = $('.group');
        $.ajax({
            type: 'GET',
            url: '/getGroup/' + id,
            dataType: 'json'
        }).then(function (data) {

            console.log(data);
            // create the option and append to Select2
            var option = new Option(data.group, data.group, true, true);
            groupSelect.append(option).trigger('change');

            // manually trigger the `select2:select` event
            groupSelect.trigger({
                type: 'select2:select',
                params: {
                    data: data
                }
            });
        });
    });
</script>
@endsection

@section('register-stylecode')
<style>
    .select2-selection__rendered {
        line-height: 38px !important;
    }
    .select2-container .select2-selection--single {
        height: 38px !important;
    }
    .select2-selection__arrow {
        height: 38px !important;
    }
</style>
@endsection