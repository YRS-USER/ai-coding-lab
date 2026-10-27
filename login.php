<?php
session_start();
$error = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $csrf = $_POST['csrf_token'] ?? '';

    if (!hash_equals($_SESSION['csrf_token'] ?? '', $csrf)) {
        $error = 'Invalid request. Please refresh and try again.';
    } elseif ($username === 'admin' && hash_equals('password@123', $password)) {
        session_regenerate_id(true);
       $_SESSION['logged_in'] = true;
        $_SESSION['demo_username'] = 'admin';
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Client Login | MarketingPro</title>
<style>
:root{font-family:Inter,system-ui,-apple-system,"Segoe UI",Arial,sans-serif;color:#17243d;font-synthesis:none}
*{box-sizing:border-box}body{margin:0;min-height:100vh;display:grid;place-items:center;background:#edf2fa}
.shell{width:min(1100px,calc(100% - 40px));min-height:620px;display:grid;grid-template-columns:1fr 1fr;overflow:hidden;background:linear-gradient(128deg,#284876 0%,#192f5b 58%,#142652 100%);border-radius:15px;box-shadow:0 22px 70px #17305c35;position:relative}
.shell:before{content:"";position:absolute;inset:0;pointer-events:none;background:radial-gradient(circle at 42% 32%,#3982e133,transparent 33%),radial-gradient(circle at 65% 85%,#3570f433,transparent 30%)}
.intro{padding:52px 48px;color:white;z-index:1;position:relative;overflow:hidden}
.brand{display:flex;align-items:center;gap:8px;font-size:18px;font-weight:750;margin-bottom:44px;letter-spacing:-.5px}
.logo{height:27px;display:flex;gap:3px;align-items:flex-end}.logo i{display:block;width:5px;border-radius:3px 3px 0 0;background:#40acff}.logo i:nth-child(1){height:10px}.logo i:nth-child(2){height:17px}.logo i:nth-child(3){height:23px}.logo i:nth-child(4){height:15px}
h1{max-width:420px;font-size:clamp(30px,3.2vw,44px);line-height:1.15;margin:0 0 18px;letter-spacing:-1.3px} .subtitle{font-size:15px;line-height:1.7;color:#c9d7f4;max-width:360px;margin-bottom:34px}
.features{list-style:none;padding:0;margin:0;display:grid;gap:19px;position:relative;z-index:1}.features li{display:flex;align-items:center;gap:13px;font-size:15px;color:#e5edff}.feature-icon{height:23px;width:23px;display:grid;place-items:center;background:#0b9be4;border-radius:6px;font-size:14px;color:#fff;font-weight:700}
.graph{position:absolute;right:-5px;bottom:0;opacity:.28;display:flex;align-items:end;gap:12px;height:180px;transform:skewY(-18deg)}.graph span{background:linear-gradient(#139eff,#175dd1);width:12px;border-radius:5px 5px 0 0}.graph span:nth-child(1){height:25%}.graph span:nth-child(2){height:44%}.graph span:nth-child(3){height:65%}.graph span:nth-child(4){height:85%}.graph span:nth-child(5){height:100%}
.right{display:flex;align-items:center;justify-content:center;padding:40px;position:relative;z-index:1}.login-card{background:#fff;border-radius:12px;padding:42px 36px 32px;width:100%;max-width:410px;box-shadow:0 16px 42px #09163144}h2{margin:0 0 7px;font-size:23px;letter-spacing:-.5px}.muted{color:#8490a4;font-size:13px;margin:0 0 30px}.field{margin-bottom:18px}label{display:block;font-size:12px;font-weight:700;margin-bottom:7px;color:#364661}.input-wrap{position:relative}input[type=email],input[type=password],input[type=text]{display:block;width:100%;height:45px;border:1px solid #dce3ef;border-radius:6px;padding:0 13px;font:inherit;font-size:13px;color:#17243d;outline:none}input:focus{border-color:#3c65ed;box-shadow:0 0 0 3px #416ef11b}input::placeholder{color:#aeb8c7}.password-input{padding-right:44px!important}.eye{position:absolute;right:7px;top:5px;height:35px;width:35px;border:0;background:transparent;color:#7e8ba4;cursor:pointer;font-size:18px}.options{display:flex;justify-content:space-between;align-items:center;gap:10px;margin:2px 0 22px;font-size:12px}.remember{display:flex;align-items:center;gap:6px;color:#3660e2;cursor:pointer}.remember input{accent-color:#3565ec;margin:0}a{color:#3560eb;text-decoration:none}a:hover{text-decoration:underline}.btn{width:100%;min-height:45px;cursor:pointer;border-radius:6px;border:0;font-weight:700;font-size:13px}.primary{background:#3562ed;color:#fff;box-shadow:0 5px 10px #3562ed27}.primary:hover{background:#254ecf}.separator{display:flex;align-items:center;gap:12px;color:#9aa5b4;font-size:12px;margin:23px 0}.separator:before,.separator:after{content:"";height:1px;background:#e5eaf2;flex:1}.google{background:#fff;border:1px solid #dce3ef;color:#40516d;display:flex;align-items:center;justify-content:center;gap:9px}.google:hover{background:#f7f9fd}.google svg{height:17px;width:17px}.error{background:#fff0f0;color:#a32626;border:1px solid #ffd0d0;padding:11px;border-radius:6px;font-size:12px;margin-bottom:18px}
@media(max-width:760px){body{display:block;background:#1c3765}.shell{width:100%;min-height:100vh;grid-template-columns:1fr;border-radius:0}.intro{padding:32px 24px 10px}.brand{margin-bottom:22px}h1{font-size:30px;max-width:480px}.subtitle{margin-bottom:16px}.features{display:none}.right{padding:20px 20px 42px}.login-card{max-width:480px;padding:32px 25px}}
</style>
</head>
<body>
<main class="shell">
<section class="intro" aria-label="About MarketingPro">
  <div class="brand"><span class="logo" aria-hidden="true"><i></i><i></i><i></i><i></i></span>MarketingPro</div>
  <h1>Your Complete<br>Digital Growth<br>Dashboard</h1>
  <p class="subtitle">Track website performance, SEO, ads, leads and more — all in one place.</p>
  <ul class="features">
    <li><span class="feature-icon">▣</span>Website Analytics</li>
    <li><span class="feature-icon">↗</span>SEO &amp; Rankings</li>
    <li><span class="feature-icon">▤</span>Ad Campaign Reports</li>
    <li><span class="feature-icon">✉</span>Leads &amp; Enquiries</li>
    <li><span class="feature-icon">♙</span>Social Media Performance</li>
  </ul>
  <div class="graph" aria-hidden="true"><span></span><span></span><span></span><span></span><span></span></div>
</section>
<section class="right">
 <div class="login-card">
  <h2>Client Login</h2>
  <p class="muted">Access your website reports and marketing data</p>
  <?php if ($error): ?><div class="error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
  <form method="post" action="">
   <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
   <div class="field"><label for="email">Username</label><input id="email" type="text" name="username" placeholder="admin" value="<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>" autocomplete="username" required></div>
   <div class="field"><label for="password">Password</label><div class="input-wrap"><input class="password-input" id="password" type="password" name="password" placeholder="Enter your password" autocomplete="current-password" required><button class="eye" type="button" id="togglePassword" aria-label="Show password" aria-pressed="false">◉</button></div></div>
   <div class="options"><label class="remember"><input type="checkbox" name="remember" value="1">Remember me</label><a href="#" onclick="alert('Password reset is disabled in demo mode.');return false">Forgot password?</a></div>
   <button class="btn primary" type="submit">Login</button>
  </form>
  <p style="font-size:11px;color:#8693a8;text-align:center;margin:15px 0 0">Demo login only — not for production use.</p>
  <div class="separator">or continue with</div>
  <button class="btn google" type="button" onclick="alert('Google sign-in needs OAuth configuration before it can be enabled.')"><svg viewBox="0 0 48 48" aria-hidden="true"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6C44.4 38.03 46.98 31.88 46.98 24.55z"/><path fill="#FBBC05" d="M10.53 28.59A14.4 14.4 0 0 1 9.75 24c0-1.59.27-3.13.77-4.59l-7.98-6.2A23.9 23.9 0 0 0 0 24c0 3.87.93 7.51 2.56 10.78z"/><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.9-5.8l-7.73-6c-2.15 1.45-4.92 2.3-8.17 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/></svg>Login with Google</button>
 </div>
</section>
</main>
<script>
document.getElementById('togglePassword').addEventListener('click',function(){const el=document.getElementById('password');const show=el.type==='password';el.type=show?'text':'password';this.setAttribute('aria-label',show?'Hide password':'Show password');this.setAttribute('aria-pressed',String(show));});
</script>
</body>
</html>
