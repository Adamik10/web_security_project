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
// + checks whether there is any edit profile error messages
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

    var pattern5 = /0a/;
    var pattern6 = /0b/;
    var pattern7 = /1a/;
    var pattern8 = /1b/;
    var pattern9 = /1c/;
    var pattern10 = /2a/;
    var pattern11 = /2b/;
    var pattern12 = /3a/;
    var pattern13 = /3b/;
    var pattern14 = /4a/;
    var pattern15 = /5a/;
    var pattern16 = /5b/;
    var pattern17 = /5c/;
    var pattern18 = /6a/;
    var pattern19 = /5d/;
    var exists5 = pattern5.test(url);
    var exists6 = pattern6.test(url);
    var exists7 = pattern7.test(url);
    var exists8 = pattern8.test(url);
    var exists9 = pattern9.test(url);
    var exists10 = pattern10.test(url);
    var exists11 = pattern11.test(url);
    var exists12 = pattern12.test(url);
    var exists13 = pattern13.test(url);
    var exists14 = pattern14.test(url);
    var exists15 = pattern15.test(url);
    var exists16 = pattern16.test(url);
    var exists17 = pattern17.test(url);
    var exists18 = pattern18.test(url);
    var exists19 = pattern19.test(url);
    if (exists1 || exists2 || exists3 || exists4 || exists5 || exists6 || exists7 || exists8 || exists9 || exists10 || exists11 || exists12 || exists13 || exists14 || exists15 || exists16 || exists17 || exists18 || exists19) {
        // if there is any error messages, add a default start
        $('#divForErrorMessagesProfile').append('<div id="errorMessagesAppendHere"><br><p class="login-error">Some things went wrong:</p></div>');
        if (exists5) {
            $('#errorMessagesAppendHere').append('<p class="profile-error"> - ".exe" files are not allowed. You will look better in .png .jpg .jpeg </p>');
        }
        if (exists6) {
            $('#errorMessagesAppendHere').append('<p class="profile-error"> - You look better in <b>.png .jpg</b> and <b>.jpeg</b> files </p>');
        }
        if (exists7 || exists8) {
            $('#errorMessagesAppendHere').append('<p class="profile-error"> - Not even we at 8gag are pros (yet). We unfortunately could not update your profile image, please try again and cross your fingers </p>');
        }
        if (exists9) {
            $('#errorMessagesAppendHere').append('<p class="profile-error"> - The file you uploaded is way too big, pal. Chill down on the size, will you? - Love, 8gag team and your mum <3 </p>');
        }
        if (exists10) {
            $('#errorMessagesAppendHere').append('<p class="profile-error"> - We are sorry to inform you, but someone already snatched that email address. Try a different one ;) </p>');
        }
        if (exists11) {
            $('#errorMessagesAppendHere').append('<p class="profile-error"> - If you would like to change your email, it better be a valid one </p>');
        }
        if (exists12) {
            $('#errorMessagesAppendHere').append('<p class="profile-error"> - We are sorry to inform you, but someone already snatched that email address. Try a different one ;) </p>');
        }
        if (exists13) {
            $('#errorMessagesAppendHere').append('<p class="profile-error"> - A username should be between 2 and 20 characters long sugarpie <3 </p>');
        }
        if (exists14) {
            $('#errorMessagesAppendHere').append('<p class="profile-error"> - Not even we at 8gag are pros (yet). We unfortunately could not update your username and/or email, please try again. And cross your fingers this time </p>');
        }
        if (exists15) {
            $('#errorMessagesAppendHere').append('<p class="profile-error"> - Password not updated - make sure that the "Password" and "Repeat Password" fields match and try again. We will cross our fingers for you </p>');
        }
        if (exists16) {
            $('#errorMessagesAppendHere').append('<p class="profile-error"> - Password not updated - Your new password needs to be at least <b>7 characters long</b>, contain at least <b>one capital letter</b>, <b>one small case letter</b>, and <b>one number</b>. Try again, cheef. </p>');
        }
        if (exists17) {
            $('#errorMessagesAppendHere').append('<p class="profile-error"> - Not even we at 8gag are pros (yet). We unfortunately could not update your password, please try again with crossed fingers </p>');
        }
        if (exists17) {
            $('#errorMessagesAppendHere').append('<p class="profile-error"> - Please try again </p>');
        }
        if (exists19) {
            $('#errorMessagesAppendHere').append('<p class="profile-error"> - The old password that you entered unfortunately did not match the reality. Go ahead, try again </p>');
        }
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
                var currentPostCommentCount = dataWeGotBack[j].comment_count;
                var upvotesCount = dataWeGotBack[j].upvote_count;
                $('#postsContainer').append('<div class= "card align-self-center card-custom mt-5 mb-2 postHolder" id = "' + currentPostId + '" \> <div class= "card-header"\> <div class="row"\> <div style="background-image: url(' + currentUserImgLocation + ');" class="OP-img mr-3"\></div\> <a href="#"\>' + currentPostUsername + '</a\> </div\> </div\> <h4 class="card-title mt-1"\>' + currentPostHeadline + '</h4\> <a href="gag.php?p_id=' + currentPostId + '"\><img class="card-img-top" src="' + currentPostImageLocation + '" alt="' + currentPostImageName + '"\></a\> <div class="card-body"\> <div class="row"\> <p class="clickable noUpvotes">'+ upvotesCount +' Upvotes</p> <a href="gag.php?p_id=' + currentPostId + '#comment" class="card-link post-link"\>' + currentPostCommentCount + ' Comments</a\> </div\> <div class="row mt-3"\><i class="clickable upvote far fa-hand-point-up fa-2x mr-3" data-id="'+currentPostId+'"></i><a href="gag.php?p_id=' +currentPostId+'#comment"\><i class="far fa-comment fa-2x"\></i\></a\> </div\> </div\> </div\>')
            }  
        })
    }
    setTimeout(contactApiForMorePosts, 10)
})

