<x-app-layout>
    <div class="container mt-5">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0"><i class="bi bi-plus-circle"></i> Tambah Pengajuan Cuti</h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Terjadi kesalahan:</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>- {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('leave.store') }}" method="POST">
                    @csrf

                    <div class="row mb-3">
                        <label for="start_date" class="col-sm-3 col-form-label fw-bold">Tanggal Mulai</label>
                        <div class="col-sm-9">
                            <input type="date" name="start_date" id="start_date"
                                   class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="end_date" class="col-sm-3 col-form-label fw-bold">Tanggal Selesai</label>
                        <div class="col-sm-9">
                            <input type="date" name="end_date" id="end_date"
                                   class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="reason" class="col-sm-3 col-form-label fw-bold">Alasan</label>
                        <div class="col-sm-9">
                            <textarea name="reason" id="reason" rows="3"
                                      class="form-control" required></textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('leave.index') }}" class="btn btn-secondary me-2">
                            <i class="bi bi-arrow-left"></i> Batal
                        </a>
                        <button type="reset" class="btn btn-warning me-2">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save2"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
