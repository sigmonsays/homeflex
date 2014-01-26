
function LmOver(elem, bfilename)
{elem.background = "images/"+bfilename;
elem.children.tags('A')[0].style.color = "#7E93AA";}

function LmOut(elem, bfilename)
{elem.background = "images/"+bfilename;
elem.children.tags('A')[0].style.color = "#FFFFFF";}

function LmDown(elem, bfilename)
{elem.background = "images/"+bfilename;
elem.children.tags('A')[0].style.color = "#7E93AA";}

function LmUp(path)
{location.href = path;}

cellswitch = new Image();
cellswitch.src = "images/navcell-off.gif"

