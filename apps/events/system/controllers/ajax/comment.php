<?php

	global $user, $db2,$C;

	// Post a new comments
	if($_POST['type'] ==1 )
	{
		$post_type='';
		if( isset($_POST['message']) && !empty($_POST['message'])){
			$user_id = intval($_POST['user_id']);
			$post_message	= isset($_POST['message'])? htmlspecialchars( trim($_POST['message']) ) : '';
			$created_date	= date('y-m-d h-i-s');
			$post_id		= $_POST['post_id'];

			$posts = $db2->query('SELECT posts.id FROM posts LEFT JOIN event_posts ON (event_posts.post_id = posts.id) WHERE event_posts.event_id="'.$post_id.'"'); 

			$obj	 = $db2->fetch_object($posts);
			$db2->query('INSERT INTO posts_comments 
						SET
						api_id="0",
						post_id="'.$obj->id.'",
						user_id="'.$user_id.'",
						message="'.$post_message.'",
						mentioned="0",
						likes="0",
						posttags="0",
						date="'.time().'"'
			); 
			$last_inserted_id = $db2->insert_id();

			$db2->query('UPDATE '.($post_type=='private'?'posts_pr':'posts').' SET comments=comments+1 WHERE id="'.$obj->id.'" LIMIT 1');
			echo "SUCCESS_".$post_id."_".$last_inserted_id;
		}

	}
	// comments delete
	elseif($_POST['type']==2)
	{
		if($_POST['id'] > 0)
		{
			$post_type='';
			$id 		=	(!empty($_POST['id']) ? $_POST['id'] : '');
			$post_id	=	(!empty($_POST['post_id']) ? $_POST['post_id'] : '');
			
				$posts = $db2->query('SELECT posts.id FROM posts LEFT JOIN event_posts ON (event_posts.post_id = posts.id) WHERE event_posts.event_id="'.$post_id.'"'); 
			$obj	 = $db2->fetch_object($posts);
			
			$db2->query('DELETE FROM '.($post_type=='private'?'posts_pr_comments':'posts_comments').' WHERE id="'.$id.'" LIMIT 1', FALSE);
			
			$db2->query('DELETE FROM '.($post_type=='private'?'posts_pr_comments_mentioned':'posts_comments_mentioned').' WHERE comment_id="'.$id.'" ', FALSE);
			
			$db2->query('UPDATE '.($post_type=='private'?'posts_pr':'posts').' SET comments=comments-1 WHERE id="'.$obj->id.'" LIMIT 1',FALSE);

			echo "DELETE_$id"; 
			return;
		}

	}
	// comments ajax retrive
	elseif($_POST['type']==3)
	{ 
		$output ='';
		$post_id = $_POST['post_id'];
		$cmt_id  = $_POST['cmt_id'];

		$sql_comments = "select cmt.id as comment_id,cmt.message as comments,cmt.date as comment_post_date,
								usr.username as username,usr.avatar,cmt.user_id
								from posts_comments as cmt
								LEFT join posts as pst on pst.id=cmt.post_id
								LEFT join event_posts as evtpst on evtpst.post_id=cmt.post_id
								LEFT join users as usr on usr.id=cmt.user_id
								where evtpst.event_id=$post_id and cmt.id=$cmt_id";

		$comments = $db2->query($sql_comments);

		$i = 0;
		$output='';
		if($db2->num_rows($comments) > 0)
		{
			while($cmt_obj = $db2->fetch_object($comments)) 
			{
				$i++;
				$created = date("g:i a", $cmt_obj->comment_post_date);

				if($cmt_obj->avatar){
					$img_url = $C->STORAGE_URL.'avatars/thumbs1/'.$cmt_obj->avatar;
				}else{
					$img_url = $C->STORAGE_URL.'avatars/thumbs3/_noavatar_user.gif';
				}



				$output.='<div class="comment" style="border-bottom:1px dotted #DADADA;" id="cmt'.$cmt_obj->comment_id.'">
							  <a data-userid="1" class="avatar bizcard" href="'.$C->SITE_URL."social/".$cmt_obj->username.'"><img alt="'.$cmt_obj->username.'" src="'.$img_url.'"></a>
							  <div class="comment-container">
							  <div class="comment-options"><a class="delete pull-right" onclick="delete_comments('.$cmt_obj->comment_id.','.$post_id.');" ></a></div>
							  <div class="comment-content"><span class="comment-author"><a data-userid="1" class="author bizcard" href="'.$C->SITE_URL."/social/".$cmt_obj->username.'">'.$cmt_obj->username.'</a></span><span class="">'.$cmt_obj->comments.'</span></div>
							  <div class="attachments lightbox-enabled"></div>
							  <div>
							  <span class="permlink">'.$created.'</span>
							  </div>


							  </div>
							  <div class="clear"></div>
							  </div>';
					




			}
		}
		echo $output;
	}
