<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Password - Server Assessment System</title>
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
                    <h1 class="h2">Set Password</h1>
                </div>

                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            User: <?php echo htmlspecialchars($data['user']['username']); ?>
                        </div>
                        <div class="card-body">
                            <form action="/users/updatePassword/<?php echo $data['user']['id']; ?>" method="POST">
                                <div class="mb-3">
                                    <label for="password" class="form-label">New Password</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                    <?php if (!empty($data['password_err'])): ?>
                                        <div class="text-danger"><?php echo $data['password_err']; ?></div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                                    <?php if (!empty($data['confirm_password_err'])): ?>
                                        <div class="text-danger"><?php echo $data['confirm_password_err']; ?></div>
                                    <?php endif; ?>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Set Password</button>
                                <a href="/users" class="btn btn-secondary">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
