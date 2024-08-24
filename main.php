<?php

$user=getUserDetails($connection);

if ($user) { ?>
<div class="container">
<h1>Hello <?php echo $user['name']; ?></h1>
</div>
<?php } ?>
