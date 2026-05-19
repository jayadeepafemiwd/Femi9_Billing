<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Femi9 — Sign In</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,700;1,400;1,500&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --rose:       #c9637a;
            --rose-light: #f2c4ce;
            --rose-deep:  #8b3a50;
            --blush:      #fde8ed;
            --cream:      #fdf6f0;
            --warm:       #f9ede8;
            --mauve:      #b57a8c;
            --dusty:      #d4a0ad;
            --text:       #3d2030;
            --muted:      #9a6b78;
            --border:     rgba(201,99,122,0.18);
            --err:        #c0394f;
            --ok:         #5a9e7a;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            color: var(--text);
            min-height: 100vh;
        }

        .wrap {
            display: flex;
            min-height: 100vh;
        }

        /* ── Left panel ── */
        .left {
            flex: 1;
            background: var(--warm);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            position: relative;
            overflow: hidden;
        }

        .petal {
            position: absolute;
            border-radius: 60% 40% 70% 30% / 50% 60% 40% 50%;
            opacity: 0.13;
            animation: drift 10s ease-in-out infinite;
        }
        .p1 { width:280px; height:280px; background:var(--rose);  top:-80px;  left:-60px;  animation-delay:0s;  }
        .p2 { width:200px; height:200px; background:var(--mauve); bottom:-40px; right:-40px; animation-delay:-4s; }
        .p3 { width:140px; height:140px; background:var(--rose-light); top:40%; right:10%; animation-delay:-7s; }

        @keyframes drift {
            0%,100% { transform: rotate(0deg) scale(1); }
            50%      { transform: rotate(8deg) scale(1.06); }
        }

        .brand { text-align: center; margin-bottom: 2.5rem; position: relative; z-index: 2; }

        .brand-logo {
            width: 70px; height: 70px;
            background: linear-gradient(135deg, var(--rose), var(--mauve));
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.2rem;
            font-size: 26px;
            box-shadow: 0 8px 30px rgba(201,99,122,0.3);
            animation: heartbeat 3s ease-in-out infinite;
        }
        @keyframes heartbeat {
            0%,100% { transform: scale(1); }
            50%      { transform: scale(1.06); }
        }

        .brand-name {
            font-family: 'Playfair Display', serif;
            font-size: 2.4rem; font-weight: 700;
            color: var(--rose-deep);
            letter-spacing: -0.5px;
        }
        .brand-tagline {
            color: var(--muted); font-size: 0.88rem;
            margin-top: 0.4rem; font-weight: 300; font-style: italic;
        }

        .stats-row {
            display: grid; grid-template-columns: repeat(2, 1fr);
            gap: 0.85rem; width: 100%; max-width: 380px;
            margin-bottom: 2.5rem; position: relative; z-index: 2;
        }
        .s-card {
            background: rgba(255,255,255,0.7);
            border: 1px solid rgba(201,99,122,0.15);
            border-radius: 16px; padding: 1.1rem; text-align: center;
        }
        .s-num {
            font-family: 'Playfair Display', serif;
            font-size: 1.9rem; font-weight: 700; color: var(--rose);
        }
        .s-lbl { color: var(--muted); font-size: 0.72rem; margin-top: 0.15rem; }

        /* ── Right panel ── */
        .right {
            width: 500px;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 3rem; background: #fff;
        }

        .lcard { width: 100%; max-width: 400px; }

        .lhead { text-align: center; margin-bottom: 2rem; }
        .lhead-pre {
            font-size: 0.78rem; color: var(--dusty);
            letter-spacing: 2px; text-transform: uppercase;
            margin-bottom: 0.5rem; font-weight: 400;
        }
        .lhead-title {
            font-family: 'Playfair Display', serif;
            font-size: 2rem; font-weight: 700;
            color: var(--rose-deep); line-height: 1.2;
        }
        .lhead-title em { font-style: italic; color: var(--rose); }
        .lhead-sub { color: var(--muted); font-size: 0.83rem; margin-top: 0.5rem; }

        .fg { margin-bottom: 1.2rem; }
        .flabel {
            font-size: 0.75rem; color: var(--muted);
            font-weight: 500; letter-spacing: 0.4px;
            margin-bottom: 0.45rem; display: block;
        }
        .finput-wrap { position: relative; }
        .ficon {
            position: absolute; left: 13px; top: 50%;
            transform: translateY(-50%);
            color: var(--dusty); font-size: 16px; pointer-events: none;
        }

        /* Phone prefix badge */
        .phone-prefix {
            position: absolute; left: 38px; top: 50%;
            transform: translateY(-50%);
            color: var(--muted); font-size: 0.88rem;
            font-weight: 500; pointer-events: none;
            border-right: 1.5px solid var(--border);
            padding-right: 8px;
            line-height: 1;
        }

        .fi {
            width: 100%;
            background: var(--blush);
            border: 1.5px solid rgba(201,99,122,0.2);
            border-radius: 12px;
            padding: 0.78rem 0.78rem 0.78rem 2.7rem;
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            outline: none;
            transition: all 0.3s;
        }
        /* Extra left padding when prefix is shown */
        .fi.fi-phone {
            padding-left: 5.5rem;
        }
        .fi::placeholder { color: var(--dusty); }
        .fi:focus {
            border-color: var(--rose);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(201,99,122,0.1);
        }
        .fi.fi-err { border-color: var(--err); background: #fff0f2; }

        .pw-tog {
            position: absolute; right: 11px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            color: var(--dusty); cursor: pointer;
            font-size: 16px; padding: 4px;
            transition: color 0.3s;
        }
        .pw-tog:hover { color: var(--rose); }

        .err-text {
            font-size: 0.72rem; color: var(--err);
            margin-top: 0.4rem;
            display: flex; align-items: center; gap: 4px;
        }

        .frow {
            display: flex; align-items: center;
            justify-content: space-between;
            margin-bottom: 1.4rem;
        }
        .rem {
            display: flex; align-items: center;
            gap: 7px; cursor: pointer;
            font-size: 0.8rem; color: var(--muted);
        }

        .btn-main {
            width: 100%; padding: 0.88rem;
            border-radius: 12px; border: none;
            background: linear-gradient(135deg, var(--rose) 0%, var(--mauve) 100%);
            color: #fff;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.95rem; font-weight: 500;
            cursor: pointer; transition: all 0.3s;
            letter-spacing: 0.2px;
        }
        .btn-main:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(201,99,122,0.35);
        }
        .btn-main:active { transform: translateY(0); }

        .div-line {
            display: flex; align-items: center;
            gap: 0.75rem; margin: 1.25rem 0;
            color: var(--dusty); font-size: 0.75rem;
        }
        .div-line::before, .div-line::after {
            content: ''; flex: 1; height: 1px; background: var(--border);
        }

        .soc-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.7rem; margin-bottom: 1.3rem; }
        .soc-btn {
            padding: 0.62rem; border-radius: 10px;
            border: 1.5px solid var(--border);
            background: var(--blush); color: var(--muted);
            font-size: 0.8rem; cursor: pointer;
            transition: all 0.3s;
            display: flex; align-items: center; justify-content: center; gap: 7px;
            font-family: 'DM Sans', sans-serif;
            text-decoration: none;
        }
        .soc-btn:hover { border-color: var(--rose); background: #fff; color: var(--rose-deep); }

        .signup-row { text-align: center; font-size: 0.82rem; color: var(--muted); }
        .signup-row a { color: var(--rose); font-weight: 500; text-decoration: none; }
        .signup-row a:hover { color: var(--rose-deep); }

        .alert-box {
            background: #fff0f2; border: 1.5px solid rgba(192,57,79,0.25);
            border-radius: 10px; padding: 0.75rem 1rem;
            margin-bottom: 1.2rem; font-size: 0.82rem; color: var(--err);
        }

        @media (max-width: 768px) {
            .left  { display: none; }
            .right { width: 100%; padding: 2rem 1.5rem; }
        }
    </style>
</head>
<body>

<div class="wrap">

    {{-- ═══ Left panel ═══ --}}
    <div class="left">
        <div class="petal p1"></div>
        <div class="petal p2"></div>
        <div class="petal p3"></div>

        <div class="brand">
            <div class="brand-logo">🌸</div>
            <div class="brand-name">Femi9</div>
            <div class="brand-tagline">Where elegance meets purpose</div>
        </div>

        <div class="stats-row">
            <div class="s-card">
                <div class="s-num">12.4k</div>
                <div class="s-lbl">Members</div>
            </div>
            <div class="s-card">
                <div class="s-num">847</div>
                <div class="s-lbl">Active today</div>
            </div>
            <div class="s-card">
                <div class="s-num">38.2k</div>
                <div class="s-lbl">Stories shared</div>
            </div>
            <div class="s-card">
                <div class="s-num">156</div>
                <div class="s-lbl">Communities</div>
            </div>
        </div>
    </div>
    {{-- /.left --}}

    {{-- ═══ Right panel — Login form ═══ --}}
    <div class="right">
        <div class="lcard">

            <div class="lhead">
                <div class="lhead-pre">Welcome back</div>
                <div class="lhead-title">Sign in to<br><em>Femi9</em></div>
                <div class="lhead-sub">Your safe space awaits you</div>
            </div>

            {{-- Session error alert --}}
            @if (session('error'))
                <div class="alert-box">
                    ⚠️ {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Phone Number --}}
                <div class="fg">
                    <label class="flabel" for="phone_number">Phone Number</label>
                    <div class="finput-wrap">
                        {{-- Phone icon --}}
                        <svg class="ficon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.35 2 2 0 0 1 3.6 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.6a16 16 0 0 0 6 6l.94-.94a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                        {{-- Country code prefix --}}
                        <span class="phone-prefix">+91</span>
                        <input
                            class="fi fi-phone @error('phone_number') fi-err @enderror"
                            type="tel"
                            id="phone_number"
                            name="phone_number"
                            value="{{ old('phone_number') }}"
                            placeholder="98765 43210"
                            autocomplete="tel"
                            inputmode="numeric"
                            maxlength="15"
                            autofocus
                        >
                    </div>
                    @error('phone_number')
                        <div class="err-text">⚠ {{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="fg">
                    <label class="flabel" for="password">Password</label>
                    <div class="finput-wrap">
                        <svg class="ficon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <input
                            class="fi @error('password') fi-err @enderror"
                            type="password"
                            id="password"
                            name="password"
                            placeholder="••••••••"
                            autocomplete="current-password"
                        >
                        <button type="button" class="pw-tog" onclick="togglePw()" aria-label="Toggle password">
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                    @error('password')
                        <div class="err-text">⚠ {{ $message }}</div>
                    @enderror
                </div>

                {{-- Remember me + Forgot password --}}
                <div class="frow">
                    <label class="rem">
                        <input type="checkbox" name="remember" id="remember" style="display:none" {{ old('remember') ? 'checked' : '' }}>
                        <div style="width:16px;height:16px;border:1.5px solid var(--border);border-radius:5px;background:var(--blush);display:flex;align-items:center;justify-content:center;" id="chkVisual"></div>
                        Remember me
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" style="font-size:0.8rem;color:var(--rose);text-decoration:none;">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <button type="submit" class="btn-main">Sign In</button>
            </form>

            <div class="div-line">or continue with</div>

            <div class="soc-row">
                <a href="#" class="soc-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                    Google
                </a>
                <a href="#" class="soc-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg>
                    Apple
                </a>
            </div>

            <div class="signup-row">
                New to Femi9?
                @if (Route::has('register'))
                    <a href="{{ route('register') }}">Join our community</a>
                @endif
            </div>

        </div>
    </div>
    {{-- /.right --}}

</div>
{{-- /.wrap --}}

<script>
    function togglePw() {
        const inp = document.getElementById('password');
        inp.type = inp.type === 'password' ? 'text' : 'password';
    }

    const chk  = document.getElementById('remember');
    const vis  = document.getElementById('chkVisual');
    function syncChk() {
        vis.innerHTML = chk.checked
            ? '<svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#c9637a" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>'
            : '';
        vis.style.borderColor = chk.checked ? 'var(--rose)' : '';
    }
    document.querySelector('.rem').addEventListener('click', () => {
        chk.checked = !chk.checked; syncChk();
    });
    syncChk();

    // Numbers only — letters type பண்ண விடாது
    document.getElementById('phone_number').addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '');
    });
</script>

</body>
</html>