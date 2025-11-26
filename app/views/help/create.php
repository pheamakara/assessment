<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Help Document - Server Assessment System</title>
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
                    <h1 class="h2">Add Help Document</h1>
                </div>

                <div class="col-md-8">
                    <form action="/help/store" method="POST">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title *</label>
                            <input type="text" class="form-control" id="title" name="title" value="<?php echo $data['title']; ?>">
                            <?php if (!empty($data['title_err'])): ?>
                                <div class="text-danger"><?php echo $data['title_err']; ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="content" class="form-label">Content *</label>
                            <textarea class="form-control" id="content" name="content" rows="10"><?php echo $data['content']; ?></textarea>
                            <?php if (!empty($data['content_err'])): ?>
                                <div class="text-danger"><?php echo $data['content_err']; ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category *</label>
                                    <select class="form-select" id="category" name="category">
                                        <option value="">Select Category</option>
                                        <?php foreach ($data['categories'] as $key => $value): ?>
                                        <option value="<?php echo $key; ?>" <?php echo ($data['category'] === $key) ? 'selected' : ''; ?>>
                                            <?php echo $value; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (!empty($data['category_err'])): ?>
                                        <div class="text-danger"><?php echo $data['category_err']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">Sort Order *</label>
                                    <input type="number" class="form-control" id="sort_order" name="sort_order" value="<?php echo $data['sort_order']; ?>">
                                    <?php if (!empty($data['sort_order_err'])): ?>
                                        <div class="text-danger"><?php echo $data['sort_order_err']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Add Document</button>
                        <a href="/help" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
