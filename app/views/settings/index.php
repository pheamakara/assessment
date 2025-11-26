<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Server Assessment System</title>
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
                    <h1 class="h2">Settings</h1>
                </div>

                <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($_GET['success']); ?>
                </div>
                <?php endif; ?>

                <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
                <?php endif; ?>

                <div class="row">
                    <!-- General Settings -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">General Settings</h5>
                            </div>
                            <div class="card-body">
                                <form action="/settings/update" method="POST">
                                    <div class="mb-3">
                                        <label for="company_name" class="form-label">Company Name *</label>
                                        <input type="text" class="form-control" id="company_name" name="company_name" value="<?php echo $data['company_name']; ?>">
                                        <?php if (!empty($data['company_name_err'])): ?>
                                            <div class="text-danger"><?php echo $data['company_name_err']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="logo" class="form-label">Company Logo</label>
                                        <?php if (!empty($data['company_logo'])): ?>
                                        <div class="mb-2">
                                            <img src="<?php echo $data['company_logo']; ?>" alt="Company Logo" class="img-fluid" style="max-height: 100px;">
                                        </div>
                                        <?php endif; ?>
                                        <input type="file" class="form-control" id="logo" name="logo">
                                        <div class="form-text">Upload a PNG or JPG image (max 5MB)</div>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary">Update General Settings</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Email Settings -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Email Settings</h5>
                            </div>
                            <div class="card-body">
                                <form action="/settings/update" method="POST">
                                    <div class="mb-3">
                                        <label for="smtp_host" class="form-label">SMTP Host</label>
                                        <input type="text" class="form-control" id="smtp_host" name="smtp_host" value="<?php echo $data['smtp_host']; ?>">
                                        <?php if (!empty($data['smtp_host_err'])): ?>
                                            <div class="text-danger"><?php echo $data['smtp_host_err']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="smtp_port" class="form-label">SMTP Port</label>
                                        <input type="text" class="form-control" id="smtp_port" name="smtp_port" value="<?php echo $data['smtp_port']; ?>">
                                        <?php if (!empty($data['smtp_port_err'])): ?>
                                            <div class="text-danger"><?php echo $data['smtp_port_err']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="smtp_user" class="form-label">SMTP Username</label>
                                        <input type="text" class="form-control" id="smtp_user" name="smtp_user" value="<?php echo $data['smtp_user']; ?>">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="smtp_pass" class="form-label">SMTP Password</label>
                                        <input type="password" class="form-control" id="smtp_pass" name="smtp_pass" value="<?php echo $data['smtp_pass']; ?>">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="smtp_from" class="form-label">From Email</label>
                                        <input type="email" class="form-control" id="smtp_from" name="smtp_from" value="<?php echo $data['smtp_from']; ?>">
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary">Update Email Settings</button>
                                    <a href="/settings/testEmail" class="btn btn-secondary">Test Email</a>
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
