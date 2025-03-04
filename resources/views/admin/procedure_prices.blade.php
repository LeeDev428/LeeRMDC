@extends('layouts.admin')

@section('title', 'Procedure Prices')
<style>
    body {
        background-color: #83baca !important; /* Restore the background */
    }
</style>

@section('content')
<div class="container">
    <br>
    <h2>Update Procedure Prices</h2>
    <br>

    <!-- Display success message with fade-out effect -->
    @if(session('success'))
        <div class="alert alert-success" id="successMessage">
            {{ session('success') }}
        </div>
    @endif

    <!-- Table to display procedure prices -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Procedure Name</th>
                <th>Price</th>
                <th>Duration</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($procedures as $procedure)
            <tr>
                <td>{{ $procedure->procedure_name }}</td>
                <td>
                    <input type="text" id="price_{{ $procedure->id }}" value="{{ old('price', $procedure->price) }}" class="form-control">
                </td>
                <td>
                    <input type="text" id="duration_{{ $procedure->id }}" value="{{ old('duration', $procedure->duration) }}" class="form-control">
                </td>
                <td>
                    <!-- Update Button -->
                    <button class="btn btn-primary" onclick="confirmUpdate({{ $procedure->id }})">Update</button>

                    <!-- Delete Button -->
                    <button class="btn btn-danger" onclick="confirmDelete({{ $procedure->id }})">Delete</button>

                    <!-- Update Form -->
                    <form id="updateForm_{{ $procedure->id }}" action="{{ route('admin.procedure_prices.update', ['id' => $procedure->id]) }}" method="POST" style="display: none;">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="price" id="hidden_price_{{ $procedure->id }}">
                        <input type="hidden" name="duration" id="hidden_duration_{{ $procedure->id }}">
                    </form>

                    <!-- Delete Form -->
                    <form id="deleteForm_{{ $procedure->id }}" action="{{ route('admin.procedure_prices.destroy', ['id' => $procedure->id]) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <hr>

    <!-- Form to Create New Procedure Price -->
    <h3>Add New Procedure Price</h3>
    <form action="{{ route('admin.procedure_prices.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="procedure_name">Procedure Name</label>
            <input type="text" name="procedure_name" id="procedure_name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="price">Price</label>
            <input type="text" name="price" id="price" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="duration">Duration</label>
            <input type="text" name="duration" id="duration" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Add Procedure Price</button>
    </form>
</div>

<!-- Custom Bootstrap Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Confirmation</h5>
                <button type="button" class="close" onclick="$('#confirmationModal').modal('hide')" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>                
            </div>
            <div class="modal-body" id="modalMessage">Are you sure?</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="$('#confirmationModal').modal('hide')">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmAction">Yes</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    function confirmUpdate(id) {
        document.getElementById('modalTitle').innerText = 'Update Confirmation';
        document.getElementById('modalMessage').innerText = 'Are you sure you want to update this procedure price?';

        document.getElementById('confirmAction').onclick = function() {
            document.getElementById('hidden_price_' + id).value = document.getElementById('price_' + id).value;
            document.getElementById('hidden_duration_' + id).value = document.getElementById('duration_' + id).value;
            document.getElementById('updateForm_' + id).submit();
        };

        $('#confirmationModal').modal('show');
    }

    function confirmDelete(id) {
        document.getElementById('modalTitle').innerText = 'Delete Confirmation';
        document.getElementById('modalMessage').innerText = 'Are you sure you want to delete this procedure price?';

        document.getElementById('confirmAction').onclick = function() {
            document.getElementById('deleteForm_' + id).submit();
        };

        $('#confirmationModal').modal('show');
    }

    // Auto-hide success message
    setTimeout(() => {
        let successMessage = document.getElementById('successMessage');
        if (successMessage) {
            successMessage.style.display = 'none';
        }
    }, 3000);
</script>

<!-- Bootstrap & jQuery (Ensure Bootstrap is included) -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

@endsection
