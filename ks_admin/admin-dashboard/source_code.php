<?php
include_once '../../library.php';
include_once '../header_isadmin.php';

// fill parameters from form
$did = 0; 
if (isset ($_GET ['did'])) {
	$did = ( int ) $_GET ['did'];
}

$dsh_code = "<?php 
//parameter $did refers to dashboard ID
KS_Dashboard::display(" . $did . "); 
?>";

include_once '../header_bootstrap.php';

?>
<p>Use this source code to generate dashboard
						function.</p>

<textarea class="form-control ks-form-control"
 name="dsh_code" id="dsh_code" rows="5" cols="50"><?php echo $dsh_code;?></textarea>

