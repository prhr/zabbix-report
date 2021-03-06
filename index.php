<?php

use ZabbixReport\Report;

require_once 'vendor/autoload.php';

if(file_exists('.env')) {
    $dotenv = Dotenv\Dotenv::createMutable(__DIR__);
    $dotenv->load();
}

$report = new Report();

if (isset($_POST['formato'])) {
    if ($_POST['formato'] == 'csv') {
        $report->view('csv');
        return;
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Relatório</title>
  <link type="css" src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" />

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" crossorigin="anonymous">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap-theme.min.css" crossorigin="anonymous">
</head>
<body>
<div class="container-fluid p-3">
  <form class="form-inline" method="POST">
  <div class="form-row align-items-center">
    <div class="col-auto align-self-end">
      <label for="host">Nome do host</label>
      <select class="custom-select mr-sm-2" id="host" name="host">
        <option value selected>Selecione...</option>
        <?php
        foreach ($report->getAllHosts() as $host) {
            echo '<option value="'.$host.'"'.($_POST['host'] == $host?' selected':'').'>'.$host.'</option>';
        }
        ?>
      </select>
    </div>
    <div class="col-auto align-self-end">
      <label for="item">Nome do item</label>
      <select class="custom-select mr-sm-2" id="item" name="item">
        <option value selected>Selecione...</option>
        <?php
        if (!empty($_POST['host'])) {
            foreach ($report->getAllItemsByHost($_POST['host']) as $item) {
                echo '<option value="'.$item.'"'.($_POST['item'] == $item?' selected':'').'>'.$item.'</option>';
            }
        }
        ?>
      </select>
    </div>
    <div class="col-auto align-self-end">
      <label for="data-inicio">Data início</label>
      <input type="date" name="start-date" value="<?php echo $_POST['start-date']; ?>" class="form-control" id="data-inicio" required>
      <input type="time" name="start-time" value="<?php echo $_POST['start-time']; ?>" class="form-control" />
    </div>
    <div class="col-auto align-self-end">
      <label for="data-fim">Data fim</label>
      <input type="date" name="recovery-date" value="<?php echo $_POST['recovery-date']; ?>" class="form-control" id="data-fim" required>
      <input type="time" name="recovery-time" value="<?php echo $_POST['recovery-time']; ?>" class="form-control" />
    </div>
    <div class="col-auto align-self-end text-sm-center">
      <label for="icmp-sim">ICMP</label>
      <div class="form-check form-check-inline m-1">
        <input class="form-check-input" type="radio" name="icmp" id="icmp-sim" value="1"<?php
            if (!isset($_POST['icmp']) || $_POST['icmp'] == '1') {
                echo ' checked="checked"';
            }?> />
        <label class="form-check-label" for="icmp-sim">Sim</label>
      </div>
      <div class="form-check form-check-inline m-1">
        <input class="form-check-input" type="radio" name="icmp" id="icmp-nao" value="0"<?php
            if (isset($_POST['icmp']) && $_POST['icmp'] == '0') {
                echo ' checked="checked"';
            }?> />
        <label class="form-check-label" for="icmp-nao">Não</label>
      </div>
    </div>
    <div class="col-auto align-self-end text-sm-center">
      <label for="separador">Separador CSV</label>
      <div class="form-check form-check-inline m-1">
        <input class="form-check-input" type="radio" name="separador" id="separador-ponto-e-virgula" value=";"<?php
            if (!isset($_POST['separador']) || (isset($_POST['separador']) && $_POST['separador'] == ';')) {
                echo ' checked="checked"';
            }?> />
        <label class="form-check-label" for="separador-ponto-e-virgula">;</label>
      </div>
      <div class="form-check form-check-inline m-1">
        <input class="form-check-input" type="radio" name="separador" id="separador-virgula" value=","<?php
            if (isset($_POST['separador']) && $_POST['separador'] == ',') {
                echo ' checked="checked"';
            }?> />
        <label class="form-check-label" for="separador-virgula">,</label>
      </div>
    </div>
    <div class="col-auto align-self-end">
      <button type="submit" class="btn btn-primary" name="formato" value="html">Gerar relatório</button>
      <button type="submit" class="btn btn-primary" name="formato" value="csv">Baixar CSV</button>
    </div>
  </div>
  </form>
</div>
<?php
if ($_POST) {
    ?><div class="container-fluid p-3"><?php
    $report->view('html');
    ?></div><?php
}
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
<script>
$('#host').change(function(){
  var form = $(this).closest('form');
  $('<input />').attr('type', 'hidden')
          .attr('name', "formato")
          .attr('value', "html")
          .appendTo(form);
  form.trigger('submit');
});
</script>
</body>
</html>