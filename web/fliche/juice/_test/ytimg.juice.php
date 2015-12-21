<div><strong>Insert YouTube url:</strong></div>
<input type="text" id="ytlink" onkeyup="youtube_parser(this.value)">

<hr>

<div><strong>Output: YouTube video id:</strong></div>
<input type="text" id="ytimagelink" value="">
<div><strong>Output: Thumbnail</strong></div>
<div id="ytimage"></div>

<hr>

<script>
function youtube_parser(url) {
    var regExp = /.*(?:youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=)([^#\&\?]*).*/;
    var match = url.match(regExp);
    if (match && match[1].length == 11) {
        urllink = match[1];
        imagelink = "<img src=\"http:\/\/img.youtube.com\/vi\/"+urllink+"\/hqdefault.jpg\">";
    } else {
        //urllink = "test"
    }
    document.getElementById("ytimagelink").value = urllink;
    document.getElementById("ytimage").innerHTML = imagelink;       
}
</script>