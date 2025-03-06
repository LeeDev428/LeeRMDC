@extends('layouts.admin')

@section('title', 'Pending Appointments')



@section('content')
<div class="container" style="margin-bottom: 50px;"><br>
    <h1 style="padding-bottom: 30px;">Pending Appointments</h1>

    <!-- Button to navigate to Upcoming Appointments -->
    <a href="{{ route('admin.appointments') }}" class="btn btn-primary" style="margin-bottom: 20px;">Go to Upcoming Appointments</a>

    <a href="{{ route('admin.patient_information') }}" class="btn btn-primary" style="margin-bottom: 20px;">See Patient Information</a>
<br>
    <a href="{{ route('admin.declined_appointments') }}" class="btn btn-danger" style="margin-bottom: 20px;">See Declined Appointments</a>

    <br>

    @if($appointments->isEmpty())
        <p>No pending appointments found.</p>
    @else
        <table class="table" style="border-color: black; border-collapse: collapse; width: 100%;">
            <thead>
                <tr>

                    <th style="padding: 20px; width: 10%; white-space: nowrap;">Actions</th>
                    <th style="padding: 20px; width: 10%; white-space: nowrap;">No.</th>
                    <th style="padding: 20px; width: 10%; white-space: nowrap;">ID</th> 
                    <th style="padding: 20px; width: 10%; white-space: nowrap;">Valid ID</th>
                    <th style="padding: 20px; width: 10%; white-space: nowrap;">Status</th>
                    <th style="padding: 20px; width: 15%; white-space: nowrap;">Procedure</th>
                    <th style="padding: 20px; width: 10%; white-space: nowrap;">Start Time</th>
                    <th style="padding: 20px; width: 10%; white-space: nowrap;">Created At</th>
                    <th style="padding: 20px; width: 10%; white-space: nowrap;">Updated At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $appointment)
                    <tr>
                        <td style="padding: 10px; white-space: nowrap;">
                            <!-- Accept and Decline Forms -->
                           <!-- Accept Button -->
<!-- Accept Button -->
<form id="acceptForm" action="{{ route('appointment.messageFromAdmin', ['id' => $appointment->id, 'action' => 'accept']) }}" method="POST" style="display:inline;">
    @csrf
    <button type="submit" class="btn btn-success">Accept</button>
</form>

<!-- JavaScript for Accept (Placed Below the Button) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert for better popups -->

<script>
    $(document).ready(function () {
        $('#acceptForm').on('submit', function (e) {
            e.preventDefault(); // Prevent default form submission

            Swal.fire({
                title: "Accept Appointment?",
                text: "Are you sure you want to accept this appointment?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Yes, Accept",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Processing...",
                        text: "Please wait...",
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: $(this).attr('action'),
                        type: "POST",
                        data: $(this).serialize(),
                        dataType: "json",
                        success: function (response) {
                            Swal.fire({
                                title: "Success!",
                                text: response.message,
                                icon: "success",
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload(); // Reload page after success
                            });
                        },
                        error: function (xhr) {
                            let errorMessage = xhr.responseJSON?.message || "An error occurred.";
                            Swal.fire("Error!", errorMessage, "error");
                        }
                    });
                }
            });
        });
    });
</script>



 <!-- Decline Button -->
<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#declineModal" data-appointment-id="{{ $appointment->id }}">
    Decline
</button>

<!-- Decline Confirmation Modal -->
<div class="modal fade" id="declineModal" tabindex="-1" aria-labelledby="declineModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="declineModalLabel">Decline Appointment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="declineForm" action="{{ route('appointment.messageFromAdmin', ['id' => ':appointment_id', 'action' => 'decline']) }}" method="POST">
                    @csrf
                    <input type="hidden" name="appointment_id" id="appointment_id">
                    <label for="message">Reason for Declining:</label>
                    <textarea name="message" id="message" class="form-control" required></textarea>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger" form="declineForm">Decline</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Decline Button -->
