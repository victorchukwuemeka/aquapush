@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">All Users</h2>

    <div class="overflow-x-auto bg-white shadow rounded-xl border border-gray-100">
        <table class="min-w-full border-collapse">
            <thead>
                <tr class="bg-gray-50 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">
                    <th class="px-6 py-3">ID</th>
                    <th class="px-6 py-3">Name</th>
                    <th class="px-6 py-3">Email</th>
                    <th class="px-6 py-3">Created</th>
                    <th class="px-6 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($users as $user)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-3 text-gray-700">{{ $user->id }}</td>
                    <td class="px-6 py-3 font-medium text-gray-900">{{ $user->name }}</td>
                    <td class="px-6 py-3 text-gray-700">{{ $user->email }}</td>
                    <td class="px-6 py-3 text-gray-600">{{ $user->created_at->format('Y-m-d') }}</td>
                    <td class="px-6 py-3 text-center">
                        <a href="{{ url('admin/users/edit/'.$user->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">Edit</a>
                        <span class="mx-2 text-gray-400">|</span>
                        <form action="{{ url('admin/users/delete/'.$user->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium" onclick="return confirm('Delete this user?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
