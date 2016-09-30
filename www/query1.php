<?

if ($_GET['id']) {
  # Attempt to run the query
  
  $rec_id = intval($_GET['id']);
  $maxdepth = intval($_GET['depth']);
  $linked_ids = array();
  $summary_data = array();
  
  $dbconn = pg_connect("host=localhost port=5432 dbname=carnivorae user=root password=orangered");
  if (!$dbconn) {
    echo "ERROR: Could not connect to database\n";
    exit;
  }
  
  # Fetch the initial record and set up the traversal process  
  $result = pg_query($dbconn, "SELECT id, uuid, species, foo, link1, link2 FROM critters WHERE id=$rec_id");
  if (!$result) {
    echo "ERROR: Target record id=$rec_id not found in database\n";
    exit;
  }
  
  $row = pg_fetch_row($result);
  $rec_species = $row[2];
  $linked_ids[0] = array($row[4], 0); # link1
  $linked_ids[1] = array($row[5], 0); # link2
  
  # Query for each record of interest and modify the summary data as appropriate
  $i = 0;
  for($i = 0; $linked_ids[$i][1] <= $maxdepth; $i++) {
    # Iterate over each record until we exceed the depth set in the parameters
    $result = pg_query($dbconn, "SELECT id, uuid, species, foo, link1, link2 FROM critters WHERE uuid=\"{$linked_ids[$i][0]}\"");
    if (!$result) {
      echo "ERROR: Target record uuid={$linked_ids[$i][0]} not found in database\n";
      exit;
    }
  
    $depth = $linked_ids[$i][1] + 1;
    $row = pg_fetch_row($result);
    if (!$array_key_exists($row[2], $summary_data)) {
      # New species, just store this example's values
      $summary_data[$row[2]] = array(1, floatval($row[3]);
    }
    else {
      # Already in summary, add to quantity and adjust average
      $summary_data[$row[2]][0]++;
      $summary_data[$row[2]][1] = $summary_data[$row[2]][1] + ((floatval($row[3]) - $summary_data[$row[2]][1]) / $summary_data[$row[2]][0]);
    }
    
    # Add the two links from here
    $linked_ids[] = array($row[4], $depth);
    $linked_ids[] = array($row[5], $depth);    
  }
   
}



?>
<!DOCTYPE html>
<html>
<title>PHPOPT Example Form</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
<link rel="stylesheet" href="http://www.w3schools.com/lib/w3-theme-black.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css">
<body>

<header class="w3-container w3-theme w3-padding" id="myHeader">
  <i onclick="w3_open()" class="fa fa-bars w3-xlarge w3-opennav"></i>
  <div class="w3-center">
  <img src="assets/logo.png" /><h1 class="w3-xxxlarge w3-animate-bottom">EXAMPLE: CARNIVORAE</h1>
  <h4>PHP OPTIMIZATION TRAINING</h4>
  </div>
</header>

<p><form method="GET" action="<? echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
<form class="w3-container w3-card-4">
  <div class="w3-row">
  <div class="w3-half">
    <div class="w3-group">
      <input class="w3-input" type="text" name="id" required />
      <label class="w3-label w3-validate">ID Number</label>
    </div>
  </div>
  <div class="w3-half">
    <div class="w3-group">
      <input class="w3-input" type="text" name="depth" required />
      <label class="w3-label w3-validate">Graph Depth</label>
    </div>
  </div>
  </div>
  <div class="w3-center">
    <input class="w3-btn" type="submit" value="Run Query" />
  </div>
</form></p>

<? if ($query_complete) { ?>

<h2 class="w3-center"><? echo "$rec_species #$rec_id's connections:"; ?></h2>
<div class="w3-responsive w3-card-4">
<table class="w3-table w3-striped w3-bordered">
<thead>
<tr class="w3-theme">
  <th>Species</th>
  <th>Quantity</th>
  <th>Average foo Value</th>
</tr>
</thead>
<tbody>
<?

#<tr>
#  <td>Jill</td>
#  <td>Smith</td>
#  <td>50</td>
#</tr>



?>
</tbody>
</table>
</div>


<iframe src="https://en.m.wikipedia.org/wiki/<? echo $rec_species ?>" width="800" height="1000"></iframe>

<? } # $query_complete = true ?>

</body>
</html>
