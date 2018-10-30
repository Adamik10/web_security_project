
function readURLregister(input) {
    if(input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#uploadImg').css('display', 'block');
            $('#uploadImg')
                .attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

$('#inputFile').change(function() {
    readURLregister(this);
});