<script>
    $(document).ready(function () {
        var isSubmitting = false;

        $('button[data-bs-toggle="modal"]').on('click', function () {
            var appointmentId = $(this).data('appointment-id');
            $('#appointment_id').val(appointmentId);
            var actionUrl = '{{ route("appointment.messageFromAdmin", ["id" => ":appointment_id", "action" => "decline"]) }}';
            actionUrl = actionUrl.replace(':appointment_id', appointmentId);
            $('#declineForm').attr('action', actionUrl);
        });

        $('#declineForm').off('submit').on('submit', function (e) {
            e.preventDefault();

            if (isSubmitting) return;
            isSubmitting = true;

            let button = $(this).find("button[type='submit']");
            button.prop("disabled", true);
            
            Swal.fire({
                title: "Processing...",
                text: "Please wait...",
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                success: function (response) {
                    Swal.fire({
                        title: "Success!",
                        text: response.message,
                        icon: "success",
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        $('#declineModal').modal('hide');
                        location.reload();
                    });
                },
                error: function (xhr) {
                    let errorMessage = xhr.responseJSON?.message || "An error occurred.";
                    Swal.fire("Error!", errorMessage, "error");
                    button.prop("disabled", false);
                },
                complete: function() {
                    isSubmitting = false;
                    button.prop("disabled", false);
                }
            });
        });
    });
</script>


 

                            
                            
                        </td>    
                        <td style="padding: 20px; white-space: nowrap;font-weight: bold;font-size: 17px;">{{ $appointment->id }}</td>      
                        <td style="padding: 20px; white-space: nowrap;font-weight: bold;font-size: 17px;">{{ $appointment->user_id }}</td>  
                        <td style="padding: 15px 10px">
                            @if($appointment->image_path)
                                <img src="{{ Storage::url($appointment->image_path) }}" 
                                     alt="Valid ID" 
                                     style="width: 60px; height: 50px; border-radius: 5px; cursor: pointer; transition: transform 0.2s;" 
                                     onclick="zoomImage(this)">
                            @else
                                <img src="https://via.placeholder.com/100" 
                                     alt="Default ID Image" 
                                     style="width: 60px; height: 50px; border-radius: 5px;">
                            @endif
                        </td>
                                             
                        <td style="padding: 20px; white-space: nowrap;font-weight: bold;font-size: 17px;">"{{ $appointment->status }}"</td>
                        <td style="padding: 20px; white-space: nowrap;">{{ $appointment->procedure }}</td>
                        <td style="padding: 20px; white-space: nowrap;">{{ $appointment->start }}</td>
                        <td style="padding: 20px; white-space: nowrap;">{{ $appointment->created_at }}</td>
                        <td style="padding: 20px; white-space: nowrap;">{{ $appointment->updated_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
<script>
    function zoomImage(img) {
        // Create a full-screen overlay with the image
        let overlay = document.createElement("div");
        overlay.style.position = "fixed";
        overlay.style.top = "0";
        overlay.style.left = "0";
        overlay.style.width = "100%";
        overlay.style.height = "100%";
        overlay.style.backgroundColor = "rgba(0, 0, 0, 0.8)";
        overlay.style.display = "flex";
        overlay.style.justifyContent = "center";
        overlay.style.alignItems = "center";
        overlay.style.zIndex = "9999";
        
        // Create an enlarged image
        let zoomedImg = document.createElement("img");
        zoomedImg.src = img.src;
        zoomedImg.style.maxWidth = "90%";
        zoomedImg.style.maxHeight = "90%";
        zoomedImg.style.borderRadius = "5px";
        zoomedImg.style.boxShadow = "0px 0px 20px rgba(255, 255, 255, 0.5)";
        
        // Close overlay when clicked
        overlay.onclick = function() {
            document.body.removeChild(overlay);
        };
        
        // Append image to overlay
        overlay.appendChild(zoomedImg);
        document.body.appendChild(overlay);
    }
    </script>
    