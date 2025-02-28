@extends('layouts.admin')

@section('title', 'Inventory')

@section('content')
<div class="container" style="margin-bottom: 50px;">
    <br>
    <h1 style="padding-bottom: 30px;">Inventory</h1>

    <!-- Start of two-column layout using Bootstrap grid system -->
    <div class="row">
        <!-- First Column: Form for Adding New Inventory -->
        <div class="col-md-6">
            <h3>Add New Item</h3>
            <form action="{{ route('admin.inventory_admin.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Enter item name" required>
                </div>

              

                <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="text" id="price" name="price" class="form-control" placeholder="Enter item price" required>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" class="form-control" placeholder="Enter item quantity" required>
                </div>
                <div class="form-group">
                    <label for="expiration_type">Expiration Type:</label>
                    <select id="expiration_type" name="expiration_type" class="form-control" required onchange="toggleExpirationField()">
                        <option value="expirable">Expirable</option>
                        <option value="inexpirable">Inexpirable</option>
                    </select>
                </div>
                <div class="form-group" id="expiration_date_group">
                    <label for="expiration_date">Expiration Date (if applicable):</label>
                    <input type="date" id="expiration_date" name="expiration_date" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="supplier">Supplier:</label>
                    <input type="text" id="supplier" name="supplier" class="form-control" placeholder="Enter supplier name">
                </div>
                <div class="form-group">
                    <label for="category">Category:</label>
                    <select id="category" name="category" class="form-control" required>
                        <option value="Patient Care Supplies">Patient Care Supplies</option>
                        <option value="Equipment">Equipment</option>
                        <option value="Dental Instruments">Dental Instruments</option>
                        <option value="Sterilization Products">Sterilization Products</option>
                        <option value="Surgical Tools">Surgical Tools</option>
                        <option value="Protective Gear">Protective Gear</option>
                    </select>
                </div>                
                <br>
                <button type="submit" class="btn btn-primary">Add Item</button>
            </form>            
        </div>

        <!-- Second Column: Form for Updating Existing Item -->
        <div class="col-md-6">
            <h3>Update Item</h3>
            @php
                $item = $item ?? null; // Ensure $item is defined
            @endphp

            @if(isset($item) && $item)
                <form action="{{ route('admin.inventory_admin.update', $item->id) }}" method="POST">
                    @csrf
                    @method('PUT')  <!-- This ensures Laravel processes it as a PUT request -->
                
                    <input type="hidden" name="id" value="{{ $item->id }}">
                
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ $item->name }}" required>
                    </div>
                
                  

                    <div class="form-group">
                        <label for="price">Price:</label>
                        <input type="text" id="price" name="price" class="form-control" value="{{ $item->price }}" required>
                    </div>
                
                    <div class="form-group">
                        <label for="quantity">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" class="form-control" value="{{ $item->quantity }}" required>
                    </div>

                    <div class="form-group">
                        <label for="expiration_type">Expiration Type:</label>
                        <select id="expiration_type" name="expiration_type" class="form-control" onchange="toggleExpirationField()">
                            <option value="expirable" {{ $item->expiration_date ? 'selected' : '' }}>Expirable</option>
                            <option value="inexpirable" {{ !$item->expiration_date ? 'selected' : '' }}>Inexpirable</option>
                        </select>
                    </div>

                    <div class="form-group" id="expiration_date_group">
                        <label for="expiration_date">Expiration Date:</label>
                        <input type="date" id="expiration_date" name="expiration_date" class="form-control" value="{{ $item->expiration_date }}" {{ !$item->expiration_date ? 'disabled' : 'required' }}>
                    </div>
                
                    <div class="form-group">
                        <label for="supplier">Supplier:</label>
                        <input type="text" id="supplier" name="supplier" class="form-control" value="{{ $item->supplier }}">
                    </div>
                
                    <div class="form-group">
                        <label for="category">Category:</label>
                        <select id="category" name="category" class="form-control">
                            <option value="Patient Care Supplies" {{ $item->category == 'Patient Care Supplies' ? 'selected' : '' }}>Patient Care Supplies</option>
                            <option value="Equipment" {{ $item->category == 'Equipment' ? 'selected' : '' }}>Equipment</option>
                            <option value="Dental Instruments" {{ $item->category == 'Dental Instruments' ? 'selected' : '' }}>Dental Instruments</option>
                            <option value="Sterilization Products" {{ $item->category == 'Sterilization Products' ? 'selected' : '' }}>Sterilization Products</option>
                            <option value="Surgical Tools" {{ $item->category == 'Surgical Tools' ? 'selected' : '' }}>Surgical Tools</option>
                            <option value="Protective Gear" {{ $item->category == 'Protective Gear' ? 'selected' : '' }}>Protective Gear</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success">Update Item</button>
                    <button type="button" class="btn btn-secondary" onclick="hideUpdateForm()">Cancel</button>
                </form>
            @else
                <p>Select an item to update.</p>
            @endif
        </div>
    </div>
    <!-- End of two-column layout -->

    <br>

    <!-- Search Bar -->
    <div class="form-group">
        <label for="search">Search Inventory:</label>
        <input type="text" id="search" class="form-control" placeholder="Search by name, price, expiration date.." onkeyup="searchInventory()">
    </div>

  <!-- Inventory Table -->
