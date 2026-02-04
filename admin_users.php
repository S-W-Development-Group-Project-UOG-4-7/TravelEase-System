<?php
// admin_users.php
require_once 'admin_auth.php';
require_once 'db.php';

// -------------------------
// Flash messages
// -------------------------
$success = $_SESSION['flash_success'] ?? '';
$error   = $_SESSION['flash_error'] ?? '';
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

// -------------------------
// Helpers
// -------------------------
function normalize_role_name(string $role): string {
    $role = trim(strtolower($role));
    $role = preg_replace('/[^a-z0-9_-]/', '', $role);
    return $role;
}

function esc($v): string {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

function initials(string $name): string {
    $name = trim($name);
    if ($name === '') return 'U';
    $parts = preg_split('/\s+/', $name);
    $first = strtoupper(substr($parts[0] ?? '', 0, 1));
    $last  = strtoupper(substr($parts[count($parts)-1] ?? '', 0, 1));
    return $first . ($last !== $first ? $last : '');
}

function role_badge(string $role): array {
    $role = strtolower($role);
    if ($role === 'admin')     return ['bg' => 'bg-red-50',   'text' => 'text-red-700',   'label' => 'Admin'];
    if ($role === 'marketing') return ['bg' => 'bg-blue-50',  'text' => 'text-blue-700',  'label' => 'Marketing'];
    if ($role === 'user')      return ['bg' => 'bg-gray-100', 'text' => 'text-gray-700',  'label' => 'Client'];
    return ['bg' => 'bg-purple-50', 'text' => 'text-purple-700', 'label' => ucfirst($role)];
}

function role_label(string $role): string {
    $role = strtolower($role);
    if ($role === 'user') return 'Client';
    return ucfirst($role);
}

// -------------------------
// Load roles + role usage counts
// -------------------------
$roles = [];
$roleCounts = []; // role => count
try {
    $stmt = $pdo->query("SELECT name FROM roles ORDER BY name ASC");
    $roles = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    $roles = ['user', 'admin', 'marketing'];
    $error = $error ?: 'Roles table not found. Please create the roles table first.';
}

// Ensure defaults always exist in UI list
foreach (['user', 'admin', 'marketing'] as $must) {
    if (!in_array($must, $roles, true)) $roles[] = $must;
}
sort($roles);

// Role usage counts from users table (works even if roles table missing some)
try {
    $stmt = $pdo->query("SELECT role, COUNT(*) AS cnt FROM users GROUP BY role");
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $r = normalize_role_name($row['role'] ?? 'user');
        $roleCounts[$r] = (int)$row['cnt'];
        if ($r && !in_array($r, $roles, true)) $roles[] = $r;
    }
    sort($roles);
} catch (PDOException $e) {
    // ignore
}

