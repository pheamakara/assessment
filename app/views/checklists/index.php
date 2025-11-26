<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checklist Management - Server Assessment System</title>
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
                    <h1 class="h2">Checklist Management</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="/checklists/create" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Create Checklist
                        </a>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Filters</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-md-4">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="">All Statuses</option>
                                    <option value="DRAFT" <?php echo ($data['filters']['status'] === 'DRAFT') ? 'selected' : ''; ?>>Draft</option>
                                    <option value="PENDING_SECURITY" <?php echo ($data['filters']['status'] === 'PENDING_SECURITY') ? 'selected' : ''; ?>>Pending Security</option>
                                    <option value="PENDING_CLOUD" <?php echo ($data['filters']['status'] === 'PENDING_CLOUD') ? 'selected' : ''; ?>>Pending Cloud</option>
                                    <option value="APPROVED" <?php echo ($data['filters']['status'] === 'APPROVED') ? 'selected' : ''; ?>>Approved</option>
                                    <option value="REJECTED" <?php echo ($data['filters']['status'] === 'REJECTED') ? 'selected' : ''; ?>>Rejected</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="server_name" class="form-label">Server Name</label>
                                <input type="text" class="form-control" id="server_name" name="server_name" value="<?php echo htmlspecialchars($data['filters']['server_name']); ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">&nbsp;</label>
                                <div>
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    <a href="/checklists" class="btn btn-secondary">Clear</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Server Name</th>
                                <th>IP</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Created Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($data['checklists'])): ?>
                            <tr>
                                <td colspan="6" class="text-center">No checklists found</td>
                            </tr>
                            <?php endif; ?>
                            <?php foreach ($data['checklists'] as $checklist): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($checklist['server_name']); ?></td>
                                <td><?php echo htmlspecialchars($checklist['server_ip']); ?></td>
                                <td><?php echo htmlspecialchars($checklist['type']); ?></td>
                                <td>
                                    <?php
                                    $statusClass = '';
                                    switch ($checklist['status']) {
                                        case 'DRAFT':
                                            $statusClass = 'bg-secondary';
                                            break;
                                        case 'PENDING_SECURITY':
                                            $statusClass = 'bg-warning';
                                            break;
                                        case 'PENDING_CLOUD':
                                            $statusClass = 'bg-info';
                                            break;
                                        case 'APPROVED':
                                            $statusClass = 'bg-success';
                                            break;
                                        case 'REJECTED':
                                            $statusClass = 'bg-danger';
                                            break;
                                    }
                                    ?>
                                    <span class="badge <?php echo $statusClass; ?>">
                                        <?php echo str_replace('_', ' ', $checklist['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('Y-m-d H:i:s', strtotime($checklist['created_at'])); ?></td>
                                <td>
                                    <a href="/checklists/show/<?php echo $checklist['id']; ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <a href="/checklists/show/<?php echo $checklist['id']; ?>?export=pdf" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-file-pdf"></i> Export PDF
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
