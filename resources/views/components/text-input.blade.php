@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} 
       class="border-gray-400 dark:text-gray-100 
              focus:border-[#007BFF] dark:focus:border-[#007BFF] 
              focus:ring-[#007BFF] dark:focus:ring-[#007BFF] 
              rounded-md shadow-sm w-full" 
       {{ $attributes }}>
