<x-app-layout>
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Tambah User</h1>

        <form method="POST" action="{{ route('users.store') }}">
            @csrf

            <div class="mb-4">
                <label>Nama</label>
                <input type="text" name="name" class="w-full border p-2" required>
            </div>

            <div class="mb-4">
                <label>Email</label>
                <input type="email" name="email" class="w-full border p-2" required>
            </div>

            <div class="mb-4">
                <label>No HP</label>
                <input type="text" name="phone" class="w-full border p-2">
            </div>

            <div class="mb-4">
                <label>Password</label>
                <input type="password" name="password" class="w-full border p-2" required>
            </div>

            <div class="mb-4">
                <label>Role</label>
                <select name="role" class="w-full border p-2" required>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                Simpan
            </button>
        </form>
    </div>
</x-app-layout>