@extends('layouts.admin')

@section('title', 'Declined Appointments')

@section('content')
<div class="container">
    <br>
    <h2>Declined Appointments</h2>
    <br>

    @if(session('success'))
        <div class="alert alert-success" id="successMessage">
            {{ session('success') }}
        </div>
    @endif

    @if($declinedAppointments->isEmpty())
        <div class="alert alert-warning">No declined appointments found.</div>
    @else
        <!-- DELETE ALL BUTTON -->
        <form id="deleteForm" action="{{ route('appointments.deleteAllDeclined') }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="button" class="btn btn-danger mb-3" onclick="showDeleteMessage()">Delete All Declined</button>
        </form>

        

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Procedure</th>
                    <th>Create at</th>
                    <th>Declined at</th>
                </tr>
            </thead>
            <tbody>
                @foreach($declinedAppointments as $appointment)
                <tr>
                    <td>{{ $appointment->user_id }}</td>
                    <td>{{ $appointment->title }}</td>
                    <td>{{ $appointment->procedure }}</td>
                    <td>{{ $appointment->created_at->format('Y-m-d H:i') }}</td>
                    <td>{{ $appointment->updated_at->format('Y-m-d H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<!-- CUSTOM DELETE CONFIRMATION (Centered) -->
<div id="deleteMessage" class="delete-popup">
    <p>Delete all declined appointments?</p>
    <button onclick="confirmDelete()" class="btn btn-danger btn-sm">Yes</button>
    <button onclick="closeMessage()" class="btn btn-secondary btn-sm">No</button>
</div>

<!-- STYLES -->
<style>
    .delete-popup {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        padding: 15px 20px;
        border: 1px solid red;
        box-shadow: 2px 2px 10px rgba(0,0,0,0.2);
        text-align: center;
        border-radius: 8px;
        z-index: 1000;
    }
    .btn-sm {
        padding: 6px 12px;
        margin: 5px;
        font-size: 14px;
    }
</style>

<!-- SCRIPT -->
<script>
    setTimeout(() => {
        let successMessage = document.getElementById('successMessage');
        if (successMessage) {
            successMessage.style.display = 'none';
        }
    }, 3000);

    function showDeleteMessage() {
        document.getElementById("deleteMessage").style.display = "block";
    }

    function closeMessage() {
        document.getElementById("deleteMessage").style.display = "none";
    }

    function confirmDelete() {
        document.getElementById("deleteForm").submit();
    }
</script>
@endsection
