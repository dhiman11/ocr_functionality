<!DOCTYPE html>
<html>
<head>
    <title>JSSample</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
</head>

<form method="post" >
<input name="image" type="file">

<input type='submit'>
</form>
<body>

<script type="text/javascript">
    $(function() {
        var params = {
            // Request parameters
            "language": "unk",
            "detectOrientation": "true",
        };
      
        $.ajax({
            url: "https://westus.api.cognitive.microsoft.com/vision/v2.0/ocr?" + $.param(params),
            beforeSend: function(xhrObj){
                // Request headers
                xhrObj.setRequestHeader("Content-Type","application/json");
                xhrObj.setRequestHeader("Ocp-Apim-Subscription-Key","{subscription key}");
            },
            type: "POST",
            // Request body
            data: "{body}",
        })
        .done(function(data) {
            alert("success");
        })
        .fail(function() {
            alert("error");
        });
    });
</script>
</body>
</html>