{{-- resources/views/alumni/create.blade.php --}}
<x-app-layout>
    <div class="w-full py-2">

        @if(session('success'))
            <div class="mb-4 p-4 rounded-xl bg-green-50 text-green-700 text-sm font-semibold">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 p-4 rounded-xl bg-red-50 text-red-700 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            {{-- Header --}}
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-start gap-4">
                    <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center text-white">
                        <i data-lucide="user-plus" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Tambah Data Alumni</h2>
                        <p class="text-gray-500 mt-1 text-sm">Input manual atau upload file CSV</p>
                    </div>
                </div>

                <a href="{{ route('admin.alumni.index') }}"
                   class="inline-flex items-center gap-2 border border-gray-200 hover:bg-gray-50 text-gray-600 px-4 py-2.5 rounded-xl text-sm font-semibold transition">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Kembali
                </a>
            </div>

            {{-- Tab Switcher --}}
            <div class="px-5 pt-5 flex gap-2">
                <button onclick="switchTab('manual')" id="btn-manual"
                    class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold transition bg-blue-600 text-white">
                    <i data-lucide="pencil-line" class="w-4 h-4"></i>
                    <span>Input Manual</span>
                </button>

                <button onclick="switchTab('import')" id="btn-import"
                    class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold transition bg-gray-100 text-gray-600 hover:bg-gray-200">
                    <i data-lucide="upload" class="w-4 h-4"></i>
                    <span>Upload Dokumen</span>
                </button>
            </div>

            <div class="p-5">

                {{-- TAB: Input Manual --}}
                <div id="tab-manual">
                    <form action="{{ route('admin.alumni.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       name="nama"
                                       value="{{ old('nama') }}"
                                       placeholder="Masukkan nama lengkap"
                                       class="w-full rounded-xl border-gray-200 text-sm px-4 py-2.5 focus:ring-blue-500 focus:border-blue-500 @error('nama') border-red-400 @enderror">
                                @error('nama')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                    Jurusan <span class="text-red-500">*</span>
                                </label>
                                <select name="jurusan_id"
                                        class="w-full rounded-xl border-gray-200 text-sm px-4 py-2.5 focus:ring-blue-500 focus:border-blue-500 @error('jurusan_id') border-red-400 @enderror">
                                    <option value="">-- Pilih Jurusan --</option>
                                    @foreach($jurusans as $jurusan)
                                        <option value="{{ $jurusan->id }}"
                                            {{ old('jurusan_id') == $jurusan->id ? 'selected' : '' }}>
                                            {{ $jurusan->nama_jurusan }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jurusan_id')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                    Angkatan <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       name="angkatan"
                                       value="{{ old('angkatan') }}"
                                       placeholder="Contoh: 2022"
                                       class="w-full rounded-xl border-gray-200 text-sm px-4 py-2.5 focus:ring-blue-500 focus:border-blue-500 @error('angkatan') border-red-400 @enderror">
                                @error('angkatan')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                        <div class="mt-5 flex gap-2">
                            <button type="submit"
                                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition">
                                <i data-lucide="save" class="w-4 h-4"></i>
                                Simpan
                            </button>
                            <a href="{{ route('admin.alumni.index') }}"
                               class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-600 px-6 py-2.5 rounded-xl text-sm font-semibold transition">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>

                {{-- TAB: Upload CSV --}}
                <div id="tab-import" class="hidden">

                    {{-- Info Format --}}
                    <div class="mb-4 p-4 rounded-xl bg-blue-50 border border-blue-100 text-sm text-blue-700">
                        <p class="font-semibold mb-2">Format dokumen yang diperlukan:</p>
                        <code class="block bg-white rounded-lg px-3 py-2 text-xs text-gray-700 border border-blue-100 leading-6">
                            nama,jurusan,angkatan<br>
                            Budi Santoso,Teknik Informatika,2022<br>
                            Siti Rahayu,Manajemen,2021
                        </code>
                        <p class="mt-2 text-xs text-blue-600">
                            ⚠️ Nama jurusan harus sama persis dengan data jurusan yang tersedia.
                        </p>
                    </div>

                    {{-- Download Template --}}
                    <a href="{{ route('admin.alumni.template') }}"
                       class="inline-flex items-center gap-2 border border-green-500 text-green-600 hover:bg-green-50 px-4 py-2.5 rounded-xl text-sm font-semibold transition mb-4">
                        <i data-lucide="download" class="w-4 h-4"></i>
                        Download Template
                    </a>

                    <form action="{{ route('admin.alumni.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Upload File <span class="text-red-500">*</span>
                            </label>

                            <label for="import-file"
                                   class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition">
                                <i data-lucide="file-up" class="w-8 h-8 text-gray-400 mb-2"></i>
                                <span class="text-sm text-gray-500">Klik untuk pilih file atau drag & drop</span>
                                <span id="file-name" class="text-xs text-blue-600 mt-1 font-semibold"></span>
                                <span class="text-xs text-gray-400 mt-1">Format: .xlsx, .xls, .csv — Maks. 2MB</span>
                            </label>

                            <input type="file"
                                   id="import-file"
                                   name="file"
                                   accept=".xlsx,.xls,.csv"
                                   class="hidden"
                                   onchange="document.getElementById('file-name').textContent = this.files[0]?.name ?? ''">

                            @error('file')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-5">
                            <button type="submit"
                                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition">
                                <i data-lucide="upload" class="w-4 h-4"></i>
                                Import Data
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script>
        function switchTab(tab) {
            document.getElementById('tab-manual').classList.toggle('hidden', tab !== 'manual');
            document.getElementById('tab-import').classList.toggle('hidden', tab !== 'import');

            const activeClass = 'flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold transition bg-blue-600 text-white';
            const inactiveClass = 'flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold transition bg-gray-100 text-gray-600 hover:bg-gray-200';

            document.getElementById('btn-manual').className =
                tab === 'manual' ? activeClass : inactiveClass;

            document.getElementById('btn-import').className =
                tab === 'import' ? activeClass : inactiveClass;
        }

        @if($errors->has('file'))
            switchTab('import');
        @endif
    </script>
</x-app-layout>