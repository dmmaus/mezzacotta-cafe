<html>
<head>
<title>Mezzacotta Caf&eacute;</title>
<style type="text/css">

.main
{
    width: 600px;
    padding-top: 30px;
    margin-left: auto;
    margin-right: auto;
    text-align: center;
    font-family: Serif;
}

.item
{
    margin-top: 15px;
    margin-bottom: 15px;
    font-size: 14pt;
    font-style: italic;
}

.break
{
    font-size: 20pt;
    font-style: normal;
}
</style>
<body>
<div class="main">
<?php
require_once('mezzacafe.php');

for ($i = 0; $i < 5; ++$i)
{
    if ($i != 0)
    {
        ?>
        <div class="break">~</div>
        <?php
    }
    ?>
    <div class="item"><?= MC_RandomWine(); ?></div>
    <?php
}
?>
</div>
</body>
</html>
