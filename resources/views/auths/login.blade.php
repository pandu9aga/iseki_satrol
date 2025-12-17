<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Patrol System</title>
    <link rel="icon" href="{{ asset('assets/favicon.png') }}">
    {{-- <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet"> --}}
    <script src="{{ asset('assets/js/html5-qrcode.min.js') }}"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            /* Gradasi pink modern & lembut */
            background: linear-gradient(135deg, #fce4ec, #f8bbd0, #f48fb1);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 6px 24px rgba(233, 30, 99, 0.12);
            width: 100%;
            max-width: 400px;
            padding: 36px;
            position: relative;
            z-index: 2;
        }

        .logo {
            text-align: center;
            margin-bottom: 28px;
        }

        .logo h2 {
            color: #d81b60;
            font-weight: 600;
            font-size: 26px;
            letter-spacing: -0.5px;
        }

        .switch-tabs {
            display: flex;
            gap: 6px;
            margin-bottom: 24px;
            background: #fdf6f8;
            padding: 4px;
            border-radius: 12px;
            border: 1px solid #f5d7e0;
        }

        .tab-btn {
            flex: 1;
            padding: 10px 0;
            border: none;
            border-radius: 10px;
            background: transparent;
            font-weight: 600;
            font-size: 14px;
            color: #a02c5a;
            cursor: pointer;
            transition: all 0.25s ease;
        }

        .tab-btn.active {
            background: #e91e63;
            color: white;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-size: 13px;
            color: #666;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 11px 14px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 15px;
            transition: border-color 0.25s;
        }

        .form-control:focus {
            outline: none;
            border-color: #e91e63;
            box-shadow: 0 0 0 2px rgba(233, 30, 99, 0.15);
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: #e91e63;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: background 0.25s;
        }

        .btn-login:hover {
            background: #d81b60;
        }

        .btn-scan {
            width: 100%;
            padding: 10px;
            background: #fff8fa;
            color: #e91e63;
            border: 1px solid #f48fb1;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            margin-top: 10px;
            cursor: pointer;
            transition: all 0.25s;
        }

        .btn-scan:hover {
            background: #ffeef2;
            border-color: #e91e63;
        }

        #reader {
            width: 100%;
            height: 250px;
            margin-top: 16px;
            border-radius: 10px;
            overflow: hidden;
            display: none;
            border: 1px solid #f0e6ea;
            box-shadow: inset 0 0 8px rgba(0, 0, 0, 0.04);
        }

        .alert {
            padding: 10px 12px;
            background: #fff0f4;
            color: #c2185b;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13px;
            border-left: 3px solid #e91e63;
        }

        .form-section {
            display: none;
        }

        .form-section.active {
            display: block;
        }

        /* Optional: subtle decorative element */
        body::before {
            content: "";
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(233, 30, 99, 0.1) 0%, transparent 70%);
            z-index: 1;
        }

        body::after {
            content: "";
            position: absolute;
            bottom: -80px;
            left: -60px;
            width: 250px;
            height: 250px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(244, 143, 177, 0.08) 0%, transparent 70%);
            z-index: 1;
        }
    </style>
</head>

<body>
    <div class="login-card">
        <div class="logo">
            <h2>Safety Patrol</h2>
        </div>

        @if ($errors->any())
            <div class="alert">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="switch-tabs">
            <button class="tab-btn active" data-target="member">Member</button>
            <button class="tab-btn" data-target="admin">Admin</button>
        </div>

        <!-- Member Form -->
        <div id="formMember" class="form-section active">
            <div class="form-group">
                <label for="nikInput">NIK Karyawan</label>
                <input type="text" id="nikInput" name="nik" class="form-control"
                    placeholder="Masukkan atau scan NIK">
            </div>
            <div id="reader"></div>
            <form id="submitMemberForm" method="POST" action="{{ route('login.member') }}" style="display:none;">
                @csrf
                <input type="hidden" name="nik" id="hiddenNik">
            </form>
            <button type="button" class="btn-login" onclick="submitMember()">Masuk sebagai Member</button>
            <button type="button" class="btn-scan" id="btnScan">📷 Scan Barcode NIK</button>

        </div>

        <!-- Admin Form -->
        <div id="formAdmin" class="form-section">
            <form method="POST" action="{{ route('login.admin') }}">
                @csrf
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="Username_User" class="form-control" placeholder="Username" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="Password_User" class="form-control" placeholder="Password" required>
                </div>
                <button type="submit" class="btn-login">Masuk sebagai Admin</button>
            </form>
        </div>
    </div>

    <script>
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.form-section').forEach(f => f.classList.remove('active'));
                btn.classList.add('active');
                const target = btn.dataset.target;
                const capitalized = target.charAt(0).toUpperCase() + target.slice(1);
                document.getElementById('form' + capitalized).classList.add('active');
            });
        });

        let html5QrCode = null;

        document.getElementById('btnScan').addEventListener('click', () => {
            const reader = document.getElementById('reader');
            if (reader.style.display === 'block') {
                stopScanner();
                reader.style.display = 'none';
                return;
            }

            reader.style.display = 'block';
            html5QrCode = new Html5Qrcode("reader");

            Html5Qrcode.getCameras().then(devices => {
                if (devices && devices.length) {
                    const cameraId = devices[0].id;
                    html5QrCode.start(
                        cameraId, {
                            fps: 10,
                            qrbox: {
                                width: 230,
                                height: 230
                            }
                        },
                        (decodedText) => {
                            document.getElementById('nikInput').value = decodedText;
                            stopScanner();
                            reader.style.display = 'none';
                        },
                        (errorMessage) => {}
                    );
                }
            }).catch(err => {
                alert("Kamera tidak tersedia: " + err);
                reader.style.display = 'none';
            });
        });

        function stopScanner() {
            if (html5QrCode) {
                html5QrCode.stop().then(() => html5QrCode.clear()).catch(console.error);
                html5QrCode = null;
            }
        }

        function submitMember() {
            const nik = document.getElementById('nikInput').value.trim();
            if (!nik) {
                alert("NIK tidak boleh kosong!");
                return;
            }
            document.getElementById('hiddenNik').value = nik;
            document.getElementById('submitMemberForm').submit();
        }
    </script>
</body>

</html>
