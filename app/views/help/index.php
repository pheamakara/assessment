<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help & Documentation - Server Assessment System</title>
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
                    <h1 class="h2">Help & Documentation</h1>
                    <?php if (in_array($_SESSION['role'], ['ADMIN', 'CLOUD_MANAGER', 'CLOUD_ENGINEER'])): ?>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="/help/create" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Add New Article
                        </a>
                    </div>
                    <?php endif; ?>
                </div>

                <?php if (empty($data['docs'])): ?>
                <div class="alert alert-info">
                    No help documents available.
                </div>
                <?php else: ?>
                <div class="accordion" id="helpAccordion">
                    <?php foreach ($data['docs'] as $category => $docs): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading<?php echo $category; ?>">
                            <button class="accordion-button <?php echo ($category !== array_key_first($data['docs'])) ? 'collapsed' : ''; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $category; ?>" aria-expanded="<?php echo ($category === array_key_first($data['docs'])) ? 'true' : 'false'; ?>" aria-controls="collapse<?php echo $category; ?>">
                                <?php echo $data['categories'][$category] ?? $category; ?>
                            </button>
                        </h2>
                        <div id="collapse<?php echo $category; ?>" class="accordion-collapse collapse <?php echo ($category === array_key_first($data['docs'])) ? 'show' : ''; ?>" aria-labelledby="heading<?php echo $category; ?>" data-bs-parent="#helpAccordion">
                            <div class="accordion-body">
                                <?php foreach ($docs as $doc): ?>
                                <div class="card mb-3">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0"><?php echo htmlspecialchars($doc['title']); ?></h5>
                                        <?php if (in_array($_SESSION['role'], ['ADMIN', 'CLOUD_MANAGER', 'CLOUD_ENGINEER'])): ?>
                                        <div>
                                            <a href="/help/edit/<?php echo $doc['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete('<?php echo $doc['id']; ?>', '<?php echo htmlspecialchars($doc['title']); ?>')">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-body">
                                        <?php echo nl2br(htmlspecialchars($doc['content'])); ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
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
                    <p>Are you sure you want to delete the help document <strong id="deleteDocTitle"></strong>?</p>
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
        function confirmDelete(docId, docTitle) {
            document.getElementById('deleteDocTitle').textContent = docTitle;
            document.getElementById('deleteForm').action = '/help/delete/' + docId;
            var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }
    </script>
</body>
</html>
