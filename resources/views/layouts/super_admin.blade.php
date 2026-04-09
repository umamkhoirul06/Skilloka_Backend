<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Skilloka Super Admin</title>

@vite(['resources/css/app.css','resources/js/app.js'])

</head>



<body class="bg-gray-900 text-gray-100">

<div class="flex h-screen">


<!-- SIDEBAR -->
<aside class="w-64 bg-gray-800 border-r border-gray-700">

<div class="p-4 text-xl font-bold text-blue-400">

SKILLOKA GOD VIEW

</div>



<nav class="space-y-2 px-4">

<a href="{{ route('super.dashboard') }}"
class="block px-4 py-2 rounded hover:bg-gray-700">

Dashboard

</a>



<a href="{{ route('super.tenants') }}"
class="block px-4 py-2 rounded hover:bg-gray-700">

LPK

</a>



<a href="{{ route('super.verifications') }}"
class="block px-4 py-2 rounded hover:bg-gray-700">

Verification

</a>



<a href="{{ route('super.users') }}"
class="block px-4 py-2 rounded hover:bg-gray-700">

Users

</a>



<a href="{{ route('super.finance') }}"
class="block px-4 py-2 rounded hover:bg-gray-700">

Finance

</a>



<a href="{{ route('super.logs') }}"
class="block px-4 py-2 rounded hover:bg-gray-700">

Logs

</a>



<a href="{{ route('super.settings') }}"
class="block px-4 py-2 rounded hover:bg-gray-700">

Settings

</a>



<!-- LOGOUT -->
<form method="POST" action="{{ route('logout') }}">

@csrf

<button
class="w-full text-left px-4 py-2 text-red-400 hover:bg-gray-700">

Logout

</button>

</form>


</nav>


</aside>



<!-- CONTENT -->
<div class="flex-1 p-6 overflow-y-auto">

<h1 class="text-2xl font-bold mb-6">

@yield('title')

</h1>



@yield('content')


</div>


</div>


</body>

</html>