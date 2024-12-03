<div class="navbar">
    <div class="navbar-container">
        <img src="assets/img/jbest_logo.png" alt="JBEST Logo" class="logo">
        <span class="navbar-title">JBEST LIBRARY <br>MANAGEMENT SYSTEM</span>
    </div>
    <div class="right-div">
        <a href="admin_login.php" class="btn btn-danger pull-right">LOG OUT</a>
    </div>
</div>
<!-- LOGO HEADER END-->
<section class="menu-section">
    <div class="container" style="left:0">
        <div class="row">
            <div class="col-md-12">
                <div class="navbar-collapse collapse">
                    <ul id="menu-top" class="nav navbar-nav navbar-right">
                        <li><a href="dashboard.php" class="menu-top-active">DASHBOARD</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown">Categories <i class="fa fa-angle-down"></i></a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="add-category.php">Add Category</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="manage-categories.php">Manage Categories</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown">Publications <i class="fa fa-angle-down"></i></a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="add-publications.php">Add Publications</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="manage-publications.php">Manage Publications</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown">Books <i class="fa fa-angle-down"></i></a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="add-book.php">Add Book</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="manage-books.php">Manage Books</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="Archive.php">Archived Book</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown">Issue Books <i class="fa fa-angle-down"></i></a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="issue-book.php">Issue New Book</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="manage-issue-stud.php">Manage Issued Books(Student)</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="manage-issue-fac.php">Manage Issued Books(Faculty)</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown">Requested Books <i class="fa fa-angle-down"></i></a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="manage-requested-books-student.php">Requested Book(Student)</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="manage-requested-books-faculty.php">Requested Book(Faculty)</a></li>
                            </ul>
                        </li>
                        <li><a href="report.php">Report</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown">Registered <i class="fa fa-angle-down"></i></a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="reg-students.php">Student</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="reg-faculty.php">Faculty</a></li>
                            </ul>
                        </li>
                        <li><a href="admin-change_pass.php">Admin Profile</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

