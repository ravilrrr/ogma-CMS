jQuery(document).ready(function () 
{

	window.setTimeout(function() {
	    $(".notifications").fadeTo(500, 0).slideUp(500, function(){
	        $(this).remove(); 
	    });
	}, 5000);

	//backups
	$(".deletebackup").on('click', function(e){
		backupfile = ($(this).data('delete'));
		$('#backup-name').val(backupfile);
		$('#confirmDelete').modal('show');
	})

	$(".restorebackup").on('click', function(e){

		$('#confirmRestore').modal('show');
	})

	$('#user-perms').multiselect({
		maxHeight: 250,
      	onChange: function(element, checked) {
      		value  = $('#user-perms').val();
			$('#post-perms').val(value);
      	}
    });
	
	$('#filter').on('change',function(){
		
		var url = document.URL;
		url = url.replace('deleterecord','view');
		var newAdditionalURL = "";
		var tempArray = url.split("?");
		var baseURL = tempArray[0];
		var aditionalURL = tempArray[1]; 
		var temp = "";
		if(aditionalURL)
		{
		var tempArray = aditionalURL.split("&");
		for ( var i in tempArray ){
		    if(tempArray[i].indexOf("filter") == -1){ 
		            newAdditionalURL += temp+tempArray[i];
		                temp = "&";
		            }
		        }
		}
		if ($(this).val()!='') {
			var rows_txt = temp+"filter="+$(this).val();
		} else {
			var rows_txt = temp;
		}
		var finalURL = baseURL+"?"+newAdditionalURL+rows_txt;
		window.location = finalURL;
	})
	Dropzone.autoDiscover = false;
	if ($("#file_upload").length){
		var uploadDropzone = new Dropzone("#file_upload");

		uploadDropzone.on("complete", function(file) {
		  	$( "#filemanager" ).load( "files.php #filemanager" );
		  	uploadDropzone.removeFile(file);
		});
	}

	$('.usertoggle').on('change',function(){
		var arr = $("input[name='userperms']:checked").getCheckboxVal();
		
		if ($(this).is(':checked')){
			$('#post-perms').tagsinput('add', $(this).val());
		} else {
			$('#post-perms').tagsinput('remove', $(this).val());
		};
		
	})

	$(".filter").on("click", function () {
        var $this = $(this);
        // if we click the active tab, do nothing
        if ( !$this.hasClass("active") ) {
            $(".filter").removeClass("active");
            $this.addClass("active"); // set the active tab
            // get the data-rel value from selected tab and set as filter
            var $filter = $this.data("rel"); 
            // if we select view all, return to initial settings and show all
            $filter == 'all' ? 
                $(".fancybox")
                .attr("data-fancybox-group", "gallery")
                .not(":visible")
                .fadeIn() 
            : // otherwise
                $(".fancybox")
                .fadeOut(0)
                .filter(function () {
                    // set data-filter value as the data-rel value of selected tab
                    return $(this).data("filter") == $filter; 
                })
                // set data-fancybox-group and show filtered elements
                .attr("data-fancybox-group", $filter)
                .fadeIn(1000); 
        } // if
    }); // on

	$('.editor-btn').on('click',function(){
		before=$(this).data('insertbefore');
		after=$(this).data('insertafter');
		editor=$(this).parent().data('editor');
		var extracted = $('#'+editor).extractSelectedText();
		$('#'+editor).replaceSelectedText(before+extracted+after);
	})

	$('.markdown-readme').on('click',function(){
		$('#modal-readme-label').html($(this).data('title'));
		$.post(  
            "ajax.php",  
            {"q": 100, "plugin":$(this).data('readme') },  
            function(responseText){  
            	$('#modal-readme-body').html(responseText);
            },  
            "html"  
        ); 
		$('#modal-readme').modal('show');
	})

	$('#mediamanager').on('click',function(){
		$('#modal-readme-label').html('Media Manager');
		$.post(  
            "ajax.php",  
            {"q": 99, "plugin":$(this).data('readme') },  
            function(responseText){  
            	$('#modal-readme-body').html(responseText);
            },  
            "html"  
        ); 
		$('#modal-readme').modal('show');
	})


	$('#previewme').on('click', function(){
		value = $('#editorarea').val();

		$.post(  
            "ajax.php",  
            {"q": 101, "value":value, "page": $('#editorslug').val() },  
            function(responseText){  
            	$('#preview').html(responseText);
            },  
            "html"  
        ); 
	})

	$('#sitemap').on('click',function(){
		 $(this).button('loading');
		 $.post(  
            "ajax.php",  
            {"q": 6 },  
            function(responseText){  
            	if (responseText=="1") {
            		setTimeout(function() {
						$('#sitemap').button('reset');
					}, 2000);

            	} 
            },  
            "html"  
        ); 
	})

	$('#clearcache').on('click',function(){
		 $(this).button('loading');
		 $.post(  
            "ajax.php",  
            {"q": 5 },  
            function(responseText){  
            	if (responseText=="1") {
            		setTimeout(function() {
						$('#clearcache').button('reset');
					}, 2000);

            	}
            },  
            "html"  
        ); 
	})
  	$('button').tooltip({container: 'body'});
  	$('a').tooltip({container: 'body'});

  	$(".icon-picker").iconPicker();

	$('th.sortable').on('click', function(){
		pluginname = GetQueryStringParams('tbl');
		id=$(this).data('id');
		sortdir=$(this).data('sortdir');
		if (typeof pluginname === "undefined"){
			window.location = window.location.pathname+"?action=view&sort="+id+"&dir="+sortdir;
		} else {
			window.location = window.location.pathname+"?tbl="+pluginname+"&action=view&sort="+id+"&dir="+sortdir;
		}
	})
	
	// Show Delete Modal
	$(".delButton").on('click', function(){
		slug=$(this).data('slug');
		table=$(this).data('table');
		href=$(this).data('href');
		nonce=$(this).data('nonce');

		$('#modalSlug').html(slug);
		$('#security-record').val(slug);
		

		$('#modalTable').html(table);
		$('#security-table').val(table);
		
		$('#modalButton').attr('data-url', href);
		$('#security-nonce').val(nonce);

		//jQuery.data('#modalButton','data-url', href);
		$('#deleteModal').modal('show');
	})



    // activate Nestable for list 1
    $('#nestable').nestable({
        group: 1
    })
    .on('change', updateOutput);

    $('#savemenu').on('click',function(){
    	updateOutput($('#nestable'));
    })

	$('.spinedit').spinedit();

	$('.spinajax').on("change", function(e){
		id = $(this).data('id');
		table =  $(this).data('table');	
		field =  $(this).data('field');	
		value = $(this).val();
		element = this;

		$.post(  
            "ajax.php",  
            {"q": 2, "record": id, "table": table, "field": field, "value":value},  
            function(responseText){  
            	if (responseText=="1") {
            		$(element).fadeOut(350).fadeIn(350);
            	} 
            },  
            "html"  
        ); 

	})

	$('.dropdownajax').on("change", function(e){
		id = $(this).data('id');
		table =  $(this).data('table');	
		field =  $(this).data('field');	
		value = $(this).val();
		element = this;

		$.post(  
            "ajax.php",  
            {"q": 3, "record": id, "table": table, "field": field, "value":value},  
            function(responseText){  
            	if (responseText=="1") {
            		$(element).fadeOut(350).fadeIn(350);
            	} 
            },  
            "html"  
        ); 

	})

	$('.yesnoajax').on("change", function(e){
		id = $(this).data('id');
		table =  $(this).data('table');	
		field =  $(this).data('field');	
		value = $(this).val();
		element = this;

		$.post(  
            "ajax.php",  
            {"q": 3, "record": id, "table": table, "field": field, "value":value},  
            function(responseText){  
            	if (responseText=="1") {
            		$(element).fadeOut(350).fadeIn(350);
            	} 
            },  
            "html"  
        ); 

	})
	$("body").on('click','.menuedit', function(e){
		cid = $(this).attr('id');
		if ($(this).hasClass('closed') ) { 
			if ($('.open').length==0){
				$('.menuedit').removeClass('open').addClass('closed');
				$(this).addClass('open').removeClass('closed');
				
				id = $('#itemdata-'+cid).attr('data-id');
				name = $('#itemdata-'+cid).attr('data-name');
				attr = $('#itemdata-'+cid).attr('data-attr');
				
				url = $('#itemdata-'+cid).attr('data-url');
				order = $('#itemdata-'+cid).attr('data-order');
				parent = $('#itemdata-'+cid).attr('data-parent');

				$('#menu-edit').hide();
				$('#menuurl').val(url);
				$('#menutext').val(name);
				$('#menuattr').val(attr);


				$('#menu-edit').appendTo('#control-'+cid);
				$('#menusavesettings').attr('data-item', id);
				$('#menu-edit').show();
			}
		} else {
			$('.menuedit').removeClass('open').addClass('closed');
			$(this).addClass('closed').removeClass('open');
			$('#menu-edit').hide();
		}
	})

	$("#menusavesettings").on('click', function(e){
		id=$('#menusavesettings').attr('data-item');
		url = $('#menuurl').val();
		name = $('#menutext').val();
		attr = $('#menuattr').val();

		$('#itemdata-c'+id).next('.dd3-content').html(name);
		$('#itemdata-c'+id).attr('data-url',url);
		$('#itemdata-c'+id).attr('data-name',name);
		$('#itemdata-c'+id).attr('data-title',name);
		$('#itemdata-c'+id).attr('data-attr',attr);
		$('.menuedit').removeClass('open').addClass('closed');
			$(this).addClass('closed').removeClass('open');
			$('#menu-edit').slideUp();

	})

	$("#cancelsavesettings").on('click', function(e){
		$('.menuedit').removeClass('open').addClass('closed');
			$(this).addClass('closed').removeClass('open');
			$('#menu-edit').slideUp();
		})


	$("#menutypeselect").on('change', function(e){
		showDiv = $("#menutypeselect").val();
		$(".menutype").hide();
		$("#menutype"+showDiv).show();
		updateOutput();
	})


	$("#addtomenu").on('click', function(e){
		id = $(".dd-item").length;
		id++;
		showDiv = $("#menutypeselect").val();
		switch(showDiv){
			case "1":
				slug = $("#menuPage").val();
				title = $("#menulabel").val();
				if (title==''){
					title = $("#menuPage option:selected").text();
				}
				type = $("#menutypeselect option:selected").text();
				break; 
			case "2":
				slug = $("#menuBlog").val();
				title = $("#menulabel").val();
				if (title==''){
					title = $("#menuBlog option:selected").text();
				}
				type = $("#menutypeselect option:selected").text();
				break;
			case "3":
				slug = $("#menuUrl").val();
				title = $("#menulabel").val();
				type = $("#menutypeselect option:selected").text();
				break;
		}
		if (slug!=''){
			html = '<li data-id="'+id+'" class="dd-item">';
			html += '<div class="dd-handle dd3-handle" id="itemdata-c'+id+'" data-name="'+title+'" data-title="'+title+'" data-attr="'+title+'" data-id="'+id+'" data-url="'+slug+'" data-order="" data-parent="">';
		    html += '</div>';
		    html += '<div class="dd3-content">'+title+'</div>';
		    html += '<span class="item-controls">';
		    html += '<span class="item-type">';                  
		    html += '<a href="javascript:null;" id="c'+id+'" class="menuedit closed"><i class="icon-edit"></i></a>';
		    html += '<a href="javascript:null;" id="d'+id+'" class="menudelete askconfirm"><i class="icon-trash"></i></a>';
		    html += '</span>';
		    html += '</span>';
		    html += '<div id="control-c'+id+'"></div></li>';

			if ($(".dd-list").length){
			   	$(".dd-list").last().append(html);
			} else {
				$(".dd-list").last().append(html);
			}
		} 
	})

})


	// Menu Function 
	// 
	
	// Menu Manager Code

    var updateOutput = function(e)
    {
        var list   = e.length ? e : $(e.target),
            output = list.data('output');
        if (window.JSON) {
            $('#nestable-output').val(window.JSON.stringify(list.nestable('serializeXml')));//, null, 2));
			order = list.nestable('serializeXml');
        } else {
            output.val('JSON browser support required for this function.');
        }
    };

    jQuery.fn.getCheckboxVal = function(){
		var vals = [];
		var i = 0;
		this.each(function(){
			vals[i++] = jQuery(this).val();
		});
		return vals;
	}

	jQuery.fn.flash = function( color, duration ){
	    var current = this.css( 'color' );
	    this.animate( { color: 'rgb(' + color + ')' }, duration / 2 );
	    this.animate( { color: current }, duration / 2 );

	}

	function GetQueryStringParams(sParam){
	    var sPageURL = window.location.search.substring(1);
	    var sURLVariables = sPageURL.split('&');
	    for (var i = 0; i < sURLVariables.length; i++)
	    {
	        var sParameterName = sURLVariables[i].split('=');
	        if (sParameterName[0] == sParam)
	        {
	            return sParameterName[1];
	        }
	    }

}
