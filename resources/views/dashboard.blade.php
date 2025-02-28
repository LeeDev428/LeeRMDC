<x-app-layout>
    @section('title', 'Dashboard')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-blue-800 overflow-hidden shadow-sm sm:rounded-lg">
                <img src="{{ asset('img/udashboard.png') }}" alt="Description" loading="lazy">
                <div class="p-3 text-black-900 dark:text-black-100" style="background-color: rgb(187, 233, 233); font-size: 14px;">
                    {{ __("You're logged in, ".auth()->user()->name. "!") }}
                </div>
            </div>
        </div>
    </div>

    
    <!-- Automatically Display Notifications and Appointment Details in One Card -->
    <div class="py-1 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Notification and Appointment Combined -->
        @php
            $unreadNotifications = App\Models\Notification::where('user_id', Auth::id())
                ->where('status', 'unread')
                ->latest()
                ->first(); // Get the most recent unread notification

            $appointments = App\Models\Appointment::where('user_id', Auth::id())->latest()->first(); // Get the most recent appointment
        @endphp

        <!-- Display Notification and Appointment Details in One Card -->
        @if ($unreadNotifications || $appointments)
            <div class="bg-white dark:bg-gray-900 p-8 rounded-xl shadow-lg overflow-hidden">
                <!-- Invoice Title -->
                <div class="flex justify-center items-center h-auto">
                    <div class="text-center font-semibold text-3xl text-gray-800 dark:text-white">
                        <span class="text-blue-600">Your</span> Notification & Appointment
                    </div>
                </div>
<br>
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                    <!-- Notification Message -->
                    @if ($unreadNotifications)
                        <div class="mb-4">
                            <div class="text-xl font-medium text-gray-800 dark:text-white">
                                Notification
                            </div>
                            <div class="text-md text-gray-600 dark:text-gray-300 mt-2">
                                {{ $unreadNotifications->message }}
                            </div>
                            <div class="mt-2 text-xs text-gray-500">
                                <span class="font-semibold">Date:</span> {{ $unreadNotifications->created_at->format('m/d/Y H:i') }}
                            </div>
                            <div class="text-xs text-gray-400">
                                {{ $unreadNotifications->created_at->diffForHumans() }}
                            </div>
                        </div>
                    @else
                        <div class="font-medium text-gray-800 dark:text-white">
                            No unread notifications.
                        </div>
                    @endif
                </div>

                <div class="border-b border-gray-200 dark:border-gray-700 pb-6 mt-6">
                    <!-- Appointment Details -->

                    
                    @if ($appointments)
                        <div class="mb-4">
                            <div class="text-xl font-medium text-gray-800 dark:text-white">
                                Appointment
                            </div>
                            @if ($appointments->status == 'accepted')
                                <div class="text-md text-green-600 dark:text-green-400 mt-2">
                                    <strong>{{ $appointments->procedure }}</strong> has been accepted.
                                </div>
                            @elseif ($appointments->status == 'pending')
                                <div class="text-md text-yellow-600 dark:text-yellow-400 mt-2">
                                    Your appointment is still pending.
                                </div>
                            @elseif ($appointments->status == 'rejected')
                                <div class="text-md text-red-600 dark:text-red-400 mt-2">
                                    Unfortunately, your appointment has been rejected.
                                </div>
                            @else
                                <div class="text-md text-gray-500 dark:text-gray-400 mt-2">
                                    No status information available for your appointment.
                                </div>
                            @endif
                            <div class="mt-2 text-xs text-gray-500">
                                <span class="font-semibold">Scheduled at:</span> {{ $appointments->created_at->format('m/d/Y H:i') }}
                            </div>
                            <div class="text-xs text-gray-400">
                                {{ $appointments->created_at->diffForHumans() }}
                            </div>
                        </div>
                    @else
                        <div class="font-medium text-gray-800 dark:text-white mt-2">
                            No appointments found.
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="p-6 bg-white dark:bg-gray-900 rounded-xl shadow-lg mb-4">
                <div class="font-medium text-gray-800 dark:text-white text-center">
                    No recent notifications or appointments.
                </div>
            </div>
        @endif
        
    </div>

    <section>
        <div class="py-10">
            <div class="max-w-6.5xl mx-auto sm:px-6 lg:px-8 bg-white dark:bg-blue-800 overflow-hidden shadow-sm" style="border-radius:10px">
                <br>
                <div class="flex justify-center items-center h-auto">
                    <div class="text-center font-semibold text-3xl text-gray-800 dark:text-white">
                        <span class="text-blue-600">Our</span> Services
                    </div>
                </div>
