<?php
// admin_users.php
require_once 'admin_auth.php'; // ensures only admins can access
require_once 'db.php';         // PDO $pdo

// --- Flash messages (success / error) ---
$success = $_SESSION['flash_success'] ?? '';
$error   = $_SESSION['flash_error']   ?? '';
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

// --- Handle POST actions: update / delete ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'update') {
        $userId    = (int)($_POST['user_id'] ?? 0);
        $fullName  = trim($_POST['full_name'] ?? '');
        $email     = trim($_POST['email'] ?? '');
        $role      = trim($_POST['role'] ?? 'user');

        if ($userId <= 0 || $fullName === '' || $email === '' || !in_array($role, ['user', 'admin'], true)) {
            $_SESSION['flash_error'] = 'Please fill all required fields correctly.';
        } else {
            try {
                $stmt = $pdo->prepare("
                    UPDATE users
                    SET full_name = :full_name,
                        email      = :email,
                        role       = :role
                    WHERE id = :id
                ");
                $stmt->execute([
                    ':full_name' => $fullName,
                    ':email'     => $email,
                    ':role'      => $role,
                    ':id'        => $userId,
                ]);
                $_SESSION['flash_success'] = 'User updated successfully.';
            } catch (PDOException $e) {
                $_SESSION['flash_error'] = 'Database error while updating user: ' . $e->getMessage();
            }
        }

        header('Location: admin_users.php');
        exit();
    }

    if ($action === 'delete') {
        $userId = (int)($_POST['user_id'] ?? 0);

        // Prevent admin from deleting themselves
        if ($userId === (int)($_SESSION['user_id'] ?? 0)) {
            $_SESSION['flash_error'] = 'You cannot delete your own account.';
        } elseif ($userId > 0) {
            try {
                $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
                $stmt->execute([':id' => $userId]);
                $_SESSION['flash_success'] = 'User deleted successfully.';
            } catch (PDOException $e) {
                $_SESSION['flash_error'] = 'Database error while deleting user: ' . $e->getMessage();
            }
        } else {
            $_SESSION['flash_error'] = 'Invalid user selected for deletion.';
        }

        header('Location: admin_users.php');
        exit();
    }
}

// --- Filters: search & role ---
$search    = trim($_GET['q'] ?? '');
$roleFilter = $_GET['role'] ?? 'all';

$where  = [];
$params = [];

if ($search !== '') {
    $where[]             = "(full_name ILIKE :search OR email ILIKE :search)";
    $params[':search']   = '%' . $search . '%';
}

if ($roleFilter === 'admin' || $roleFilter === 'user') {
    $where[]            = "role = :role";
    $params[':role']    = $roleFilter;
}

$whereSql = '';
if (!empty($where)) {
    $whereSql = 'WHERE ' . implode(' AND ', $where);
}

// --- Fetch users list ---
$users = [];
try {
    $sql = "SELECT id, full_name, email, role FROM users $whereSql ORDER BY id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = 'Database error while fetching users: ' . $e->getMessage();
}

// --- Single user for edit (when edit_id is present) ---
$editUser = null;
if (isset($_GET['edit_id']) && ctype_digit($_GET['edit_id'])) {
    $editId = (int)$_GET['edit_id'];
    try {
        $stmt = $pdo->prepare("SELECT id, full_name, email, role FROM users WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $editId]);
        $editUser = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error = 'Database error while loading user for edit: ' . $e->getMessage();
    }
}

$adminName = $_SESSION['full_name'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Users | TravelEase Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

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
          boxShadow: {
            'soft': '0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03)',
            'medium': '0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025)',
            'large': '0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02)'
          }
        }
      }
    };
  </script>

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: Inter, sans-serif; background-color: #f3f4f6; }
  </style>
