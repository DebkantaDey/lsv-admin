<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ App_Name() }} — Welcome</title>
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <style>
        body { min-height: 100vh; background: radial-gradient(1200px 800px at 80% -10%, rgba(255,255,255,0.06), transparent),
                           radial-gradient(1000px 600px at -20% 120%, rgba(255,255,255,0.06), transparent),
                           linear-gradient(135deg, #0f172a 0%, #111827 35%, #0b1220 100%); color: #fff; }
        .glass {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.12);
            box-shadow: 0 10px 30px rgba(0,0,0,0.4);
            border-radius: 20px;
            backdrop-filter: blur(10px);
        }
        .hero-title { font-weight: 800; letter-spacing: -0.02em; }
        .brand { color: #60a5fa; }
        .btn-primary { background: linear-gradient(135deg, #2563eb, #1d4ed8); border: none; }
        .btn-outline-light:hover { color: #0b1220; background: #fff; }
        .badge-soft { background: rgba(96,165,250,0.15); color: #93c5fd; border: 1px solid rgba(147,197,253,0.25); }
        .card-choice:hover { transform: translateY(-4px); box-shadow: 0 16px 40px rgba(0,0,0,0.45); }
        .card-choice { transition: all .25s ease; }
        .logo { font-size: 34px; font-weight: 900; letter-spacing: .5px; }
        a.link-muted { color: #cbd5e1; text-decoration: none; }
        a.link-muted:hover { color: #fff; }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="logo">{{ App_Name() }}</div>
            <div>
                <span class="badge badge-soft px-3 py-2 rounded-pill">Welcome</span>
            </div>
        </div>

        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="mb-3 text-uppercase small text-light">Start here</div>
                <h1 class="hero-title display-5 mb-3">
                    One gateway to <span class="brand">all dashboards</span>
                </h1>
                <p class="lead text-light" style="opacity:.9;">
                    Choose how you want to sign in. Whether you're managing the platform or creating content, we've got you covered.
                </p>
                <div class="mt-4 d-flex flex-wrap gap-3">
                    <a href="{{ route('admin.login') }}" class="btn btn-primary btn-lg mr-3 mb-2">Admin Login</a>
                    <a href="{{ route('user.login') }}" class="btn btn-outline-light btn-lg mb-2">User Login</a>
                </div>
                <div class="mt-4 small text-secondary">
                    Need help? Visit <a class="link-muted" href="{{ url('pages/terms') }}">Terms</a> · <a class="link-muted" href="{{ url('pages/privacy') }}">Privacy</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="glass p-4 p-lg-5">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card-choice glass p-4 h-100">
                                <div class="h5 mb-2">Fast Access</div>
                                <div class="text-secondary">Optimized entry points to your dashboards.</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card-choice glass p-4 h-100">
                                <div class="h5 mb-2">Secure</div>
                                <div class="text-secondary">Role-based authentication and guards.</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card-choice glass p-4 h-100">
                                <div class="h5 mb-2">Modern UI</div>
                                <div class="text-secondary">Elegant, minimal, and responsive design.</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card-choice glass p-4 h-100">
                                <div class="h5 mb-2">Consistent</div>
                                <div class="text-secondary">Seamless across admin and user portals.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-5 text-secondary">
            © {{ date('Y') }} {{ App_Name() }}. All rights reserved.
        </div>
    </div>

    <script src="{{ asset('assets/bootstrap/bootstrap.bundle.min.js') }}"></script>
</body>
</html>