<br>
                <div class="text-center" style="font-family: 'Alef', 'Ploni Bold', sans-serif; font-weight: bold; letter-spacing: 2px; font-size: 20px;">
                    • General Dentistry
                </div>

                <div class="grid grid-cols-4 gap-6 p-4">
                    <div class="p-4 rounded-lg text-center cursor-pointer transition transform hover:scale-105" style="background-color: rgb(25, 221, 221);">
                        <img src="{{ asset('btndashboard/btn1.png') }}" alt="button" loading="lazy" class="w-15 h-15 mx-auto mb-2"> 
                    </div>

                    <div class="p-4 rounded-lg text-center cursor-pointer transition transform hover:scale-105" style="background-color: rgb(25, 221, 221);">
                        <img src="{{ asset('btndashboard/button.gif') }}" alt="button" loading="lazy" class="w-20 h-15 mx-auto mb-2">
                    </div>

                    <div class="p-4 rounded-lg text-center cursor-pointer transition transform hover:scale-105" style="background-color: rgb(25, 221, 221);">
                        <img src="{{ asset('btndashboard/button.gif') }}" alt="button" loading="lazy" class="w-20 h-15 mx-auto mb-2">
                    </div>

                    <div class="p-4 rounded-lg text-center cursor-pointer transition transform hover:scale-105" style="background-color: rgb(25, 221, 221);">
                        <img src="{{ asset('btndashboard/button.gif') }}" alt="button" loading="lazy" class="w-20 h-15 mx-auto mb-2">
                    </div>

                    <div class="p-4 rounded-lg text-center cursor-pointer transition transform hover:scale-105" style="background-color: rgb(25, 221, 221);">
                        <img src="{{ asset('btndashboard/button.gif') }}" alt="button" loading="lazy" class="w-20 h-15 mx-auto mb-2">
                    </div>
                </div>
                <br>
            </div>
        </div>
    </section>

    <section>
        <div class="py-1">
            <div class="max-w-6.5xl mx-auto sm:px-6 lg:px-8 bg-white dark:bg-blue-800 overflow-hidden shadow-sm" style="border-radius:10px">
                <br>

                <div class="text-center" style="font-family: 'Alef', 'Ploni Bold', sans-serif; font-weight: bold; letter-spacing: 2px; font-size: 20px;">
                    • Restorative Services
                </div>

                <div class="grid grid-cols-4 gap-5 p-4">
                    <div class="p-4 rounded-lg text-center cursor-pointer transition transform hover:scale-105" style="background-color: rgb(25, 221, 221);">
                        <img src="{{ asset('btndashboard/button.gif') }}" alt="button" loading="lazy" class="w-20 h-15 mx-auto mb-2">
                    </div>

                    <div class="p-4 rounded-lg text-center cursor-pointer transition transform hover:scale-105" style="background-color: rgb(25, 221, 221);">
                        <img src="{{ asset('btndashboard/button.gif') }}" alt="button" loading="lazy" class="w-20 h-15 mx-auto mb-2">
                    </div>

                    <div class="p-4 rounded-lg text-center cursor-pointer transition transform hover:scale-105" style="background-color: rgb(25, 221, 221);">
                        <img src="{{ asset('btndashboard/button.gif') }}" alt="button" loading="lazy" class="w-20 h-15 mx-auto mb-2">
                    </div>

                    <div class="p-4 rounded-lg text-center cursor-pointer transition transform hover:scale-105" style="background-color: rgb(25, 221, 221);">
                        <img src="{{ asset('btndashboard/button.gif') }}" alt="button" loading="lazy" class="w-20 h-15 mx-auto mb-2">
                    </div>
                </div>
                <br>
            </div>
        </div>
    </section>

</x-app-layout>
