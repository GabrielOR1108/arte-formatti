<!DOCTYPE html>
<html lang="pt-br">

<head>
    <?= $this->insert('site/partials/head') ?>
</head>

<body>
    <?= $this->insert('site/partials/header') ?>
    <?= $this->section('content') ?>
</body>

<?= $this->insert('site/partials/footer') ?>

</html>