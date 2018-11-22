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



//checks URL if we had a failed post and shows the post upload section
$(document).ready(function(){
    //check whether we are getting anything in the URL
    var url = window.location.href;
    var pattern1 = /status=post_invalid/;
    var pattern2 = /file_too_large/;
    var pattern3 = /error_uploading_image/;
    var exists1 = pattern1.test(url);
    var exists2 = pattern2.test(url);
    var exists3 = pattern3.test(url);
    if (exists1 || exists2 || exists3){
        //show the post upload section
        $('#screenBlind').css('visibility', 'visible');
        $('#uploadBox').css('visibility', 'visible');
    }
})



//changing session ID every 15 seconds
function callApi(){
    $.ajax({
        "method": "post",
        "url": "api-session-renewal.php",
    }).done(function () {
        // This is what we get back from the server
        // in case u wanna see anything put 'data' in the parenthesis above like this function(data)
        // console.log(data);
    })
};
setInterval(callApi, 15000);



//adding session token on every:
    //click of POST a post button
    //click of COMMENT submit button
    // any more ?? - add if you find anything
function callForToken() {
    $.ajax({
        "method": "post",
        "url": "api-set-token.php"
    }).done(function () {
        // This is what we get back from the server
        // in case u wanna see anything put 'data' in the parenthesis above like this function(data)
        // console.log(data);
        //console.log('New token has been created');
    })
};
$('#postSubmitButton').click(callForToken);
$('#commentSubmitButton').click(callForToken);



//load more posts
$('#loadMorePostsButton').click(function(){
    //get all the post IDs there are loaded on the page
    var currentPostsIds = [];
    $('.postHolder').each(function (i, obj) {
        // obj is the dom object, while $(this) would be the jQuery object
        // console.log($(obj).attr('id'));
        var post_id = $(obj).attr('id');
        currentPostsIds.push(post_id);
    })

    function contactApiForMorePosts(){
        $.ajax({
            method : "post",
            url: "api-get-more-posts.php",
            data: { kvcArray: currentPostsIds }
        }).done(function (gotBack) {
            console.log(JSON.parse(gotBack));
            // console.log(data);
            // console.log('we got here');
        })
    }
    setTimeout(contactApiForMorePosts, 1000)
})