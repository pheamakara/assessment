<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Management - Server Assessment System</title>
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
                    <h1 class="h2">Server Management</h1>
                    <?php if (in_array($_SESSION['role'], ['ADMIN', 'CLOUD_ENGINEER'])): ?>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="/servers/create" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Add Server
                        </a>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Filters -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Filters</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-md-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($data['filters']['name']); ?>">
                            </div>
                            <div class="col-md-3">
                                <label for="ip" class="form-label">IP</label>
                                <input type="text" class="form-control" id="ip" name="ip" value="<?php echo htmlspecialchars($data['filters']['ip']); ?>">
                            </div>
                            <div class="col-md-3">
                                <label for="owner" class="form-label">Owner</label>
                                <input type="text" class="form-control" id="owner" name="owner" value="<?php echo htmlspecialchars($data['filters']['owner']); ?>">
                            </div>
                            <div class="col-md-3">
                                <label for="type" class="form-label">Type</label>
                                <select class="form-select" id="type" name="type">
                                    <option value="">All Types</option>
                                    <option value="Virtual" <?php echo ($data['filters']['type'] === 'Virtual') ? 'selected' : ''; ?>>Virtual</option>
                                    <option value="Physical" <?php echo ($data['filters']['type'] === 'Physical') ? 'selected' : ''; ?>>Physical</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="env" class="form-label">Environment</label>
                                <select class="form-select" id="env" name="env">
                                    <option value="">All Environments</option>
                                    <option value="PROD" <?php echo ($data['filters']['env'] === 'PROD') ? 'selected' : ''; ?>>PROD</option>
                                    <option value="DEV" <?php echo ($data['filters']['env'] === 'DEV') ? 'selected' : ''; ?>>DEV</option>
                                    <option value="UAT" <?php echo ($data['filters']['env'] === 'UAT') ? 'selected' : ''; ?>>UAT</option>
                                    <option value="DR" <?php echo ($data['filters']['env'] === 'DR') ? 'selected' : ''; ?>>DR</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="site" class="form-label">Site</label>
                                <select class="form-select" id="site" name="site">
                                    <option value="">All Sites</option>
                                    <option value="Phnom Penh" <?php echo ($data['filters']['site'] === 'Phnom Penh') ? 'selected' : ''; ?>>Phnom Penh</option>
                                    <option value="Siem Reap" <?php echo ($data['filters']['site'] === 'Siem Reap') ? 'selected' : ''; ?>>Siem Reap</option>
                                    <option value="Battambang" <?php echo ($data['filters']['site'] === 'Battambang') ? 'selected' : ''; ?>>Battambang</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="asset_class" class="form-label">Asset Class</label>
                                <select class="form-select" id="asset_class" name="asset_class">
                                    <option value="">All Classes</option>
                                    <option value="Critical" <?php echo ($data['filters']['asset_class'] === 'Critical') ? 'selected' : ''; ?>>Critical</option>
                                    <option value="High" <?php echo ($data['filters']['asset_class'] === 'High') ? 'selected' : ''; ?>>High</option>
                                    <option value="Medium" <?php echo ($data['filters']['asset_class'] === 'Medium') ? 'selected' : ''; ?>>Medium</option>
                                    <option value="Low" <?php echo ($data['filters']['asset_class'] === 'Low') ? 'selected' : ''; ?>>Low</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="asset_type" class="form-label">Asset Type</label>
                                <select class="form-select" id="asset_type" name="asset_type">
                                    <option value="">All Types</option>
                                    <option value="Server" <?php echo ($data['filters']['asset_type'] === 'Server') ? 'selected' : ''; ?>>Server</option>
                                    <option value="Storage" <?php echo ($data['filters']['asset_type'] === 'Storage') ? 'selected' : ''; ?>>Storage</option>
                                    <option value="Network" <?php echo ($data['filters']['asset_type'] === 'Network') ? 'selected' : ''; ?>>Network</option>
                                    <option value="Database" <?php echo ($data['filters']['asset_type'] === 'Database') ? 'selected' : ''; ?>>Database</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="/servers" class="btn btn-secondary">Clear</a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>IP</th>
                                <th>OS</th>
                                <th>Site</th>
                                <th>Type</th>
                                <th>Environment</th>
                                <th>Owner</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($data['servers'])): ?>
                            <tr>
                                <td colspan="8" class="text-center">No servers found</td>
                            </tr>
                            <?php endif; ?>
                            <?php foreach ($data['servers'] as $server): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($server['name']); ?></td>
                                <td><?php echo htmlspecialchars($server['ip']); ?></td>
                                <td><?php echo htmlspecialchars($server['os']); ?></td>
                                <td><?php echo htmlspecialchars($server['site']); ?></td>
                                <td><?php echo htmlspecialchars($server['type']); ?></td>
                                <td><?php echo htmlspecialchars($server['env']); ?></td>
                                <td><?php echo htmlspecialchars($server['owner']); ?></td>
                                <td>
                                    <a href="/servers/show/<?php echo $server['id']; ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <?php if (in_array($_SESSION['role'], ['ADMIN', 'CLOUD_ENGINEER'])): ?>
                                    <a href="/servers/edit/<?php echo $server['id']; ?>" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <button class="btn btn-sm btn-outline-danger" 
                                            onclick="confirmDelete('<?php echo $server['id']; ?>', '<?php echo htmlspecialchars($server['name']); ?>')">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete server <strong id="deleteServerName"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST" style="display:inline;">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDelete(serverId, serverName) {
            document.getElementById('deleteServerName').textContent = serverName;
            document.getElementById('deleteForm').action = '/servers/delete/' + serverId;
            var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }
    </script>
</body>
</html>
