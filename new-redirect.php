<?php
echo <<<EOF
<html>
	<form action="place.php" method="POST">
		<label>Source URL</label>
		<input type="text" name="source" value="Please enter source url!!"></input>
		<label>Target URL</label>
		<input type="text" name="target" value="Please enter target url!!"></input>
		<input type="submit" />
	</form>
</html>
EOF;

?>