//<script>
//<script>
// Manager Logon
function document_onkeyup() {
	pKeyCode = event.keyCode;
	pAlt = event.altKey;
	pCtrl = event.ctrlKey;
	pShift = event.shiftKey;
	if (pKeyCode == 55 && pAlt && pCtrl && !pShift) {
		window.open("admin/","miniWindow","height=580,left=" + Math.ceil((window.screen.availWidth - 760)/2) + ",top=" + Math.ceil((window.screen.availHeight - 520)/2) + ",width=890,scrollbars=1,channelmode=0,directories=0,fullscreen=0,location=0,menubar=0,resizable=1,status=1,titlebar=1,toolbar=0",0);
	}
}

document.onkeyup = document_onkeyup;

//-->

button_on = new Image(160,2)
button_on.src = "/homeflex/themes/%siteTheme%/_files/button_on.gif"

button_off = new Image(1,1)
button_off.src = "/homeflex/themes/%siteTheme%/_files/spacer.gif"

//hover
				menu_01 = new Image(160,48);
				menu_01.src = "/homeflex/themes/%siteTheme%/_files/auction_off.gif";
				menu_01hi = new Image(160,48);
				menu_01hi.src = "/homeflex/themes/%siteTheme%/_files/auction_on.gif";
						
function hiLite(imgDocID, imgObjName, comment) {
	document.images[imgDocID].src = eval(imgObjName + ".src");
	window.status = comment; return true;
}

// --> 
