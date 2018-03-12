<nav class="navbar fixed-top d-flex align-items-center d-print">
    <i id="left-toggle" class="fas fa-fw fa-bars left-toggle"></i>

    <div class="navbar-header d-flex align-items-center">
        <a href="/" class="navbar-brand brand">UniStudyMate</a>
        <i id="right-toggle" class="far fa-fw fa-user right-toggle"></i>
    </div>

    <ul id="left-slide" class="left-slide">
        <li class="<?php if ($navbar == 'textbooks') { echo 'active'; } ?>"><a href="/textbooks"><i class="fas fa-fw fa-book"></i> Textbooks</a></li>
        <li class="<?php if ($navbar == 'reviews') { echo 'active'; } ?>"><a href="/reviews"><i class="fas fa-fw fa-comments"></i> Subject Reviews</a></li>
    </ul>

    <ul id="right-slide" class="right-slide">
        <?php 
            if (!isset($_SESSION['userID'])) {
                echo '<li><a href="/account/"><i class="fas fa-fw fa-sign-in-alt"></i> Log In</a></li>';
            } elseif ($navbar == 'account') {
                echo '<li class="active"><a href="/account/"><i class="far fa-fw fa-user"></i> Account</a></li>';
            } else {
                echo '<li><a href="/account/"><i class="far fa-fw fa-user"></i> Account</a></li>';
            }
        ?>
        <?php 
            if (!isset($_SESSION['userID'])) {
                echo '<li class="special"><a href="/account/register"><i class="fas fa-fw fa-user-plus"></i> Create Account</a></li>';
            } else {
                echo '<li><a href="/account/logout"><i class="fas fa-fw fa-sign-out-alt"></i> Log Out</a></li>';
            }
        ?>
        
    </ul>    
</nav>