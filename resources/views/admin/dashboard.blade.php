@extends('layouts.app')
@section('content')
<div class="min-h-screen bg-gray-50 text-gray-900">
    {{-- Topbar --}}
    <div class="bg-white shadow p-4 flex justify-between items-center">
        <h1 class="text-2xl font-semibold">Welcome Admin {{ Auth::user()->name }}</h1>
        <a href="{{ url('/logout') }}" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">Logout</a>
    </div>

    {{-- Quick Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-6">
        <div class="bg-white rounded-xl p-4 shadow text-center">
            <h3 class="text-gray-500 text-sm">Total Users</h3>
            <p class="text-2xl font-bold">{{-- $userCount --}} 10</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow text-center">
            <h3 class="text-gray-500 text-sm">Deployments</h3>
            <p class="text-2xl font-bold">{{-- $deploymentCount --}} 2</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow text-center">
            <h3 class="text-gray-500 text-sm">Active Droplets</h3>
            <p class="text-2xl font-bold">{{-- $dropletCount --}} 4</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow text-center">
            <h3 class="text-gray-500 text-sm">Monthly Cost</h3>
            <p class="text-2xl font-bold">${{-- $monthlyCost --}} 45</p>
        </div>
    </div>

    {{-- Management Panels --}}
    <div class="p-6 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        <a href="{{ route('admin.user-index')}}" class="bg-white hover:bg-gray-100 p-6 rounded-xl shadow transition">
            <h2 class="text-xl font-semibold mb-2">👥 Manage Users</h2>
            <p class="text-sm text-gray-600">View, edit, delete, or promote users.</p>
        </a>

        <a href="{{ url('/admin/projects') }}" class="bg-white hover:bg-gray-100 p-6 rounded-xl shadow transition">
            <h2 class="text-xl font-semibold mb-2">🚀 Projects & Deployments</h2>
            <p class="text-sm text-gray-600">Monitor and manage user deployments.</p>
        </a>

        <a href="{{ url('/admin/servers') }}" class="bg-white hover:bg-gray-100 p-6 rounded-xl shadow transition">
            <h2 class="text-xl font-semibold mb-2">🖥️ All Servers</h2>
            <p class="text-sm text-gray-600">View and manage all DigitalOcean droplets.</p>
        </a>

        <a href="{{ url('/admin/settings') }}" class="bg-white hover:bg-gray-100 p-6 rounded-xl shadow transition">
            <h2 class="text-xl font-semibold mb-2">⚙️ Site Settings</h2>
            <p class="text-sm text-gray-600">Control global configuration and API keys.</p>
        </a>

        <a href="{{ url('/admin/logs') }}" class="bg-white hover:bg-gray-100 p-6 rounded-xl shadow transition">
            <h2 class="text-xl font-semibold mb-2">🧾 Activity Logs</h2>
            <p class="text-sm text-gray-600">Track platform activity and errors.</p>
        </a>

        <a href="{{ url('/admin/analytics') }}" class="bg-white hover:bg-gray-100 p-6 rounded-xl shadow transition">
            <h2 class="text-xl font-semibold mb-2">📊 Analytics</h2>
            <p class="text-sm text-gray-600">View usage trends and growth stats.</p>
        </a>
    </div>
</div>
@endsection