<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Report Schedule - Server Assessment System</title>
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
                    <h1 class="h2">Create Report Schedule</h1>
                </div>

                <div class="col-md-8">
                    <form action="/reports/storeSchedule" method="POST">
                        <div class="mb-3">
                            <label for="template_id" class="form-label">Report Template *</label>
                            <select class="form-select" id="template_id" name="template_id">
                                <option value="">Select Template</option>
                                <?php foreach ($data['templates'] as $template): ?>
                                <option value="<?php echo $template['id']; ?>" <?php echo ($data['template_id'] === $template['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($template['name']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (!empty($data['template_err'])): ?>
                                <div class="text-danger"><?php echo $data['template_err']; ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="frequency" class="form-label">Frequency *</label>
                            <select class="form-select" id="frequency" name="frequency">
                                <option value="">Select Frequency</option>
                                <?php foreach ($data['frequencies'] as $key => $value): ?>
                                <option value="<?php echo $key; ?>" <?php echo ($data['frequency'] === $key) ? 'selected' : ''; ?>>
                                    <?php echo $value; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (!empty($data['frequency_err'])): ?>
                                <div class="text-danger"><?php echo $data['frequency_err']; ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="day_of_week" class="form-label">Day of Week</label>
                                    <select class="form-select" id="day_of_week" name="day_of_week">
                                        <option value="">Select Day</option>
                                        <?php foreach ($data['days_of_week'] as $key => $value): ?>
                                        <option value="<?php echo $key; ?>" <?php echo ($data['day_of_week'] == $key) ? 'selected' : ''; ?>>
                                            <?php echo $value; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="day_of_month" class="form-label">Day of Month</label>
                                    <select class="form-select" id="day_of_month" name="day_of_month">
                                        <option value="">Select Day</option>
                                        <?php foreach ($data['days_of_month'] as $key => $value): ?>
                                        <option value="<?php echo $key; ?>" <?php echo ($data['day_of_month'] == $key) ? 'selected' : ''; ?>>
                                            <?php echo $value; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="time" class="form-label">Time (HH:MM) *</label>
                            <input type="text" class="form-control" id="time" name="time" placeholder="14:30" value="<?php echo $data['time']; ?>">
                            <?php if (!empty($data['time_err'])): ?>
                                <div class="text-danger"><?php echo $data['time_err']; ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="recipients" class="form-label">Recipient Emails (comma separated) *</label>
                            <input type="text" class="form-control" id="recipients" name="recipients" value="<?php echo $data['recipients']; ?>">
                            <div class="form-text">Enter email addresses separated by commas</div>
                            <?php if (!empty($data['recipients_err'])): ?>
                                <div class="text-danger"><?php echo $data['recipients_err']; ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" <?php echo $data['is_active'] ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Create Schedule</button>
                        <a href="/reports" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Show/hide day fields based on frequency
        document.getElementById('frequency').addEventListener('change', function() {
            const frequency = this.value;
            const dayOfWeekField = document.getElementById('day_of_week').closest('.mb-3');
            const dayOfMonthField = document.getElementById('day_of_month').closest('.mb-3');
            
            // Hide both fields initially
            dayOfWeekField.style.display = 'none';
            dayOfMonthField.style.display = 'none';
            
            // Show appropriate field based on frequency
            if (frequency === 'WEEKLY') {
                dayOfWeekField.style.display = 'block';
            } else if (frequency === 'MONTHLY') {
                dayOfMonthField.style.display = 'block';
            }
        });
        
        // Trigger change event on page load to set initial state
        document.getElementById('frequency').dispatchEvent(new Event('change'));
    </script>
</body>
</html>