//UPVOTES

$(document).on('click', '.upvote', function(){ 

    // console.log('upvote clicked');
    var post_id = $(this).data('id');
    // console.log('this post id is - ' + post_id);

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

    // TRYING DATATABLE BLEH
    // $(document).ready(function() {
    //     $('#example').DataTable({
    //         "scrollY":        "500px",
    //         "scrollCollapse": true,
    //         "paging":         false
    //     });
    // } );

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
        // console.log($('.posts-crud-form').serialize())
  
        $(this).parent().parent('tr').removeClass("bg-danger");
        
        if($('title').text() == 'posts crud'){ 
            $.ajax({
                "method":"post",
                "url":"api-posts-crud.php",
                "data": $('.posts-crud-form').serialize() //passing in data from form to ajax
              }).done( function( responseFromServer ){ 
                // This is what we get back from the server
                console.log( responseFromServer );
              })
        }

        if($('title').text() == 'users crud'){ 
            $.ajax({
                "method":"post",
                "url":"users-crud-save.php",
                "data": $('.users-crud-form').serialize() //passing in data from form to ajax
              }).done( function( responseFromServer ){ 
                // This is what we get back from the server
                console.log( responseFromServer );
              })
        }

  
        $(this).parent().siblings().find('input').attr('disabled', 'disabled')
        $(this).parent().siblings().find('input').removeClass('editInputStyle')

  
      }
      $(this).toggleClass('edit save')
      })  


  // POSTS CRUD FOR ADMIN
if($('title').text() == 'comments crud'){

    $(document).on('click', '.btnSaveChangesAdminComments', function(e){ //dynamically built so it must be document on click
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
        console.log($('.comments-crud-form').serialize())
  
        $(this).parent().parent('tr').removeClass("bg-danger");
  
        $.ajax({
          "method":"post",
          "url":"api-comments-crud.php",
          "data": $('.comments-crud-form').serialize() //passing in data from form to ajax
        }).done( function( responseFromServer ){ 
          // This is what we get back from the server
          console.log( responseFromServer );
          
        })
  
        $(this).parent().siblings().find('input').attr('disabled', 'disabled')
        $(this).parent().siblings().find('input').removeClass('editInputStyle')
  
      }
      $(this).toggleClass('edit save')
      })  
  }


