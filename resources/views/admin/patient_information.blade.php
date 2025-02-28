@extends('layouts.admin')

@section('title', 'Patient Information')

@section('content')
<div class="container">
    <br>
    <h1>Patient Information</h1>
    <br>

    <!-- Search Bar -->
    <form method="GET" action="{{ route('admin.patient_information') }}" class="mb-3">
        <div class="input-group">
            <input type="text" class="form-control" name="search" placeholder="Search by ID, Name, Email, Usertype..." value="{{ request('search') }}">
            <button class="btn btn-primary" type="submit">Search</button>
            <!-- Clear Button -->
            <a href="{{ route('admin.patient_information') }}" class="btn btn-secondary">Clear</a>
        </div>
    </form>

    @if($user->isEmpty())
        <p>No Information was found.</p>
    @else
        <table class="table" style="border-color: black; border-collapse: separate; border-spacing: 0px;">
            <thead>
                <tr>
                    <th style="padding: 15px 10px">Profile Image</th> <!-- New column for image -->
                    <th style="padding: 15px 10px">ID</th>
                    <th style="padding: 15px 10px">Name</th>
                    <th style="padding: 15px 10px">Email</th>
                    <th style="padding: 15px 10px">Usertype</th>
                    <th style="padding: 15px 10px">Created at</th>
                    <th style="padding: 15px 10px">Updated at</th>
                </tr>
            </thead>
            <tbody>
                @foreach($user as $u)
                    <tr>
                        <td style="padding: 15px 10px">
                            @if($u->avatar)
                                <img src="{{ Storage::url($u->avatar) }}" alt="Profile Image" style="width: 50px; height: 50px; border-radius: 50%;">
                            @else
                                <img src="https://via.placeholder.com/50" alt="Default Profile Image" style="width: 50px; height: 50px; border-radius: 50%;">
                            @endif
                        </td>
                        <td style="padding: 15px 10px">{{ $u->id }}</td>
                        <td style="padding: 15px 10px">{{ $u->name }}</td>
                        <td style="padding: 15px 10px">{{ $u->email }}</td>
                        <td style="padding: 15px 10px">{{ $u->usertype }}</td>
                        <td style="padding: 15px 10px">{{ $u->created_at }}</td>
                        <td style="padding: 15px 10px">{{ $u->updated_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
