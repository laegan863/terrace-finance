<footer class="footer d-none">
    <div class="container-fluid d-flex justify-content-between">
        <nav class="pull-left d-none">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link" href="javascript:void(0)">
                        {{ config('app.name') }}
                    </a>
                </li>
                <li class="nav-item"><a class="nav-link" href="#">Help</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Licenses</a></li>
            </ul>
        </nav>
        <div class="copyright d-none">
            {{ now()->year }}, made with <i class="fa fa-heart heart text-danger"></i>
        </div>
    </div>
</footer>
