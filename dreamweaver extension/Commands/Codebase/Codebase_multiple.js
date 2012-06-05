//
// Copyright 2009 Adobe Systems Incorporated.  All rights reserved.
// ----------------------------------------------------
//

var CodebaseSelectList = null;
var gWidgets = [];

function canInsertObject()
{
    return true;
}

function isDOMRequired()
{
    // The DesignView DOM must be in sync with CodeView.

    return true;
}
function commandButtons(){
	return new Array(
	"OK" , "doCommand()" ,
	"Cancel" , "window.close()" ,
	"Help" , "showHelp()");
}
function doCommand(){
	var currentVal = CodebaseSelectList.getValue();
	var dom = dw.getDocumentDOM();
	var i;
	for (var i=0; i<serverObj.length; i++) {
		if(serverObj[i]['id']==currentVal){
			dom.source.replaceRange(lineStart, lineEnd, serverObj[i][rootVal]);		
		};
	}	
	window.close();
}
function presetSelect(obj, root, lineNo)
{
	lineStart = lineNo.from;
	lineEnd = lineNo.to;
	serverObj = obj;
	rootVal = root;
	CodebaseSelectList = new ListControl("codeList");
	
	var i;
	for (var i=0; i<obj.length; i++) {
		CodebaseSelectList.append(obj[i]['title'], obj[i]['id']);
	}
}

function widgetSortFunc(a, b)
{
    a = a.name.toLowerCase() + a.version;
    b = b.name.toLowerCase() + b.version;
    return (a > b) ? 1 : (a < b ? -1 : 0);
}

 

function initUI()
{
		
    
}
