<x-app-layout>
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Manajemen User</h1>

        <a href="{{ route('users.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">
            Tambah User
        </a>

        @if(session('success'))
            <p class="mt-4 text-green-600">{{ session('success') }}</p>
        @endif

        <table class="w-full mt-6 border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border p-2">Nama</th>
                    <th class="border p-2">Email</th>
                    <th class="border p-2">Phone</th>
                    <th class="border p-2">Role</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td class="border p-2">{{ $user->name }}</td>
                        <td class="border p-2">{{ $user->email }}</td>
                        <td class="border p-2">{{ $user->phone }}</td>
                        <td class="border p-2">{{ $user->getRoleNames()->implode(', ') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>