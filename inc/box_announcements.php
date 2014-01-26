<table width="100%" borer=0 align="center">

<script language="JavaScript1.2">

var delay=5000 //set delay between message change (in miliseconds)
var fcontent=new Array()
begintag='' //set opening tag, such as font declarations

<?
	$qry = "SELECT * FROM {$dbTables['announcements']} ORDER BY id DESC";
	$dbi->db->query($qry);

	if ($dbi->db->num_rows()) {
		$i = 0;
		while ($obj = $dbi->db->fetch_object()) {
			echo "fcontent[$i]=\"<font color='#ff0000'><b>" . str_replace('"', '\"', $obj->subject) . "</b></font><br>" . str_replace('"', '\"', $obj->message) . "\"\n";
			$i++;
		}
	}

/*
fcontent[0]="<font color='#ff0000'><b>Announcement 1</b></font><hr/>Click here to go back to Dynamicdrive.com frontpage"
fcontent[1]="<font color='#ff0000'><b>Announcement 2</b></font><hr/>Visit JavaScriptKit for award winning JavaScript tutorials"
fcontent[2]="<font color='#ff0000'><b>Announcement 3</b></font><hr/>Get help on scripting and web development. Visit CodingForums.com!"
fcontent[3]="<font color='#ff0000'><b>Announcement 4</b></font><hr/>Looking for Free Java applets? Visit Freewarejava.com!"
fcontent[4]="<font color='#ff0000'><b>Announcement 5</b></font><hr/>If you find this script useful, please click here to link back to Dynamic Drive!"
*/
?>

closetag=''

var fwidth='100%' //set scroller width
var fheight='200px' //set scroller height

var fadescheme=0 //set 0 to fade text color from (white to black), 1 for (black to white)
var fadelinks=1  //should links inside scroller content also fade like text? 0 for no, 1 for yes.

///No need to edit below this line/////////////////

var hex=(fadescheme==0)? 255 : 0
var startcolor=(fadescheme==0)? "rgb(255,255,255)" : "rgb(0,0,0)"
var endcolor=(fadescheme==0)? "rgb(0,0,0)" : "rgb(255,255,255)"

var ie4=document.all&&!document.getElementById
var ns4=document.layers
var DOM2=document.getElementById
var faderdelay=0
var index=0

if (DOM2)
faderdelay=2000

//function to change content
function changecontent(){
if (index>=fcontent.length)
index=0
if (DOM2){
document.getElementById("fscroller").style.color=startcolor
document.getElementById("fscroller").innerHTML=begintag+fcontent[index]+closetag
linksobj=document.getElementById("fscroller").getElementsByTagName("A")
if (fadelinks)
linkcolorchange(linksobj)
colorfade()
}
else if (ie4) {
document.all.fscroller.innerHTML=begintag+fcontent[index]+closetag
} else if (ns4){
document.fscrollerns.document.fscrollerns_sub.document.write(begintag+fcontent[index]+closetag)
document.fscrollerns.document.fscrollerns_sub.document.close()
}

index++
setTimeout("changecontent()",delay+faderdelay)
}

// colorfade() partially by Marcio Galli for Netscape Communications.  ////////////
// Modified by Dynamicdrive.com

frame=20;

function linkcolorchange(obj){
if (obj.length>0){
for (i=0;i<obj.length;i++)
obj[i].style.color="rgb("+hex+","+hex+","+hex+")"
}
}

function colorfade() {	         	
// 20 frames fading process
if(frame>0) {	
hex=(fadescheme==0)? hex-12 : hex+12 // increase or decrease color value depd on fadescheme
document.getElementById("fscroller").style.color="rgb("+hex+","+hex+","+hex+")"; // Set color value.
if (fadelinks)
linkcolorchange(linksobj)
frame--;
setTimeout("colorfade()",20);	
}

else{
document.getElementById("fscroller").style.color=endcolor;
frame=20;
hex=(fadescheme==0)? 255 : 0
}   
}

if (ie4||DOM2)
document.write('<div id="fscroller" style="border: 0px solid black; width: '+fwidth+'; height: '+fheight+'; padding: 0px"></div>')

window.onload=changecontent
</script>

<ilayer id="fscrollerns" width=&{fwidth}; height=&{fheight};><layer id="fscrollerns_sub" width=&{fwidth}; height=&{fheight}; left=0 top=0></layer></ilayer>
</td></tr>
</table>