</head>
<body class="min-h-screen bg-bgSoft">

  <!-- Top Navbar -->
  <header class="bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
      <div class="flex items-center space-x-3">
        <a href="admin_dashboard.php" class="flex items-center text-xs font-medium text-inkMuted hover:text-ink transition">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          Back to Admin Dashboard
        </a>
        <div class="flex items-center space-x-2 ml-4">
          <img src="img/Logo.png" alt="TravelEase Logo" class="h-8 w-8 object-contain">
          <div>
            <div class="flex items-center space-x-2">
              <span class="text-lg font-bold text-ink">TravelEase</span>
              <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-primarySoft text-primaryDark">
                Admin
              </span>
            </div>
            <p class="text-[11px] text-inkMuted">User Management</p>
          </div>
        </div>
      </div>
      <div class="flex items-center space-x-4">
        <span class="text-sm text-inkMuted">Hello, <span class="font-semibold text-ink"><?= htmlspecialchars($adminName) ?></span></span>
        <a href="logout.php"
           class="text-xs font-medium text-inkMuted hover:text-red-600 border px-3 py-1.5 rounded-lg">
          Logout
        </a>
      </div>
    </div>
  </header>

  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-3">
      <div>
        <h1 class="text-2xl font-bold text-ink">Manage Users</h1>
        <p class="text-sm text-inkMuted">View, search, edit, and delete TravelEase users.</p>
      </div>
      <div class="flex items-center gap-2 text-xs text-inkMuted">
        <span class="px-2 py-1 rounded-full bg-primarySoft text-primaryDark font-medium">Admin Only</span>
      </div>
    </div>

    <!-- Flash messages -->
    <?php if ($success): ?>
      <div class="mb-4 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-lg">
        <?= htmlspecialchars($success) ?>
      </div>
    <?php endif; ?>

    <?php if ($error): ?>
      <div class="mb-4 bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-3 rounded-lg">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-soft mb-6 p-4 sm:p-5">
      <form method="GET" class="flex flex-col sm:flex-row gap-3 sm:items-end">
        <div class="flex-1">
          <label class="block text-xs font-semibold text-ink mb-1">Search by name or email</label>
          <input type="text" name="q" value="<?= htmlspecialchars($search) ?>"
                 placeholder="Type a name or email..."
                 class="w-full border border-yellow-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
        </div>
        <div>
          <label class="block text-xs font-semibold text-ink mb-1">Role</label>
          <select name="role"
                  class="border border-yellow-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary bg-white">
            <option value="all"   <?= $roleFilter === 'all'   ? 'selected' : '' ?>>All</option>
            <option value="user"  <?= $roleFilter === 'user'  ? 'selected' : '' ?>>User</option>
            <option value="admin" <?= $roleFilter === 'admin' ? 'selected' : '' ?>>Admin</option>
          </select>
        </div>
        <div>
          <button type="submit"
                  class="mt-1 sm:mt-0 inline-flex items-center justify-center px-4 py-2.5 rounded-lg bg-primary text-ink text-sm font-semibold hover:bg-primaryDark">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4h13M8 9h13M8 14h13M8 19h13M3 4h.01M3 9h.01M3 14h.01M3 19h.01" />
            </svg>
            Filter
          </button>
        </div>
      </form>
    </div>

    <!-- Edit User Card (only when edit_user is set) -->
    <?php if ($editUser): ?>
      <div class="bg-white rounded-2xl shadow-soft mb-6 p-5 border border-yellow-100">
        <div class="flex items-center justify-between mb-3">
          <h2 class="text-lg font-semibold text-ink">Edit User #<?= (int)$editUser['id'] ?></h2>
          <a href="admin_users.php" class="text-xs text-inkMuted hover:text-ink underline">
            Cancel edit
          </a>
        </div>
        <form method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <input type="hidden" name="action" value="update">
          <input type="hidden" name="user_id" value="<?= (int)$editUser['id'] ?>">

          <div>
            <label class="block text-xs font-semibold text-ink mb-1">Full Name</label>
            <input type="text" name="full_name" required
                   value="<?= htmlspecialchars($editUser['full_name']) ?>"
                   class="w-full border border-yellow-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary">
          </div>

          <div>
            <label class="block text-xs font-semibold text-ink mb-1">Email</label>
            <input type="email" name="email" required
                   value="<?= htmlspecialchars($editUser['email']) ?>"
                   class="w-full border border-yellow-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary">
          </div>

          <div>
            <label class="block text-xs font-semibold text-ink mb-1">Role</label>
            <select name="role"
                    class="w-full border border-yellow-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary bg-white">
              <option value="user"  <?= $editUser['role'] === 'user'  ? 'selected' : '' ?>>User</option>
              <option value="admin" <?= $editUser['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
          </div>

          <div class="md:col-span-3 flex items-center justify-end gap-3 mt-2">
            <button type="submit"
                    class="inline-flex items-center px-4 py-2.5 rounded-lg bg-primary text-ink text-sm font-semibold hover:bg-primaryDark">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              Save Changes
            </button>
          </div>
        </form>
      </div>
    <?php endif; ?>

    <!-- Users Table -->
    <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
      <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between">
        <h2 class="text-sm font-semibold text-ink">All Users (<?= count($users) ?>)</h2>
        <span class="text-[11px] text-inkMuted">Showing most recent first</span>
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100 text-sm">
          <thead class="bg-primarySoft">
            <tr>
              <th class="px-4 py-3 text-left font-semibold text-xs text-ink uppercase tracking-wide">ID</th>
              <th class="px-4 py-3 text-left font-semibold text-xs text-ink uppercase tracking-wide">Name</th>
              <th class="px-4 py-3 text-left font-semibold text-xs text-ink uppercase tracking-wide">Email</th>
              <th class="px-4 py-3 text-left font-semibold text-xs text-ink uppercase tracking-wide">Role</th>
              <th class="px-4 py-3 text-right font-semibold text-xs text-ink uppercase tracking-wide">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 bg-white">
          <?php if (empty($users)): ?>
            <tr>
              <td colspan="5" class="px-4 py-6 text-center text-sm text-inkMuted">
                No users found. Try changing the filters.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($users as $u): ?>
              <tr class="hover:bg-bgSoft">
                <td class="px-4 py-3 align-middle text-xs text-inkMuted">#<?= (int)$u['id'] ?></td>
                <td class="px-4 py-3 align-middle">
                  <div class="flex flex-col">
                    <span class="font-medium text-ink"><?= htmlspecialchars($u['full_name']) ?></span>
                  </div>
                </td>
                <td class="px-4 py-3 align-middle text-inkMuted">
                  <?= htmlspecialchars($u['email']) ?>
                </td>
                <td class="px-4 py-3 align-middle">
                  <?php if ($u['role'] === 'admin'): ?>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-red-50 text-red-700">
                      Admin
                    </span>
                  <?php else: ?>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-gray-100 text-gray-700">
                      User
                    </span>
                  <?php endif; ?>
                </td>
                <td class="px-4 py-3 align-middle">
                  <div class="flex items-center justify-end gap-2">
                    <a href="admin_users.php?edit_id=<?= (int)$u['id'] ?>"
                       class="inline-flex items-center px-2.5 py-1.5 rounded-lg border border-yellow-200 text-xs text-ink hover:bg-primarySoft">
                      Edit
                    </a>

                    <form method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                      <input type="hidden" name="action" value="delete">
                      <input type="hidden" name="user_id" value="<?= (int)$u['id'] ?>">
                      <button type="submit"
                              class="inline-flex items-center px-2.5 py-1.5 rounded-lg border border-red-200 text-xs text-red-700 hover:bg-red-50">
                        Delete
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </main>
</body>
</html>