// -------------------------
// POST actions: create_role / delete_role / update
// -------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // Create role
    if ($action === 'create_role') {
        $newRole = normalize_role_name($_POST['new_role'] ?? '');
        if ($newRole === '' || strlen($newRole) < 2) {
            $_SESSION['flash_error'] = 'Role name is required (min 2 chars). Use letters/numbers/_/- only.';
        } elseif (in_array($newRole, ['postgres', 'root'], true)) {
            $_SESSION['flash_error'] = 'That role name is not allowed.';
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO roles (name) VALUES (:name)");
                $stmt->execute([':name' => $newRole]);
                $_SESSION['flash_success'] = "Role '$newRole' created successfully.";
            } catch (PDOException $e) {
                $_SESSION['flash_error'] = "Could not create role. It may already exist.";
            }
        }
        header('Location: admin_users.php');
        exit();
    }

    // Delete role (only if not default + not in use)
    if ($action === 'delete_role') {
        $roleToDelete = normalize_role_name($_POST['role_name'] ?? '');

        if ($roleToDelete === '') {
            $_SESSION['flash_error'] = 'Invalid role selected.';
        } elseif (in_array($roleToDelete, ['user', 'admin', 'marketing'], true)) {
            $_SESSION['flash_error'] = 'Default roles cannot be deleted.';
        } else {
            try {
                $check = $pdo->prepare("SELECT COUNT(*) FROM users WHERE role = :role");
                $check->execute([':role' => $roleToDelete]);
                $count = (int)$check->fetchColumn();

                if ($count > 0) {
                    $_SESSION['flash_error'] = "Cannot delete role '$roleToDelete' because $count user(s) are assigned.";
                } else {
                    $del = $pdo->prepare("DELETE FROM roles WHERE name = :name");
                    $del->execute([':name' => $roleToDelete]);
                    $_SESSION['flash_success'] = "Role '$roleToDelete' deleted successfully.";
                }
            } catch (PDOException $e) {
                $_SESSION['flash_error'] = 'Database error while deleting role.';
            }
        }
        header('Location: admin_users.php');
        exit();
    }

    // Update user
    if ($action === 'update') {
        $userId   = (int)($_POST['user_id'] ?? 0);
        $fullName = trim($_POST['full_name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $role     = normalize_role_name($_POST['role'] ?? 'user');

        if ($userId <= 0 || $fullName === '' || $email === '') {
            $_SESSION['flash_error'] = 'Please fill all required fields correctly.';
            header('Location: admin_users.php');
            exit();
        }

        // allow only known roles (but include roles used in users table too)
        if (!in_array($role, $roles, true)) {
            $_SESSION['flash_error'] = 'Invalid role selected. Create it first.';
            header('Location: admin_users.php');
            exit();
        }

        // Prevent removing admin from themselves
        $currentUserId = (int)($_SESSION['user_id'] ?? 0);
        if ($userId === $currentUserId && $role !== 'admin') {
            $_SESSION['flash_error'] = 'You cannot remove your own admin role.';
            header('Location: admin_users.php');
            exit();
        }

        try {
            $stmt = $pdo->prepare("
                UPDATE users
                SET full_name = :full_name,
                    email = :email,
                    role = :role
                WHERE id = :id
            ");
            $stmt->execute([
                ':full_name' => $fullName,
                ':email'     => $email,
                ':role'      => $role,
                ':id'        => $userId
            ]);
            $_SESSION['flash_success'] = 'User updated successfully.';
        } catch (PDOException $e) {
            $_SESSION['flash_error'] = 'Database error while updating user.';
        }

        header('Location: admin_users.php');
        exit();
    }

    // Block any delete attempt
    if ($action === 'delete') {
        $_SESSION['flash_error'] = 'User deletion is disabled.';
        header('Location: admin_users.php');
        exit();
    }
}

// -------------------------
// Filters + Pagination
// -------------------------
$search     = trim($_GET['q'] ?? '');
$roleFilter = normalize_role_name($_GET['role'] ?? 'all');

$page     = (int)($_GET['page'] ?? 1);
$page     = max(1, $page);
$perPage  = 10;
$offset   = ($page - 1) * $perPage;

$where  = [];
$params = [];

if ($search !== '') {
    $where[]           = "(full_name ILIKE :search OR email ILIKE :search)";
    $params[':search'] = '%' . $search . '%';
}

if ($roleFilter !== 'all' && in_array($roleFilter, $roles, true)) {
    $where[]         = "role = :role";
    $params[':role'] = $roleFilter;
}

$whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

// Count total matching users
$totalRows = 0;
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users $whereSql");
    $stmt->execute($params);
    $totalRows = (int)$stmt->fetchColumn();
} catch (PDOException $e) {
    $totalRows = 0;
}

$totalPages = max(1, (int)ceil($totalRows / $perPage));

// Fetch paginated users
$users = [];
try {
    $sql  = "SELECT id, full_name, email, role FROM users $whereSql ORDER BY id DESC LIMIT $perPage OFFSET $offset";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = $error ?: 'Database error while fetching users.';
}

$adminName = $_SESSION['full_name'] ?? 'Admin';

// Simple stats
$totalRoles = count($roles);
$showingFrom = min($totalRows, $offset + 1);
$showingTo   = min($totalRows, $offset + $perPage);
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
            soft: '0 8px 24px -12px rgba(0,0,0,0.25)',
          }
        }
      }
    };
  </script>

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: Inter, sans-serif; }
    .modal-backdrop { background: rgba(17,24,39,0.55); }
  </style>
</head>

