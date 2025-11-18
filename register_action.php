<?php
// register_action.php
// Handles account creation for TravelEase using PostgreSQL + PDO.

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: create_account.php');
    exit;
}

/* -----------------------------
   1. Read & basic validate input
------------------------------*/
$fullName         = trim($_POST['fullname'] ?? '');
$email            = trim($_POST['email'] ?? '');
$phone            = trim($_POST['phone'] ?? '');
$password         = $_POST['password'] ?? '';
$confirmPassword  = $_POST['confirm_password'] ?? '';

$errors = [];

// Required checks
if ($fullName === '') {
    $errors[] = 'Full name is required.';
}
if ($email === '') {
    $errors[] = 'Email is required.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address.';
}
if ($phone === '') {
    $errors[] = 'Phone number is required.';
}
if ($password === '' || $confirmPassword === '') {
    $errors[] = 'Password and confirm password are required.';
} elseif ($password !== $confirmPassword) {
    $errors[] = 'Passwords do not match.';
} elseif (strlen($password) < 6) {
    $errors[] = 'Password must be at least 6 characters.';
}

// If errors, show back to user
if (!empty($errors)) {
    showResult(false, $errors);
    exit;
}

/* -----------------------------
   2. Connect to PostgreSQL via PDO
------------------------------*/

// CHANGE THESE IF NEEDED
$dbHost = 'localhost';
$dbPort = '5432';
$dbName = 'travelease_db';
$dbUser = 'travelease_user';
$dbPass = 'strongpassword'; // your real password

$dsn = "pgsql:host=$dbHost;port=$dbPort;dbname=$dbName;";

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    showResult(false, ['Database connection failed: ' . $e->getMessage()]);
    exit;
}

/* -----------------------------
   3. Check if email already exists
------------------------------*/
try {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => $email]);

    if ($stmt->fetch()) {
        showResult(false, ['An account with this email already exists.']);
        exit;
    }
} catch (PDOException $e) {
    showResult(false, ['Error checking existing user: ' . $e->getMessage()]);
    exit;
}

/* -----------------------------
   4. Insert new user
------------------------------*/
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare("
        INSERT INTO users (full_name, email, phone, password_hash)
        VALUES (:full_name, :email, :phone, :password_hash)
        RETURNING id
    ");

    $stmt->execute([
        ':full_name'     => $fullName,
        ':email'         => $email,
        ':phone'         => $phone,
        ':password_hash' => $passwordHash,
    ]);

    $newUserId = $stmt->fetchColumn();

    if ($newUserId) {
        showResult(true);
        exit;
    } else {
        showResult(false, ['Failed to create account. Please try again.']);
        exit;
    }

} catch (PDOException $e) {
    showResult(false, ['Database error while creating account: ' . $e->getMessage()]);
    exit;
}


/* -----------------------------
   Helper: result page
------------------------------*/
function showResult(bool $success, array $errors = [])
{
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title><?php echo $success ? 'Account Created' : 'Registration Error'; ?> | TravelEase</title>
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

            <?php if ($success): ?>
                <h1 class="text-xl font-extrabold text-ink mb-2">Account created successfully!</h1>
                <p class="text-sm text-inkMuted mb-4">
                    Your TravelEase account has been created. You can now log in.
                </p>

                <a href="login.php"
                   class="block w-full py-2.5 rounded-xl bg-primary text-ink font-semibold text-sm hover:bg-primaryDark hover:text-white transition mb-2">
                    Go to Login
                </a>

                <a href="guest_dashboard.php"
                   class="block w-full py-2.5 rounded-xl border border-yellow-200 text-inkMuted text-sm hover:bg-primarySoft hover:text-ink transition">
                    Back to Guest Dashboard
                </a>

            <?php else: ?>
                <h1 class="text-xl font-extrabold text-red-600 mb-2">Registration failed</h1>
                <p class="text-sm text-inkMuted mb-4">
                    Please fix the issues below:
                </p>

                <?php if (!empty($errors)): ?>
                    <ul class="text-left text-sm text-red-600 mb-4 list-disc list-inside">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <a href="create_account.php"
                   class="block w-full py-2.5 rounded-xl bg-primary text-ink font-semibold text-sm hover:bg-primaryDark hover:text-white transition mb-2">
                    Back to Create Account
                </a>

                <a href="guest_dashboard.php"
                   class="block w-full py-2.5 rounded-xl border border-yellow-200 text-inkMuted text-sm hover:bg-primarySoft hover:text-ink transition">
                    Back to Guest Dashboard
                </a>
            <?php endif; ?>

        </div>
    </body>
    </html>
    <?php
}
