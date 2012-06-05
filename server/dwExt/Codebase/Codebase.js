function CodebaseGetCode() {
	
	var dom = dw.getDocumentDOM(); // Get current document DOM.
	var theSel = dom.getSelection();
	var docElem = dom.documentElement;
	var content = dom.source.getText();
	var caret_pos = dom.source.getSelection()[1];
	var line = getLineBounds(content, caret_pos);
	var ValText = content.substring(line.start, line.end);
	var ValText = ValText.replace(/^\s+|\s+$/g, "");
	var serverData = MMHttp.postText('http://192.168.0.71/codebase/getResult.php',"q="+ValText);
	var parseJson = JSON.parse(serverData.data);
	var root = ValText.split(":");
	if(root[0] =='h'){
		root[0] = 'html';
	}
	if(root[0] =='c'){
		root[0] = 'css';
	}
	if(root[0] =='j'){
		root[0] = 'js';
	}
	if(root[0] =='f'){
		root[0] = 'full';
	}
	
	if(!parseJson.length){
		alert('Sorry!! No result');
		return;
	};
	
	if(root[0] === 'f' || root[0] ==='full'){
		
		
		//Multi line
		/*var lineNo = {
				"from":line.start,
				"to":line.end
			}
		dw.runCommand('Codebase_multiple.html', parseJson, root[0], lineNo);*/
		if(parseJson.length === 1){
			//alert(JSON.stringify(parseJson[0]));
			var roots = ["html","css","js","options"];
			
			for(var i = 0; i<roots.length; i++){
				if(parseJson[0][roots[i]] !== undefined || parseJson[0][roots[i]] !== ""){
					//dom.source.replaceRange(line.start, line.end, "");
					//var insertionPoint = dom.getSelection();
					//dom.insertHTML(parseJson[0][roots[i]]);
					
					/*var currentDOM = dw.getDocumentDOM();
					var offsets = currentDOM.getSelection();
					var theSelection = currentDOM.offsetsToNode(offsets[0],offsets[0]+1);*/
					
					dom.insertHTML(parseJson[0][roots[i]]);
					
					//dom.source.replaceRange(line.start, line.end, "");
					
					//dom.insertHTML(parseJson[0][roots[i]]);
					
				}
			}
		}
		return;	
	}
	
	if(parseJson.length === 1){
		dom.source.replaceRange(line.start, line.end, parseJson[0][root[0]]);	
	}else{
		var lineNo = {
				"from":line.start,
				"to":line.end
			}
		dw.runCommand('Codebase_multiple.html', parseJson, root[0], lineNo);
		//alert("more...");
	};
	
	//dom.insertHTML(parseJson[0].html);
	
	
	/*var content = this.getContent(),
			    caret_pos = this.getCaretPos(),
				line = getLineBounds(content, caret_pos);

			return content.substring(line.start, line.end);*/
	
	
	
	
	
	
	
	//var selText = dom.substring(theSel[0],theSel[1]);
	
	return false;
}


function getLineBounds(text, from) {
		var ch = '',
			end = from,
			text_length = text.length;

		// Find start of line
		while (from && (ch = text.charAt(from - 1)) !== '\n' && ch !== '\r')
			from--;

		// Find end of line
		while (end < text_length && (ch = text.charAt(end)) !== '\n' && ch !== '\r')
			end++;

		return {start: from, end: end};
	}
