<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Logs - Server Assessment System</title>
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
                    <h1 class="h2">Audit Logs</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="/auditlogs/export" class="btn btn-primary">
                            <i class="bi bi-download"></i> Export to CSV
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
                            <div class="col-md-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($data['filters']['username']); ?>">
                            </div>
                            <div class="col-md-3">
                                <label for="action" class="form-label">Action</label>
                                <select class="form-select" id="action" name="action">
                                    <option value="">All Actions</option>
                                    <?php foreach ($data['actions'] as $action): ?>
                                    <option value="<?php echo $action; ?>" <?php echo ($data['filters']['action'] === $action) ? 'selected' : ''; ?>>
                                        <?php echo str_replace('_', ' ', $action); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="entity" class="form-label">Entity</label>
                                <select class="form-select" id="entity" name="entity">
                                    <option value="">All Entities</option>
                                    <?php foreach ($data['entities'] as $key => $value): ?>
                                    <option value="<?php echo $key; ?>" <?php echo ($data['filters']['entity'] === $key) ? 'selected' : ''; ?>>
                                        <?php echo $value; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="date_from" class="form-label">Date From</label>
                                <input type="date" class="form-control" id="date_from" name="date_from" value="<?php echo $data['filters']['date_from']; ?>">
                            </div>
                            <div class="col-md-3">
                                <label for="date_to" class="form-label">Date To</label>
                                <input type="date" class="form-control" id="date_to" name="date_to" value="<?php echo $data['filters']['date_to']; ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <div>
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    <a href="/auditlogs" class="btn btn-secondary">Clear</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Timestamp</th>
                                <th>Username</th>
                                <th>Action</th>
                                <th>Entity</th>
                                <th>Entity ID</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($data['logs'])): ?>
                            <tr>
                                <td colspan="6" class="text-center">No audit logs found</td>
                            </tr>
                            <?php endif; ?>
                            <?php foreach ($data['logs'] as $log): ?>
                            <tr>
                                <td><?php echo date('Y-m-d H:i:s', strtotime($log['timestamp'])); ?></td>
                                <td><?php echo htmlspecialchars($log['username']); ?></td>
                                <td><?php echo str_replace('_', ' ', $log['action']); ?></td>
                                <td><?php echo $data['entities'][$log['entity']] ?? $log['entity']; ?></td>
                                <td><?php echo htmlspecialchars($log['entity_id'] ?? 'N/A'); ?></td>
                                <td>
                                    <?php if (!empty($log['details'])): ?>
                                        <?php 
                                        $details = json_decode($log['details'], true);
                                        if (is_array($details)) {
                                            if (isset($details['changes'])) {
                                                echo 'Changed fields: ' . implode(', ', array_keys($details['changes']));
                                            } else {
                                                echo htmlspecialchars(json_encode($details));
                                            }
                                        } else {
                                            echo htmlspecialchars($log['details']);
                                        }
                                        ?>
                                    <?php else: ?>
                                        N/A
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
