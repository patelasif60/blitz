@extends('admin/adminLayout')

@section('content')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="mb-2">
                    <a href="{{ route('grades-list') }}" class="mb-2" style="float:right;"><i class="fa fa-times"
                        aria-hidden="true"></i></a>
                </div>
                <h3 class="card-title">Add Grade</h3>
                <form class="row g-3" method="POST" enctype="multipart/form-data" action="{{ route('grade-create') }}"
                    data-parsley-validate>
                    @csrf
                    <div class="col-md-9">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="col-md-9">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description"></textarea>
                    </div>
                    <div class="col-md-9">
                        <label for="status" class="form-label">Status</label>
                        <input type="radio" value="1" name="status" checked> Active
                        <input type="radio" value="0" name="status"> Deactive
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
