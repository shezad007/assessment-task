@extends('layouts.main')

@section('title', 'category')

@section('content')
<div class="container text-center">
    <div class="row">
        <h1>Category List Page</h1>
        <div class="d-flex">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNewCat">Add New Category</button>
        </div>
        <div class="border mt-3">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">id</th>
                        <th scope="col">Name</th>
                        <th scope="col">Count of Products</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($categories->isEmpty())
                    <tr>
                        <td colspan="4" class="text-center">No Category found.</td>
                    </tr>
                    @else
                        @foreach ($categories as $category)
                        <tr>
                            <th scope="row">{{ $category->id }}</th>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->products_count }}</td>
                            <td>
                            <button type="button" 
                                class="btn btn-primary me-2 editCategory" 
                                data-id="{{ $category->id }}" 
                                data-name="{{ $category->name }}" 
                                data-bs-toggle="modal" 
                                data-bs-target="#editCat">
                                <i class="fa-light fa-pen-to-square"></i>
                            </button>
                                <button type="button" class="btn btn-danger" data-id="{{ $category->id }}">
                                    <i class="fa-light fa-trash-can"></i>
                                </button>
                                
                            </td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            <div class="d-flex justify-content-start">
                {{ $categories->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addNewCat">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addCategoryForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="categoryName" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="categoryName" name="name" placeholder="Enter category name">
                        <span class="text-danger" id="nameError"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editCat">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCategoryForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="editCategoryId" name="id">
                    <div class="mb-3">
                        <label for="categoryName" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="editCategoryName" name="name">
                        <span class="text-danger" id="editNameError"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('pageScript')
<script>
    $(document).ready(function () {
        $('#addCategoryForm').submit(function (e) {
            e.preventDefault();
            let formData = $(this).serialize();
            $('#nameError').text('');
            $.ajax({
                url: '/addCategory',
                type: 'POST',
                data: formData,
                success: function (response) {
                    if (response.success) {
                        $('#addNewCat').modal('hide');
                        alert('Category added successfully!');
                        location.reload();
                    }
                },
                error: function (response) {
                    if (response.responseJSON.errors.name) {
                        $('#nameError').text(response.responseJSON.errors.name[0]);
                    }
                },
            });
        });

        $('.editCategory').click(function () {
            const id = $(this).data('id');
            const name = $(this).data('name');
            
            $('#editCategoryId').val(id);
            $('#editCategoryName').val(name);
        });

        $('#editCategoryForm').submit(function (e) {
            e.preventDefault();
            const id = $('#editCategoryId').val();
            const formData = $(this).serialize();

            $.ajax({
                url: `/categoryEdit/${id}`,
                type: 'PUT',
                data: formData,
                success: function (response) {
                    if (response.success) {
                        $('#editCat').modal('hide');
                        alert('Category updated successfully!');
                        location.reload();
                    }
                },
                error: function (response) {
                    if (response.responseJSON.errors.name) {
                        $('#editNameError').text(response.responseJSON.errors.name[0]);
                    }
                },
            });
        });

        $('.btn-danger').click(function () {
            const id = $(this).data('id');

            if (confirm('Are you sure you want to delete this category?')) {
                $.ajax({
                    url: `/categoryDelete/${id}`,
                    type: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function (response) {
                        if (response.success) {
                            alert('Category deleted successfully!');
                            location.reload();
                        }
                    },
                    error: function (response) {
                        alert('Error deleting category.');
                    },
                });
            }
        });
    });

</script>
@endsection