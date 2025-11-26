<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Server Assessment System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Server Assessment System</h3>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <small>
                                <strong>Note:</strong> LDAP authentication is enabled. You can log in with your LDAP credentials.
                                Local accounts are also supported.
                            </small>
                        </div>
                        <form action="/auth/login" method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?php echo $data['username'] ?? ''; ?>">
                                <?php if (!empty($data['username_err'])): ?>
                                    <div class="text-danger"><?php echo $data['username_err']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password">
                                <?php if (!empty($data['password_err'])): ?>
                                    <div class="text-danger"><?php echo $data['password_err']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
