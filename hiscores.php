<!DOCTYPE html>
<html>
<head>
<title>Runescape Stat Shower</title>
<meta charset="UTF-8">

<script src="../common/jquery-1.12.0.min.js"></script>

<!-- Latest compiled and minified JavaScript -->
<script src="../common/bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="../common/bootstrap-3.3.6-dist/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="../common/bootstrap-3.3.6-dist/css/bootstrap-theme.min.css">

<link rel="stylesheet" type="text/css" href="style.css">

<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="tables.css">
  
<!-- DataTables -->
<script type="text/javascript" charset="utf8" src="tables.js"></script>

</head>
<body>

<?php
	include 'funcs.php';
	
	$acName = ucwords(@htmlentities($_GET["username"]));
	
	$inVal;
	
	if ($acName)
		$inVal = $acName;
	else
		$inVal = "";
?>

	<div id="outer">
		<div id="innerUpper">
			<form action=<?php echo basename(__FILE__); ?> method=GET>
				<div class="input-group">
				
				<?php
					if (@$_GET["username"] != "")
						echo "<input type='text' class='form-control' name='username' placeholder='Runescape Name Here' maxlength=12 value=\"$inVal\">\n";
					else
						echo "<input type='text' class='form-control' name='username' placeholder='Runescape Name Here' maxlength=12 autofocus>\n";
				?>
					<span class="input-group-btn">
						<button class="btn btn-default" type="submit">Go!</button>
					</span>
				</div>
			</form>
		</div>
		<?php
			if ($acName != "")
			{
				$conts = @file_get_contents("http://hiscore.runescape.com/index_lite.ws?player=$acName");
				
				if ($conts === false)
				{
					?>
					<br>
					<div id="inner2">
						<h2>Username <?php echo $acName; ?> not found!</h2>
					</div>
					<?php
					die();
				}
				
				$uAv = getUserAvatar($acName);
		?>
			<div id="innerLeft">
				<h3><?php echo $acName; ?></h3>
				<div id="userImage">
					<img src="<?php echo $uAv; ?>" height=86px width=86px alt="<?php echo $acName; ?>'s avatar">
				</div>
			</div>
			
			<div id="innerRight">
				<table id="tab" class="table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<th width=20px class="text-center">#</th>
							<th width=40px class="text-center">Skill</th>
							<th width=120px class="text-center">Rank</th>
							<th width=60px class="text-center">Level</th>
							<th width=140px class="text-center">Virtual Level</th>
							<th width=140px class="text-center">Experience</th>
							<th width=140px class="text-center">To Next Level</th>
							<th width=390px class="text-center">Progress</th>
						</tr>
					</thead>
					<?php
						loadStats($conts);
						
						$imgPathBase = "rs_hs_images/";
						$imgType = ".png";
						$imgPath;
						
						/* first iteration is total, no progress bar there */
						
						$iterNum = 0;
						
						$imgPath = $imgPathBase.index2Skill($iterNum).$imgType;
						
						$rank = number_format($ranks[$iterNum]);
						$level = number_format($levels[$iterNum]);
						$exp = number_format($exps[$iterNum]);
						
						$virtualLevel;
						$toNextLevel;
						$progressPercent;
						
						$virtualTotalLevel = 0;
						
						$isMaxTtl = ($level == "2,715");
						
						$colorMax = "style='color: #ff0000;'";
						
						if (!$isMaxTtl)
							$colorMax = "";
						
						for($i = 1; $i < $skillsCount-1; ++$i)
						{
							$virtualTotalLevel += getVirtualLevel($exps[$i], $xpArray, 126);
						}
						//invention
						$virtualTotalLevel += getVirtualLevel($exps[$i], $inventionXpArray, 120);
						
						$colorMaxVirtual = "style='color: #ff0000;'";
						
						if (!($virtualTotalLevel === 3396))
							$colorMaxVirtual = "";
						
						echo "<tr>\n";
						echo "<td class='text-center'>".($iterNum + 1)."</td>\n";
						echo "<td class='text-center'><img src=".$imgPath." width=32 height=32></td>\n";
						echo "<td class='text-center'>".$rank."</td>\n";
						echo "<td class='text-center' ".$colorMax.">".$level."</td>\n";
						echo "<td class='text-center' ".$colorMaxVirtual.">".number_format($virtualTotalLevel)."</td>\n";
						
						if ($exp == "5,400,000,000")
							$colorMax = "style='color: #ff0000'";
						else
							$colorMax = "";
						
						echo "<td class='text-center'".$colorMax.">".$exp."</td>\n";
						echo "<td class='text-center'>0</td>\n";
						echo "<td class='text-center'>N/A</td>\n";
						echo "</tr>\n";
						
						$iterNum++;
						
						for($i = $iterNum; $i < $skillsCount; ++$i)
						{
							$isMaxTotal = false;
							
							$imgPath = $imgPathBase.index2Skill($i).$imgType;
							$rank = number_format($ranks[$i]);
							
							$level = number_format($levels[$i]);
							$exp = $exps[$i];
							
							if ($exp == 200000000)
								$isMaxTotal = true;
							
							if (index2Skill($i) == "Invention")
							{
								$virtualLevel = getVirtualLevel($exp, $inventionXpArray, 120);
								$toNextLevel = $inventionXpArray[$virtualLevel] - $exp;
							}
							else
							{
								$virtualLevel = getVirtualLevel($exp, $xpArray, 126);
								$toNextLevel = $xpArray[$virtualLevel] - $exp;
							}
							
							echo "<tr>\n";
							echo "<td class='text-center'>".($i + 1)."</td>\n";
							echo "<td class='text-center'><img src=".$imgPath." width=32 height=32></td>\n";
							echo "<td class='text-center'>".$rank."</td>\n";
							echo "<td class='text-center'>".$level."</td>\n";
							
							if ($virtualLevel == 126 || (index2Skill($i) == "Invention" && $virtualLevel == 120))
								$colorMax = "style='color: #ff0000'";
							else
								$colorMax = "";
								
							echo "<td class='text-center'".$colorMax.">".$virtualLevel."</td>\n";
							
							if ($exp == 200000000)
								$colorMax = "style='color: #ff0000'";
							else
								$colorMax = "";
							
							echo "<td class='text-center'".$colorMax.">".number_format($exp)."</td>\n";
							echo "<td class='text-center'>".number_format($toNextLevel)."</td>\n";
							
							if ($isMaxTotal)
							{
								echo "<td>\n";
								echo "<div class='progress'>\n";
								echo "<div class='progress-bar progress-bar-success' role='progressbar' style='width: 100%;'>\n";
								echo "<span>100%</span>\n";
								echo "</div>\n";
								echo "</div>\n";
								echo "</td>\n";
							}							
							else
							{
								$xpRemaining;
								$xpTotalDiff;
								
								if (index2Skill($i) == "Invention")
								{
									$xpRemaining = $inventionXpArray[$virtualLevel] - $exp;
									$xpTotalDiff = $inventionXpArray[$virtualLevel] - $inventionXpArray[$virtualLevel-1];
									
									$xpRemaining = $xpTotalDiff - $xpRemaining;
								}
								else
								{
									$xpRemaining = $xpArray[$virtualLevel] - $exp;
									$xpTotalDiff = $xpArray[$virtualLevel] - $xpArray[$virtualLevel-1];
									
									$xpRemaining = $xpTotalDiff - $xpRemaining;
								}
								
								$progressPercent = round(((float)($xpRemaining) / $xpTotalDiff * 100));
								
								echo "<td>\n";
								echo "<div class='progress'>\n";
								echo "<div class='progress-bar progress-bar-warning' role='progressbar' style='width:".$progressPercent."%; color: #000000;'>\n";
								echo "<span>".$progressPercent."%</span>\n";
								echo "</div>\n";
								echo "</div>\n";
								echo "</td>\n";
								echo "</tr>\n";
							}
						}
					?>
				</table>
			</div>
		<?php
			}
		?>
	</div>
</body>
<script>
$(document).ready(function() {
    $('#tab').dataTable({
		columns: [
		{ "bSortable": true, "asSorting": [ "asc", "desc" ] },
		{ "bSortable": false },
		{ "bSortable": true, "asSorting": [ "asc", "desc" ] },
		{ "bSortable": true, "asSorting": [ "desc", "asc" ] },
		{ "bSortable": true, "asSorting": [ "desc", "asc" ] },
		{ "bSortable": true, "asSorting": [ "desc", "asc" ] },
		{ "bSortable": true, "asSorting": [ "desc", "asc" ] },
		{ "bSortable": false },
	],
		info: false,
		searching: false,
		paging: false
	});
});

</script>
</html>