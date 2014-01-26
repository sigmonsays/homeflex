<H2>Who Am I?</H2>
<?

	echo "IP: " . $_SERVER['REMOTE_ADDR'] . "<BR>";

	echo "Outgoing Port: " . $_SERVER['REMOTE_PORT'] . "<BR>";

	$host = gethostbyaddr($_SERVER['REMOTE_ADDR']);

	echo "Host Name: " . ((empty($host)) ? "<I>Reverse Lookup failed</I>" : $host) . "<BR>";
?>
