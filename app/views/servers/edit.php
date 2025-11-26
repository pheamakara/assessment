<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Server - Server Assessment System</title>
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
                    <h1 class="h2">Edit Server</h1>
                </div>

                <div class="col-md-8">
                    <form action="/servers/update/<?php echo $data['server']['id']; ?>" method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Server Name *</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $data['name']; ?>">
                                    <?php if (!empty($data['name_err'])): ?>
                                        <div class="text-danger"><?php echo $data['name_err']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="ip" class="form-label">IP Address *</label>
                                    <input type="text" class="form-control" id="ip" name="ip" value="<?php echo $data['ip']; ?>">
                                    <?php if (!empty($data['ip_err'])): ?>
                                        <div class="text-danger"><?php echo $data['ip_err']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="os" class="form-label">Operating System *</label>
                                    <select class="form-select" id="os" name="os">
                                        <option value="">Select OS</option>
                                        <option value="Ubuntu" <?php echo ($data['os'] === 'Ubuntu') ? 'selected' : ''; ?>>Ubuntu</option>
                                        <option value="Rocky" <?php echo ($data['os'] === 'Rocky') ? 'selected' : ''; ?>>Rocky</option>
                                        <option value="Windows" <?php echo ($data['os'] === 'Windows') ? 'selected' : ''; ?>>Windows</option>
                                        <option value="RHEL" <?php echo ($data['os'] === 'RHEL') ? 'selected' : ''; ?>>RHEL</option>
                                        <option value="Other" <?php echo ($data['os'] === 'Other') ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                    <?php if (!empty($data['os_err'])): ?>
                                        <div class="text-danger"><?php echo $data['os_err']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="site" class="form-label">Site *</label>
                                    <select class="form-select" id="site" name="site">
                                        <option value="">Select Site</option>
                                        <option value="Phnom Penh" <?php echo ($data['site'] === 'Phnom Penh') ? 'selected' : ''; ?>>Phnom Penh</option>
                                        <option value="Siem Reap" <?php echo ($data['site'] === 'Siem Reap') ? 'selected' : ''; ?>>Siem Reap</option>
                                        <option value="Battambang" <?php echo ($data['site'] === 'Battambang') ? 'selected' : ''; ?>>Battambang</option>
                                    </select>
                                    <?php if (!empty($data['site_err'])): ?>
                                        <div class="text-danger"><?php echo $data['site_err']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Server Type *</label>
                                    <select class="form-select" id="type" name="type">
                                        <option value="">Select Type</option>
                                        <option value="Virtual" <?php echo ($data['type'] === 'Virtual') ? 'selected' : ''; ?>>Virtual</option>
                                        <option value="Physical" <?php echo ($data['type'] === 'Physical') ? 'selected' : ''; ?>>Physical</option>
                                    </select>
                                    <?php if (!empty($data['type_err'])): ?>
                                        <div class="text-danger"><?php echo $data['type_err']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="env" class="form-label">Environment *</label>
                                    <select class="form-select" id="env" name="env">
                                        <option value="">Select Environment</option>
                                        <option value="PROD" <?php echo ($data['env'] === 'PROD') ? 'selected' : ''; ?>>PROD</option>
                                        <option value="DEV" <?php echo ($data['env'] === 'DEV') ? 'selected' : ''; ?>>DEV</option>
                                        <option value="UAT" <?php echo ($data['env'] === 'UAT') ? 'selected' : ''; ?>>UAT</option>
                                        <option value="DR" <?php echo ($data['env'] === 'DR') ? 'selected' : ''; ?>>DR</option>
                                    </select>
                                    <?php if (!empty($data['env_err'])): ?>
                                        <div class="text-danger"><?php echo $data['env_err']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="owner" class="form-label">Owner *</label>
                                    <input type="text" class="form-control" id="owner" name="owner" value="<?php echo $data['owner']; ?>">
                                    <?php if (!empty($data['owner_err'])): ?>
                                        <div class="text-danger"><?php echo $data['owner_err']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="pic" class="form-label">Person In Charge (PIC) *</label>
                                    <input type="text" class="form-control" id="pic" name="pic" value="<?php echo $data['pic']; ?>">
                                    <?php if (!empty($data['pic_err'])): ?>
                                        <div class="text-danger"><?php echo $data['pic_err']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="vendor" class="form-label">Vendor</label>
                                    <input type="text" class="form-control" id="vendor" name="vendor" value="<?php echo $data['vendor']; ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="hypervisor" class="form-label">Hypervisor</label>
                                    <input type="text" class="form-control" id="hypervisor" name="hypervisor" value="<?php echo $data['hypervisor']; ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="cpu" class="form-label">CPU</label>
                                    <input type="text" class="form-control" id="cpu" name="cpu" value="<?php echo $data['cpu']; ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="ram" class="form-label">RAM</label>
                                    <input type="text" class="form-control" id="ram" name="ram" value="<?php echo $data['ram']; ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="disk" class="form-label">Disk</label>
                                    <input type="text" class="form-control" id="disk" name="disk" value="<?php echo $data['disk']; ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="asset_class" class="form-label">Asset Class *</label>
                                    <select class="form-select" id="asset_class" name="asset_class">
                                        <option value="">Select Asset Class</option>
                                        <option value="Critical" <?php echo ($data['asset_class'] === 'Critical') ? 'selected' : ''; ?>>Critical</option>
                                        <option value="High" <?php echo ($data['asset_class'] === 'High') ? 'selected' : ''; ?>>High</option>
                                        <option value="Medium" <?php echo ($data['asset_class'] === 'Medium') ? 'selected' : ''; ?>>Medium</option>
                                        <option value="Low" <?php echo ($data['asset_class'] === 'Low') ? 'selected' : ''; ?>>Low</option>
                                    </select>
                                    <?php if (!empty($data['asset_class_err'])): ?>
                                        <div class="text-danger"><?php echo $data['asset_class_err']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="asset_type" class="form-label">Asset Type *</label>
                                    <select class="form-select" id="asset_type" name="asset_type">
                                        <option value="">Select Asset Type</option>
                                        <option value="Server" <?php echo ($data['asset_type'] === 'Server') ? 'selected' : ''; ?>>Server</option>
                                        <option value="Storage" <?php echo ($data['asset_type'] === 'Storage') ? 'selected' : ''; ?>>Storage</option>
                                        <option value="Network" <?php echo ($data['asset_type'] === 'Network') ? 'selected' : ''; ?>>Network</option>
                                        <option value="Database" <?php echo ($data['asset_type'] === 'Database') ? 'selected' : ''; ?>>Database</option>
                                    </select>
                                    <?php if (!empty($data['asset_type_err'])): ?>
                                        <div class="text-danger"><?php echo $data['asset_type_err']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="deploy_date" class="form-label">Deployment Date</label>
                                    <input type="date" class="form-control" id="deploy_date" name="deploy_date" value="<?php echo $data['deploy_date']; ?>">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Server</button>
                        <a href="/servers" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
