<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="light">

<head>
    <?= $this->insert('admin/partials/header') ?>
    <?= $this->insert('admin/partials/navbar') ?>
    <?= $this->insert('admin/partials/sidebar') ?>
</head>

<body>
    <?= $this->section('content') ?>
</body>

<?= $this->insert('admin/partials/footer') ?>

</html>