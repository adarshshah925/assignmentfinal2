function searchFun(){
	let filter=document.getElementById('my_input').value.toUpperCase();

	let myTable=document.getElementById('content-table');
	let tr=myTable.getElementsByTagName('tr');

	for(var i=0;i<tr.length;i++){
		let td=tr[i].getElementsByTagName('td')[2];
		if(td){
			let textvalue=td.textContent|| td.innerHTML;
			if(textvalue.toUpperCase().indexOf(filter) >-1){
				tr[i].style.display="";
			}
			else{
				tr[i].style.display="none";
			}
		}
	}
}
// search by category
function searchFun2(){
	let filter=document.getElementById('my_category').value.toUpperCase();

	let myTable=document.getElementById('content-table');
	let tr=myTable.getElementsByTagName('tr');

	for(var i=0;i<tr.length;i++){
		let td=tr[i].getElementsByTagName('td')[3];
		if(td){
			let textvalue=td.textContent|| td.innerHTML;
			if(textvalue.toUpperCase().indexOf(filter) >-1){
				tr[i].style.display="";
			}
			else{
				tr[i].style.display="none";
			}
		}
	}
}

jQuery(document).ready(function() {
    
    jQuery("#btn-upload").on("click",function(){
    	var image=wp.media({title:"Upload Image for Movie",
         multiple:false}).open().on("select",function(){
         	var upload_image=image.state().get("selection").first();
            var get_image=upload_image.toJSON().url;
            jQuery("#show-image").html("<img src='"+get_image+"' style='height:50px;width:50px;'/>")
            jQuery("#image_name").val(get_image);
         });
    });
    jQuery('#my-movie').DataTable();

    jQuery(document).on("click",".btnmoviedelete",function(){
    	var movie_id=jQuery(this).attr("data-id");
        var postdata="action=mymovielibrary&param=delete_movie&id="+movie_id;
    	jQuery.post(mymovieajaxurl,postdata,function(response){
    	   var data=jQuery.parseJSON(response);
    	    	if(data.status==1){
    	    		jQuery.notifyBar({
    	    			cssClass:"success",
    	    			html:data.message
    	    		});
    	    		
    	    	}else{

    	    	}                       
    	    });
    });
    jQuery("#frmAddMovie").validate({
    	submitHandler:function(){
            var postdata="action=mymovielibrary&param=save_movie&"+jQuery("#frmAddMovie").serialize();
    	    jQuery.post(mymovieajaxurl,postdata,function(response){
    	    	var data=jQuery.parseJSON(response);
    	    	if(data.status==1){
    	    		jQuery.notifyBar({
    	    			cssClass:"success",
    	    			html:data.message
    	    		})
    	    	}else{

    	    	}                       
    	    });
    	}
    });

     jQuery("#frmEditMovie").validate({
    	submitHandler:function(){
              var postdata="action=mymovielibrary&param=edit_movie&"+jQuery("#frmEditMovie").serialize();
    	    jQuery.post(mymovieajaxurl,postdata,function(response){
    	    	var data=jQuery.parseJSON(response);
    	    	if(data.status==1){
    	    		jQuery.notifyBar({
    	    			cssClass:"success",
    	    			html:data.message
    	    		});
    	    		setTimeout(function(){
    	    			location.reload();
    	    		},1300)
    	    	}else{

    	    	}                       
    	    });
    	}
    });
} );