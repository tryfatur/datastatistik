<?php
	$portal     = "http://data.jakarta.go.id/";
	$org_url    = $portal."api/3/action/organization_list";
	$org_result = file_get_contents($org_url);
	$org_result = json_decode($org_result);
?>

<form action="index.php" method="get">
	<select name="dataset" onchange="this.form.submit()">
		<option value="">-- Pilih Organisasi --</option>
	<?php for ($i=0; $i < count($org_result->result); $i++):?>
		<option value="<?php echo $org_result->result[$i] ?>">
			<?php echo ucwords(strtolower(str_replace('-', ' ', $org_result->result[$i]))) ?>
		</option>
	<?php endfor; ?>
	</select>
</form>

<?php

if (isset($_GET['dataset']))
{
	$base_url = $portal."api/3/action/";
	$api_url = "package_search?q=organization:".$_GET['dataset']."&start=0&rows=100";

	$result = file_get_contents($base_url.$api_url);
	$result = json_decode($result);

	for ($i=0; $i < count($result->result->results); $i++)
	{ 
		for ($j=0; $j < count($result->result->results[$i]->resources); $j++)
		{ 
			$judul_dataset[] = ucwords(strtolower($result->result->results[$i]->resources[$j]->name));
		}
	}

	echo "<pre>";
	print_r ($judul_dataset);
	echo "</pre>";
}

$org_url = $portal."api/3/action/organization_list";
$org_result = file_get_contents($org_url);
$org_result = json_decode($org_result);

echo "<pre>";
print_r ($org_result->result);
echo "</pre>";

?>

<script src="https://code.jquery.com/jquery-1.12.3.min.js"></script>
<script src="engine.js"></script>