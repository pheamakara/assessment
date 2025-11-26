<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Details - Server Assessment System</title>
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
                    <h1 class="h2">Server Details</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="/checklists/create/<?php echo $data['server']['id']; ?>" class="btn btn-primary me-2">
                            <i class="bi bi-clipboard-check"></i> Create Checklist
                        </a>
                        <?php if (in_array($_SESSION['role'], ['ADMIN', 'CLOUD_ENGINEER'])): ?>
                        <a href="/servers/edit/<?php echo $data['server']['id']; ?>" class="btn btn-secondary me-2">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <?php endif; ?>
                        <a href="/servers" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <div class="row">
                    <!-- General Information -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">General Information</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <th>Name:</th>
                                        <td><?php echo htmlspecialchars($data['server']['name']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>IP Address:</th>
                                        <td><?php echo htmlspecialchars($data['server']['ip']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Operating System:</th>
                                        <td><?php echo htmlspecialchars($data['server']['os']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Site:</th>
                                        <td><?php echo htmlspecialchars($data['server']['site']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Type:</th>
                                        <td><?php echo htmlspecialchars($data['server']['type']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Environment:</th>
                                        <td><?php echo htmlspecialchars($data['server']['env']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Owner:</th>
                                        <td><?php echo htmlspecialchars($data['server']['owner']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Person In Charge:</th>
                                        <td><?php echo htmlspecialchars($data['server']['pic']); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- System Specifications -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">System Specifications</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <th>CPU:</th>
                                        <td><?php echo htmlspecialchars($data['server']['cpu'] ?? 'N/A'); ?></td>
                                    </tr>
                                    <tr>
                                        <th>RAM:</th>
                                        <td><?php echo htmlspecialchars($data['server']['ram'] ?? 'N/A'); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Disk:</th>
                                        <td><?php echo htmlspecialchars($data['server']['disk'] ?? 'N/A'); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Vendor:</th>
                                        <td><?php echo htmlspecialchars($data['server']['vendor'] ?? 'N/A'); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Hypervisor:</th>
                                        <td><?php echo htmlspecialchars($data['server']['hypervisor'] ?? 'N/A'); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Asset Class:</th>
                                        <td><?php echo htmlspecialchars($data['server']['asset_class']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Asset Type:</th>
                                        <td><?php echo htmlspecialchars($data['server']['asset_type']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Deployment Date:</th>
                                        <td><?php echo $data['server']['deploy_date'] ? date('Y-m-d', strtotime($data['server']['deploy_date'])) : 'N/A'; ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Details -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Additional Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Created At:</strong> <?php echo date('Y-m-d H:i:s', strtotime($data['server']['created_at'])); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Updated At:</strong> <?php echo date('Y-m-d H:i:s', strtotime($data['server']['updated_at'])); ?></p>
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
