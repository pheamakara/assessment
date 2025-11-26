<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Server Assessment System</title>
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
                    <h1 class="h2">Reports</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="/reports/createTemplate" class="btn btn-primary me-2">
                            <i class="bi bi-file-earmark-plus"></i> Create Template
                        </a>
                        <a href="/reports/createSchedule" class="btn btn-secondary">
                            <i class="bi bi-alarm"></i> Create Schedule
                        </a>
                    </div>
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

                <!-- Report Templates -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Report Templates</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Type</th>
                                        <th>Created By</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($data['templates'])): ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No report templates found</td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php foreach ($data['templates'] as $template): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($template['name']); ?></td>
                                        <td><?php echo htmlspecialchars($template['description']); ?></td>
                                        <td><?php echo htmlspecialchars($template['type']); ?></td>
                                        <td><?php echo htmlspecialchars($template['created_by']); ?></td>
                                        <td><?php echo date('Y-m-d H:i:s', strtotime($template['created_at'])); ?></td>
                                        <td>
                                            <a href="/reports/generate/<?php echo $template['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-file-earmark-bar-graph"></i> Generate
                                            </a>
                                            <button class="btn btn-sm btn-outline-danger" 
                                                    onclick="confirmDeleteTemplate('<?php echo $template['id']; ?>', '<?php echo htmlspecialchars($template['name']); ?>')">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Report Schedules -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Report Schedules</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Template</th>
                                        <th>Frequency</th>
                                        <th>Time</th>
                                        <th>Recipients</th>
                                        <th>Status</th>
                                        <th>Last Run</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($data['schedules'])): ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No report schedules found</td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php foreach ($data['schedules'] as $schedule): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($schedule['template_name']); ?></td>
                                        <td><?php echo $data['frequencies'][$schedule['frequency']] ?? $schedule['frequency']; ?></td>
                                        <td><?php echo htmlspecialchars($schedule['time']); ?></td>
                                        <td>
                                            <?php 
                                            $recipients = json_decode($schedule['recipients'], true);
                                            if (is_array($recipients)) {
                                                echo htmlspecialchars(implode(', ', $recipients));
                                            } else {
                                                echo htmlspecialchars($schedule['recipients']);
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php if ($schedule['is_active']): ?>
                                            <span class="badge bg-success">Active</span>
                                            <?php else: ?>
                                            <span class="badge bg-secondary">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo $schedule['last_run'] ? date('Y-m-d H:i:s', strtotime($schedule['last_run'])) : 'Never'; ?></td>
                                        <td>
                                            <?php if ($schedule['is_active']): ?>
                                            <a href="/reports/deactivateSchedule/<?php echo $schedule['id']; ?>" class="btn btn-sm btn-outline-warning">
                                                <i class="bi bi-pause"></i> Deactivate
                                            </a>
                                            <?php else: ?>
                                            <a href="/reports/activateSchedule/<?php echo $schedule['id']; ?>" class="btn btn-sm btn-outline-success">
                                                <i class="bi bi-play"></i> Activate
                                            </a>
                                            <?php endif; ?>
                                            <button class="btn btn-sm btn-outline-danger" 
                                                    onclick="confirmDeleteSchedule('<?php echo $schedule['id']; ?>')">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </td>
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

    <!-- Delete Template Confirmation Modal -->
    <div class="modal fade" id="deleteTemplateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete the report template <strong id="deleteTemplateName"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteTemplateForm" method="POST" style="display:inline;">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Schedule Confirmation Modal -->
    <div class="modal fade" id="deleteScheduleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this report schedule?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteScheduleForm" method="POST" style="display:inline;">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDeleteTemplate(templateId, templateName) {
            document.getElementById('deleteTemplateName').textContent = templateName;
            document.getElementById('deleteTemplateForm').action = '/reports/deleteTemplate/' + templateId;
            var deleteModal = new bootstrap.Modal(document.getElementById('deleteTemplateModal'));
            deleteModal.show();
        }

        function confirmDeleteSchedule(scheduleId) {
            document.getElementById('deleteScheduleForm').action = '/reports/deleteSchedule/' + scheduleId;
            var deleteModal = new bootstrap.Modal(document.getElementById('deleteScheduleModal'));
            deleteModal.show();
        }
    </script>
</body>
</html>
