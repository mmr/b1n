// Precisa de IE >= 5.5
bLoad=false
pureText=true
bodyTag="<BODY MONOSPACE STYLE=\"font:10pt arial,sans-serif\">"
bTextMode=false
public_description=new Editor

/*****************************
 Power Editor class
 member function:
 SetHtml
 GetHtml
 SetText
 GetText
 GetCompFocus()
 *****************************/
function Editor() {
  this.put_html=SetHtml;
  this.get_html=GetHtml;
  this.put_text=SetText;
  this.get_text=GetText;
  this.CompFocus=GetCompFocus;
}
function GetCompFocus() {
  Composition.focus();
}

function GetText() {
  return Composition.document.body.innerText;
}

function SetText(text) {
  text = text.replace(/\n/g, "<br>")
  Composition.document.body.innerHTML=text;
}

function GetHtml() {
  if (bTextMode) 
    return Composition.document.body.innerText;
  else {
    cleanHtml();
    cleanHtml();
    return Composition.document.body.innerHTML;
  }
}

function SetHtml(sVal) {
  if (bTextMode) Composition.document.body.innerText=sVal;
  else Composition.document.body.innerHTML=sVal;
}
//End  of Editor Class

/***********************************************
 Initialize everything when the document is ready
 ***********************************************/
var YInitialized = false;
function document.onreadystatechange(){
  if (YInitialized) return;
  YInitialized = true;
  var i, s, curr;
  // Find all the toolbars and initialize them.
  for (i=0; i<document.body.all.length; i++) {
    curr=document.body.all[i];
    if (curr.className == "Btn" && !InitBtn(curr))
      alert("Toolbar: " + curr.id + " failed to initialize. Status: false");
  }
  Composition.document.open("text/html","replace")
  Composition.document.write(bodyTag);
  Composition.document.close()
  Composition.document.designMode="On"
  Composition.document.onkeydown = _handleKeyDown;
  SetHtml(hiddencomposeForm.hiddencomposeFormTextArea.value);

}

function _handleKeyDown () {
  var ev = this.parentWindow.event
  if(ev.keyCode == 13) {
    var sel=Composition.document.selection.createRange();
    sel.pasteHTML("<BR>");
    sel.select();
    ev.returnValue=false;
    ev.cancelBubble=true;
  }
}

/***********************************************
 Initialize a button ontop of toolbar
 ***********************************************/
function InitBtn(btn) {
  btn.onmouseover = BtnMouseOver;
  btn.onmouseout = BtnMouseOut;
  btn.onmousedown = BtnMouseDown;
  btn.onmouseup = BtnMouseUp;
  btn.ondragstart = YCancelEvent;
  btn.onselectstart = YCancelEvent;
  btn.onselect = YCancelEvent;
  btn.YUSERONCLICK = btn.onclick;
  btn.onclick = YCancelEvent;
  btn.YINITIALIZED = true;
  return true;
}

// Hander that simply cancels an event
function YCancelEvent() {
  event.returnValue=false;
  event.cancelBubble=true;
  return false;
}

// Toolbar button onmouseover handler
function BtnMouseOver() {
  if (event.srcElement.tagName != "IMG") return false;
  var image = event.srcElement;
  var element = image.parentElement;
  // Change button look based on current state of image.- we don't actually have chaned image
  // could be commented but don't remove for future extension
  if (image.className == "Ico") element.className = "BtnMouseOverUp";
  else if (image.className == "IcoDown") element.className = "BtnMouseOverDown";
  event.cancelBubble = true;
}

// Toolbar button onmouseout handler
function BtnMouseOut() {
  if (event.srcElement.tagName != "IMG") {
    event.cancelBubble = true;
    return false;
  }
  var image = event.srcElement;
  var element = image.parentElement;
  yRaisedElement = null;
  element.className = "Btn";
  image.className = "Ico";
  event.cancelBubble = true;
}

