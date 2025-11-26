<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">Server Assessment System</a>
        
        <div class="d-flex align-items-center">
            <div class="dropdown me-3">
                <button class="btn btn-link text-white dropdown-toggle" type="button" id="notificationDropdown" data-bs-toggle="dropdown">
                    <i class="bi bi-bell"></i>
                    <span class="badge bg-danger">0</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><h6 class="dropdown-header">Notifications</h6></li>
                    <li><a class="dropdown-item" href="#">No new notifications</a></li>
                </ul>
            </div>
            
            <div class="dropdown">
                <button class="btn btn-link text-white dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle"></i> <?php echo $_SESSION['username']; ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="/profile"><i class="bi bi-person"></i> Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="/auth/logout"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>
