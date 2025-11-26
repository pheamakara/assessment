<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Server Assessment System</title>
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
                    <h1 class="h2">User Profile</h1>
                </div>

                <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($_GET['success']); ?>
                </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">User Information</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <th>Username:</th>
                                        <td><?php echo htmlspecialchars($data['user']['username']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td><?php echo htmlspecialchars($data['user']['email']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Role:</th>
                                        <td><?php echo htmlspecialchars($data['user']['role']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Last Login:</th>
                                        <td><?php echo $data['user']['last_login'] ? date('Y-m-d H:i:s', strtotime($data['user']['last_login'])) : 'Never'; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Member Since:</th>
                                        <td><?php echo date('Y-m-d H:i:s', strtotime($data['user']['created_at'])); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Change Password</h5>
                            </div>
                            <div class="card-body">
                                <form action="/profile/changePassword" method="POST">
                                    <div class="mb-3">
                                        <label for="current_password" class="form-label">Current Password</label>
                                        <input type="password" class="form-control" id="current_password" name="current_password">
                                        <?php if (!empty($data['current_password_err'])): ?>
                                            <div class="text-danger"><?php echo $data['current_password_err']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="new_password" class="form-label">New Password</label>
                                        <input type="password" class="form-control" id="new_password" name="new_password">
                                        <?php if (!empty($data['new_password_err'])): ?>
                                            <div class="text-danger"><?php echo $data['new_password_err']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                                        <?php if (!empty($data['confirm_password_err'])): ?>
                                            <div class="text-danger"><?php echo $data['confirm_password_err']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary">Change Password</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
