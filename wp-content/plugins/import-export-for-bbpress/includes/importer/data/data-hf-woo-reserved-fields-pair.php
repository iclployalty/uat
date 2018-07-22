<?php

// Reserved column names
return array(
                
                'comment_ID'                    => 'ID  | ID of the comments',
                'comment_post_ID'               => 'Post ID | ID of the product, on which the comment is done',
                'comment_author'                => 'Post Author Name | The author name, who made comments',
                'comment_author_email'          => 'Post Author Email | The author email, who made comments',
                'comment_author_IP'             => 'Post Author IP | The author IP, who made comments',
                'comment_date'                  => 'Post Date | The date, when comments is done',
                'comment_date_gmt'              => 'Post Date(GMT) | The date, when comments is done',
                'comment_content'               => 'Post Content | The content of the comments',
                //'comment_karma'                       => 'comment_karma',
                'comment_approved'              => 'Post Approved or Not? | 1, for YES and 0, for NO',
                'comment_parent'                => 'Post Parent | The parent comments id',
                'user_id'                       => 'User ID | The user id who comments, if the user is GUEST USER then it is 0',
                //Meta

               // 'rating'                        => 'Rating | 1: for 1 star, 2: for 2 star,...',
               // 'verified'                      => 'Verified or Not? | 1: for verified, 0: for non-verified',
		
                'comment_alter_id'              =>  'Post Alteration ID | System generated',

    
);