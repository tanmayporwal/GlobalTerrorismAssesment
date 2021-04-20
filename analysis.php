<?php include "common/common.php" ?>

<div class="row">
    <div class="col-md-2" style="margin-top: 100px;">
        <h2 style="margin-left:2%"> Analysis</h2>
        <div style="margin-top: 10%;"><?php include "analysis/analysis_list.php" ?></div>
    </div>
    <div class="col-md-7" style="margin-top: 100px;">
        <div class="col-md-1"></div>
        <?php if($_GET['analysis'] == 1) { ?>
            <div class="col-md-10">
                <h2> Countrywise Terror Attack Analysis</h2>
                <div style="margin-top: 10%"><?php include "analysis/analysis1.php" ?></div>
            </div>
        <?php } ?>
        <?php 
        if($_GET['analysis'] == 2) { ?>
            <div class="col-md-10">
                <h2>Year Wise Terrorism trend</h2>
                <div><?php include "analysis/analysis2.php" ?></div>
            </div>
        <?php } ?>
        <?php 
        if($_GET['analysis'] == 3) { ?>
            <div class="col-md-10">
                <h2>Growth Rate of Terrorist Groups [Countries affected by a terrorist group]</h2>
                <div><?php include "analysis/analysis3.php" ?></div>
            </div>
        <?php } ?>
        <?php 
        if($_GET['analysis'] == 4) { ?>
            <div class="col-md-10">
                <h2>Lethalities Based on Type of Terror Attacks</h2>
                <div><?php include "analysis/analysis4.php" ?></div>
            </div>
        <?php } ?>
        <?php 
        if($_GET['analysis'] == 5) { ?>
            <div class="col-md-10">
                <h2>Analysis on Target-Type by Country</h2>
                <div><?php include "analysis/analysis5.php" ?></div>
            </div>
        <?php } ?>
        <?php 
        if($_GET['analysis'] == 6) { ?>
            <div class="col-md-10">
                <h2>Analysis of Attacks Based on Casualties</h2>
                <div><?php include "analysis/analysis6.php" ?></div>
            </div>
        <?php } ?>
        <?php 
        if($_GET['analysis'] == 7) { ?>
            <div class="col-md-10">
                <h2>Most Destructive Terror Attacks Within a Year</h2>
                <div><?php include "analysis/analysis7.php" ?></div>
            </div>
        <?php } ?>
        <?php 
        if($_GET['analysis'] == 8) { ?>
            <div class="col-md-10">
                <h2>Countries Affected the Most by Terrorism</h2>
                <div><?php include "analysis/analysis8.php" ?></div>
            </div>
        <?php } ?>
        <div class="col-md-1"></div>
    </div>
    <div class="col-md-3" style="margin-top: 100px;padding-right:30px;">
        <?php include "common/sidebar.php" ?>
    </div>
</div>

<?php include "common/footer.php" ?>