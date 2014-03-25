<?php 

 /**
 *	ogmaCMS About Admin Page
 *
 *	@package ogmaCMS
 *	@author Mike Swan / n00dles101
 *	@copyright 2013 Mike Swan / n00dles101
 *	@since 1.0.0
 *
 */

 	
	include "template/head.inc.php";

	include "template/navbar.inc.php";



	extract(Query::getSortOptions());

?>
		<div class="col-md-12">
			
			<legend>TEST BED</legend>

			<?php
			?>
<div id="divNoImage"></div><div id="myDropdown"></div>
		</div>
	</div>

	<script type="text/javascript">

	//Dropdown plugin data
	var ddData = [
	    {
	        text: "Facebook",
	        value: 1,
	        selected: false,
	        description: "Description with Facebook",
	        imageSrc: "http://dl.dropbox.com/u/40036711/Images/facebook-icon-32.png"
	    },
	    {
	        text: "Twitter",
	        value: 2,
	        selected: false,
	        description: "Description with Twitter",
	        imageSrc: "http://dl.dropbox.com/u/40036711/Images/twitter-icon-32.png"
	    },
	    {
	        text: "LinkedIn",
	        value: 3,
	        selected: false,
	        description: "Description with LinkedIn",
	        imageSrc: "http://dl.dropbox.com/u/40036711/Images/linkedin-icon-32.png"
	    },
	    {
	        text: "Foursquare",
	        value: 4,
	        selected: false,
	        description: "Description with Foursquare",
	        imageSrc: "http://dl.dropbox.com/u/40036711/Images/foursquare-icon-32.png"
	    }
	];

	var ddBasic = [
	    { text: "Facebook", value: 1, },
	    { text: "Twitter", value: 2, },
	    { text: "LinkedIn", value: 3, },
	    { text: "Foursquare", value: 4, }
	];

	$.getJSON("/admin/ajax.php?q=8",function(result){
	    var ddData = result;
	   $('#myDropdown').ddslick({
	    data:ddData,
	    width:300,
	    height: "300px",
	    selectText: "Select an Image",
	    imagePosition:"left",
	    onSelected: function(selectedData){
	        //callback function: do something with selectedData;
	    }   
	});
	  });

	$('#divNoImage').ddslick({
	    data: ddBasic,
	    selectText: "Select a Tag"
	});

	

	</script>

<?php 

	include "template/footer.inc.php"; 

	
?>
<div id="fullscreenedit">
	<div id="editortoolbar">
	<?php echo Ogmaeditor::displayToolbar('editorarea', true, true); ?>
	<input id="editorslug" type="hidden" value="index" />
	</div>
	<div id="editwrapper">
		<div id="editor">
		  <textarea id="editorarea">[jumbotron fullwidth='true']
#[title]
Congratulations, your new OGMS CMS site is now installed and ready for use.   

[button type='primary' value='Learn More' /]
[/jumbotron]

[button type='warning' value='Download' icon="repeat" /]

		  </textarea>
		</div>

		<div id="preview">
			
		</div>
	</div>
</div>
