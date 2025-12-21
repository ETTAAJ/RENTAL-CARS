<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    // Include config to get logo path for favicon
    require_once __DIR__ . '/../config.php';
    
    // Require admin login for all admin pages
    requireAdminLogin();
    
    $favicon_path = getLogoPath();
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Admin Panel'; ?></title>
    <link rel="icon" type="image/png" href="../<?php echo htmlspecialchars($favicon_path); ?>">
    <link rel="apple-touch-icon" href="../<?php echo htmlspecialchars($favicon_path); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #F8F9FA;
            font-family: 'Inter', sans-serif;
        }
        .admin-header {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem 2rem;
            margin-bottom: 2rem;
        }
        .admin-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        @media (max-width: 768px) {
            .admin-header {
                padding: 1rem;
            }
            .admin-container {
                padding: 0 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="admin-header">
        <div class="admin-container">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="bi bi-gear"></i> Admin Panel
                </h4>
                <div class="d-flex gap-2 align-items-center">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="settingsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-sliders"></i> Settings
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="settingsDropdown">
                            <li><a class="dropdown-item" href="settings.php"><i class="bi bi-currency-exchange"></i> Currency & Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="admin-container">

