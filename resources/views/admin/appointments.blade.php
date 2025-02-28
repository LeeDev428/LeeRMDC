@extends('layouts.admin')

@section('title', 'Upcoming Appointments')

@section('content')
<div class="container mt-4">
    <h1>Upcoming Appointments</h1>
    
    <!-- Calendar Container -->
    <div id="calendar-container">
        <div id="calendar"></div>
    </div>
</div>

<!-- Modal for Viewing Appointments -->
<div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="appointmentModalLabel" style="color: black;">
                    Appointments on <span id="modal-date"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Title</th>
                            <th>Procedure</th>
                            <th>Start Time</th>
                        </tr>
                    </thead>
                    <tbody id="appointment-data">
                        <!-- Filled dynamically -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- FullCalendar & Bootstrap -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom Styles -->
<style>
    /* Set background color to #83BACA */
    body {
        background-color: #83BACA !important;
    }

    /* Calendar background */
    #calendar-container {
        background: none !important;
        padding: 15px;
    }

    /* Center the event text in the calendar */
    .fc-event-title {
        color: black !important;
        text-align: center !important;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* Calendar events */
    .fc-event {
        background: rgba(226, 25, 35, 0.8) !important;
        border: none;
        color: black !important;
        font-weight: bold;
        border-radius: 4px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* White close button in modal */
    .btn-close {
        filter: invert(1) !important;
    }

    /* ✅ FIX: Make the entire modal background #83BACA */
    .modal-content {
        background-color: #83BACA !important; /* Set full modal background */
        color: black !important; /* Ensure all text inside is black */
    }

    /* ✅ FIX: Ensure all text inside modal is black */
    .modal-header, 
    .modal-body, 
    .modal-footer {
        background-color: #83BACA !important;
        color: black !important;
    }

    /* ✅ FIX: Ensure table inside modal also has #83BACA background */
    .modal-body .table {
        background-color: #83BACA !important;
    }

    .modal-body .table th,
    .modal-body .table td {
        background-color: #83BACA !important;
        color: black !important; /* Force text to black */
        border-color: rgba(0, 0, 0, 0.2) !important;
    }

    /* Optional: Add border to make table more visible */
    .modal-body .table th {
        border-bottom: 2px solid black !important;
    }
</style>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var appointments = @json($acceptedAppointments);
    
        // Group appointments by date
        var eventCounts = {};
        appointments.forEach(app => {
            let date = app.start.split("T")[0]; // Extract YYYY-MM-DD format
            if (!eventCounts[date]) {
                eventCounts[date] = { count: 0, appointments: [] };
            }
            eventCounts[date].count++;
            eventCounts[date].appointments.push(app);
        });
    
        // Create event list with appointment count
        var eventList = Object.keys(eventCounts).map(date => ({
            title: `${eventCounts[date].count}`,
            start: date,
            allDay: true,
            extendedProps: { appointments: eventCounts[date].appointments }
        }));
    
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            themeSystem: 'bootstrap',
            headerToolbar: {
                left: '', // 🔥 Removed prev, next, today buttons
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: eventList,
    
            eventClick: function(info) {
                let selectedDate = info.event.startStr.split("T")[0];
                document.getElementById("modal-date").innerText = selectedDate;
    
                let tableBody = document.getElementById("appointment-data");
                tableBody.innerHTML = "";
    
                let appointments = info.event.extendedProps.appointments;
                if (appointments.length > 0) {
                    appointments.forEach((app, index) => {
                        let row = `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${app.title}</td>
                                <td>${app.procedure}</td>
                                <td>${app.start}</td>
                            </tr>
                        `;
                        tableBody.innerHTML += row;
                    });
                } else {
                    tableBody.innerHTML = "<tr><td colspan='4' class='text-center'>No appointments found for this date.</td></tr>";
                }
    
                // Open modal
                new bootstrap.Modal(document.getElementById('appointmentModal')).show();
            }
        });
    
        calendar.render();
    });
    </script>
    

@endsection
