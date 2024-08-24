<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo route('admin/index.php') ?>">Admin Panel</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo route('admin/users/manage_user.php') ?>">Manage Users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo route('admin/category/categories.php') ?>">Manage Categories</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo route('admin/statistic.php') ?>">Statistics</a> 
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo route('profile/profile.php') ?>">Profile</a>
                </li>

                <li class="nav-item">
                    <img src="http://localhost/Backend/mohtesemTask2/auth/uploads/profile_pics/<?php echo $_SESSION['profile']; ?>"
                        alt="Profile Pic" class="rounded-circle"
                        style="width: 40px; height: 40px; margin-left: 10px;">
                </li>
            </ul>
        </div>
    </div>
</nav>
