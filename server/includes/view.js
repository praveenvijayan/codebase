    //SyntaxHighlighter.all();
 
(function($){
    //Dom ready
    $(function(){
      
      var codebaseView = window.codebaseView || {}
      
      codebaseView = {
          init:function(){
              this.layout();
              //SyntaxHighlighter.all(); 
              //this.tab();
               this.registerHandlers();
              
          },
          registerHandlers:function(){
              $('#noofpage').on('change', function(){
                  codebaseView.layout();
              });
              
              $('#pages').on('click','span', function(event){
                  $('#pages span').removeClass('current');
                  $(this).addClass('current');
                  codebaseView.layout({pagerefresh:'no', pageno:$(this).text()});
                  return;
              });
              
              $('#search').on('keyup', function(){
				  clearTimeout( codebaseView.timer ); 
                  var $this = $(this);
				  
				  //If input has no value reset layout
                   if($this.val().length === 0){
                        $this.val('');
						codebaseView.layout();
                        return;
                   }
				  
                   
                   	codebaseView.timer = ( $this.val().length >= 3 ) && setTimeout(function() {
                       codebaseView.getSearch($this.val());
                   }, 400);
              });
              
              $('#clear').on('click', function(){
                  $('#search').val('');
                  codebaseView.layout();
              });
              
              //Keyboard shortcuts
              key('ctrl+alt+n', function(){ 
                window.location = '/codebase/edit.php';
              });
              
          },
          /*tab:function(){
              
              $('#codeView').not('.current').on('click','.tabs li', function(event){
                  var $this = $(this);
                  var index = $this.index();
                  var displayBlock = $this.parents('.block').find('.code-wrap li').get(index);
                  if($this.hasClass('edit')){
                      window.location = $this.find('a').attr('href');
                  }
                   $this.parents('.block').find('.code-wrap li').hide();
                   $this.parents('.block').find('.tabs li').removeClass('current');
                   $this.addClass('current');
                   $(displayBlock).show()
                  event.preventDefault();
              });
              
              
          },*/
          getSearch:function(query){
              
              this.layout({search:query});
          },
          layout:function(obj){
              var source   = $('#codebase-template').html();
              var template = Handlebars.compile(source);
              var range = $('#noofpage option:selected').val();
              
              if(!obj){
                  obj = {};
                  obj.pagerefresh = "yes";
              }
              
              var page = obj.pageno-1 || 0;
              var search = obj.search || "";
              
              var getVal = $.ajax('getCode.php',{
                              data:"range="+range+"&page="+page+"&q="+search
                           });
               $('#codeView .block').remove();
              getVal.done(function(msg){
                  var codeObj = $.parseJSON(msg).reverse();
                  //Paging
                  if(search !==""){
                    obj.pagerefresh = "no";
                    $('#pages').html('');
                  }
                  if(obj.pagerefresh !== 'no'){
                      var pageCount = codeObj[0].pageCount || 0;
                      codebaseView.setPage(pageCount);
                      $('#pages span:first').addClass('current');
                  };                
                  $(codeObj).each(function(i, item){
                    //  console.log(item);
                     var output = template(item);
                     $('#codeView').prepend(output);
                     Handlebars.registerHelper('notes', function() {
                      return this.notes.replace(/xscript/g,'script');
                  });
                     
                  });
                  
                  
                  
               });
          },
          setPage:function(msg){
              
              var pageElem = $('#pages');
              pageElem.html('');
              var offset = $('#noofpage option:selected').val();
              var pageLen = Math.ceil(msg/offset);
              for(var i=0; i<pageLen; i++){
                  var pno = parseInt(i)+1;
                  pageElem.append("<span>"+pno+"</span>");
              }
          }
      }
      
      window.codebaseView = codebaseView;
      
      codebaseView.init();
      
      
    })    
    
}(jQuery))