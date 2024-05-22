@extends('admin.layouts.app')


@section('content')
    

<!-- Content Header (Page header) -->
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Category</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('categories.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        @include('admin.message')
        <form method="POST" action="{{ route('admin.categories.update', $category->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-body">								
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Name" value="{{ $category->name }}">
                                <p></p>	
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Slug</label>
                                <input type="text"  name="slug" id="slug" class="form-control" placeholder="Slug" value="{{ $category->slug }}">
                                <p></p>	
                            </div>
                        </div>	
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="image">Image</label>
                                <div id="image_id" class="dropzone dz-clickable">
                                    <input type="file" name="image" id="image_id">
                                    <div class="dz-message needsclick">
                                        <br> Drop files here or click to upload.<br> <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option {{ $category->status == 1 ? 'selected' : '' }} value="1">Active</option>
                                    <option {{ $category->status == 0 ? 'selected' : '' }} value="0">Block</option>
                                </select>	
                            </div>
                        </div>								
                    </div>
                </div>							
            </div>
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.categories.store') }}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </form>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->

@section('customjs')
<script>
    $(document).ready(function() {
        $('#name').change(function() {
            var element = $(this);
            $.ajax({
                url: "{{ route('getSlug') }}",
                type: "GET",
                data: { title: element.val() },
                dataType: "json",
                success: function(response) {
                    if (response.status == true) {
                        $('#slug').val(response.slug);
                    }
                }
            });
        });

        Dropzone.autoDiscover = false;
        const dropzone = $('#image_id').dropzone({
            init: function() {
                this.on("addedfile", function(file) {
                    if (this.files.length > 1) {
                        this.removeFile(this.files[0]);
                    }
                });
            },
            url: "{{ route('temp_images.create') }}",
            maxFiles: 1,
            paramName: 'temp_images',
            addRemovelinkes: true,
            acceptedFiles: "image/jpeg,image/png,image/gif",
            headers: {
                "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr('content')
            },on
            success: function(file, response) {
                $("#image_id").val(response.image_id);
            }
        });
    });
</script>
@endsection

    
@endsection