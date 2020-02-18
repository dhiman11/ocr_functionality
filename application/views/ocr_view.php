<?php ?>

<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<style>
.data div {
    display: inline-block;
    vertical-align: top;
    width: 21%;
}
</style>
</head>

<body>
	<form class="ocr_form"> 
		<input onchange="readURL(this);" name ='file' type="file" > 
		<input type="submit" >
	</form> 
	 <div class="data">
		<div class="image">
			<img width= '400' id="blah" src="#" alt="your image" />
		</div>
		<div class="image_data">
			<span>Name</span>
			<br>
			<input type="name">
			<br>
			
			<span>Position</span>
			<br>
			<input type="position">
			<br>
			
			<span>Phone</span>
			<br>
			<textarea class="phones" style="margin: 0px; width: 173px; height: 56px;" type="phones"></textarea>
			<br>
			
			<span>Email</span>
			<br>
			<input type="email">
			<br>	
			
			<span>QQ</span>
			<br>
			<input type="qq">
			<br>
			
			<span>Website</span>
			<br>
			<input type="website">
			<br>
			<span style="margin: 0px; width: 273px; height: 119px;">Remarks</span>
			<br>
			<textarea class="remarks" style="margin: 0px; width: 173px; height: 56px;" type="phones"></textarea>
			<br>
			
			
		</div>
	 </div>
</body>



<script>


$(".ocr_form").submit(function(e) {

	    event.preventDefault();
        var form = $('.ocr_form')[0];
        var data = new FormData(form);
        data.append("CustomField", "This is some extra data, testing");
        
		   $.ajax({
				type: "POST",
				enctype: 'multipart/form-data',
				url: "<?php echo base_url('Ocr/process_ocr_data') ?>",
				data: data,
				processData: false,
				contentType: false,
				cache: false,
				timeout: 600000,
				success: function (data) {
					console.log(data);
					 
					 /////GET NAME 
					 $('input[type="name"]').val(data.name);
					 $('input[type="position"]').val(data.position);
				
					var phones_number = data.phone_number.join(' , '); 
					$('textarea.phones').text(phones_number);
					
					

					
					 $('input[type="qq"]').val(data.qq);
					 $('input[type="email"]').val(data.email);
					 $('input[type="website"]').val(data.website);
					
					var remarks_array = Object.keys(data.remarks).map(function (key) { return data.remarks[key]; });
					
					var remarks_data = remarks_array.join(' '); 
					
					  $('textarea.remarks').text(remarks_data);
					
					
					
				},
			});

});


 
 ///SHow image 
 
 function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah')
                    .attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
	
	

</script>