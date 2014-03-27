// jQuery-typing
//
// Version: 0.2.0
// Website: http://narf.pl/jquery-typing/
// License: public domain <http://unlicense.org/>
// Author:  Maciej Konieczny <hello@narf.pl>

(function ($) {

    //--------------------
    //  jQuery extension
    //--------------------

    $.fn.typing = function (options) {
        return this.each(function (i, elem) {
            listenToTyping(elem, options);
        });
    };


    //-------------------
    //  actual function
    //-------------------

    function listenToTyping(elem, options) {
        // override default settings
        var settings = $.extend({
            start: null,
            stop: null,
            delay: 400
        }, options);

        // create other function-scope variables
        var $elem = $(elem),
            typing = false,
            delayedCallback;

        // start typing
        function startTyping(event) {
            if (!typing) {
                // set flag and run callback
                typing = true;
                if (settings.start) {
                    settings.start(event, $elem);
                }
            }
        }

        // stop typing
        function stopTyping(event, delay) {
            if (typing) {
                // discard previous delayed callback and create new one
                clearTimeout(delayedCallback);
                delayedCallback = setTimeout(function () {
                    // set flag and run callback
                    typing = false;
                    if (settings.stop) {
                        settings.stop(event, $elem);
                    }
                }, delay >= 0 ? delay : settings.delay);
            }
        }

        // listen to regular keypresses
        $elem.keypress(startTyping);

        // listen to backspace and delete presses
        $elem.keydown(function (event) {
            if (event.keyCode === 8 || event.keyCode === 46) {
                startTyping(event);
            }
        });

        // listen to keyups
        $elem.keyup(stopTyping);

        $elem.focus(function (event) {
            startTyping(event);
        });

        // listen to blurs
        $elem.blur(function (event) {
            stopTyping(event, 0);
        });
    }
})(jQuery);


jQuery(document).ready(function () 
{

fullscreen = false;

$('#shortcode_value').on("change", function(){
    $('#shortcodeTxt').val($('#shortcode_value').val()); 
})

$('.showshortcodes').on("click", function(){
    if (fullscreen){
        $('#shortcode_area').val('editorarea');     
    } else {
        $('#shortcode_area').val($(this).parent().data('editor'));
    } 
    $('#shortcodeTxt').val("");
    $('#shortcode_value').prop('selectedIndex',0);
    $('#shortcodeModal').modal('show');
}) 

$('#addshortcodebtn').on("click", function(){
    replaceText = $('#shortcodeTxt').val();
    editor=$('#shortcode_area').val();
    var extracted = $('#'+editor).extractSelectedText();
    $('#'+editor).replaceSelectedText(replaceText.replace('content',extracted));
    $('#shortcodeModal').modal('hide');
})  

$('#showfullscreen').on('click',function(){
    editarea = $(this).data('area');
    $('#editorarea').val($('#'+editarea).val());
    $('#editorarea').data('area', editarea);
    $('#fullscreenedit').show();
    $('.showshortcodes').on("click", function(){
        if (fullscreen){
            $('#shortcode_area').val('editorarea');     
        } else {
            $('#shortcode_area').val($(this).parent().data('editor'));
        } 
        $('#shortcodeTxt').val("");
        $('#shortcode_value').prop('selectedIndex',0);
        $('#shortcodeModal').modal('show');
    }) 
    doPreview();
    fullscreen = true;
    $('#editorarea').typing({
      stop: function (event, $elem) {        
        doPreview();
      },
      delay: 1000
  }); 
})

$('#hidefullscreen').on('click',function(){
    editarea = $('#editorarea').data('area');
    $('#'+editarea).val($('#editorarea').val());
    $('#fullscreenedit').hide();
    fullscreen = false; 
})

window.document.onkeydown = function(evt) {
    evt = evt || window.event;
    if (evt.keyCode == 27 && fullscreen==true) {
        editarea = $('#editorarea').data('area');
        $('#'+editarea).val($('#editorarea').val());
        $('#fullscreenedit').hide();
        fullscreen = false; 
    } 
};


function doPreview(){
    value = $('#editorarea').val();
    $.post(  
          "ajax.php",  
          {"q": 101, "value":value, "page": $('#editorslug').val() },  
          function(responseText){  
            $('#preview').html(responseText);
          },  
          "html"  
      );   
}

function doList(){
  if (selected.length == 0) {
              // Give extra word
              chunk = 'list text here'
                
              e.replaceSelection('- '+chunk)

              // Set the cursor
              cursor = selected.start+2
            } else {
              if (selected.text.indexOf('\n') < 0) {
                chunk = selected.text

                e.replaceSelection('- '+chunk)

                // Set the cursor
                cursor = selected.start+2
              } else {
                var list = []

                list = selected.text.split('\n')
                chunk = list[0]

                $.each(list,function(k,v) {
                  list[k] = '- '+v
                })

                e.replaceSelection('\n\n'+list.join('\n'))

                // Set the cursor
                cursor = selected.start+4
              }
            }
  }
  
})

