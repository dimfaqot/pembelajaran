<?= $this->extend('template/guest') ?>

<?= $this->section('content') ?>

<div class="d-none d-md-block" style="margin-top: 150px;">
    <div class="d-flex justify-content-center">
        <div class="d-flex p-1 rounded bg_3 justify-content-center" style="width: 70%;">
            <div class="bg_1 p-5 rounded-start" style="width: 50%;">
                <div class="d-flex justify-content-center mb-3">
                    <i class="fa-solid fa-circle-user text-center" style="font-size: 60px;"></i>
                </div>

                <form action="<?= base_url('login/auth'); ?>" method="post">
                    <div class="input_light mb-3">
                        <div>Username</div>
                        <input name="username" placeholder="Username" style="width: 100%;" type="text">
                    </div>
                    <div class="input_light">
                        <div>Password</div>
                        <input name="password" placeholder="Password" style="width: 100%;" type="password">
                    </div>
                    <div class="d-grid mt-5">
                        <button class="btn_4 rounded px-2 py-1"><i class="fa-solid fa-arrow-right-to-bracket"></i> Login</button>
                    </div>
                </form>
            </div>
            <div class="bg_2 rounded-end py-5" style="width: 50%;">
                <div class="d-flex justify-content-center mb-5">

                    <img class="bg-white p-2 rounded" width="75" src="<?= base_url('logo.png'); ?>" alt="LOGO">
                </div>

                <h1 class="text-center" style="font-family: Advent Pro;font-size: 22px;font-size:xx-large">
                    <div>PEMBELAJARAN DARING</div>
                    <div>YAYASAN PONPES WALISONGO SRAGEN</div>
                    <div class="mt-3" style="font-size:medium;">- <?= date('Y'); ?> -</div>
                </h1>
            </div>


        </div>
    </div>
</div>
<div class="d-block d-md-none d-sm-block" style="margin-top:100px">
    <div class="d-flex justify-content-center">
        <div class="d-flex p-1 rounded bg_3 justify-content-center">
            <div class="bg_1 p-5 rounded-start">
                <div class="d-flex justify-content-center mb-4">
                    <i class="fa-solid fa-circle-user text-center" style="font-size: 60px;"></i>
                </div>

                <div class="input_light mb-3">
                    <div>Username</div>
                    <input placeholder="Username" style="width: 100%;" type="text">
                </div>
                <div class="input_light">
                    <div>Password</div>
                    <input placeholder="Password" style="width: 100%;" type="password">
                </div>
                <div class="d-grid mt-5">
                    <button class="btn_4 rounded px-2 py-1"><i class="fa-solid fa-arrow-right-to-bracket"></i> Login</button>
                </div>
            </div>

        </div>
    </div>
</div>
<?= $this->endSection() ?>