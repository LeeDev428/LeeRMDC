@extends('layouts.admin')

@section('title', 'Pending Appointments')



@section('content')
<div class="container" style="margin-bottom: 50px;"><br>
    <h1 style="padding-bottom: 30px;">Pending Appointments</h1>

    <!-- Button to navigate to Upcoming Appointments -->
    <a href="{{ route('admin.appointments') }}" class="btn btn-primary" style="margin-bottom: 20px;">Go to Upcoming Appointments</a>

    <a href="{{ route('admin.patient_information') }}" class="btn btn-primary" style="margin-bottom: 20px;">See Patient Information</a>

    

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
                            <form action="{{ route('appointment.handleAction', ['id' => $appointment->id, 'action' => 'accept']) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-success">Accept</button>
                            </form>

                            <form action="{{ route('appointment.handleAction', ['id' => $appointment->id, 'action' => 'decline']) }}" method="POST" style="display:inline;">
                                @csrf
                                <textarea name="decline_reason" placeholder="Enter decline reason" rows="3"></textarea>
                                <button type="submit" class="btn btn-danger">Decline</button>
                            </form>
                            
                            
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
                                             
                        <td style="padding: 20px; white-space: nowrap;font-weight: bold;font-size: 17px;">{{ $appointment->status }}</td>
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
    