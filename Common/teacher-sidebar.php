<div class="row w-100">
    <button class="show-btn button-show ml-auto">
        <i class="fa fa-bars py-1" aria-hidden="true"></i>
    </button> 
</div>
<nav id="sidebarMenu" class="">
    <div class="col-xl-2 col-lg-3 col-md-4 sidebar position-fixed border-right">
        <div class="sidebar-header">
            <a class="nav-link text-white" href="../teacher/teacher-index.php">
                <span class="home"></span>
                <i class="fa fa-home mr-2" aria-hidden="true"></i> Dashboard
            </a>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="../teacher/teacher-research-registration.php">
                    <span data-feather="file"></span>
                    <i class="fa fa-info-circle mr-2" aria-hidden="true"></i> Research Registration
                </a>
            </li>
            <!-- New items -->
            <li class="nav-item">
                <a class="nav-link" href="../teacher/manage-research.php">
                    <span data-feather="layers"></span>
                    <i class="fa fa-users mr-2" aria-hidden="true"></i> Manage Students' Research
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../teacher/manage-departments.php">
                    <span data-feather="layers"></span>
                    <i class="fa fa-building mr-2" aria-hidden="true"></i> Manage Department
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../admin/see-department-funding.php">
                    <span data-feather="layers"></span>
                    <i class="fa fa-money mr-2" aria-hidden="true"></i> See Department Funding
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
