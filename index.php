<?php include('../header_pre.php'); ?>
<title>mezzacotta Café</title>
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
<?php include('../header_post.php'); ?>

<div class="main">
<div class="item">
<b>Welcome to the mezzacotta Café</b><br />
Today's Menu
</div>
<div class="break">~</div>
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
    <div class="item"><?= MC_RandomFood(); ?></div>
    <?php
}
?>
<div class="break">~</div>
<div class="item">
<a href="/mezzacafe/">See more of our chef's offerings</a>
</div>
<div class="break">~</div>
<div class="item">
<a href="/mezzacafe/winelist.php">See our Wine List</a>
</div>
<div class="break">~</div>
<div class="item">
<a href="/mezzacafe/photos.php">See photos of some of our dishes</a>
</div>
</div>

<br class="clear">
<?php include('../footer.php'); ?>
