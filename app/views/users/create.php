<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User - Server Assessment System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <?php include '../app/views/partials/navbar.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <?php include '../app/views/partials/sidebar.php'; ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Add User</h1>
                </div>

                <div class="col-md-6">
                    <form action="/users/store" method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?php echo $data['username']; ?>">
                            <?php if (!empty($data['username_err'])): ?>
                                <div class="text-danger"><?php echo $data['username_err']; ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo $data['email']; ?>">
                            <?php if (!empty($data['email_err'])): ?>
                                <div class="text-danger"><?php echo $data['email_err']; ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role">
                                <option value="">Select Role</option>
                                <option value="ADMIN" <?php echo ($data['role'] === 'ADMIN') ? 'selected' : ''; ?>>Admin</option>
                                <option value="CLOUD_MANAGER" <?php echo ($data['role'] === 'CLOUD_MANAGER') ? 'selected' : ''; ?>>Cloud Manager</option>
                                <option value="CLOUD_ENGINEER" <?php echo ($data['role'] === 'CLOUD_ENGINEER') ? 'selected' : ''; ?>>Cloud Engineer</option>
                                <option value="SECURITY" <?php echo ($data['role'] === 'SECURITY') ? 'selected' : ''; ?>>Security</option>
                                <option value="AUDITOR" <?php echo ($data['role'] === 'AUDITOR') ? 'selected' : ''; ?>>Auditor</option>
                            </select>
                            <?php if (!empty($data['role_err'])): ?>
                                <div class="text-danger"><?php echo $data['role_err']; ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Add User</button>
                        <a href="/users" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
