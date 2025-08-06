<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excel İdarəetməsi - Products</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-file-excel me-2"></i>
                            Products Excel İdarəetməsi
                        </h4>
                    </div>
                    <div class="card-body">
                        
                        <!-- Alerts -->
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Statistics -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center">
                                        <i class="fas fa-box fa-2x mb-2"></i>
                                        <h3>{{ $productsCount ?? 0 }}</h3>
                                        <p class="mb-0">Cəmi Məhsul</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Export Section -->
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-header bg-success text-white">
                                        <h5 class="mb-0">
                                            <i class="fas fa-download me-2"></i>
                                            Excel Export
                                        </h5>
                                    </div>
                                    <div class="card-body d-flex flex-column">
                                        <p class="text-muted">
                                            Bütün məhsulları Excel faylına yüklə
                                        </p>
                                        <div class="mt-auto">
                                            <a href="{{ route('excel.products.export') }}" 
                                               class="btn btn-success btn-lg w-100">
                                                <i class="fas fa-file-excel me-2"></i>
                                                Məhsulları Export Et
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Import Section -->
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-header bg-warning text-dark">
                                        <h5 class="mb-0">
                                            <i class="fas fa-upload me-2"></i>
                                            Excel Import
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <p class="text-muted mb-3">
                                            Excel faylından məhsulları import et
                                        </p>
                                        
                                        <form action="{{ route('excel.products.import') }}" 
                                              method="POST" 
                                              enctype="multipart/form-data">
                                            @csrf
                                            
                                            <div class="mb-3">
                                                <label for="file" class="form-label">Excel Faylı Seç</label>
                                                <input type="file" 
                                                       class="form-control @error('file') is-invalid @enderror" 
                                                       id="file" 
                                                       name="file" 
                                                       accept=".xlsx,.xls"
                                                       required>
                                                @error('file')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <button type="submit" class="btn btn-warning w-100">
                                                <i class="fas fa-upload me-2"></i>
                                                Import Et
                                            </button>
                                        </form>
                                        
                                        <hr>
                                        
                                        <a href="{{ route('excel.products.template') }}" 
                                           class="btn btn-outline-info w-100">
                                            <i class="fas fa-download me-2"></i>
                                            Template Yüklə
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Instructions -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">
                                            <i class="fas fa-info-circle me-2"></i>
                                            İstifadə Təlimatları
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6 class="text-success">Export üçün:</h6>
                                                <ol class="text-muted">
                                                    <li>"Məhsulları Export Et" düyməsinə basın</li>
                                                    <li>Excel faylı avtomatik yüklənəcək</li>
                                                    <li>Fayl bütün məhsul məlumatlarını əhatə edir</li>
                                                </ol>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="text-warning">Import üçün:</h6>
                                                <ol class="text-muted">
                                                    <li>Əvvəlcə "Template Yüklə" edin</li>
                                                    <li>Template-i doldurun</li>
                                                    <li>Excel faylını seçib "Import Et" edin</li>
                                                    <li>ID sütunu boş buraxın (avtomatik yaranır)</li>
                                                </ol>
                                            </div>
                                        </div>
                                        
                                        <div class="alert alert-info mt-3">
                                            <strong>Qeyd:</strong> Import zamanı ID sütunu boş qoyun. 
                                            Sistem avtomatik ID təyin edəcək.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // File upload üçün drag & drop effect
        document.getElementById('file').addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                console.log('Fayl seçildi:', e.target.files[0].name);
            }
        });
        
        // Alert auto dismiss
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                if (alert.querySelector('.btn-close')) {
                    var bsAlert = new bootstrap.Alert(alert);
                    setTimeout(function() {
                        bsAlert.close();
                    }, 5000);
                }
            });
        }, 100);
    </script>
</body>
</html>