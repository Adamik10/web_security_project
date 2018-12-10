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
    var pattern2 = /status=file_too_large/;
    var pattern3 = /status=error_uploading_image/;
    var pattern4 = /status=wrong_file_format/;
    var exists1 = pattern1.test(url);
    var exists2 = pattern2.test(url);
    var exists3 = pattern3.test(url);
    var exists4 = pattern4.test(url);
    if (exists1 || exists2 || exists3 || exists4){
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


/* WE ARE NOT DOING TOKENS LIKE THIS ANYMORE
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
*/


//load more posts
$('#loadMorePostsButton').click(function(){
    var dataWeGotBack;
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
            // console.log(gotBack);
            // console.log('we got here');
            dataWeGotBack = JSON.parse(gotBack);

            //now we grab the data and display it
            //loop through the result 
            for (var j = 0; j < dataWeGotBack.length; j++) {
                var currentPostId = dataWeGotBack[j].id_posts;
                var currentUserImgLocation = dataWeGotBack[j].user_image_location;
                var currentPostImageLocation = dataWeGotBack[j].image_location;
                var currentPostUsername = dataWeGotBack[j].username;
                var currentPostHeadline = dataWeGotBack[j].headline;
                var currentPostImageName = dataWeGotBack[j].image_name;
                var iCommentCount = dataWeGotBack[j].id_posts;
                $('#postsContainer').append('<div class= "card align-self-center card-custom mt-5 mb-2 postHolder" id = "' + currentPostId +'" \> <div class= "card-header"\> <div class="row"\> <div style="background-image: url('+currentUserImgLocation+');" class="OP-img mr-3"\></div\> <a href="#"\>'+currentPostUsername+'</a\> </div\> </div\> <h4 class="card-title mt-1"\>'+currentPostHeadline+'</h4\> <a href="gag.php?p_id='+currentPostId+'"\><img class="card-img-top" src="'+currentPostImageLocation+'" alt="'+currentPostImageName+'"\></a\> <div class="card-body"\> <div class="row"\> <a href="gag.php?p_id='+currentPostId+'" class="card-link post-link"\># Upvotes</a\> <a href="gag.php?p_id='+currentPostId+'#comment" class="card-link post-link"\>'+iCommentCount+' Comments</a\> </div\> <div class="row mt-3"\> <a href="#"\><i class="far fa-hand-point-up fa-2x mr-3"\></i\></a\> <a href="gag.php?p_id='+currentPostId+'#comment"\><i class="far fa-comment fa-2x"\></i\></a\> </div\> </div\> </div\>')
            }  
        })
    }
    setTimeout(contactApiForMorePosts, 1000)
})

//UPVOTES

$(document).on('click', '.upvote', function(){ 

    console.log('upvote clicked');
    var post_id = $(this).data('id');
    console.log('this post id is - ' + post_id);

    $.ajax({
        method : "post",
        url: "upvote.php",
        data: { p_id: post_id }
    }).done(function (gotBack) {
         console.log(gotBack);
         var numberOfUpvotes = gotBack + ' Upvotes';
         $('.noUpvotes').text(numberOfUpvotes);
    })

});

//when you have upvoted the icon changes color

// $(document).on('click', '.fa-hand-point-up', function(){ 
//     $(".fa-hand-point-up").toggleClass('clickedUpvote');
// })


// POSTS CRUD FOR ADMIN
// if($('title').text() == 'posts crud'){

    $(document).on('click', '.btnSaveChangesAdmin', function(e){ //dynamically built so it must be document on click
      e.preventDefault();
  
      if($(this).hasClass('edit')){
        console.log('clicked on edit');
        $(this).find('.editIcon').css('display', 'none')
        $(this).find('.saveIcon').css('display', 'block')
  
        $(this).parent().parent('tr').addClass("bg-danger");
  
        $(this).parent().siblings().find('input').attr('disabled', false)
        $(this).parent().siblings().find('input').addClass('editInputStyle')
        // $(this).parent().siblings().find('input[name=txtPostIdCrud]').attr('disabled', true)
       
      }
  
      if($(this).hasClass('save')){
        console.log('clicked on save');
        $(this).find('.saveIcon').css('display', 'none')
        $(this).find('.editIcon').css('display', 'block')
        console.log($('.posts-crud-form').serialize())
  
        $(this).parent().parent('tr').removeClass("bg-danger");
  
        $.ajax({
          "method":"post",
          "url":"api-posts-crud.php",
          "data": $('.posts-crud-form').serialize() //passing in data from form to ajax
        }).done( function( responseFromServer ){ 
          // This is what we get back from the server
          console.log( responseFromServer );
          
        })
  
        $(this).parent().siblings().find('input').attr('disabled', 'disabled')
        $(this).parent().siblings().find('input').removeClass('editInputStyle')

  
      }
      $(this).toggleClass('edit save')
      })  
//   }