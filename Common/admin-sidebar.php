<div class="row w-100">
    <button class="show-btn button-show ml-auto">
        <i class="fa fa-bars py-1" aria-hidden="true"></i>
    </button> 
</div>
<nav id="sidebarMenu" class="">
    <div class="col-xl-2 col-lg-3 col-md-4 sidebar position-fixed border-right">
        <div class="sidebar-header">
            <div class="nav-item">
                <a class="nav-link text-white" href="../admin/admin-index.php">
                    <span class="home"></span>
                    <i class="fa fa-home mr-2" aria-hidden="true"></i> Dashboard 
                </a>
            </div>
        </div>   
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="../admin/Teacher.php">
                    <span data-feather="file"></span>
                    <i class="fa fa-user mr-2" aria-hidden="true"></i>Teacher Registration
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../admin/Student.php">
                    <span data-feather="shopping-cart"></span>
                    <i class="fa fa-user-circle mr-2" aria-hidden="true"></i> Student Registration
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../admin/research_list.php">
                    <span data-feather="shopping-cart"></span>
                    <i class="fa fa-list mr-2" aria-hidden="true"></i> Research List
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../admin/manage-accounts.php">
                    <span data-feather="users"></span>
                    <i class="fa fa-users mr-2" aria-hidden="true"></i> Manage Researchers
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../admin/manage_depart_funding.php">
                    <span data-feather="layers"></span>
                    <i class="fa fa-money mr-2" aria-hidden="true"></i> Manage Research Funding
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../admin/research-report.php">
                    <span data-feather="bar-chart-2"></span>
                    <i class="fa fa-file-text-o mr-2" aria-hidden="true"></i> Research Report
                </a>
            </li>
        </ul>
    </div>
</nav>

<script>
    const toggleBtn = document.querySelector(".show-btn");
    const sidebar = document.querySelector(".sidebar");
    toggleBtn.addEventListener("click", function(){
        sidebar.classList.toggle("show-sidebar");
    });
</script>
