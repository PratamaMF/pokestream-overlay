@extends('layout.main')

@section('title', 'Products - ' . Auth::user()->name)
@section('namepage', 'Product Management')
@section('route', route('products.index'))
@section('namemenu', 'Product Data')

@section('content')
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 fw-bold text-dark"><i class="fas fa-box me-2"></i>All Pokémon Products</h6>
        <button type="button" class="btn btn-primary btn-sm px-3" data-bs-toggle="modal" data-bs-target="#createProductModal">
            <i class="fas fa-plus me-1"></i>Add Product
        </button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table id="datatablesSimple" class="table table-hover align-middle mb-0 text-nowrap">
                <thead class="table-light">
                    <tr>
                        <th class="px-4" style="width: 10px;">No</th>
                        <th class="px-4">Product Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th class="text-center" style="width: 150px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td class="px-4 fw-bold text-muted">{{ $loop->iteration }}</td>
                        <td class="px-4">
                            <span class="fw-bold text-dark d-block">{{ $product->product_name }}</span>
                        </td>
                        <td><span class="badge bg-soft-primary text-primary fw-medium px-2.5 py-1.5">{{ $product->category->category_name }}</span></td>
                        <td class="fw-bold text-dark">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td>
                            @if($product->stock <= 5)
                                <span class="text-danger fw-bold"></i>{{ $product->stock }} Pcs</span>
                            @else
                                <span class="text-success fw-medium">{{ $product->stock }} Pcs</span>
                            @endif
                        </td>
                       <td>
                            @if($product->status === 'ready')
                                <span class="badge bg-success">Ready</span>
                            @else
                                <span class="badge bg-danger">Empty</span>
                            @endif
                        </td>
                        <td class="text-center px-4">
                            <div class="d-flex justify-content-center gap-2">
                                <button type="button" class="btn btn-sm btn-soft-warning" data-bs-toggle="modal" data-bs-target="#editProductModal{{ $product->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" id="delete" class="btn btn-sm btn-soft-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <div class="modal fade" id="editProductModal{{ $product->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow">
                                <div class="modal-header bg-light border-0 py-3">
                                    <h5 class="modal-title fw-bold text-dark"><i class="fas fa-edit me-2 text-warning"></i>Edit Product</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('products.update', $product->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body p-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-600">Product Name</label>
                                            <input type="text" name="product_name" class="form-control" value="{{ $product->product_name }}" required />
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-600">Category</label>
                                            <select name="category_id" class="form-select" required>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                                        {{ $category->category_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-600">Price (Rp)</label>
                                                <input type="number" name="price" class="form-control" value="{{ $product->price }}" min="0" required />
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-600">Stock</label>
                                                <input type="number" name="stock" class="form-control" value="{{ $product->stock }}" min="0" required />
                                            </div>
                                        </div>
                                        <div class="mb-0">
                                            <label class="form-label fw-600 d-block">Status Products</label>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="status" id="statusActive{{ $product->id }}" value="ready" {{ $product->status ? 'checked' : '' }}>
                                                <label class="form-check-label" for="statusActive{{ $product->id }}">Ready (show)</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="status" id="statusInactive{{ $product->id }}" value="empty" {{ !$product->status ? 'checked' : '' }}>
                                                <label class="form-check-label" for="statusInactive{{ $product->id }}">Empty (hide)</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light border-0 py-3">
                                        <button type="button" class="btn btn-soft-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-warning text-white">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            Not found any product.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="createProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-0 py-3">
                <h5 class="modal-title fw-bold text-dark"><i class="fas fa-box-open me-2 text-primary"></i>Add New Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('products.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-600">Product Name</label>
                        <input type="text" name="product_name" class="form-control" placeholder="e.g. Booster Pack Shiny Treasure ex" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600">Category</label>
                        <select name="category_id" class="form-select" required>
                            <option value="" disabled selected>-- Select Category --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-0">
                            <label class="form-label fw-600">Price (Rp)</label>
                            <input type="number" name="price" class="form-control" placeholder="e.g. 70000" min="0" required />
                        </div>
                        <div class="col-md-6 mb-0">
                            <label class="form-label fw-600">Initial Stock</label>
                            <input type="number" name="stock" class="form-control" placeholder="e.g. 150" min="0" required />
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 py-3">
                    <button type="button" class="btn btn-soft-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Product</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection