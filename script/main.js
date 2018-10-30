// ----------------------------------------------------------------- INDEX PAGE
// showing and hiding the post upload section
$('#postNav').click(function() {
    $('#screenBlind').css('visibility','visible');
    $('#uploadBox').css('visibility', 'visible');
})

$('#closePostingPopup').click(function () {
    $('#screenBlind').css('visibility', 'hidden');
    $('#uploadBox').css('visibility', 'hidden');
})

//showing a preview of the image
function readUrl(input) {
    var reader = new FileReader();
    reader.readAsDataURL(input.files[0])
    reader.onload = function (event) {
        $('#preview-image').attr('src', event.target.result)
    }
}