<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Skilloka Dashboard</title>

@vite(['resources/css/app.css','resources/js/app.js'])

<style>

.sidebar{
background:linear-gradient(180deg,#0f172a,#1e3a8a,#312e81);
color:white;
}

.logo-box{
display:flex;
align-items:center;
gap:10px;
padding:20px;
border-bottom:1px solid rgba(255,255,255,0.08);
}

.logo-icon{
width:38px;
height:38px;
border-radius:10px;
display:flex;
align-items:center;
justify-content:center;
background:linear-gradient(135deg,#667eea,#764ba2);
font-size:18px;
}

.logo-text{
font-size:18px;
font-weight:600;
}

.menu-item{
display:flex;
align-items:center;
gap:12px;
padding:12px 16px;
border-radius:10px;
color:#c7d2fe;
transition:.2s;
text-decoration:none;
}

.menu-item:hover{
background:rgba(255,255,255,0.08);
color:white;
}

.menu-icon{
width:18px;
text-align:center;
}

</style>

</head>



<body class="font-sans antialiased bg-gray-100">

<div class="flex h-screen">




<!-- SIDEBAR -->
<aside class="w-64 sidebar shadow-lg">


<div class="logo-box">

<div class="logo-icon">
📘
</div>

<div class="logo-text">
Skilloka
</div>

</div>




@php
$role = auth()->user()->role;
@endphp




<nav class="mt-6 px-3 space-y-2">




{{-- =====================
ADMIN LPK MENU
===================== --}}
@if($role == 'admin' || $role == 'admin_lpk')

<a href="{{ route('admin.dashboard') }}" class="menu-item">
<span class="menu-icon">📊</span>
Dashboard
</a>


<a href="{{ route('admin.profile') }}" class="menu-item">
<span class="menu-icon">🏢</span>
LPK Profile
</a>


<a href="{{ route('admin.courses.index') }}" class="menu-item">
<span class="menu-icon">📚</span>
Courses
</a>


<a href="{{ route('admin.students.index') }}" class="menu-item">
<span class="menu-icon">👨‍🎓</span>
Students
</a>


<a href="{{ route('admin.bookings.index') }}" class="menu-item">
<span class="menu-icon">📝</span>
Bookings
</a>


<a href="{{ route('admin.course-schedules.index') }}" class="menu-item">
<span class="menu-icon">📅</span>
Schedule
</a>

@endif






{{-- =====================
SUPER ADMIN MENU
===================== --}}
@if($role == 'super_admin')

<a href="{{ route('super.dashboard') }}" class="menu-item">
<span class="menu-icon">📊</span>
Dashboard
</a>


<a href="{{ route('super.tenants') }}" class="menu-item">
<span class="menu-icon">🏫</span>
LPK
</a>


<a href="{{ route('super.verifications') }}" class="menu-item">
<span class="menu-icon">✔</span>
Verification
</a>


<a href="{{ route('super.users') }}" class="menu-item">
<span class="menu-icon">👨‍💻</span>
Users
</a>


<a href="{{ route('super.finance') }}" class="menu-item">
<span class="menu-icon">💰</span>
Finance
</a>


<a href="{{ route('super.logs') }}" class="menu-item">
<span class="menu-icon">📑</span>
Logs
</a>


<a href="{{ route('super.settings') }}" class="menu-item">
<span class="menu-icon">⚙️</span>
Settings
</a>

@endif






<form method="POST" action="{{ route('logout') }}">

@csrf

<button class="menu-item w-full text-left text-red-300">

<span class="menu-icon">🚪</span>

Logout

</button>

</form>



</nav>


</aside>







<!-- CONTENT -->
<div class="flex-1 flex flex-col">


<header class="bg-white border-b px-6 py-4">

<h1 class="text-lg font-semibold text-gray-800">

@yield('title')

</h1>

</header>



<main class="flex-1 p-6 overflow-y-auto bg-gray-50">

@yield('content')

</main>



</div>



</div>



</body>

</html>