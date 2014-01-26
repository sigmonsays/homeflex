<H2>PGP Public Key</H2>
<LI><A HREF="/homeflex/pgp.txt">Download Key</A></LI>
<PRE>

<FONT SIZE="+1">
<?
	$data = join("", file("$localPath/pgp.txt"));
	if (!empty($data)) {
		echo $data;
	} else {
		echo "No PGP signature available";
	}
?>
</FONT>
</PRE>
