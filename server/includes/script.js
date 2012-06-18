(function($){

    //Dom ready

    $(function(){

      var delay;

      var codebase = window.codebase || {}

      

      codebase = {

          init:function(){

              this.setEditors();

              this.registerHandlers();

              this.loadEditorValues();

          },

          registerHandlers:function(){

              //Preview button handler

              $('#preview').on('click', function(){

                  codebase.preview()

              });

              //Save button handler

               $('#save').on('click', function(){

                  codebase.save();

              });

              //Close preview

              $(document).on('click','#close',function(){

                  codebase.closePreview();

              });

              //Store on cookies

              var htmlTxt = this.Htmleditor.getInputField();

              var cssTxt = this.CssEditor.getInputField();

              var jsTxt = this.JsEditor.getInputField();

              

              $(htmlTxt).on('keyup', function(){

                  codebase.storeValue('html') 

              });

              $(cssTxt).on('keyup', function(){

                  codebase.storeValue('css') 

              });

              $(jsTxt).on('keyup', function(){

                  codebase.storeValue('js') 

              });

              

              $('#options input').on('click', function(){

                  if($(this).is(':checked')){

                      $.cookie($(this).attr('id'), 'checked')

                  }else{

                      $.cookie($(this).attr('id'), 'unchecked')

                  }

              });

              

              $('#name').on('keyup',function(){

                  codebase.storeValue('name') 

              });

              

              $('#notes').on('keyup', function(){

                  codebase.storeValue('notes');

              });

              //New snippet

              $('#new').on('click', function(){

                   //codebase.newSnippet();

                   window.location = "edit.php"

              });

              //Delete snippet

              $('#delete').on('click', function(){

                  codebase.deleteSnippet();

              });

              //Format selection

              $('#labelHTML').on('click', function(){

                  codebase.formatSelection(); 

              });

              

              //Home link

              $('#ribbon').on('click', function(){

                  window.location = "/";

              });

              

              //Auto fill 

              $('#name').on('keyup', function(e){

                   clearTimeout( codebase.timer ); 

                   var $this = $(this);

                   

                   //If input has no value clear autocomplete

                   if($this.val().length === 0){

                        $this.val('');

                        $('#autoComplete').html('');

                        $('.autocomplete').hide();

                        return;

                   }

                   codebase.timer = ( $this.val().length >= 3 ) && setTimeout(function() {

                       codebase.autocomplete($this);

                   }, 400);

              });

              //Autofill li clicked

              $('#autoComplete').on('click','li',function(){

                  var id = $(this).attr('id');

                  window.location = '/edit.php?id='+id;

              });

              //Keyboard shortcuts

              key('esc', function(){ 

                $('.autocomplete').hide();

              });

              

              /*$('#name').blur(function(){

                $('.autocomplete').hide();

              })*/

              

              //Remove dependency file

              $('#dependencyFiles').on('click','span', function(){

                  var currentUrl = $(this).parent().data('url');

                  //var optCookieVal = $.cookie('options');

                  //var optArry = optCookieVal.split(",");

                  var optArry = $(this).find('li').data('url');

                  var temArry = [];

                  $(optArry).each(function(i,item){

                      if(currentUrl !== item && currentUrl !== ""){

                          temArry.push(item);

                      }

                      

                      //var getFileName = codebase.getDependancyFilename(item);

                      //$('#dependencyFiles').append('<li data-url="'+item+'">'+getFileName+'<span class="close">x</span></li>');

                  });

                  $.cookie('options',temArry);

                  $(this).parent().remove();

              });

              

              //Button login 

              $('#btnLogin').on('click', function(){

                  codebase.auth();

              });

              //Logout

              if($.cookie('login') === 'true' ){

                  $('.loginBox').hide();

                  $('#btnLoginOut').show();

              }else{

                  $('.loginBox').show();

                  $('#btnLoginOut').hide();

              }

              //Keyboard shortcuts

              key('ctrl+alt+l', function(){ 

                  $('#login').toggle();

                if($.cookie('login') === 'true'){

                  $('.loginBox').hide();

                  $('#btnLoginOut').show();

                }else{

                  $('.loginBox').show();

                  $('#btnLoginOut').hide();

                }

              });

              

              $('#btnLoginOut').on('click', function(){

                  $.cookie('login',false);

                  $('#login').hide();

              })

          },

          setEditors:function(){

             this.Htmleditor = CodeMirror.fromTextArea(document.getElementById("html"), {
				 mode: "text/html", 
				 tabMode: "indent", 
				 lineNumbers:true,
				onChange: function() {
				  clearTimeout(delay);
				  delay = setTimeout(codebase.livePreview, 300);
				}
				 });  

             this.CssEditor = CodeMirror.fromTextArea(document.getElementById("css"), {
				 mode: "css", 
				 tabMode: "indent", 
				 lineNumbers:true,
					onChange: function() {
					  clearTimeout(delay);
					  delay = setTimeout(codebase.livePreview, 300);
					}
				 }); 

             this.JsEditor = CodeMirror.fromTextArea(document.getElementById("javascript"), {
				 mode: "javascript", 
				 tabMode: "indent", 
				 lineNumbers:true,
					onChange: function() {
					  clearTimeout(delay);
					  delay = setTimeout(codebase.livePreview, 300);
					}
				 });  

          },

          loadEditorValues:function(){

              if($.cookie('html') !== null){

                  this.Htmleditor.setValue($.cookie('html'));

              }

              if($.cookie('css') !== null){

                  this.CssEditor.setValue($.cookie('css'));

              }

              

              if($.cookie('js') !== null){

                  this.JsEditor.setValue($.cookie('js'));

              }

              

              if($.cookie('name') !== null){

                  $('#name').val($.cookie('name'));

              }



              if($.cookie('notes') !== null){

                  $('#notes').val($.cookie('notes'));

              } 

              

              $('#options input').each(function(key,item){

                  var chkboxState = $.cookie($(item).attr('id'));

                  if(chkboxState == 'checked'){

                       this.checked = true;

                  }

              });

              

              if($.cookie('id') !== null){

                  $('#codeid').val($.cookie('id'))

              }

              

              if($.cookie('options') !== null && $.cookie('options') !== ""){

                  var optCookieVal = $.cookie('options');

                  var optArry = optCookieVal.split(",");

                  $(optArry).each(function(i,item){

                      if(item !== ""){

                         var getFileName = codebase.getDependancyFilename(item);

                         $('#dependencyFiles').append('<li data-url="'+item+'">'+getFileName+'<span class="close">x</span></li>'); 

                      }

                  })

              }

              

          },

          storeValue:function(code, val){

              if(code === 'html'){

                 $.cookie('html', this.Htmleditor.getValue()); 

              }else if(code === 'css'){

                 $.cookie('css', this.CssEditor.getValue());

              }else if(code === 'js'){

                 $.cookie('js', this.JsEditor.getValue());

              }else if(code === 'name'){

                 $.cookie('name', $('#name').val());

              }else if(code === 'notes'){

                 $.cookie('notes', $('#notes').val());

              }else if(code === 'options'){

                 if($.cookie('options') === null){

                     $.cookie(code, val);

                 }else{

                     var optCookie = $.cookie('options');

                     $.cookie(code, optCookie +" , "+val);

                 }

              }

          },

          livePreview: function () {
				var html = codebase.Htmleditor.getValue();
				var css = codebase.CssEditor.getValue();
				var js = codebase.JsEditor.getValue();
				html += "<style>"+css+"<style>";
				var previewFrame = document.getElementById('previewFrame');
				var preview =  previewFrame.contentDocument ||  previewFrame.contentWindow.document;
				preview.open();
				preview.write(html);
				preview.close();

          },

          addHTML:function(code){

              if(code !==""){

                 var ifrm =  this.getIframe();

                 ifrm.body.innerHTML = code;

              }

          },

          addCSS:function(code){

              if(code !==""){

                  var ifrm =  this.getIframe();

                  var ibody = ifrm.body

                  $(ibody).prepend("<style>"+code+"</style>");

              }

          },

          addJS:function(code){

               if(code !==""){

                  var ifrm =  this.getIframe();

                  var ihead = ifrm.head

                  var inlineSrcipt = document.createElement('script');

                      inlineSrcipt.type = 'text/javascript';

                      inlineSrcipt.text = code;

                     // ihead.appendChild(inlineSrcipt);

                     

                  var timeOut = setTimeout(function(){

                      ihead.appendChild(inlineSrcipt);

                  },300)

                  

              }

          },

          getIframe:function(){

              codePreview = $('#codePreview');

              iframeDoc = codePreview[0].contentWindow.document;

              return iframeDoc;

          },

          /*loadOptions:function(){

              var ifrm = this.getIframe();

                  opts = $('#options input:checked');

                 if(opts.length !==0){

                     opts.each(function(index, item){

                        var href = $(item).val();                       

                        ifrm.head.appendChild(codebase.getScript(href));

                        

                        if($(this).data('related')){

                           var related = $(this).data('related');

                               related = related.split(',');

                               $(related).each(function(i,sub){

                                   if(sub.match(/.js/)){   

                                       ifrm.head.appendChild(codebase.getScript(sub));

                                   }else{

                                      $(ifrm.head).prepend('<link href="'+sub+'" rel="stylesheet" type="text/css" />')

                                   }

                               })

                           //console.log(related)

                        }

                     })

                 } 

          },*/

          getScript:function(url){

              var scrpt = document.createElement('script');

                  scrpt.type = 'text/javascript';

                  scrpt.src = url;

                  return scrpt;

          },

          formatSelection:function(){

                function getSelectedRange() {

                    return {from: codebase.Htmleditor.getCursor(true), to: codebase.Htmleditor.getCursor(false)};

                }

                var range = getSelectedRange();

                this.Htmleditor.autoFormatRange(range.from, range.to);

          },

          save:function(){

              /*if($.cookie('login') === 'true'){

                  this.loguser();

              }else{

                  $('#login').show();

                  return;

              }*/

              //Get values

              var title = $('#name').val();

              var html =  this.Htmleditor.getValue();

              var css = this.CssEditor.getValue();

              var js = this.JsEditor.getValue();

              var notes = $('#notes').val();

              var optId=[];

              if(title === "" || title === "Untitled"){

                  alert("Please enter title!");

                  return;

              }

              var options = $('#dependencyFiles li');

                  options.each(function(i,item){

                      optId.push($(item).data('url'));

                  });

                            

              var flag = $.cookie('flag');

              var codeId = window.location.search.replace('?id=','');

			  //var encodedHtml = $('div').text(html).html();

                          var htmlEncode = html.replace(/script/g,'xscript');

                          notes = notes.replace(/script/g,'xscript');
						  
						  htmlEncode = escape(htmlEncode);
						  
			  			  //htmlEncode = escape(htmlEncode); 

                          js = escape(js);

                          notes = escape(notes);  

			  //return;

              var params = 'title='+title+'&html='+htmlEncode+'&css='+css+'&js='+js+'&notes='+notes+'&options='+optId+'&status='+flag+'&id='+codeId+'';

              //console.log(params)

              

             var saveAjax = $.ajax({

                                type: "POST",

                                url: "save.php",

                                data: params

                            });

                            

                    saveAjax.done(function( msg ) {

                        //codebase.resetAll();

                       // alert("Snippet saved");

                       //console.log(msg);

                       var latestId = $.parseJSON(msg);

                        window.location = "edit.php?id="+latestId.id;

                    });

          },

          newSnippet:function(){

              this.resetAll();

              /*this.getId(function(msg){

                  $('#codeid').attr('value',parseInt(msg)+1);

                  

                  $.cookie('id', parseInt(msg)+1);

              });*/
			  
			  $('#name').val('Untitled');

              $.cookie('status', "new");

          },

          deleteSnippet:function(){

              var id = $('#codeid').val();

              $.ajax('save.php',{

                  type: "POST",

                  data: "id="+id+"&status=delete"

              }).done(function(){

                  codebase.resetAll();//Resets everything

                  window.location = '/';

                  alert('Deleted');

                  

              });

              return;

          },

          getId:function(callback){

             $.ajax({

                  type: "POST",

                  url: "getid.php"

                }).done(function( msg ) {

                    callback(msg);

              }); 

          },

          empty:function(code){

              if(code !=='' && code !== null && code !== undefined){

                  return true;

              }

              return false;

          },

          showPreview:function(){

              $('#preview-box').show();

          },

          closePreview:function(){

              $('#preview-box').hide();

          },

          resetAll:function(){

             var cookiesVal=[];

             this.Htmleditor.setValue('');  

             this.CssEditor.setValue('');  

             this.JsEditor.setValue('');

             /*$('#dependencyFiles li').each(function(i, item){

                $(this).remove();

             });*/

             $('#name').val('');

             $('#notes').val('');

             cookiesVal.push('html','css','js','name','id','notes','options');

             $(cookiesVal).each(function(i,item){

                 $.cookie(cookiesVal[i], null);

             });

          },

          autocomplete:function(data){

              var param = data.val() || "";

              var autoComp = $.ajax('autocomplete.php',{

                  data:"q="+ param

              })

              

              autoComp.done(function(msg){

                  var objTitle = $.parseJSON(msg);

                  var source   = $('#codebase-autocomplete').html();

                  var template = Handlebars.compile(source);

                  $('#autoComplete').html('');

                  $('.autocomplete').show();

                  if(objTitle.result){

                      $('.autocomplete').hide();

                      return;

                  };

                  $(objTitle).each(function(index, item){

                         var output = template(item);

                        $('#autoComplete').prepend(output); 

                  })

              });

              

          },

          getKeyCode:function(){

              $(window).on('keyup', function(e){

                  return e.keyCode;

              })

          },

          manageDependancy:function(file){

              //alert(file);

              var container = $('#dependencyFiles');

              var lis = container.find('li');

              var dupe = true;

              var filename = this.getDependancyFilename(file);

              $(lis).each(function(index, item){

                  var liurl = $(item).data('url');

                  if(liurl === file){

                      dupe = false;

                  }

              });

              if(dupe){

                  container.append('<li data-url="'+file+'">'+filename+'<span class="close">x</span></li>');

                  codebase.storeValue('options', file);

              }

          },

          getDependancyFilename:function(file){

              var filearry = file.split('/');

              var fileLen = filearry.length;

              var filename = filearry[fileLen-1];

              return filename;

          },

          auth:function(){

                var userId = $('#txtUsername').val();

                var pass = $('#txtPassword').val();

		var hashPass = hex_md5(pass);

                if (userId !== "" & pass !== "") {

                    

                    var login = $.ajax({

                        type: "POST",

                        dataType: 'json',

                        url: "auth.php",

                        data: "tgt=user&act=login&username=" + userId + "&password=" + hashPass + ""

                    });

                    

                    login.done(function(data){

                        if(data.login === 'SUCCESS'){

                            var userName = data.user.display_name;

                            var userId = data.user.ID;

                            $.cookie('login', true);

                            $('#login').hide();

                        }

                    })



                }

                return false;

          },

          loguser:function(){

              

          }

      }

      

      window.codebase = codebase;

      

      codebase.init();

      

      

    })    

    

}(jQuery))