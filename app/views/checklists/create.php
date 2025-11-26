<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Checklist - Server Assessment System</title>
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
                    <h1 class="h2">Create Checklist</h1>
                </div>

                <div class="col-md-8">
                    <form action="/checklists/store" method="POST">
                        <?php if (empty($data['server'])): ?>
                        <!-- Server selection if not pre-selected -->
                        <div class="mb-3">
                            <label for="server_id" class="form-label">Select Server *</label>
                            <select class="form-select" id="server_id" name="server_id">
                                <option value="">Select Server</option>
                                <?php foreach ($data['servers'] as $server): ?>
                                <option value="<?php echo $server['id']; ?>" <?php echo ($data['server_id'] === $server['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($server['name']) . ' (' . htmlspecialchars($server['ip']) . ')'; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (!empty($data['server_err'])): ?>
                                <div class="text-danger"><?php echo $data['server_err']; ?></div>
                            <?php endif; ?>
                        </div>
                        <?php else: ?>
                        <!-- Pre-selected server -->
                        <input type="hidden" name="server_id" value="<?php echo $data['server']['id']; ?>">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Server Information</h5>
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
                                        <th>Type:</th>
                                        <td><?php echo htmlspecialchars($data['server']['type']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Environment:</th>
                                        <td><?php echo htmlspecialchars($data['server']['env']); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($data['items'])): ?>
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Checklist Items</h5>
                            </div>
                            <div class="card-body">
                                <?php foreach ($data['items'] as $index => $item): ?>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="items[]" value="<?php echo htmlspecialchars($item); ?>" id="item_<?php echo $index; ?>">
                                    <label class="form-check-label" for="item_<?php echo $index; ?>">
                                        <?php echo htmlspecialchars($item); ?>
                                    </label>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <button type="submit" class="btn btn-primary">Create Checklist</button>
                        <a href="/checklists" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
