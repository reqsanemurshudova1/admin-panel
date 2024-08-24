<?php

$userauthCheck = auth();
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light container">
   
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto">
            <li class="nav-item active">
                <a class="nav-link" href="#">Home </a>
            </li>
            <li class="nav-item ">
                <a class="nav-link" href="../mohtesemTask2/home/home.php">Blog </a>
            </li>
            <?php
            if (!$userauthCheck) {
                echo '<li class="nav-item"><a  class="nav-link" href="' . route('auth/login.php') . '">Login</a></li>
                 <li class="nav-item"><a  class="nav-link" href="' . route('auth/register.php') . '">Register</a></li>';
                ;
            }
            ?>
            <?php
            if ($userauthCheck) {
                echo '<li class="nav-item"><a class="nav-link" href="' . route('profile/profile.php') . '">Profile</a></li>';

              
                if (isset($_SESSION['profile']) && !empty($_SESSION['profile'])) {
                  
                    echo '<li class="nav-item">';
                    echo '<img src="/Backend/mohtesemTask2/auth/uploads/profile_pics/' . $_SESSION['profile'] . '" alt="Profile Pic" class="rounded-circle" style="width: 40px; height: 40px; margin-left: 10px;">';

                    echo '</li>';
                }

                echo '<li class="nav-item"><a class="nav-link" href="' . route('client/blog/blogs.php') . '">My blog</a></li>';
                echo '<li class="nav-item"><a class="nav-link" href="' . route('auth/logout.php') . '">Logout</a></li>';
            }
            ?>
        </ul>
        <form class="d-flex">
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>