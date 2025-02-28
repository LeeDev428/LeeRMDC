<style>
    .custom-button {
        padding: 10px 20px;
        background-color: #007BFF;
        color: white;
        border: none;
        border-radius: 9px;
        cursor: pointer;
        transition: background-color 0.6s ease;
        text-decoration: none;
        border: 1px solid #007BFF;
    }
    
    .custom-button:hover {
        background-color: white; /* Change the background color on hover */
            color: #007BFF;
    }
</style>

<button class="custom-button">
    {{ $slot }}
</button>