<body class="min-h-screen bg-gradient-to-b from-bgSoft to-white">

  <!-- Toast container -->
  <div id="toastWrap" class="fixed top-4 right-4 z-50 space-y-2"></div>

  <script>
    function showToast(message, type = 'info') {
      const wrap = document.getElementById('toastWrap');
      const el = document.createElement('div');

      const base = "px-4 py-3 rounded-xl shadow-soft border text-sm flex items-start gap-2";
      let cls = "bg-white border-gray-200 text-gray-800";
      let icon = `
        <svg class="h-5 w-5 mt-0.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z"/>
        </svg>`;

      if (type === 'success') {
        cls = "bg-green-50 border-green-200 text-green-900";
        icon = `
          <svg class="h-5 w-5 mt-0.5 text-green-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          </svg>`;
      } else if (type === 'error') {
        cls = "bg-red-50 border-red-200 text-red-900";
        icon = `
          <svg class="h-5 w-5 mt-0.5 text-red-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>`;
      }

      el.className = `${base} ${cls}`;
      el.innerHTML = `
        ${icon}
        <div class="flex-1">${message}</div>
        <button class="text-xs opacity-70 hover:opacity-100" aria-label="Close">✕</button>
      `;

      el.querySelector('button').onclick = () => el.remove();
      wrap.appendChild(el);

      setTimeout(() => { el.style.opacity = '0'; el.style.transform = 'translateY(-6px)'; }, 3500);
      setTimeout(() => { el.remove(); }, 4200);
    }

    // Flash message toasts
    <?php if ($success): ?>
      window.addEventListener('load', () => showToast(<?= json_encode($success) ?>, 'success'));
    <?php endif; ?>
    <?php if ($error): ?>
      window.addEventListener('load', () => showToast(<?= json_encode($error) ?>, 'error'));
    <?php endif; ?>
  </script>

  <!-- Top Navbar -->
  <header class="sticky top-0 z-40 bg-white/80 backdrop-blur border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <a href="admin_dashboard.php" class="inline-flex items-center gap-2 text-xs font-semibold text-inkMuted hover:text-ink">
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
          </svg>
          Dashboard
        </a>

        <div class="hidden sm:flex items-center gap-2 ml-2">
          <img src="img/Logo.png" class="h-8 w-8 object-contain" alt="Logo">
          <div class="leading-tight">
            <div class="flex items-center gap-2">
              <span class="text-lg font-bold text-ink">TravelEase</span>
              <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-primarySoft text-primaryDark">Admin</span>
            </div>
            <div class="text-[11px] text-inkMuted">User & Role Management</div>
          </div>
        </div>
      </div>

      <div class="flex items-center gap-3">
        <div class="hidden sm:block text-sm text-inkMuted">
          Hello, <span class="font-semibold text-ink"><?= esc($adminName) ?></span>
        </div>
        <a href="logout.php" class="inline-flex items-center gap-2 px-3 py-2 rounded-xl border border-gray-200 text-sm hover:bg-gray-50">
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1"/>
          </svg>
          Logout
        </a>
      </div>
    </div>
  </header>

  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Header + Stats -->
    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4 mb-6">
      <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-ink tracking-tight">Manage Users</h1>
        <p class="text-sm text-inkMuted mt-1">Create roles, assign roles, and update user profiles.</p>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 w-full lg:w-auto">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-soft p-4">
          <div class="flex items-center justify-between">
            <div>
              <div class="text-xs font-semibold text-inkMuted uppercase">Total Users</div>
              <div class="text-2xl font-extrabold text-ink mt-1"><?= (int)$totalRows ?></div>
            </div>
            <div class="h-10 w-10 rounded-2xl bg-primarySoft flex items-center justify-center text-primaryDark">
              <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m8-4a4 4 0 10-8 0 4 4 0 008 0z"/>
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-soft p-4">
          <div class="flex items-center justify-between">
            <div>
              <div class="text-xs font-semibold text-inkMuted uppercase">Total Roles</div>
              <div class="text-2xl font-extrabold text-ink mt-1"><?= (int)$totalRoles ?></div>
            </div>
            <div class="h-10 w-10 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-700">
              <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 12h10M7 17h10"/>
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-soft p-4">
          <div class="flex items-center justify-between">
            <div>
              <div class="text-xs font-semibold text-inkMuted uppercase">Showing</div>
              <div class="text-sm font-semibold text-ink mt-2">
                <?= (int)$showingFrom ?>–<?= (int)$showingTo ?> / <?= (int)$totalRows ?>
              </div>
            </div>
            <div class="h-10 w-10 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-700">
              <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M5 11h14M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
              </svg>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Roles Management -->
    <section class="bg-white rounded-2xl border border-gray-100 shadow-soft p-5 mb-6">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
          <h2 class="text-lg font-bold text-ink flex items-center gap-2">
            <svg class="h-5 w-5 text-primaryDark" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a4 4 0 110 8m0-8a4 4 0 000 8m0 0v2m0-2a4 4 0 01-4-4m4 4a4 4 0 004-4"/>
            </svg>
            Roles
          </h2>
          <p class="text-sm text-inkMuted mt-1">Create roles and manage role assignments (delete disabled if in use).</p>
        </div>

        <form method="POST" class="flex flex-col sm:flex-row gap-2 sm:items-end">
          <input type="hidden" name="action" value="create_role">
          <div>
            <label class="block text-xs font-semibold text-ink mb-1">New Role</label>
            <input type="text" name="new_role" placeholder="e.g., guide"
                   class="w-full sm:w-56 border border-yellow-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            <p class="text-[11px] text-inkMuted mt-1">letters/numbers/_/- only</p>
          </div>
          <button type="submit"
                  class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-ink text-sm font-semibold hover:bg-primaryDark">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Create
          </button>
        </form>
      </div>

      <div class="mt-5 flex flex-wrap gap-2">
        <?php foreach ($roles as $r): ?>
          <?php
            $badge = role_badge($r);
            $count = (int)($roleCounts[$r] ?? 0);
            $isDefault = in_array($r, ['user','admin','marketing'], true);
            $canDelete = (!$isDefault && $count === 0);
          ?>
          <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-[12px] font-semibold <?= $badge['bg'] ?> <?= $badge['text'] ?>">
            <span><?= esc($badge['label']) ?></span>
            <span class="px-2 py-0.5 rounded-full bg-white/70 text-[11px] font-bold">
              <?= $count ?>
            </span>

            <?php if (!$isDefault): ?>
              <?php if ($canDelete): ?>
                <form method="POST" class="inline"
                      onsubmit="return confirm('Delete role <?= esc($r) ?>?');">
                  <input type="hidden" name="action" value="delete_role">
                  <input type="hidden" name="role_name" value="<?= esc($r) ?>">
                  <button type="submit"
                          class="inline-flex items-center gap-1 text-[11px] underline opacity-80 hover:opacity-100">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0H7m3-3h4a1 1 0 011 1v2H9V5a1 1 0 011-1z"/>
                    </svg>
                    delete
                  </button>
                </form>
              <?php else: ?>
                <span class="text-[11px] opacity-70" title="Cannot delete: role is assigned to users.">
                  in use
                </span>
              <?php endif; ?>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- Filters -->
    <section class="bg-white rounded-2xl border border-gray-100 shadow-soft p-5 mb-6">
      <form method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
        <div class="md:col-span-7">
          <label class="block text-xs font-semibold text-ink mb-1">Search</label>
          <div class="relative">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-inkMuted">
              <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z"/>
              </svg>
            </span>
            <input type="text" name="q" value="<?= esc($search) ?>"
                   placeholder="Name or email..."
                   class="w-full border border-yellow-200 rounded-xl pl-10 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
          </div>
        </div>

        <div class="md:col-span-3">
          <label class="block text-xs font-semibold text-ink mb-1">Role</label>
          <select name="role"
                  class="w-full border border-yellow-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary bg-white">
            <option value="all" <?= ($roleFilter === 'all' ? 'selected' : '') ?>>All</option>
            <?php foreach ($roles as $r): ?>
              <option value="<?= esc($r) ?>" <?= ($roleFilter === $r ? 'selected' : '') ?>>
                <?= esc(role_label($r)) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="md:col-span-2 flex gap-2">
          <button type="submit"
                  class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-ink text-sm font-semibold hover:bg-primaryDark">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M7 12h10M10 18h4"/>
            </svg>
            Apply
          </button>
          <a href="admin_users.php"
             class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold hover:bg-gray-50">
            Reset
          </a>
        </div>
      </form>
    </section>

    <!-- Users Table -->
    <section class="bg-white rounded-2xl border border-gray-100 shadow-soft overflow-hidden">
      <div class="px-5 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <div>
          <h2 class="text-sm font-bold text-ink flex items-center gap-2">
            <svg class="h-4 w-4 text-inkMuted" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m8-4a4 4 0 10-8 0 4 4 0 008 0z"/>
            </svg>
            Users
          </h2>
          <p class="text-xs text-inkMuted">Click “Quick Edit” to update without leaving the page.</p>
        </div>

        <div class="text-xs text-inkMuted">
          Page <span class="font-semibold text-ink"><?= (int)$page ?></span> of <span class="font-semibold text-ink"><?= (int)$totalPages ?></span>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100 text-sm">
          <thead class="bg-primarySoft">
            <tr>
              <th class="px-4 py-3 text-left font-bold text-xs text-ink uppercase tracking-wide">User</th>
              <th class="px-4 py-3 text-left font-bold text-xs text-ink uppercase tracking-wide">Email</th>
              <th class="px-4 py-3 text-left font-bold text-xs text-ink uppercase tracking-wide">Role</th>
              <th class="px-4 py-3 text-right font-bold text-xs text-ink uppercase tracking-wide">Actions</th>
            </tr>
          </thead>

          <tbody class="divide-y divide-gray-100 bg-white">
          <?php if (empty($users)): ?>
            <tr>
              <td colspan="4" class="px-4 py-10 text-center text-sm text-inkMuted">
                No users found. Try changing the filters.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($users as $u): ?>
              <?php
                $uId = (int)$u['id'];
                $uName = $u['full_name'] ?? '';
                $uEmail = $u['email'] ?? '';
                $uRole = $u['role'] ?? 'user';
                $badge = role_badge($uRole);
              ?>
              <tr class="hover:bg-bgSoft">
                <td class="px-4 py-3 align-middle">
                  <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-2xl bg-gray-100 flex items-center justify-center font-extrabold text-gray-700">
                      <?= esc(initials($uName)) ?>
                    </div>
                    <div class="min-w-0">
                      <div class="font-semibold text-ink truncate">
                        <?= esc($uName) ?>
                        <span class="text-xs text-inkMuted font-medium">#<?= $uId ?></span>
                      </div>
                      <div class="text-xs text-inkMuted">Active user</div>
                    </div>
                  </div>
                </td>

                <td class="px-4 py-3 align-middle">
                  <div class="flex items-center gap-2">
                    <span class="text-inkMuted truncate max-w-[240px]"><?= esc($uEmail) ?></span>
                    <button type="button"
                            class="inline-flex items-center gap-1 px-2 py-1 rounded-lg border border-gray-200 text-xs hover:bg-gray-50"
                            onclick="navigator.clipboard.writeText('<?= esc($uEmail) ?>'); showToast('Email copied', 'success');">
                      <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16h8m-8-4h8m-7-8h6a2 2 0 012 2v14a2 2 0 01-2 2H9a2 2 0 01-2-2V6a2 2 0 012-2z"/>
                      </svg>
                      Copy
                    </button>
                  </div>
                </td>

                <td class="px-4 py-3 align-middle">
                  <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold <?= $badge['bg'] ?> <?= $badge['text'] ?>">
                    <?= esc($badge['label']) ?>
                  </span>
                </td>

                <td class="px-4 py-3 align-middle">
                  <div class="flex items-center justify-end gap-2">
                    <button type="button"
                            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl border border-yellow-200 text-xs font-semibold text-ink hover:bg-primarySoft"
                            data-user='<?= esc(json_encode([
                                'id' => $uId,
                                'full_name' => $uName,
                                'email' => $uEmail,
                                'role' => normalize_role_name($uRole),
                            ])) ?>'
                            onclick="openEditModal(this)">
                      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l10.586-10.586z"/>
                      </svg>
                      Quick Edit
                    </button>

                    <!-- Delete button removed (by request) -->
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="px-5 py-4 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div class="text-xs text-inkMuted">
          Showing <span class="font-semibold text-ink"><?= (int)$showingFrom ?></span>–<span class="font-semibold text-ink"><?= (int)$showingTo ?></span>
          of <span class="font-semibold text-ink"><?= (int)$totalRows ?></span>
        </div>

        <div class="flex items-center gap-2">
          <?php
            $baseParams = [];
            if ($search !== '') $baseParams['q'] = $search;
            if ($roleFilter !== 'all') $baseParams['role'] = $roleFilter;
          ?>

          <?php if ($page > 1): ?>
            <?php $baseParams['page'] = $page - 1; ?>
            <a href="admin_users.php?<?= esc(http_build_query($baseParams)) ?>"
               class="inline-flex items-center gap-2 px-3 py-2 rounded-xl border border-gray-200 text-sm hover:bg-gray-50">
              <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
              </svg>
              Prev
            </a>
          <?php else: ?>
            <span class="inline-flex items-center gap-2 px-3 py-2 rounded-xl border border-gray-100 text-sm text-gray-300 cursor-not-allowed">
              Prev
            </span>
          <?php endif; ?>

          <?php if ($page < $totalPages): ?>
            <?php $baseParams['page'] = $page + 1; ?>
            <a href="admin_users.php?<?= esc(http_build_query($baseParams)) ?>"
               class="inline-flex items-center gap-2 px-3 py-2 rounded-xl border border-gray-200 text-sm hover:bg-gray-50">
              Next
              <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
              </svg>
            </a>
          <?php else: ?>
            <span class="inline-flex items-center gap-2 px-3 py-2 rounded-xl border border-gray-100 text-sm text-gray-300 cursor-not-allowed">
              Next
            </span>
          <?php endif; ?>
        </div>
      </div>
    </section>

  </main>

  <!-- Quick Edit Modal -->
  <div id="editModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 modal-backdrop" onclick="closeEditModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
      <div class="w-full max-w-xl bg-white rounded-2xl shadow-soft border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
          <div class="font-bold text-ink flex items-center gap-2">
            <svg class="h-5 w-5 text-primaryDark" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l10.586-10.586z"/>
            </svg>
            Quick Edit User
          </div>
          <button class="text-inkMuted hover:text-ink" onclick="closeEditModal()">✕</button>
        </div>

        <form method="POST" class="p-5 space-y-4">
          <input type="hidden" name="action" value="update">
          <input type="hidden" name="user_id" id="m_user_id" value="">

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-semibold text-ink mb-1">Full Name</label>
              <input type="text" name="full_name" id="m_full_name" required
                     class="w-full border border-yellow-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            </div>

            <div>
              <label class="block text-xs font-semibold text-ink mb-1">Email</label>
              <input type="email" name="email" id="m_email" required
                     class="w-full border border-yellow-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
          </div>

          <div>
            <label class="block text-xs font-semibold text-ink mb-1">Role</label>
            <select name="role" id="m_role"
                    class="w-full border border-yellow-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary bg-white">
              <?php foreach ($roles as $r): ?>
                <option value="<?= esc($r) ?>"><?= esc(role_label($r)) ?></option>
              <?php endforeach; ?>
            </select>
            <p class="text-[11px] text-inkMuted mt-1">Need a new role? Create it in the Roles section.</p>
          </div>

          <div class="flex items-center justify-end gap-2 pt-2">
            <button type="button"
                    onclick="closeEditModal()"
                    class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold hover:bg-gray-50">
              Cancel
            </button>
            <button type="submit"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-ink text-sm font-semibold hover:bg-primaryDark">
              <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
              </svg>
              Save
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    function openEditModal(btn) {
      try {
        const data = JSON.parse(btn.getAttribute('data-user') || '{}');
        document.getElementById('m_user_id').value = data.id || '';
        document.getElementById('m_full_name').value = data.full_name || '';
        document.getElementById('m_email').value = data.email || '';
        document.getElementById('m_role').value = data.role || 'user';

        document.getElementById('editModal').classList.remove('hidden');
      } catch (e) {
        showToast('Could not open editor.', 'error');
      }
    }

    function closeEditModal() {
      document.getElementById('editModal').classList.add('hidden');
    }

    // Close modal with ESC
    window.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') closeEditModal();
    });
  </script>

</body>
</html>
