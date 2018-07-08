<script src="/assets/js/app.js"></script>
<nav class="navbar navbar-expand-sm navbar-light bg-light">
    <a class="navbar-brand" href="#">Tickets</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <form class="navbar-form  navbar-left">
                    <button class="btn btn-sm btn-outline-success" type="button">Create ticket</button>
                </form>
            </li>
        </ul>
        <div class="navbar-text mr-3 ml-3">
            Hello, <?=$html->login?>
        </div>
        <form class="navbar-form navbar-right">
            <button class="btn btn-outline-success" type="button" id="logoutButton">Logout</button>
        </form>
    </div>
</nav>


