<?php
// login_action.php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

// 1. Read input
$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

$errors = [];

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address.';
}
if ($password === '') {
    $errors[] = 'Password is required.';
}

if (!empty($errors)) {
    showLoginResult(false, $errors);
    exit;
}

// 2. Connect to PostgreSQL
$dbHost = 'localhost';
$dbPort = '5432';
$dbName = 'travelease_db';
$dbUser = 'travelease_user';
$dbPass = 'strongpassword'; // change if different

$dsn = "pgsql:host=$dbHost;port=$dbPort;dbname=$dbName;";

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    showLoginResult(false, ['Database connection failed: ' . $e->getMessage()]);
    exit;
}

// 3. Find user by email
try {
    $stmt = $pdo->prepare("SELECT id, full_name, email, password_hash FROM users WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if (!$user) {
        showLoginResult(false, ['No account found with that email address.']);
        exit;
    }

    // 4. Verify password
    if (!password_verify($password, $user['password_hash'])) {
        showLoginResult(false, ['Incorrect password.']);
        exit;
    }

    // 5. Success: set session and redirect
    $_SESSION['user_id']    = $user['id'];
    $_SESSION['full_name']  = $user['full_name'];
    $_SESSION['email']      = $user['email'];

    header('Location: user_dashboard.php');
    exit;

} catch (PDOException $e) {
    showLoginResult(false, ['Error during login: ' . $e->getMessage()]);
    exit;
}


// Helper function: show error page if login fails
function showLoginResult(bool $success, array $errors = [])
{
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title><?php echo $success ? 'Login Successful' : 'Login Error'; ?> | TravelEase</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            primary: "#facc15",
                            primaryDark: "#eab308",
                            primarySoft: "#fef9c3",
                            ink: "#111827",
                            inkMuted: "#6b7280",
                            card: "#ffffff",
                            bgSoft: "#f9fafb",
                        },
                    },
                },
            };
        </script>

        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <style> body { font-family: Inter, sans-serif; } </style>
    </head>
    <body class="bg-bgSoft min-h-screen flex items-center justify-center px-4">
        <div class="w-full max-w-md bg-white rounded-3xl shadow-lg border border-yellow-100 p-6 sm:p-8 text-center">
            <img src="img/Logo.png" alt="TravelEase Logo" class="h-12 mx-auto mb-3">

            <h1 class="text-xl font-extrabold text-red-600 mb-2">Login failed</h1>
            <p class="text-sm text-inkMuted mb-4">
                Please check the issues below and try again.
            </p>

            <?php if (!empty($errors)): ?>
                <ul class="text-left text-sm text-red-600 mb-4 list-disc list-inside">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <a href="login.php"
               class="block w-full py-2.5 rounded-xl bg-primary text-ink font-semibold text-sm hover:bg-primaryDark hover:text-white transition mb-2">
                Back to Login
            </a>

            <a href="guest_dashboard.php"
               class="block w-full py-2.5 rounded-xl border border-yellow-200 text-inkMuted text-sm hover:bg-primarySoft hover:text-ink transition">
                Back to Guest Dashboard
            </a>
        </div>
    </body>
    </html>
    <?php
}
