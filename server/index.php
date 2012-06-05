<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title>Codebase</title>
        <link href="style.css" rel="stylesheet" type="text/css">
        <link href="includes/tricolore.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        <script type="text/javascript" src="includes/handlebars.js"></script>
        <script src="includes/jquery.cookie.js"></script>
        <script src="includes/rainbow-custom.min.js"></script>
        <script src="includes/keymaster.min.js"></script>
        <script src="includes/view.js"></script>
    </head>

    <body>
        <div class="header cf">
            <div id="ribbon">
                <div class="inset"></div>
                <div class="container">
                    <div class="base">
                        <h1>Codebase</h1>
                    </div>
                </div>
            </div>
            <div class="search-wrap">
                <label>Filter</label>
                <input type="text" id="search" class="btn search" />
                <span id="clear">x</span>
            </div>  
            <div class="buttons">
                         <a href="edit.php" class="btn new" id="new">New</a>
                     </div>
        </div>
        <div class="view" id="codeView">

            <script id="codebase-template" type="text/x-handlebars-template">
                <div class="block" data-id="{{id}}">
                    <h2 title="{{id}}">{{title}} {{#if notes}}<span class="notes">:: {{notes}}</span>{{/if}}</h2>
                    <div class="links">
                        <a class="edit" href="edit.php?id={{id}}">Edit</a>
						<a class="download" href="download.php?id={{id}}">Download</a>
                        <a class="preview" href="preview.php?id={{id}}">Preview</a>
                    </div>
                    
                </div>
            </script>
            <div id="pagination">
                <select id="noofpage">
                    <option value="5">5</option>
                    <option value="10" selected="selected">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                </select>
                <div id="pages">
                    
                </div>
            </div>
        </div>
    </body>
</html>