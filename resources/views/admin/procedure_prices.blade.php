@extends('layouts.admin')

@section('title', 'Procedure Prices')

@section('content')
<div class="container">
    <h2>Update Procedure Prices</h2>

    <!-- Display success message -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Procedure Prices Form -->
    @foreach($procedures as $procedure)
        <form action="{{ route('admin.procedure_prices.update', ['id' => $procedure->id]) }}" method="POST">
            @csrf
            @method('PUT') <!-- Ensure this is a PUT method for updating -->

            <div class="form-group">
                <label for="procedure_{{ $procedure->id }}">Procedure: {{ $procedure->procedure_name }}</label>
                <input type="text" name="price" id="price_{{ $procedure->id }}" value="{{ old('price', $procedure->price) }}" class="form-control" required>
                <input type="text" name="duration" id="duration_{{ $procedure->id }}" value="{{ old('duration', $procedure->duration) }}" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Update Price for {{ $procedure->procedure_name }}</button>
        </form>
        <br> <!-- Space between the forms -->
    @endforeach

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
@endsection
