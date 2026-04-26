<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

  <section class="section profile">
    <div class="row">

      <!-- KIRI (FOTO + USER) -->
      <div class="col-xl-4">
        <div class="card">
          <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

            <img src="<?= base_url()?>NiceAdmin/assets/img/profile-img.jpg" alt="Profile" class="rounded-circle">
            <h2><?= $username ?></h2>
            <h3><?= $role ?></h3>

          </div>
        </div>
      </div>

      <!-- KANAN (DETAIL DATA) -->
      <div class="col-xl-8">
        <div class="card">
          <div class="card-body pt-3">

            <h5 class="card-title">Detail Profil</h5>

            <div class="row">
              <div class="col-lg-3 col-md-4 label">Username</div>
              <div class="col-lg-9 col-md-8"><?= $username ?></div>
            </div>

            <div class="row">
              <div class="col-lg-3 col-md-4 label">Email</div>
              <div class="col-lg-9 col-md-8">
                <?= isset($email) ? $email : '-' ?>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-3 col-md-4 label">Role</div>
              <div class="col-lg-9 col-md-8"><?= $role ?></div>
            </div>

            <div class="row">
              <div class="col-lg-3 col-md-4 label">Waktu Login</div>
              <div class="col-lg-9 col-md-8"><?= $login_time ?></div>
            </div>

            <div class="row">
              <div class="col-lg-3 col-md-4 label">Status</div>
              <div class="col-lg-9 col-md-8">
                <span class="badge bg-success"><?= $status ?></span>
              </div>
            </div>

          </div>
        </div>
      </div>

    </div>
  </section>

<?= $this->endSection() ?>