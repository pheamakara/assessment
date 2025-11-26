<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Server Assessment System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include '../app/views/partials/navbar.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <?php include '../app/views/partials/sidebar.php'; ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Executive Dashboard</h1>
                </div>

                <!-- Server Statistics -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="card text-white bg-primary mb-3">
                            <div class="card-header">Total Servers</div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $data['serverStats']['total_servers'] ?? 0; ?></h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-info mb-3">
                            <div class="card-header">Virtual Servers</div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $data['serverStats']['virtual_servers'] ?? 0; ?></h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-success mb-3">
                            <div class="card-header">Physical Servers</div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $data['serverStats']['physical_servers'] ?? 0; ?></h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-warning mb-3">
                            <div class="card-header">Approved Checklists</div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $data['checklistStats']['approved_checklists'] ?? 0; ?></h5>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Environment Breakdown -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="card text-white bg-danger mb-3">
                            <div class="card-header">PROD Servers</div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $data['serverStats']['prod_servers'] ?? 0; ?></h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-secondary mb-3">
                            <div class="card-header">DEV Servers</div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $data['serverStats']['dev_servers'] ?? 0; ?></h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-dark mb-3">
                            <div class="card-header">UAT Servers</div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $data['serverStats']['uat_servers'] ?? 0; ?></h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-light text-dark mb-3">
                            <div class="card-header">DR Servers</div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $data['serverStats']['dr_servers'] ?? 0; ?></h5>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Server Type Distribution</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="serverTypeChart" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">OS Family Distribution</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="osFamilyChart" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Environment Breakdown</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="environmentChart" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Hypervisor Distribution</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="hypervisorChart" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activities -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Recent Activities</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Server Name</th>
                                        <th>IP</th>
                                        <th>Status</th>
                                        <th>Timestamp</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($data['recentActivities'])): ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No recent activities</td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php foreach ($data['recentActivities'] as $activity): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($activity['server_name']); ?></td>
                                        <td><?php echo htmlspecialchars($activity['server_ip']); ?></td>
                                        <td>
                                            <?php
                                            $statusClass = '';
                                            switch ($activity['status']) {
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
                                                <?php echo str_replace('_', ' ', $activity['status']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('Y-m-d H:i:s', strtotime($activity['updated_at'])); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Server Type Distribution Chart
        var serverTypeCtx = document.getElementById('serverTypeChart').getContext('2d');
        var serverTypeChart = new Chart(serverTypeCtx, {
            type: 'pie',
            data: {
                labels: ['Virtual', 'Physical'],
                datasets: [{
                    data: [
                        <?php echo $data['serverStats']['virtual_servers'] ?? 0; ?>,
                        <?php echo $data['serverStats']['physical_servers'] ?? 0; ?>
                    ],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(75, 192, 192, 0.8)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Server Type Distribution'
                    }
                }
            }
        });

        // OS Family Distribution Chart
        var osFamilyCtx = document.getElementById('osFamilyChart').getContext('2d');
        var osFamilyChart = new Chart(osFamilyCtx, {
            type: 'pie',
            data: {
                labels: [
                    <?php 
                    $osLabels = [];
                    foreach ($data['osDistribution'] as $os) {
                        $osLabels[] = "'" . htmlspecialchars($os['os_family']) . "'";
                    }
                    echo implode(',', $osLabels);
                    ?>
                ],
                datasets: [{
                    data: [
                        <?php 
                        $osData = [];
                        foreach ($data['osDistribution'] as $os) {
                            $osData[] = $os['count'];
                        }
                        echo implode(',', $osData);
                        ?>
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 205, 86, 0.8)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 205, 86, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'OS Family Distribution'
                    }
                }
            }
        });

        // Environment Breakdown Chart
        var environmentCtx = document.getElementById('environmentChart').getContext('2d');
        var environmentChart = new Chart(environmentCtx, {
            type: 'bar',
            data: {
                labels: ['PROD', 'DEV', 'UAT', 'DR'],
                datasets: [{
                    label: 'Server Count',
                    data: [
                        <?php echo $data['serverStats']['prod_servers'] ?? 0; ?>,
                        <?php echo $data['serverStats']['dev_servers'] ?? 0; ?>,
                        <?php echo $data['serverStats']['uat_servers'] ?? 0; ?>,
                        <?php echo $data['serverStats']['dr_servers'] ?? 0; ?>
                    ],
                    backgroundColor: [
                        'rgba(220, 53, 69, 0.8)',
                        'rgba(108, 117, 125, 0.8)',
                        'rgba(33, 37, 41, 0.8)',
                        'rgba(248, 249, 250, 0.8)'
                    ],
                    borderColor: [
                        'rgba(220, 53, 69, 1)',
                        'rgba(108, 117, 125, 1)',
                        'rgba(33, 37, 41, 1)',
                        'rgba(248, 249, 250, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Environment Breakdown'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Hypervisor Distribution Chart
        var hypervisorCtx = document.getElementById('hypervisorChart').getContext('2d');
        var hypervisorChart = new Chart(hypervisorCtx, {
            type: 'pie',
            data: {
                labels: [
                    <?php 
                    $hypervisorLabels = [];
                    foreach ($data['hypervisorDistribution'] as $hypervisor) {
                        $hypervisorLabels[] = "'" . htmlspecialchars($hypervisor['hypervisor']) . "'";
                    }
                    echo implode(',', $hypervisorLabels);
                    ?>
                ],
                datasets: [{
                    data: [
                        <?php 
                        $hypervisorData = [];
                        foreach ($data['hypervisorDistribution'] as $hypervisor) {
                            $hypervisorData[] = $hypervisor['count'];
                        }
                        echo implode(',', $hypervisorData);
                        ?>
                    ],
                    backgroundColor: [
                        'rgba(153, 102, 255, 0.8)',
                        'rgba(255, 159, 64, 0.8)',
                        'rgba(201, 203, 207, 0.8)'
                    ],
                    borderColor: [
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(201, 203, 207, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Hypervisor Distribution'
                    }
                }
            }
        });
    </script>
</body>
</html>
