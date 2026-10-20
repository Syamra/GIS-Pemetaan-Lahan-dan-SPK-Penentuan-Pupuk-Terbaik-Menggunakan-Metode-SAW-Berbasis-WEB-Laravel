<!DOCTYPE html>
<html lang="id">
<head>
    <title>Login - SPK SAW GIS</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/icon/themify-icons/themify-icons.css') }}">
    <style>
        body { 
            background: linear-gradient(135deg, #7367f0 0%, #9e95f5 100%); 
            height: 100vh; display: flex; align-items: center; justify-content: center; 
        }
        .card { 
            border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); border: none; width: 100%; max-width: 400px; 
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center">
        <div class="card p-4">
            <div class="text-center mb-4">
                <i class="ti-map-alt text-primary mb-2" style="font-size: 40px;"></i>
                <h4 class="font-weight-bold text-dark">SPK SAW GIS</h4>
                <p class="text-muted" style="font-size: 14px;">Silakan masuk ke akun Admin Anda</p>
            </div>
            
            @if($errors->any())
                <div class="alert alert-danger" style="border-radius: 6px; font-size: 14px;">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label class="text-muted" style="font-size: 14px;">Email Admin</label>
                    <input type="email" name="email" class="form-control" style="border-radius: 6px;" required value="{{ old('email') }}">
                </div>
                <div class="form-group mb-4">
                    <label class="text-muted" style="font-size: 14px;">Password</label>
                    <input type="password" name="password" class="form-control" style="border-radius: 6px;" required>
                </div>
                <button type="submit" class="btn text-white w-100 font-weight-bold" style="border-radius: 6px; background-color: #7367f0;">
                    Masuk
                </button>
            </form>
        </div>
    </div>
</body>
</html>