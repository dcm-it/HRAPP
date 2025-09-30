<x-app-layout>
    <div class="container mx-auto mt-6 px-4">

        <!-- FORM PENGAJUAN -->
        @if(auth()->user()->role === 'karyawan')
        <h2 class="text-2xl font-bold mb-4 text-gray-800">Form Pengajuan Cuti</h2>
        <form action="{{ route('leave.store') }}" method="POST" class="flex space-x-3 items-center mb-6">
            @csrf
            <input type="date" name="start_date" required class="border rounded px-3 py-2">
            <input type="date" name="end_date" required class="border rounded px-3 py-2">
            <input type="text" name="reason" placeholder="Alasan" required class="border rounded px-3 py-2 w-64">
            <button type="submit"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow">
                Ajukan
            </button>
        </form>

        <!-- FOR KARYAWAN -->
        <h3 class="text-xl font-semibold mb-3 text-gray-700">Daftar Pengajuan Anda</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 rounded-lg shadow-sm">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left">Tanggal Cuti</th>
                        <th class="px-4 py-2 text-left">Alasan</th>
                        <th class="px-4 py-2 text-left">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($requests as $req)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $req->start_date }} - {{ $req->end_date }}</td>
                        <td class="px-4 py-2">{{ $req->reason }}</td>
                        <td class="px-4 py-2">
                            @if($req->status === 'menunggu')
                            <span class="px-2 py-1 text-sm rounded bg-yellow-100 text-yellow-800">
                                Menunggu
                            </span>
                            @elseif($req->status === 'approve')
                            <span class="px-2 py-1 text-sm rounded bg-green-100 text-green-800">
                                Disetujui
                            </span>
                            @else
                            <span class="px-2 py-1 text-sm rounded bg-red-100 text-red-800">
                                Ditolak
                            </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- hrd & admin -->
        @if(in_array(auth()->user()->role, ['hrd', 'admin']))
        {{-- Tabel Menunggu Persetujuan --}}
        <h2 class="text-2xl font-bold mt-8 mb-4 text-gray-800">Daftar Pengajuan Menunggu</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 rounded-lg shadow-sm">
                <thead class="bg-yellow-500 text-white">
                    <tr>
                        <th class="px-4 py-2 text-left">Karyawan</th>
                        <th class="px-4 py-2 text-left">Tanggal Cuti</th>
                        <th class="px-4 py-2 text-left">Alasan</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($waitingRequests as $req)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $req->user->name }}</td>
                        <td class="px-4 py-2">{{ $req->start_date }} - {{ $req->end_date }}</td>
                        <td class="px-4 py-2">{{ $req->reason }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 text-sm rounded bg-yellow-100 text-yellow-800">
                                Menunggu
                            </span>
                        </td>
                        <td class="px-4 py-2 text-center">
                            <form action="{{ route('leave.updateStatus', $req->id) }}" method="POST"
                                class="inline-flex space-x-2 items-center">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="border rounded px-2 py-1">
                                    <option value="approve">Approve</option>
                                    <option value="tolak">Tolak</option>
                                </select>
                                <button type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded shadow">
                                    Kirim
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Recent Pengajuan  -->
        <h3 class="text-2xl font-semibold mt-8 mb-4 text-gray-700">Pengajuan Disetujui / Ditolak</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 rounded-lg shadow-sm">
                <thead class="bg-gray-200 text-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left">Nama</th>
                        <th class="px-4 py-2 text-left">Tanggal Cuti</th>
                        <th class="px-4 py-2 text-left">Alasan</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($processedRequests as $req)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $req->user->name }}</td>
                        <td class="px-4 py-2">{{ $req->start_date }} - {{ $req->end_date }}</td>
                        <td class="px-4 py-2">{{ $req->reason }}</td>
                        <td class="px-4 py-2">
                            @if($req->status === 'approve')
                            <span class="px-2 py-1 text-sm rounded bg-green-100 text-green-800">
                                Disetujui
                            </span>
                            @else
                            <span class="px-2 py-1 text-sm rounded bg-red-100 text-red-800">
                                Ditolak
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-2 flex justify-center space-x-3">
                            {{-- Tambah --}}
                            <a href="{{ route('leave.create') }}"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded shadow"
                                title="Tambah">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
                                    <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z" />
                                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                                </svg>
                            </a>

                            <!-- edit -->
                            <a href="{{ route('leave.edit', $req->id) }}"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded shadow"
                                title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen-fill" viewBox="0 0 16 16">
                                    <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001" />
                                </svg>
                            </a>



                            <!-- Delete -->
                            <form action="{{ route('leave.destroy', $req->id) }}" method="POST"
                                onsubmit="return confirm('Yakin mau hapus?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded shadow"
                                    title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                        <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5" />
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</x-app-layout>