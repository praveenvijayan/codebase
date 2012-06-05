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
	var txtEditor = document.getElementById('txtServerPath');
	txtEditor = txtEditor.value;
	dreamweaver.setPreferenceString("Pref Codebase path", "Codebase path", txtEditor);
	window.close();
}

function initUI()
{
	var txtEditor = document.getElementById('txtServerPath');
	txtEditorVal = txtEditor.value;
	txtEditor.value =  dreamweaver.getPreferenceString("Pref Codebase path", "Codebase path", txtEditorVal);
}