<table class="table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Expiration Date</th>
            <th>Supplier</th>
            <th>Category</th> <!-- Added Category Column -->
            <th>Actions</th>
        </tr>
    </thead>
    <tbody id="inventoryTable">
        @foreach ($inventory as $item)
            <tr class="inventory-item">
                <td>{{ $item->name }}</td>
                <td>{{ $item->price }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->expiration_date }}</td>
                <td>{{ $item->supplier }}</td>
                <td>{{ $item->category }}</td> <!-- Displaying Category -->
                <td>
                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal" 
                        onclick="editItem({{ $item->id }}, '{{ $item->name }}', '{{ $item->price }}', '{{ $item->quantity }}', '{{ $item->expiration_date }}', '{{ $item->supplier }}', '{{ $item->category }}')">
                        Edit
                    </button>
                    <form action="{{ route('admin.inventory_admin.destroy', $item->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal" 
                            onclick="setDeleteAction('{{ route('admin.inventory_admin.destroy', $item->id) }}')">
                            Delete
                        </button>
                    </form>
                </td>                    
            </tr>
        @endforeach
    </tbody>
</table>

<!-- Modal for Edit Item -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Update Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="updateForm" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="update_name">Name:</label>
                        <input type="text" id="update_name" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="update_price">Price:</label>
                        <input type="text" id="update_price" name="price" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="update_quantity">Quantity:</label>
                        <input type="number" id="update_quantity" name="quantity" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="update_expiration_date">Expiration Date:</label>
                        <input type="date" id="update_expiration_date" name="expiration_date" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="update_supplier">Supplier:</label>
                        <input type="text" id="update_supplier" name="supplier" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="category">Category:</label>
                        <select id="category" name="category" class="form-control" required>
                            <option value="Patient Care Supplies">Patient Care Supplies</option>
                            <option value="Equipment">Equipment</option>
                            <option value="Dental Instruments">Dental Instruments</option>
                            <option value="Sterilization Products">Sterilization Products</option>
                            <option value="Surgical Tools">Surgical Tools</option>
                            <option value="Protective Gear">Protective Gear</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">Update Item</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function editItem(id, name, price, quantity, expirationDate, supplier, category) {
        document.getElementById('update_name').value = name;
        document.getElementById('update_price').value = price;
        document.getElementById('update_quantity').value = quantity;
        document.getElementById('update_expiration_date').value = expirationDate;
        document.getElementById('update_supplier').value = supplier;
        document.getElementById('update_category').value = category;

        // Set the correct form action URL dynamically
        const formAction = '/admin/inventory-admin/update/' + id;
        document.getElementById('updateForm').action = formAction;
    }
</script>

@endsection
