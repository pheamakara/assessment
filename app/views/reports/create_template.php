<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Report Template - Server Assessment System</title>
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
                    <h1 class="h2">Create Report Template</h1>
                </div>

                <div class="col-md-8">
                    <form action="/reports/storeTemplate" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Template Name *</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo $data['name']; ?>">
                            <?php if (!empty($data['name_err'])): ?>
                                <div class="text-danger"><?php echo $data['name_err']; ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"><?php echo $data['description']; ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="type" class="form-label">Report Type *</label>
                            <select class="form-select" id="type" name="type">
                                <option value="">Select Type</option>
                                <option value="SERVER_LIST" <?php echo ($data['type'] === 'SERVER_LIST') ? 'selected' : ''; ?>>Server List</option>
                                <option value="CHECKLIST" <?php echo ($data['type'] === 'CHECKLIST') ? 'selected' : ''; ?>>Checklist</option>
                            </select>
                            <?php if (!empty($data['type_err'])): ?>
                                <div class="text-danger"><?php echo $data['type_err']; ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Filters -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Filters</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="filter_env" class="form-label">Environment</label>
                                            <select class="form-select" id="filter_env" name="filter_env">
                                                <option value="">All Environments</option>
                                                <option value="PROD" <?php echo ($data['filters']['env'] === 'PROD') ? 'selected' : ''; ?>>PROD</option>
                                                <option value="DEV" <?php echo ($data['filters']['env'] === 'DEV') ? 'selected' : ''; ?>>DEV</option>
                                                <option value="UAT" <?php echo ($data['filters']['env'] === 'UAT') ? 'selected' : ''; ?>>UAT</option>
                                                <option value="DR" <?php echo ($data['filters']['env'] === 'DR') ? 'selected' : ''; ?>>DR</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="filter_type" class="form-label">Server Type</label>
                                            <select class="form-select" id="filter_type" name="filter_type">
                                                <option value="">All Types</option>
                                                <option value="Virtual" <?php echo ($data['filters']['type'] === 'Virtual') ? 'selected' : ''; ?>>Virtual</option>
                                                <option value="Physical" <?php echo ($data['filters']['type'] === 'Physical') ? 'selected' : ''; ?>>Physical</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="filter_site" class="form-label">Site</label>
                                            <select class="form-select" id="filter_site" name="filter_site">
                                                <option value="">All Sites</option>
                                                <option value="Phnom Penh" <?php echo ($data['filters']['site'] === 'Phnom Penh') ? 'selected' : ''; ?>>Phnom Penh</option>
                                                <option value="Siem Reap" <?php echo ($data['filters']['site'] === 'Siem Reap') ? 'selected' : ''; ?>>Siem Reap</option>
                                                <option value="Battambang" <?php echo ($data['filters']['site'] === 'Battambang') ? 'selected' : ''; ?>>Battambang</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Columns -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Columns to Include</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="column_name" id="column_name" <?php echo $data['columns']['name'] ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="column_name">Name</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="column_ip" id="column_ip" <?php echo $data['columns']['ip'] ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="column_ip">IP Address</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="column_os" id="column_os" <?php echo $data['columns']['os'] ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="column_os">Operating System</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="column_site" id="column_site" <?php echo $data['columns']['site'] ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="column_site">Site</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="column_type" id="column_type" <?php echo $data['columns']['type'] ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="column_type">Type</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="column_env" id="column_env" <?php echo $data['columns']['env'] ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="column_env">Environment</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="column_owner" id="column_owner" <?php echo $data['columns']['owner'] ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="column_owner">Owner</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Create Template</button>
                        <a href="/reports" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
