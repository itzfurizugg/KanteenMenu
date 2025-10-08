<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Menu Kantin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <style>
        /* Scroll horizontal */
        .menu-scroll {
            display: flex;
            overflow-x: auto;
            gap: 1rem;
            scroll-behavior: smooth;
            padding-bottom: 1rem;
        }

        .menu-card {
            min-width: 250px;
            flex: 0 0 auto;
        }

        .menu-card img {
            height: 160px;
            object-fit: cover;
            border-radius: 10px;
        }

        .menu-card .card-body {
            padding: 0.8rem;
        }

        ::-webkit-scrollbar {
            height: 8px;
        }
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>

<body class="bg-light">

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Daftar Menu Kantin</h2>
        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMenuModal"><i class="bi bi-plus-square-fill"></i> Tambah Menu</a>
    </div>

    @if (session('status'))
        <div id="alertSuccess" class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <strong>{{ session('status') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const alertBox = document.getElementById('alertSuccess');
                if (alertBox) {
                    // Hilang otomatis dalam 5 detik
                    setTimeout(() => {
                        const alert = bootstrap.Alert.getOrCreateInstance(alertBox);
                        alert.close();
                    }, 5000);
                }
            });
        </script>
    @endif

    <div class="menu-scroll">
        @forelse ($menus as $menu)
            <div class="card menu-card shadow-sm">
                <img src="{{ asset('storage/' . $menu->photo) }}" class="card-img-top" alt="{{ $menu->name }}">
                <div class="card-body">
                    <h5 class="card-title mb-1">{{ $menu->name }}</h5>
                    <p class="text-muted small mb-1">{{ ucfirst($menu->category) }}</p>
                    <p class="mb-1 fw-bold text-success">Rp{{ number_format($menu->price, 0, ',', '.') }}</p>
                    <p class="small mb-2">{{ $menu->description }}</p>
                    <div class="d-flex justify-content-between">
                        <form action="{{ route('menu.destroy', $menu->id) }}" method="POST" onsubmit="return confirm('Hapus menu ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash3"></i> Hapus</button>
                        </form>
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editMenuModal{{ $menu->id }}"><i class="bi bi-pencil-square"></i></button>
                    </div>
                </div>
            </div>

            <!-- Modal Edit Menu -->
            <div class="modal fade" id="editMenuModal{{ $menu->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <form class="modal-content" action="{{ route('menu.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Menu</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-2">
                                <label>Nama Menu</label>
                                <input type="text" name="name" class="form-control" value="{{ $menu->name }}" required>
                            </div>
                            <div class="mb-2">
                                <label>Deskripsi</label>
                                <textarea name="description" class="form-control">{{ $menu->description }}</textarea>
                            </div>
                            <div class="mb-2">
                                <label>Harga</label>
                                <input type="number" name="price" class="form-control" value="{{ $menu->price }}" required>
                            </div>
                            <div class="mb-2">
                                <label>Stok</label>
                                <input type="number" name="stock" class="form-control" value="{{ $menu->stock }}">
                            </div>
                            <div class="mb-2">
                                <label>Kategori</label>
                                <select name="category" class="form-select" required>
                                    <option value="makanan" {{ $menu->category == 'makanan' ? 'selected' : '' }}>Makanan</option>
                                    <option value="minuman" {{ $menu->category == 'minuman' ? 'selected' : '' }}>Minuman</option>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label>Foto (opsional)</label>
                                <input type="file" name="photo" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary"><i class="bi bi-floppy-fill"></i> Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>

        @empty
            <p>Tidak ada menu tersedia.</p>
        @endforelse
    </div>
</div>

<!-- Modal Tambah Menu -->
<div class="modal fade" id="addMenuModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" action="{{ route('menu.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Menu Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                    <label>Nama Menu</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label>Deskripsi</label>
                    <textarea name="description" class="form-control"></textarea>
                </div>
                <div class="mb-2">
                    <label>Harga</label>
                    <input type="number" name="price" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label>Stok</label>
                    <input type="number" name="stock" class="form-control">
                </div>
                <div class="mb-2">
                    <label>Kategori</label>
                    <select name="category" class="form-select" required>
                        <option value="makanan">Makanan</option>
                        <option value="minuman">Minuman</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label>Foto Menu</label>
                    <input type="file" name="photo" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success"><i class="bi bi-floppy-fill"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