// Toolbar button onmousedown handler
function BtnMouseDown() {
  if (event.srcElement.tagName != "IMG") {
    event.cancelBubble = true;
    event.returnValue=false;
    return false;
  }
  var image = event.srcElement;
  var element = image.parentElement;

  element.className = "BtnMouseOverDown";
  image.className = "IcoDown";

  event.cancelBubble = true;
  event.returnValue=false;
  return false;
}

// Toolbar button onmouseup handler
function BtnMouseUp() {
  if (event.srcElement.tagName != "IMG") {
    event.cancelBubble = true;
    return false;
  }

  var image = event.srcElement;
  var element = image.parentElement;

  if (element.YUSERONCLICK) eval(element.YUSERONCLICK + "anonymous()");

  element.className = "BtnMouseOverUp";
  image.className = "Ico";

  event.cancelBubble = true;
  return false;
}

// Check if toolbar is being used when in text mode
function validateMode() {
  if (! bTextMode) return true;

  alert('Por favor desmarque a opção "Ver código HTML" para usar a barra de ferramentas');

  Composition.focus();
  return false;
}

function sendHtml(){
  if(bTextMode){
    document.composeForm.body.value = public_description.get_text();
    return true;
  }
  else{
    document.composeForm.body.value = public_description.get_html();
    return true;
  }
}

//Formats text in composition.
function formatC(what,opt) {
  if (!validateMode()) return;
  if (opt=="removeFormat") {
    what=opt;
    opt=null;
  }
  if (opt==null) Composition.document.execCommand(what);
  else Composition.document.execCommand(what,"",opt);
  pureText = false;
  Composition.focus();
}

//Switches between text and html mode.
function setMode(newMode) {
  bTextMode = newMode;
  var cont;
  if (bTextMode) {
    cleanHtml();
    cleanHtml();
    cont=Composition.document.body.innerHTML;
    Composition.document.body.innerText=cont;
  } else {
    cont=Composition.document.body.innerText;
    Composition.document.body.innerHTML=cont;
  }
  
  Composition.focus();
}

//Finds and returns an element.
function getEl(sTag,start) {
  while ((start!=null) && (start.tagName!=sTag)) start = start.parentElement;
  return start;
}

function createLink() {
  if (!validateMode()) return;
  
  var isA = getEl("A",Composition.document.selection.createRange().parentElement());

  var str=prompt("Digite a URL:", isA ? isA.href : "http:\/\/");

  if ((str!=null) && (str!="http://")) {
    if (Composition.document.selection.type=="None") {
      var sel=Composition.document.selection.createRange();
      sel.pasteHTML("<A HREF=\""+str+"\">"+str+"</A> ");
      sel.select();
    }
    else formatC("CreateLink",str);
  }
  else Composition.focus();
}

//Sets the text color.
function foreColor() {
  if (! validateMode()) return;
  var arr = showModalDialog("/ym/ColorSelect?3", "", "font-family:Verdana; font-size:12; dialogWidth:30em; dialogHeight:35em");
  if (arr != null) formatC('forecolor', arr);
  else Composition.focus();
}

//Sets the background color.
function backColor() {
  if (!validateMode()) return;
  var arr = showModalDialog("/ym/ColorSelect?3", "", "font-family:Verdana; font-size:12; dialogWidth:30em; dialogHeight:35em");
  if (arr != null) formatC('backcolor', arr);
  else Composition.focus()
}

function cleanHtml() {
  var fonts = Composition.document.body.all.tags("FONT");
  var curr;
  for (var i = fonts.length - 1; i >= 0; i--) {
    curr = fonts[i];
    if (curr.style.backgroundColor == "#ffffff") curr.outerHTML = curr.innerHTML;
  }
}

function getPureHtml() {
  var str = "";
  var paras = Composition.document.body.all.tags("P");
  if (paras.length > 0) {
    for (var i=paras.length-1; i >= 0; i--) str = paras[i].innerHTML + "\n" + str;
  } else {
    str = Composition.document.body.innerHTML;
  }
  return str;
}
