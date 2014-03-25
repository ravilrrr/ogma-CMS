jQuery(document).ready(function () 
{


var BASE_PATH = '../../../uploads';

/**
 * Add a new message to the messagebox.
 */
function add_msg(msg, type) {
    $('div#msgbox').prepend(
        $('<div />').addClass('alert').addClass(type).append(
            '<a class="close" data-dismiss="alert">&times;</a>',
            msg
        )
    );
}

/**
 * Add message with error context.
 */
function err_msg(msg, context) {
    add_msg('<strong>' + context + ' error:</strong> ' + msg, 'alert-error');
}

/**
 * Add json result.
 */
function add_result(result) {
    // add msg
    add_msg(result.msg, (result.status == 'ok') ? 'alert-success' : 'alert-error');

    // hide progressbar
    $('div#progress').hide();

    // reload fm
    refresh();
}

/**
 * progress bar callback for upload
 */
function progressBar(event) {
    var done = event.position || event.loaded;
    var total = event.totalSize || event.total;
    var per = ( Math.floor(done / total * 1000) / 10 ) + '%';
    $('div#progress > div.bar').css('width', per).text(per);
}


/**
 * Fill input elements with data from DOM nodes and show the 'new-modal'.
 */
function action_show_new_modal(event) {
    event.preventDefault();

    // set type
    $('div#new input#new-type').val($(event.target).data('type'));

    // set path
    $('div#new input#new-target').val($('div#filemanager').data('path'));

    // show modal
    $('div#new').modal('show');
}

/**
 * Fill input elements and show 'move-modal'.
 */
function action_show_move_modal(event) {
    event.preventDefault();

    // get path
    var path = $('div#filemanager').data('path');

    // get file
    var file = $(event.target).parent().attr('href');

    // set source & destination
    $('div#move input#move-source').val(path + file);
    $('div#move input#move-destination').val(path);

    // show modal
    $('div#move').modal('show');
}

/**
 * Submit a request to edit a file. (This is not done via ajax, since we want
 * to leave the file manager and receive an editor.
 */
function action_edit(event) {
    event.preventDefault();

    // get target
    var target = $('div#filemanager').data('path') + $(event.target).parent().attr('href');

    // do post request
    var form = $('<form method="post" action="index.php" />').append(
        $('<input name="fun" />').val('edit'),
        $('<input name="target" />').val(target)
    ).appendTo("body");

    // submit
    form.submit();

    // remove form
    form.remove();
}

/**
 * Fill input elements with data from DOM nodes and show the 'remove-modal'.
 */
function action_show_remove_modal(event) {
    event.preventDefault();

    // get target
    var target = $('div#filemanager').data('path') + $(event.target).parent().attr('href');

    // set type
    $('div#remove input#remove-target').val(target);

    // show modal
    $('div#remove').modal('show');
}

/**
 * Show 'upload-modal'.
 */
function action_show_upload_modal(event) {
    // set fun
    $('div#upload input#upload-fun').val('upload');
    // set path
    $('div#upload input#upload-path').val($('div#filemanager').data('path'));

    // show modal
    $('div#upload').modal('show');
}

// focus field on shown event
$('div#new').on('shown', function(event) {
    $('div#new input#new-target').focus();
});
$('div#move').on('shown', function(event) {
    $('div#move input#move-destination').focus();
});

// register modal buttons
$('div#new a.submit').on('click', function(){
    $('#newfolder').submit();
});


$('div#move a.submit').click(function(event) {
    request(
        {
            'fun'        : 'move',
            'source'     : $('div#move input#move-source').val(),
            'destination': $('div#move input#move-destination').val()
        },
        add_result
    );
});
$('div#remove a.submit').click(function(event) {
    request(
        {
            'fun'   : 'remove',
            'target': $('div#remove input#remove-target').val()
        },
        add_result
    );
});
$('div#upload a.submit').click(function(event) {
    // submit form in background
    request(
        new FormData($('div#upload form')[0]),
        add_result,
        true
    );

    // show progress bar
    $('div#progress div.bar').css('width', 0);
    $('div#progress').show();
});

// clear upload modal on hide
$('div#upload').on('hide', function(event) {
    // clear input
    $('div#upload input').replaceWith($('div#upload input').val('').clone(true));
});

// register tool button events
$('#upload-button').on('click', function(){
    $('div#upload').modal('show');
});

$('#new-folder-button').on('click', function(){
    $('div#newfolder').modal({
        show:true, 
        backdrop:'static'
    });
});

$('#createfolder').on('click', function(){
    $('#createnewfolder').submit();
});



$('.new-media').on('click', function(){
    $('#fileurl').text($(this).data('fileurl'));
    $('#post-fileurl').val($(this).data('fileurl'));
    $('div#addmedia').modal({
        show:true, 
        backdrop:'static'
    });
});

$('.new-media a.submit').on('click', function(){
    $('#submitmedia').submit();
});

$('#clear-msgbox-button').click(function(event) {$('div#msgbox').empty();});

})


