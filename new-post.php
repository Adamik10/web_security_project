<div id='screenBlind'>
</div>

<div id='uploadBox'>
        <div id="posting_header">
            <span id="closePostingPopup"><i class="fas fa-window-close"></i></span>
            <h3>Upload your post</h3>
            <p>Select a file you wish to upload and choose a headline.<br><br></p>
        </div>
        <form action="post-upload.php" method="post" enctype="multipart/form-data">
            <input type="file" name="postFile" onchange="readUrl(this)">
            <div id='preview-image-placeholder'>
                <img id="preview-image" src="">
            </div>
            <textarea type="text" name='postHeader' placeholder="Post headline up to 280 characters." rows="5" cols="40"></textarea>
            <span id="naughtyCheckboxSpan"><input type="checkbox" id="naughtyCheckbox" name="postSensitive">This post has sensitive content.<br></span>
            <?php
                require_once('api-set-token.php');
            ?>
            <button type="submit" class="btn btn-primary littleExtraSpaceTop" id="postSubmitButton" >Post</button>
            <?php
                if(isset($_GET['status'])){
                    if($_GET['status'] == 'post_invalid'){
                        echo '<div class="error_message">Sorry mate, something went wrong with your post. 
                        Make sure to upload a valid picture file and write a headline for your masterpiece.</div>';
                    }else if($_GET['status'] == 'file_too_large'){
                       echo '<div class="error_message">That was a really big file champ. Try cutting down a bit and then we can post it.</div>';
                    }else if($_GET['status'] == 'error_uploading_image'){
                        echo '<div class="error_message">Unfortunately, there were some shinanigans going on uploading your post image. 
                        Please try it again ..and cross your fingers this time.</div>';
                    }else if($_GET['status'] == 'wrong_file_format'){
                        echo '<div class="error_message">Make sure to upload a valid file format: .png .jpg .jpeg or .gif</div>';
                    }
                }
            ?>
        </form>
</div>