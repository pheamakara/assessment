<nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="/dashboard">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/servers">
                    <i class="bi bi-server"></i> Servers
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/checklists">
                    <i class="bi bi-clipboard-check"></i> Checklists
                </a>
            </li>
            <?php if ($_SESSION['role'] === 'ADMIN'): ?>
            <li class="nav-item">
                <a class="nav-link" href="/users">
                    <i class="bi bi-people"></i> Users
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/auditlogs">
                    <i class="bi bi-journal-text"></i> Audit Logs
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/reports">
                    <i class="bi bi-file-earmark-bar-graph"></i> Reports
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/settings">
                    <i class="bi bi-gear"></i> Settings
                </a>
            </li>
            <?php endif; ?>
            <li class="nav-item">
                <a class="nav-link" href="/help">
                    <i class="bi bi-question-circle"></i> Help & Docs
                </a>
            </li>
        </ul>
    </div>
</nav>
