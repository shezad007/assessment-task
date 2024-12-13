@extends('layouts.main')

@section('title', 'Product')

@section('content')
<div class="container text-center">
    <div class="row">
        <h1>Products List Page</h1>
        <div class="d-flex">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNewProduct">Add New Product</button>
        </div>
        <div class="border mt-3">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">id</th>
                        <th scope="col">Name</th>
                        <th scope="col">Category</th>
                        <th scope="col">Price</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                @if ($products->isEmpty())
                <tr>
                    <td colspan="5" class="text-center">No products found.</td>
                </tr>
                @else
                    @foreach ($products as $product)
                        <tr>
                            <th scope="row">{{ $product->id }}</th>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category->name }}</td>
                            <td>{{ $product->price }}</td>
                            <td>
                            <button type="button" 
                                class="btn btn-primary editProduct" 
                                data-id="{{ $product->id }}" 
                                data-name="{{ $product->name }}" 
                                data-category-id="{{ $product->category_id }}" 
                                data-price="{{ $product->price }}" 
                                data-bs-toggle="modal" 
                                data-bs-target="#editProduct">
                                <i class="fa-light fa-pen-to-square"></i>
                            </button>
                                <button type="button" class="btn btn-danger" data-id="{{ $product->id }}">
                                    <i class="fa-light fa-trash-can"></i>
                                </button>
                                
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
            <div class="d-flex justify-content-start">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addNewProduct">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addProductForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="productName" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="productName" name="name" placeholder="Enter product name">
                        <span class="text-danger" id="nameError"></span>
                    </div>
                    <div class="mb-3">
                        <label for="categoryId" class="form-label">Category Name</label>
                        <select class="form-select" id="categoryId" name="category_id">
                            <option value="" disabled selected>Select a category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger" id="categoryError"></span>
                    </div>
                    <div class="mb-3">
                        <label for="productPrice" class="form-label">Price</label>
                        <input type="number" class="form-control" id="productPrice" name="price" placeholder="Enter product price">
                        <span class="text-danger" id="priceError"></span>
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

<div class="modal fade" id="editProduct">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editProductForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="editProductId" name="id">
                    <div class="mb-3">
                        <label for="editProductName" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="editProductName" name="name">
                        <span class="text-danger" id="editNameError"></span>
                    </div>
                    <div class="mb-3">
                        <label for="editCategoryId" class="form-label">Category</label>
                        <select class="form-select" id="editCategoryId" name="category_id">
                            <option value="" disabled>Select a category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger" id="editCategoryError"></span>
                    </div>
                    <div class="mb-3">
                        <label for="editProductPrice" class="form-label">Price</label>
                        <input type="number" class="form-control" id="editProductPrice" name="price">
                        <span class="text-danger" id="editPriceError"></span>
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
        $('#addProductForm').submit(function (e) {
            e.preventDefault();
            let formData = $(this).serialize();
            $.ajax({
                url: '/addProduct',
                type: 'POST',
                data: formData,
                success: function (response) {
                    if (response.success) {
                        $('#addNewProduct').modal('hide');
                        alert('Product added successfully!');
                        location.reload();
                    }
                },
                error: function (response) {
                    const errors = response.responseJSON.errors;
                    if (errors.name) {
                        $('#nameError').text(errors.name[0]);
                    }
                    if (errors.category_id) {
                        $('#categoryError').text(errors.category_id[0]);
                    }
                    if (errors.price) {
                        $('#priceError').text(errors.price[0]);
                    }
                },
            });
        });

        $(document).on('click', '.editProduct', function () {
            const productId = $(this).data('id');
            const productName = $(this).data('name');
            const categoryId = $(this).data('category-id');
            const productPrice = $(this).data('price');

            $('#editProductId').val(productId);
            $('#editProductName').val(productName);
            $('#editCategoryId').val(categoryId);
            $('#editProductPrice').val(productPrice);
        });

        $('#editProductForm').submit(function (e) {
            e.preventDefault();

            const productId = $('#editProductId').val();
            const formData = $(this).serialize();

            $.ajax({
                url: `/productEdit/${productId}`,
                type: 'PUT',
                data: formData,
                success: function (response) {
                    if (response.success) {
                        alert('Product updated successfully!');
                        $('#editProductForm')[0].reset();
                        $('#editProduct').modal('hide');
                        location.reload();
                    }
                },
                error: function (response) {
                    const errors = response.responseJSON.errors;
                    if (errors.name) {
                        $('#editNameError').text(errors.name[0]);
                    }
                    if (errors.category_id) {
                        $('#editCategoryError').text(errors.category_id[0]);
                    }
                    if (errors.price) {
                        $('#editPriceError').text(errors.price[0]);
                    }
                },
            });
        });


        $('.btn-danger').click(function () {
            const id = $(this).data('id');

            if (confirm('Are you sure you want to delete this Product?')) {
                $.ajax({
                    url: `/productDelete/${id}`,
                    type: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function (response) {
                        if (response.success) {
                            alert('Product deleted successfully!');
                            location.reload();
                        }
                    },
                    error: function (response) {
                        alert('Error deleting Product.');
                    },
                });
            }
        });
    });

</script>
@endsection