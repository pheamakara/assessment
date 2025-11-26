<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checklist Details - Server Assessment System</title>
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
                    <h1 class="h2">Checklist Details</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="/checklists" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
                <?php endif; ?>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Server Information</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th>Server Name:</th>
                                <td><?php echo htmlspecialchars($data['checklist']['server_name']); ?></td>
                            </tr>
                            <tr>
                                <th>IP Address:</th>
                                <td><?php echo htmlspecialchars($data['checklist']['server_ip']); ?></td>
                            </tr>
                            <tr>
                                <th>Server Type:</th>
                                <td><?php echo htmlspecialchars($data['checklist']['server_type']); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="mb-0">Checklist Status</h5>
                        <?php
                        $statusClass = '';
                        switch ($data['checklist']['status']) {
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
                        <span class="badge <?php echo $statusClass; ?> fs-6">
                            <?php echo str_replace('_', ' ', $data['checklist']['status']); ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th>Requested By:</th>
                                <td><?php echo htmlspecialchars($data['checklist']['requested_by'] ?? 'N/A'); ?></td>
                            </tr>
                            <tr>
                                <th>Approved By:</th>
                                <td><?php echo htmlspecialchars($data['checklist']['approved_by'] ?? 'N/A'); ?></td>
                            </tr>
                            <?php if ($data['checklist']['status'] === 'REJECTED' && !empty($data['checklist']['rejection_reason'])): ?>
                            <tr>
                                <th>Rejection Reason:</th>
                                <td><?php echo htmlspecialchars($data['checklist']['rejection_reason']); ?></td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <th>Created At:</th>
                                <td><?php echo date('Y-m-d H:i:s', strtotime($data['checklist']['created_at'])); ?></td>
                            </tr>
                            <tr>
                                <th>Updated At:</th>
                                <td><?php echo date('Y-m-d H:i:s', strtotime($data['checklist']['updated_at'])); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Checklist Items</h5>
                    </div>
                    <div class="card-body">
                        <?php foreach ($data['checklist']['items'] as $item => $completed): ?>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" <?php echo $completed ? 'checked' : ''; ?> disabled>
                            <label class="form-check-label <?php echo $completed ? 'text-decoration-line-through' : ''; ?>">
                                <?php echo htmlspecialchars($item); ?>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php if (in_array($_SESSION['role'], ['SECURITY', 'CLOUD_MANAGER'])): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Actions</h5>
                    </div>
                    <div class="card-body">
                        <?php if (($_SESSION['role'] === 'SECURITY' && $data['checklist']['status'] === 'DRAFT') || 
                                  ($_SESSION['role'] === 'SECURITY' && $data['checklist']['status'] === 'REJECTED')): ?>
                        <form action="/checklists/approve/<?php echo $data['checklist']['id']; ?>" method="POST" class="d-inline">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Submit for Security Approval
                            </button>
                        </form>
                        <?php elseif ($_SESSION['role'] === 'SECURITY' && $data['checklist']['status'] === 'PENDING_SECURITY'): ?>
                        <form action="/checklists/approve/<?php echo $data['checklist']['id']; ?>" method="POST" class="d-inline">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Approve Security
                            </button>
                        </form>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="bi bi-x-circle"></i> Reject
                        </button>
                        <?php elseif ($_SESSION['role'] === 'CLOUD_MANAGER' && $data['checklist']['status'] === 'PENDING_CLOUD'): ?>
                        <form action="/checklists/approve/<?php echo $data['checklist']['id']; ?>" method="POST" class="d-inline">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Approve Cloud
                            </button>
                        </form>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="bi bi-x-circle"></i> Reject
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reject Checklist</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/checklists/reject/<?php echo $data['checklist']['id']; ?>" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="rejection_reason" class="form-label">Rejection Reason *</label>
                            <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Reject Checklist</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
