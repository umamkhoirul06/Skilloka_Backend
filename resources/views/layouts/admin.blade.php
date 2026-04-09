<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Skilloka Admin LPK</title>

@vite(['resources/css/app.css','resources/js/app.js'])

</head>



<body class="font-sans antialiased bg-gray-100">

<div class="flex h-screen">


<!-- SIDEBAR -->
<aside class="w-64 bg-slate-900 text-white">

<div class="flex items-center justify-center h-16 bg-slate-800 shadow-md">

<span class="text-xl font-bold text-blue-400">

SKILLOKA LPK

</span>

</div>



<nav class="mt-5 px-4 space-y-2">


<a href="{{ route('admin.dashboard') }}"
class="block px-4 py-2 rounded hover:bg-slate-700">

Dashboard

</a>



<a href="{{ route('admin.profile') }}"
class="block px-4 py-2 rounded hover:bg-slate-700">

LPK Profile

</a>



<a href="{{ route('admin.courses.index') }}"
class="block px-4 py-2 rounded hover:bg-slate-700">

Courses

</a>



<a href="{{ route('admin.students.index') }}"
class="block px-4 py-2 rounded hover:bg-slate-700">

Students

</a>



<a href="{{ route('admin.bookings.index') }}"
class="block px-4 py-2 rounded hover:bg-slate-700">

Bookings

</a>



<a href="{{ route('admin.course-schedules.index') }}"
class="block px-4 py-2 rounded hover:bg-slate-700">

Schedule

</a>



<!-- LOGOUT -->
<form method="POST" action="{{ route('logout') }}">

@csrf

<button
class="w-full text-left px-4 py-2 text-red-400 hover:bg-slate-700">

Logout

</button>

</form>


</nav>


</aside>



<!-- CONTENT -->
<div class="flex-1 flex flex-col">


<header class="bg-white shadow px-6 py-4">

<h1 class="text-xl font-semibold">

@yield('title')

</h1>

</header>



<main class="flex-1 p-6 overflow-y-auto">

@yield('content')

</main>



</div>


</div>


</body>

</html>