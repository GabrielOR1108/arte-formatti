<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MakeWeb - Admin | <?= $this->e($errcode) ?> </title>
    <base href="<?= $this->e($base_url) ?>/">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="views/admin/assets/css/style.css">
</head>

<body>
    <div class="error-area">
        <div class="error-area-text">
            <h1><?= $this->e($errcode) ?></h1>
            <h4 class="mb-4"><?= $this->e($errmsg) ?></h4>
            <button class="btn btn-secondary" onclick="history.back()"><i class="bi bi-arrow-return-left"></i> Voltar</button>
        </div>
    </div>
</body>

</html>