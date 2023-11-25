     <?php   
error_reporting(1); 
class post   
{   
    private $network;   
    private $user;  
    private $cache; 
    private $db1;   
    private $db2;   
    private $available_uploads; 
    public $post_id;    
    public $post_type;  
    public $post_tmp_id;    
    public $post_api_id;    
    public $post_user;  
    public $post_user_details;  
    public $post_to_user;   
    public $post_group; 
    public $post_message;   
    public $post_mentioned; 
    public $post_attached;  
    public $post_posttags;  
    public $post_date;  
    public $video_id;
    public $video_url;
    public $post_comments;  
    public $post_commentsnum;   
    public $permalink;  
    public $is_system_post = FALSE; 
    public $is_feed_post = FALSE;   
    public $error = FALSE;  
    public $tmp;    
    public $newcomments;    
        
    public function __construct($type, $load_id = FALSE, $load_obj = FALSE) 
    {   
            
        global $C;  
        $this->tmp = new stdClass;  
        $this->network =& $GLOBALS['network'];  
        $this->user =& $GLOBALS['user'];    
        $this->page =& $GLOBALS['page'];    
        $this->cache =& $GLOBALS['cache'];  
        $this->db1 =& $GLOBALS['db1'];  
        $this->db2 =& $GLOBALS['db2'];  
        $type            = $type == 'private' ? 'private' : 'public';   
        $this->post_type = $type;   
        if (!$this->network->id) {  
            $this->error = TRUE;    
            return; 
        }   
            
        if ($load_id) { 
            $this->parentid = intval($obj->parentid);   
            $id             = intval($load_id); 
            if($type != 'private'){ 
                $r              = $this->db2->query('SELECT * FROM ' . ($type == 'private' ? 'posts_pr' : 'posts') . ' WHERE id="' . $id . '" LIMIT 1 ', FALSE);    
                if (!$obj = $this->db2->fetch_object($r)) { 
                    $this->error = TRUE;    
                    return; 
                }   
            }   
        } elseif ($load_obj) {  
                
            $obj = $load_obj;   
            $id  = intval($obj->id);    
            if (!$id) { 
                // $this->error = TRUE; 
                // return;  
                //print_r($obj);    
                $this->parentid = intval($obj->parentid);   
            }   
        } else {    
                
            $this->error = TRUE;    
            return; 
        }   
        if ($type == 'private' && !$this->user->is_logged) {    
            $this->error = TRUE;    
            return; 
        }   
        
        $g = FALSE; 
        if ($type == 'public' && $obj->group_id > 0) {  
            $g = $this->network->get_group_by_id($obj->group_id);   
            if ($g) {   
                if (!$g->is_public && !$this->user->is_logged) {    
                    $this->error = TRUE;    
                    return; 
                }   
                if (!$g->is_public && !$this->user->info->is_network_admin) {   
                    $i = $this->network->get_group_invited_members($g->id); 
                    if (!$i || !in_array(intval($this->user->id), $i)) {    
                        $this->error = TRUE;    
                        return; 
                    }   
                }   
            }   
            if (!$g) {  
                $g = $this->network->get_deleted_group_by_id($obj->group_id);   
            }   
            if (!$g) {  
                $this->error = TRUE;    
                return; 
            }   
            $g->group_link = $C->SITE_URL . $g->groupname; //@TODO: edit this   
        }   
        $u1  = FALSE;   
        $ud1 = FALSE;   
        if ($obj->user_id == 0) {   
            $u1                   = (object) array( 
                'id' => 0   
            );  
            $this->is_system_post = TRUE;   
        } else {    
            $u1 = $this->network->get_user_by_id($obj->user_id);    
                
            if (!$u1) { 
                $this->error = TRUE;    
                return; 
            }   
            if ($this->user->id == $obj->user_id) { 
                $ud1 = $this->network->get_user_details_by_id($obj->user_id);   
            }   
        }   
        $u2 = FALSE;    
            
            
        $this->post_id     = intval($obj->id);  
        $this->post_api_id = intval($obj->api_id);  
        $this->post_user =& $u1;    
            
        $this->post_user_details =& $ud1;   
        $this->post_to_user =& $u2; 
        $this->post_group =& $g;    
        $this->post_message      = stripslashes($obj->message); 
        $this->post_date         = intval($obj->date);  
        $this->post_mentioned    = array(); 
        $this->post_attached     = array(); 
        $this->post_posttags     = array(); 
        $this->post_comments     = array(); 
        $this->post_commentsnum  = 0;   
        $this->available_uploads = array(   
            'image',    
            'file', 
            'videoembed',   
            'link'  
        );  
         $this->title      = ($obj->title); 
        if ($obj->mentioned > 0) {  
            if ($C->post_cache_is_activated) {  
                $this->post_mentioned = $this->get_post_mentioned();    
            } else {    
                $post_table = ($this->post_type == 'private' ? 'posts_pr_mentioned' : 'posts_mentioned');   
                $r          = $this->db2->query('SELECT users.id, username, fullname FROM users, ' . $post_table . ' WHERE users.id=' . $post_table . '.user_id AND post_id="' . $this->post_id . '" LIMIT ' . $obj->mentioned, FALSE); 
                while ($o = $this->db2->fetch_object($r)) { 
                    $this->post_mentioned[] = array(    
                        $o->username,   
                        $o->fullname,   
                        $o->id  
                    );  
                }   
            }   
        }   
        if ($obj->attached > 0) { 
           
            if ($C->post_cache_is_activated) {  
                $this->post_attached = $this->get_post_attached();  
            } else {    
                $r = $this->db2->query('SELECT id, video_id,video_url,type,post_id, data FROM ' . ($type == 'private' ? 'posts_pr_attachments' : 'posts_attachments') . ' WHERE post_id="' . $obj->id . '" LIMIT ' . $obj->attached, FALSE);   
                    
                    
                foreach ($this->available_uploads as $file_type) {  
                    $this->post_attached[$file_type] = array(); 
                }   
                    
                while ($o = $this->db2->fetch_object($r)) { 
                    //print_r($o);
                    //print_r($o->data);   
                  $this->video_id=$o->video_id;  
                     $this->video_url=$o->video_url;  
                        
                    $tmp = @unserialize(stripslashes($o->data));    
                    if (!$tmp) {    
                        $tmp = preg_replace_callback('!(?<=^|;)s:(\d+)(?=:"(.*?)";(?:}|a:|s:|b:|d:|i:|o:|N;))!s', 'serialize_fix_callback', stripslashes($o->data));    
                        $tmp = @unserialize(stripslashes($tmp));    
                    }   
                    $o->data = $tmp;    
                      
                        
                    $o->data->attachment_id = $o->id;   
                    $o->data->post_id       = $o->post_id;  
                    $rDS                    = $this->db2->query('SELECT pu.event_status,ep.edit_status,ev.status FROM post_userbox AS pu    
                        inner join event_posts AS ep ON ep.post_id= pu.post_id  
                        inner join events AS ev ON ev.id= ep.event_id   
                            
                            
                        WHERE pu.post_id="' . $o->post_id . '" AND pu.user_id="' . $this->user->id . '"  LIMIT 1'); 
                    $strre_status           = $this->db2->fetch_object($rDS);   
                        
                    $o->data->event_status = $strre_status->event_status;   
                    $o->data->edit_status  = $strre_status->edit_status;    
                    $o->data->status       = $strre_status->status; 
                        
                    $this->post_attached[stripslashes($o->type)][] = $o->data;  
                }   
                    
                foreach ($this->available_uploads as $file_type) {  
                    if (count($this->post_attached[$file_type]) == 0)   
                        unset($this->post_attached[$file_type]);    
                }   
            }   
        }   
        if ($obj->posttags > 0) {   
        $tag=$this->post_message;

            if( preg_match_all('/\#([א-תÀ-ÿ一-龥а-яa-z0-9ա-ֆ\-_]{1,50})/iu', $this->post_message, $matches, PREG_PATTERN_ORDER) ) { 
        //    if (preg_match_all('/\#([\pL0-9]{1,50})/iu', $this->post_message, $matches, PREG_PATTERN_ORDER)) {  
                foreach ($matches[1] as $tg) {  
                   
                    $this->post_posttags[] = trim($tg); 
                }   
                $this->post_posttags = array_unique($this->post_posttags);  
            }   
        }   
            
         $this->newcomments = 0;    
         if( $obj->comments > 0 ) {     
            $limit_number = ($this->page->request[0] == 'view')? '' : ' LIMIT '.$C->POST_LAST_COMMENTS; 
            $r  = $this->db2->query('SELECT *, (SELECT newcomments FROM posts_comments_watch WHERE post_id="'.$obj->id.'" AND user_id="'.$this->user->id.'" LIMIT 1) AS newcomments FROM '.($type=='private'?'posts_pr_comments':'posts_comments').' WHERE post_id="'.$obj->id.'" ORDER BY id DESC '.$limit_number, FALSE); 
            
            while($o = $this->db2->fetch_object($r)) {  
            $tmp    = new postcomment($this, FALSE, $o);    
                if( $tmp->error ) { 
                    continue;   
                }   
                $this->post_comments[]  = $tmp; 
                $this->newcomments = is_null($o->newcomments)? 0 : $o->newcomments; 
            }   
            
            $this->post_comments = array_reverse($this->post_comments, TRUE);   
        $this->post_commentsnum = ($this->page->request[0] == 'view')? count($this->post_comments) : $obj->comments;    
        }   
        $this->post_type   = $type; 
        $this->post_tmp_id = $type . '_' . $this->post_id;  
        $this->permalink   = $C->SITE_URL . 'view/' . ($type == 'private' ? 'priv' : 'post') . ':' . $this->post_id;    
        if ($this->is_system_post) {    
            $this->permalink = $C->SITE_URL;    
            $tmp             = @unserialize($this->post_message);   
            if (!$tmp || !is_object($tmp) || !isset($tmp->lang_key) || !isset($tmp->lang_params)) { 
                $this->error = TRUE;    
                return; 
            }   
            global $page;   
            $page->load_langfile('inside/notifications.php');   
            $this->post_message = $page->lang($tmp->lang_key, $tmp->lang_params);   
            $this->post_message = str_replace('#AND#', $page->lang('ntfcombined_and'), $this->post_message);    
            if (empty($this->post_message)) {   
                $this->error = TRUE;    
                return; 
            }   
            $this->tmp->syspost_about_user = FALSE; 
            if ($tmp->from_user_id) {   
                $this->tmp->syspost_about_user = $this->network->get_user_by_id($tmp->from_user_id);    
            }   
        }   
        $this->post_likes    = ($this->post_type == 'public') ? $this->get_post_likes() : array();  
        $this->post_likesnum = ($this->post_likes && isset($this->post_likes['post'])) ? count($this->post_likes['post']) : 0; 

        return TRUE;    
    }   
        
    public function is_post_faved() 
    {   
        if (isset($this->tmp->is_post_faved)) { 
            return $this->tmp->is_post_faved;   
        }   
        if ($this->error) { 
            $this->tmp->is_post_faved = FALSE;  
            return FALSE;   
        }   
        if ($this->is_system_post) {    
            $this->tmp->is_post_faved = FALSE;  
            return FALSE;   
        }   
        if (!$this->user->is_logged) {  
            $this->tmp->is_post_faved = FALSE;  
            return FALSE;   
        }   
        if (!$favs = $this->get_post_favs()) {  
            $this->tmp->is_post_faved = FALSE;  
            return FALSE;   
        }   
        $this->tmp->is_post_faved = in_array(intval($this->user->id), $favs);   
        return $this->tmp->is_post_faved;   
    }   
        
    public function get_post_favs($force_refresh = FALSE)   
    {   
        if ($this->error) { 
            return FALSE;   
        }   
        if ($this->is_system_post) {    
            return FALSE;   
        }   
        $cachekey = 'n:' . $this->network->id . ',post_favs:' . $this->post_type . ':' . $this->post_id;    
        $data     = $this->cache->get($cachekey);   
        if (FALSE !== $data && TRUE != $force_refresh) {    
            return $data;   
        }   
        $data = array();    
        $r    = $this->db2->query('SELECT user_id FROM post_favs WHERE post_type="' . $this->post_type . '" AND post_id="' . $this->post_id . '" ', FALSE); 
        while ($o = $this->db2->fetch_object($r)) { 
            $data[] = intval($o->user_id);  
        }   
        $this->cache->set($cachekey, $data, $GLOBALS['C']->CACHE_EXPIRE);   
        return $data;   
    }   
        
    public function fave_post($state = TRUE)    
    {   
        if ($this->error) { 
            return FALSE;   
        }   
        if ($this->is_system_post) {    
            return FALSE;   
        }   
        if (!$this->user->is_logged) {  
            return FALSE;   
        }   
        $b = $this->is_post_faved();    
        $u = intval($this->user->id);   
        if ($b && !$state) {    
            $this->db2->query('DELETE FROM post_favs WHERE user_id="' . $u . '" AND post_type="' . $this->post_type . '" AND post_id="' . $this->post_id . '" LIMIT 1', FALSE); 
        } elseif (!$b && $state) {  
            $this->db2->query('INSERT INTO post_favs SET user_id="' . $u . '", post_type="' . $this->post_type . '", post_id="' . $this->post_id . '", date="' . time() . '" ', FALSE); 
        }   
        $this->get_post_favs(TRUE); 
        return TRUE;    
    }
    public function parse_text()
    {
        global $C;
        if ($this->error) {
            return FALSE;
        }
        if ($this->is_system_post) {
            if ($C->API_ID == 1) {
                if (substr($C->DOMAIN, 0, 2) == 'm.') {
                    $s                  = preg_replace('/^m\./i', '', $C->DOMAIN);
                    $this->post_message = str_replace($s, $C->DOMAIN, $this->post_message);
                } elseif (preg_match('/\/m(\/|$)/', $_SERVER['REQUEST_URI'])) {
                    $tmp                = preg_replace('/\/m(\/|$)/', '', $C->SITE_URL);
                    $tmp                = rtrim($tmp, '/') . '/';
                    $this->post_message = str_replace($tmp, $C->SITE_URL, $this->post_message);
                }
            }
            return $this->post_message;
        }
      //  $message  = htmlspecialchars($this->post_message);
        $message = $this->post_message;
        
        if (FALSE !== strpos($message, 'http://') || FALSE !== strpos($message, 'http://') || FALSE !== strpos($message, 'ftp://')) {
            //$message  = preg_replace('#(^|\s)((http|https|ftp)://\w+[^\s\[\]]+)#ie', 'post::_postparse_build_link("\\2", "\\1")', $message);
            $message = preg_replace_callback('#(^|\s)((http|https|ftp)://\w+[^\s\[\]]+)#i', create_function('$matches', 'return post::_postparse_build_link($matches[2], $matches[1]);'), $message);
        }
        
        foreach ($C->POST_ICONS as $k => $v) {
            $txt     = '<img src="' . $C->STATIC_URL . 'images/icons/' . $v . '" class="post_smiley" alt="' . $k . '" title="' . $k . '" />';
            $message = str_replace($k, $txt, $message);
        }
        
        if (count($this->post_mentioned) > 0) {
            $tmp = array();
            foreach ($this->post_mentioned as $i => $v) {
                $tmp[$i] = mb_strlen($v[0]);
            }
            arsort($tmp);
            $tmp2 = array();
            foreach ($tmp as $i => $v) {
                $tmp2[] = $this->post_mentioned[$i];
            }
            foreach ($tmp2 as $u) {
                $txt     = '<a href="' . $C->SITE_URL . $u[0] . '" title="' . htmlspecialchars($u[1]) . '" class="bizcard" data-userid="' . $u[2] . '"><span class="post_mentioned"><b>@</b>' . ($C->NAME_INDENTIFICATOR == 1 ? $u[0] : $u[1]) . '</span></a>';
                $message = preg_replace('/(^|\s)\@' . preg_quote($u[0]) . '/ius', '$1' . $txt, $message);
            }
        }else{
             if (strpos($this->post_message, '@') !== false) {
                $str  = '';
                $text = explode(" ", $this->post_message);
                foreach ($text as $keys => $vals) {
                    if (strpos($vals, '@') !== false) {
                        $expl = explode("@", $vals);
                        $expl = array_filter($expl);
                        foreach ($expl as $arrkeys => $arrvals) {
                              $arrvals = str_replace('"', "", $arrvals);
                            $arrvals = str_replace("'", "", $arrvals);
                            
                            $r      = $this->db2->query('select id  FROM  users  WHERE username="' . $arrvals . '"  LIMIT 1', FALSE);
                            $result = $this->db2->fetch_object($r);
                            if ($result->id != '') {
                                $str .= ' <a href="' . $C->SITE_URL . '/' . $arrvals . '" title="' . $arrvals . '" class="bizcard" data-userid="' . $result->id . '"><span class="post_mentioned"><b>@</b>' . $arrvals . '</span></a> ';
                            } else {
                                $str .= '';
                            }
                            
                            // $str .=' <a href="http://localhost/streetbuzz/sdftt" title="kkll" class="bizcard" data-userid="55"><span class="post_mentioned"><b>@</b>sdftt</span></a>';
                            
                            
                        }
                        
                    } else {
                        $str .= $vals . ' ';
                    }
                    
                    
                }
                $message = $str;
                
            }
            
        }
        if (count($this->post_posttags) > 0) {
            $tmp = array();
            foreach ($this->post_posttags as $i => $v) {
                $tmp[$i] = mb_strlen($v);
            }
            arsort($tmp);
            $tmp2 = array();
            foreach ($tmp as $i => $v) {
                $tmp2[] = $this->post_posttags[$i];
            }
            foreach ($tmp2 as $tag) {

                $txt     = '<a href="' . $C->SITE_URL . 'search/tab:tags/s:' . $tag . '" title="' . $tag . '"><span class="post_tag1"><b>#</b>' . $tag . '</span></a>';
                $message = preg_replace('/(^|\s)\#' . preg_quote($tag) . '/ius', '$1' . $txt, $message);
            }
        }
        
        return $message;
    }   
        
   
    public static function parse_date($timestamp, $return_words = 'auto', $return_dt_format = '%b %d %Y, %H:%M')    
    {   
        if ($return_words == FALSE) {   
            return strftime($return_dt_format, $timestamp); 
        }   
        $time = time() - $timestamp;    
        $h    = floor($time / 3600);    
        $time -= $h * 3600; 
        $m = floor($time / 60); 
        $time -= $m * 60;   
        $s = $time; 
        if ($return_words === 'auto' && $h >= 12) { 
            return strftime($return_dt_format, $timestamp); 
        }   
        $txt = '##BEFORE## ';   
        if ($h > 0) {   
            $txt .= $h; 
            $txt .= $h == 1 ? ' ##HOUR##' : ' ##HOURS##';   
        }   
        if ($h >= 3) {  
            $txt .= ' ##AGO##'; 
            return post::_parse_date_replace_strings($txt); 
        }   
        if ($m > 0) {   
            if ($h > 0) {   
                $txt .= ' ##AND## ';    
            }   
            $txt .= $m; 
            $txt .= $m == 1 ? ' ##MIN##' : ' ##MINS##'; 
            if ($h > 0) {   
                $txt .= ' ##AGO##'; 
                return post::_parse_date_replace_strings($txt); 
            }   
        }   
        if ($h == 0 && $m == 0) {   
            if ($s == 0) {  
                return post::_parse_date_replace_strings('##NOW##');    
            }   
            $txt .= $s; 
            $txt .= $s == 1 ? ' ##SEC##' : ' ##SECS##'; 
        }   
        $txt .= ' ##AGO##'; 
        return post::_parse_date_replace_strings($txt); 
    }   
        
    public static function _parse_date_replace_strings($txt = '')   
    {   
        global $page;   
        $tmp = array(   
            '##BEFORE##' => $page->lang('posttime_before'), 
            '##HOUR##' => $page->lang('posttime_hour'), 
            '##HOURS##' => $page->lang('posttime_hours'),   
            '##MIN##' => $page->lang('posttime_min'),   
            '##MINS##' => $page->lang('posttime_mins'), 
            '##SEC##' => $page->lang('posttime_sec'),   
            '##SECS##' => $page->lang('posttime_secs'), 
            '##AND##' => $page->lang('posttime_and'),   
            '##AGO##' => $page->lang('posttime_ago'),   
            '##NOW##' => $page->lang('posttime_now')    
        );  
        $txt = str_replace(array_keys($tmp), array_values($tmp), $txt); 
        $txt = trim($txt);  
        $txt = str_replace(' ', '&nbsp;', $txt);    
        return $txt;    
    }   
        
    public function parse_group($cutstr = 20)   
    {   
        if ($this->error) { 
            return FALSE;   
        }   
        if (!$this->post_group) {   
            return '';  
        }   
        if ($this->post_group->is_deleted) {    
            return $GLOBALS['page']->lang('postgroup_in') . '&nbsp;<a title="' . $GLOBALS['page']->lang('postgroup_del') . ' ' . $this->post_group->title . '">' . str_cut($this->post_group->title, intval($cutstr)) . '</a>'; 
        }   
        return $GLOBALS['page']->lang('postgroup_in') . '&nbsp;<a href="' . $GLOBALS['C']->SITE_URL . $this->post_group->groupname . '" title="' . $this->post_group->title . '">' . str_cut($this->post_group->title, intval($cutstr)) . '</a>';   
    }   
        
    public static function parse_api($api_id = 0)   
    {   
        if ($api_id == 0) { 
            return '';  
        }   
        if (!$api = $GLOBALS['network']->get_posts_api($api_id)) {  
            return '';  
        }   
        return $GLOBALS['page']->lang('postapi_via') . '&nbsp;' . $api->name;   
    }   
        
    public function if_can_delete() 
    {   
            
        global $C;  
        if ($this->error) { 
            return FALSE;   
        }   
        if (!$this->user->is_logged) {  
            return FALSE;   
        }   
        if ($this->post_type == 'private' && $this->post_to_user->id == $this->user->id) {  
            return TRUE;    
        }   
        if ($this->is_system_post && !$this->post_group) {  
            return TRUE;    
        }   
        if ($this->user->id == $this->post_user->id) {  
            return TRUE;    
        }   
        if ($this->user->info->is_network_admin == 1) { 
            return TRUE;    
        }   
        if ($this->post_type == 'public' && $this->post_group) {    
            $currentpage = $GLOBALS['page']->request[0];    
            if ($currentpage == 'group' || $currentpage == 'services') {    
                $g_ids = array();   
                $g_ids = $this->network->user_admin_group_ids($this->user->id); 
                if (isset($g_ids[$this->post_group->id])) { 
                    return TRUE;    
                }   
            }   
        }   
        return FALSE;   
    }   
    public function if_can_delete_notification()    
    {   
        if ($this->user->id == $this->post_user->id) {  
            return TRUE;    
        }   
        if ($this->user->info->is_network_admin == 1) { 
            return TRUE;    
        }   
    }   
    public function is_spam($postid, $posttype) 
    {   
        $r = $this->db2->fetch_field('SELECT 1 FROM posts_spamprotector WHERE marked_by_user_id="' . $this->user->id . '" AND post_id ="' . $postid . '" AND post_type="' . $posttype . '" LIMIT 1');   
            
        return $r;  
            
            
    }   
        
    public function delete_this_post($check_plugins = TRUE) 
    {   
        global $C, $plugins_manager;    
            
        if ($check_plugins) {   
            $plugins_manager->onPostDelete($this);  
            if (!$plugins_manager->isValidEventCall()) {    
                return FALSE;   
            }   
        }   
            
        if (!$this->if_can_delete()) {  
            return FALSE;   
        }   
        if ($this->is_system_post) {    
                
            if ($this->post_type == 'private' && $this->post_to_user->id == $this->user->id) {  
                $this->db2->query('DELETE FROM posts_pr WHERE id="' . $this->post_id . '" LIMIT 1', FALSE); 
                $this->error = TRUE;    
                return TRUE;    
            }   
            if ($this->post_type == 'public' && $this->post_group) {    
                $this->db2->query('DELETE FROM post_userbox WHERE post_id="' . $this->post_id . '" ', FALSE);   
                $this->db2->query('DELETE FROM posts WHERE id="' . $this->post_id . '" LIMIT 1', FALSE);    
                $this->error = TRUE;    
                return TRUE;    
            } else if ($this->post_type == 'public' && !$this->post_group) {    
                    
                $this->db2->query('DELETE FROM post_userbox WHERE user_id="' . $this->user->id . '" AND post_id="' . $this->post_id . '" LIMIT 1', FALSE);  
                $this->error = TRUE;    
                return TRUE;    
            }   
                
            if ($this->user->is_network_admin) {    
                if ($this->post_type == 'public') { 
                    $this->db2->query('DELETE FROM post_userbox WHERE post_id="' . $this->post_id . '" ', FALSE);   
                }   
                $this->db2->query('DELETE FROM ' . ($this->post_type == 'private' ? 'posts_pr' : 'posts') . ' WHERE id="' . $this->post_id . '" LIMIT 1', FALSE);   
                $this->error = TRUE;    
                return TRUE;    
            }   
        }   
        if ($this->post_type == 'private' && $this->post_to_user->id == $this->user->id) {  
            $this->fave_post(FALSE);    
            $this->db2->query('UPDATE posts_pr SET is_recp_del=1 WHERE id="' . $this->post_id . '" LIMIT 1');   
            $this->error = TRUE;    
            return TRUE;    
        }   
        if ($this->post_commentsnum > 0) {  
            $this->post_comments = array(); 
            $r                   = $this->db2->query('SELECT * FROM ' . ($this->post_type == 'private' ? 'posts_pr_comments' : 'posts_comments') . ' WHERE post_id="' . $this->post_id . '" ', FALSE);  
            while ($o = $this->db2->fetch_object($r)) { 
                $tmp = new postcomment($this, FALSE, $o);   
                if ($tmp->error) {  
                    continue;   
                }   
                $this->post_comments[] = $tmp;  
            }   
            foreach ($this->post_comments as $c) {  
                $c->delete_this_comment(FALSE); 
            }   
        }   
        if ($this->post_type == 'public') { 
            $result = $this->db2->query('select * from posts where parent_id="' . $this->post_id . '"');    
            $data   = array();  
            while ($row = $this->db2->fetch_object($result)) {  
                $data[] = $row->id; 
            }   
                
            if (count($data) > 0) { 
                $this->db2->query('insert into sb_chield (parent_id) values ("' . $this->post_id . '")');   
            }   
                
            $this->db2->query('DELETE FROM posts WHERE id="' . $this->post_id . '" ', FALSE);   
        }   
        // $this->db2->query('DELETE FROM post_favs WHERE post_type="'.$this->post_type.'" AND post_id="'.$this->post_id.'" ', FALSE);  
        // $this->db2->query('DELETE FROM '.($this->post_type=='private'?'posts_pr_mentioned':'posts_mentioned').' WHERE post_id="'.$this->post_id.'" ', FALSE);    
        // $this->db2->query('DELETE FROM '.($this->post_type=='private'?'posts_pr':'posts').' WHERE id="'.$this->post_id.'" LIMIT 1', FALSE);  
        // $this->db2->query('DELETE FROM '.($this->post_type=='private'?'posts_pr_comments_watch':'posts_comments_watch').' WHERE post_id="'.$this->post_id.'" ', FALSE);  
        // $this->db2->query('DELETE FROM '.($this->post_type=='private'?'posts_pr_attachments':'posts_attachments').' WHERE post_id="'.$this->post_id.'" ', FALSE);    
        // $this->db2->query('DELETE FROM post_tags WHERE post_id="'.$this->post_id.'" ', FALSE);   
        // $this->db2->query('DELETE FROM post_likes WHERE post_id="'.$this->post_id.'" AND user_id="'.$this->user->id.'"');    
            
        $at_dir = $C->STORAGE_DIR . 'attachments/' . $this->network->id . '/';  
        foreach ($this->post_attached as $tp => $at) {  
            foreach ($at as $k => $v) { 
                if (!isset($v->file_original) && !isset($v->file_thumbnail)) {  
                    continue;   
                }   
                if (isset($v->file_original)) { 
                    rm($at_dir . $v->file_original);    
                }   
                if (isset($v->file_preview)) {  
                    rm($at_dir . $v->file_preview); 
                }   
                if (isset($v->file_thumbnail)) {    
                    rm($at_dir . $v->file_thumbnail);   
                }   
            }   
        }   
        if ($this->post_type == 'public') { 
            $this->db2->query('UPDATE users SET num_posts=num_posts-1 WHERE id="' . $this->post_user->id . '" LIMIT 1');    
            if ($this->post_group) {    
                $this->db2->query('UPDATE groups SET num_posts=num_posts-1 WHERE id="' . $this->post_group->id . '" LIMIT 1');  
            }   
        }   
            
        $this->get_post_likes(TRUE);    
            
        $this->error = TRUE;    
        return TRUE;    
    }   
        
    public static function _postparse_build_link($url, $before = '')    
    {   
        $after = '';    
        if (preg_match('/(javascript|vbscript)/', $url)) {  
            return $before . $url . $after; 
        }   
        if (preg_match('/([\.,\?]|&#33;)$/', $url, $matches)) { 
            $after .= $matches[1];  
            $url = preg_replace('/([\.,\?]|&#33;)$/', '', $url);    
        }   
        $txt = $url;    
        if (strlen($txt) > 60) {    
            $txt = substr($txt, 0, 45) . '...' . substr($txt, -10); 
        }   
        return $before . '<a href="' . $url . '" title="' . $url . '" target="_blank" rel="nofollow">' . $txt . '</a>' . $after;    
    }   
        
    public function get_all_comments()  
    {   
        if ($this->page->request[0] == 'view') {    
            return $this->post_comments;    
        }   
            
        $comments = array();    
        $r        = $this->db2->query('SELECT * FROM ' . ($this->post_type == 'private' ? 'posts_pr_comments' : 'posts_comments') . ' WHERE post_id="' . $this->post_id . '" ORDER BY id ASC ', FALSE); 
            
        while ($o = $this->db2->fetch_object($r)) { 
            $tmp = new postcomment($this, FALSE, $o);   
            if ($tmp->error) {  
                continue;   
            }   
            $comments[] = $tmp; 
        }   
            
        return $comments;   
    }   
        
    public function get_comments()  
    {   
        return $this->post_comments;    
    }   
        
    public function get_attachments_data($at_type = FALSE)  
    {   
        if ($at_type) { 
            return isset($this->post_attached[$at_type]) ? count($this->post_attached[$at_type]) : 0;   
        }   
        $counter = 0;   
        foreach ($this->post_attached as $file_type) {  
            $counter += count($file_type);  
        }   
    }   
    private function get_post_mentioned($force_refresh = FALSE) 
    {   
        if ($this->error) { 
            return array(); 
        }   
        if ($this->is_system_post) {    
            return array(); 
        }   
        $cachekey = 'n:' . $this->network->id . ',post_mentioned:' . $this->post_type . ':' . $this->post_id;   
        $data     = $this->cache->get($cachekey);   
        if (FALSE !== $data && TRUE != $force_refresh) {    
            return $data;   
        }   
        $data = array();    
        $r    = $this->db2->query('SELECT username, fullname FROM users, ' . ($this->post_type == 'private' ? 'posts_pr_mentioned' : 'posts_mentioned') . ' WHERE post_id="' . $this->post_id . '" AND posts_mentioned.user_id=users.id', FALSE);   
        while ($o = $this->db2->fetch_object($r)) { 
            $data[] = array(    
                $o->username,   
                $o->fullname    
            );  
        }   
            
        $this->cache->set($cachekey, $data, $GLOBALS['C']->CACHE_EXPIRE);   
        return $data;   
    }   
        
    private function get_post_attached($force_refresh = FALSE)  
    {   
        if ($this->error) { 
            return array(); 
        }   
        if ($this->is_system_post) {    
            return array(); 
        }   
        $cachekey = 'n:' . $this->network->id . ',post_attached:' . $this->post_type . ':' . $this->post_id;    
        $data     = $this->cache->get($cachekey);   
        if (FALSE !== $data && TRUE != $force_refresh) {    
            return $data;   
        }   
        $data = array();    
        $r    = $this->db2->query('SELECT id,video_id,video_url,type, data FROM ' . ($this->post_type == 'private' ? 'posts_pr_attachments' : 'posts_attachments') . ' WHERE post_id="' . $this->post_id . '"', FALSE);   
            
        foreach ($this->available_uploads as $file_type) {  
            $data[$file_type] = array();    
        }   
            
            
        while ($o = $this->db2->fetch_object($r)) { 
          
            $this->video_id=$o->video_id;  
                     $this->video_url=$o->video_url;
            
            $o->data                        = unserialize(stripslashes($o->data));  
            $o->data->attachment_id         = $o->id;   
            $data[stripslashes($o->type)][] = $o->data; 
        }   
            
        foreach ($this->available_uploads as $file_type) {  
            if (count($data[$file_type]) == 0)  
                unset($data[$file_type]);   
        }   
            
        $this->cache->set($cachekey, $data, $GLOBALS['C']->CACHE_EXPIRE);   
        return $data;   
    }   
    //for poll  
    public function is_poll()   
    {   
        $data = array();    
        $r    = $this->db2->query('SELECT posts.*,pol.*,pola.* FROM polls as pol inner join polls_answers as pola on pol.poll_id=pola.poll_id inner join posts on posts.id=pol.posts_id  WHERE posts_id="' . $this->post_id . '"', FALSE);  
      
    
        while ($result = $this->db2->fetch_object($r)) {    
            $data[] = $result;  
        }   
        
        return $data;   
    }     
    public function is_video()   
    {   //se
    // die('------========gthgt');
        $data = array();    
        $r    = $this->db2->query('SELECT * FROM posts WHERE id="' . $this->post_id . '" AND posttype=3', FALSE);  
     
    // echo 'SELECT * FROM posts WHERE id="' . $this->post_id . '" AND posttype=3';
    
        while ($result = $this->db2->fetch_object($r)) {  
            // print_r($result);
            // echo "====";
         //   $data[] = $result;  
                 return $result;   

        }   
        
    }   
    //end poll  
    //checking poll answer for user 
    public function is_pollanswer($userid, $pollid) 
    {   
       
        $data = array();    
        $r    = $this->db2->query('SELECT * FROM post_poll_votes WHERE POLL_ID="' . $pollid . '" and VOTER_USER_ID="' . $userid . '"', FALSE);  
        while ($result = $this->db2->fetch_object($r)) {    
            $data[] = $result;  
        }   
        return $data;   
            
    }   
    //end checking poll answer for user 
    //checking per answer count 
    public function is_countpollanswer($pollid, $pollanswerid)  
    {   
        $data = array();    
        $r    = $this->db2->query('SELECT * FROM post_poll_votes WHERE POLL_ID="' . $pollid . '" and ANSWER_ID="' . $pollanswerid . '"', FALSE);    
        while ($result = $this->db2->fetch_object($r)) {    
            $data[] = $result;  
        }   
        return $data;   
    }   
    //end checkingperanswer count   
    //checking chield   
    public function is_chield() 
    {   
        $data = array();    
        $r    = $this->db2->query('SELECT p.id,p.group_id,p.message,p.date,p.group_name,users.id as userid,users.avatar as pic, users.username as username FROM posts as p  
                                        inner join post_replay as pr ON p.id = pr.replay_id         
                                        inner join users on p.user_id=users.id WHERE pr.parent_id="' . $this->post_id . '" order by p.date ASC', FALSE);    
        while ($result = $this->db2->fetch_object($r)) {    
            $data[] = $result;  
        }   
            
            
        return $data;   
    }   
    public function is_chield_chield($id)   
    {   
            
            
        $data = array();    
        $r    = $this->db2->query('SELECT posts.*,users.id as userid,users.avatar as pic, users.username as username FROM posts inner join users on posts.user_id=users.id WHERE parent_id="' . $id . '" order by date desc', FALSE);   
        while ($result = $this->db2->fetch_object($r)) {    
            $data[] = $result;  
        }   
            
        return $data;   
    }   
    public function refresh_post_cache()    
    {   
        $this->get_post_mentions(true); 
        $this->get_post_attached(true); 
    }   
    public function is_post_reshared($postid)   
    {   
        $res    = $this->db2->query('select *  FROM post_reshares WHERE user_id="' . $this->user->id . '" AND post_id="' . $postid . '" LIMIT 1', FALSE);   
        $result = $this->db2->fetch_object($res);   
        return $result; 
            
            
    }   
    public function loaded_posts_reshares($postid)  
    {   
        $res = $this->db2->query('select id  FROM post_reshares WHERE  post_id="' . $postid . '" ', FALSE); 
        while ($result = $this->db2->fetch_object($res)) {  
            $data[] = $result;  
        }   
            
        return $data;   
            
            
    }   
     public function could_be_liked()   
    {   
        if ($this->error) { 
            return FALSE;   
        }   
        if ($this->is_system_post) {    
            return FALSE;   
        }   
        if (!$this->user->is_logged) {  
            return FALSE;   
        }   
        if ($this->post_type == 'private') {    
            return FALSE;   
        }   
        if (!isset($this->post_user) || !$this->post_user) {    
            return FALSE;   
        }   
        if ($this->user->id == $this->post_user->id) {  
            //return FALSE; //removed for FB style, you can like your own post  
        }   
        if ($this->is_post_liked()) {   
            return FALSE;   
        }   
            
            
            
        return TRUE;    
    }   
    
    public function get_post_share($share_id) 
    {
   
$id=$share_id;

$query = $this->db2->query('SELECT * FROM `posts_details` WHERE post_id ='.$id);
$res=mysqli_fetch_array($query);
if(empty($res)){
    $this->db2->query('INSERT INTO `posts_details`(`post_id`, `shares`) VALUES ("'.$id.'",1)');
      $count=1;
     return "<span>'.$count.'</span>";
}else{
    $count = $res['shares'];
    $count = $count+1;
     $this->db2->query('UPDATE `posts_details` SET `shares`="'.$count.'" WHERE post_id ='.$id);
     return "<span>'.$count.'</span>";
}  
    }
    /*post profile*/
        public function get_post_profile($post_profile_who,$post_profile_whom) 
    {
  $id2=$post_profile_whom;
  $id1=$post_profile_who;

$this->db2->query('INSERT INTO `profile_share`(`who`, `whom`) VALUES ("'.$id1.'","'.$id2.'")');
$res=mysqli_fetch_array($query);

$query = $this->db2->query('SELECT * FROM `profile_share` WHERE  whom ='.$id2);
$ress=mysqli_fetch_array($query);
$num_rows = mysqli_num_rows($query);

    $count = $num_rows;
    return "<span>'.$count.'</span>";
    }
    
    /**/
    public function is_post_liked() 
    {   
        if (!isset($this->post_likes['post'][$this->user->id])) {   
            return FALSE;   
        }   
            
        return TRUE;    
    }   
        
    public function get_post_likes($force_refresh = FALSE)  
    {   
        if ($this->error) { 
            return FALSE;   
        }   
        if ($this->is_system_post) {    
            return FALSE;   
        }   
            
        $cachekey = 'n:' . $this->network->id . ',post_likes:' . $this->post_type . ':' . $this->post_id;   
        $data     = $this->cache->get($cachekey);   
        if (FALSE !== $data && TRUE != $force_refresh) {    
            return $data;   
        }   
        $data = array(  
            'post' => array()   
        );  
        $r    = $this->db2->query('SELECT u.id, u.avatar, u.username, pl.post_id, pl.comment_id FROM users u, post_likes pl WHERE pl.user_id=u.id AND post_id="' . $this->post_id . '" ', FALSE);   
        while ($o = $this->db2->fetch_object($r)) { 
            if ($o->comment_id == 0) {  
                $data['post'][$o->id] = array(  
                    $o->username,   
                    (!empty($o->avatar) ? $o->avatar : $GLOBALS['C']->DEF_AVATAR_USER)  
                );  
            } elseif ($o->comment_id > 0) { 
                $data['comment_' . $o->comment_id][$o->id] = array( 
                    $o->username,   
                    (!empty($o->avatar) ? $o->avatar : $GLOBALS['C']->DEF_AVATAR_USER)  
                );  
            }   
                
        }   
        $this->cache->set($cachekey, $data, $GLOBALS['C']->CACHE_EXPIRE);   
        return $data;   
    }   
        
    public function like_post($state = TRUE)    
    {   
        if ($this->error) { 
            return FALSE;   
        }   
        if ($this->is_system_post) {    
            return FALSE;   
        }   
        if (!$this->user->is_logged) {  
            return FALSE;   
        }   
        $b = $this->is_post_liked();    
        $u = intval($this->user->id);   
        if ($b && !$state) {    
            $this->db2->query('DELETE FROM post_likes WHERE user_id="' . $u . '" AND post_id="' . $this->post_id . '" AND comment_id=0 LIMIT 1', FALSE);    
            //$this->db2->query('UPDATE posts SET likes=likes-1 WHERE id="'.$this->post->post_id.'" LIMIT 1', FALSE);   
            $this->update_like_count($this->post_id, -1);
        } elseif (!$b && $state) {  
            $this->db2->query('INSERT INTO post_likes SET user_id="' . $u . '", post_id="' . $this->post_id . '", date="' . time() . '" ', FALSE);  
            //$this->db2->query('UPDATE posts SET likes=likes+1 WHERE id="'.$this->post->post_id.'" LIMIT 1', FALSE);   
            $this->update_like_count($this->post_id);
        }   
            
        $this->post_likes = array();    
        $this->post_likes = $this->get_post_likes(TRUE);    
        return TRUE;    
    }  
    
    public function update_like_count($post_id, $count = 1) { 

        $query = $this->db2->query('SELECT posts_detail_id,likes FROM  posts_details WHERE post_id="' . $post_id . '" limit 1', FALSE);    
           
        if($query->num_rows > 0){
              $obj = $this->db2->fetch_object($query);
            $likes = $obj->likes;
            if($likes <= 0 && $count < 0) {
                $count = 0;
            }
            $this->db2->query('UPDATE posts_details SET likes=likes+('.$count.') WHERE post_id="'.$post_id.'"', FALSE); 
        } else if($count == 1) {
            $this->db2->query('INSERT INTO posts_details SET likes=1, post_id="' . $post_id . '"', FALSE);  
        }
        
    }
        
    public function if_can_edit()   
    {   
        global $C;  
            
        if ($this->error) { 
            return FALSE;   
        }   
        if (!$this->user->is_logged) {  
            return FALSE;   
        }   
        if ($this->user->id == $this->post_user->id) {  
            return TRUE;    
        }   
            
        return FALSE;   
    }   
        
    public function edit($set_message = '') 
    {   
        if (empty($set_message)) {  
            return false;   
        }   
            
        $this->db2->query('UPDATE ' . ($this->post_type == 'private' ? 'posts_pr' : 'posts') . ' SET message="' . $this->db2->e($set_message) . '" WHERE id="' . $this->post_id . '"', FALSE);  
            
        return true;    
    }   
    public function assetdata($postid)  
    {   
          $aquerysset = $this->db2->query('SELECT pd.id,pd.ticker,pd.predicted_price,pd.stoploss_price,pd.final_price,pd.current_price,pd.status,pd.result,p.message FROM  posts as p   
        INNER JOIN post_dayfeel as pd ON p.id =  pd.post_id 
        WHERE p.id="' . $postid . '" ', FALSE); 
            
        while ($result = $this->db2->fetch_object($aquerysset)) {   
            $data[] = $result;  
        }   
            
        return $data;   
            
    }   
    public function agree_post($state = TRUE)   
    {   
            
        if ($this->error) { 
            return FALSE;   
        }   
        if ($this->is_system_post) {    
            return FALSE;   
        }   
        if (!$this->user->is_logged) {  
            return FALSE;   
        }   
        $u = intval($this->user->id);   
            
        $b = $this->is_post_agree($u, $this->post_id);  
            
        if ($b && !$state) {    
                
            $this->db2->query('DELETE FROM  post_agree WHERE user_id="' . $u . '" AND post_id="' . $this->post_id . '" AND comment_id=0 LIMIT 1', FALSE);   
        } elseif (!$b && $state) {  
                
            $this->db2->query('INSERT INTO  post_agree SET user_id="' . $u . '", post_id="' . $this->post_id . '", date="' . time() . '" ', FALSE); 
        }   
            
        $this->post_agree = array();    
        $this->post_agree = $this->get_post_agree(TRUE);    
        return TRUE;    
    }   
    public function is_post_agree($userid, $postid) 
    {   
            
        $r   = $this->db2->query('SELECT id FROM post_agree as pa WHERE pa.user_id="' . $userid . '" AND pa.post_id="' . $postid . '" ');   
        $res = $this->db2->fetch_object($r);    
        if (count($res) > 0) {  
            return TRUE;    
                
        } else {    
            return FALSE;   
        }   
            
            
    }   
    public function is_post_agree_cnt($postid)  
    {   
        $r   = $this->db2->query('SELECT count(id) as cnt FROM post_agree as pa WHERE  pa.post_id="' . $postid . '" '); 
        $res = $this->db2->fetch_object($r);    
        return $res;    
            
            
    }   
    public function get_post_agree($force_refresh = FALSE)  
    {   
        if ($this->error) { 
            return FALSE;   
        }   
        if ($this->is_system_post) {    
            return FALSE;   
        }   
            
        $cachekey = 'n:' . $this->network->id . ',post_agree:' . $this->post_type . ':' . $this->post_id;   
        $data     = $this->cache->get($cachekey);   
        if (FALSE !== $data && TRUE != $force_refresh) {    
            return $data;   
        }   
        $data = array(  
            'post' => array()   
        );  
        $r    = $this->db2->query('SELECT u.id, u.avatar, u.username, pl.post_id, pl.comment_id FROM users u, post_agree pl WHERE pl.user_id=u.id AND post_id="' . $this->post_id . '" ', FALSE);   
        while ($o = $this->db2->fetch_object($r)) { 
            if ($o->comment_id == 0) {  
                $data['post'][$o->id] = array(  
                    $o->username,   
                    (!empty($o->avatar) ? $o->avatar : $GLOBALS['C']->DEF_AVATAR_USER)  
                );  
            } elseif ($o->comment_id > 0) { 
                $data['comment_' . $o->comment_id][$o->id] = array( 
                    $o->username,   
                    (!empty($o->avatar) ? $o->avatar : $GLOBALS['C']->DEF_AVATAR_USER)  
                );  
            }   
                
        }   
        $this->cache->set($cachekey, $data, $GLOBALS['C']->CACHE_EXPIRE);   
        return $data;   
    }   
    public function myintraday($userid, $offset)    
    {   
        $from = $offset;    
        $upto = 10; 
            
            
            
        $infraselect = $this->db2->query('SELECT u.id as userid,u.avatar,u.username,pd.id,pd.post_id,pd.ticker,pd.updated_date,pd.predicted_price,pd.stoploss_price,pd.current_price,pd.result,pd.status,p.date,p.message   
        FROM  posts  as p   
        inner join post_dayfeel as pd on p.id= pd.post_id   
        inner join users as u on u.id= p.user_id    
        WHERE   p.user_id="' . $userid . '" AND p.post_level is null group by pd.post_id order by p.date_lastcomment desc,pd.id desc limit ' . $from . ' ,' . $upto . ' '); 
        while ($result = $this->db2->fetch_object($infraselect)) {  
            $data[] = $result;  
        }   
            
        return $data;   
            
            
    }   
    public function peopleyoufollowintradaydata($userid, $offset)   
    {   
        $ifollow     = $this->getpeopleifollow($userid);    
        $ifollowdata = implode(',', $ifollow);  
        $from        = $offset; 
        $upto        = 10;  
            
            
            
            
        $infraselect = $this->db2->query('SELECT u.id as userid,u.username,u.avatar,pd.id,pd.post_id,pd.ticker,pd.updated_date,pd.predicted_price,pd.stoploss_price,pd.current_price,pd.result,pd.status,p.date,p.message   
        FROM   post_dayfeel as pd   
        inner join posts as p on p.id= pd.post_id  AND p.user_id IN(' . $ifollowdata . ')   
        inner join users as u on u.id= p.user_id AND p.post_level is null   
        group by pd.post_id order by p.date_lastcomment desc,pd.id desc limit ' . $from . ' ,' . $upto . '');   
            
        while ($result = $this->db2->fetch_object($infraselect)) {  
            $data[] = $result;  
        }   
            
        return $data;   
            
            
    }   
    public function allintraday($userid, $offset)   
    {   
        $ifollow      = $this->getpeopleifollow($userid);   
        //$ifollows =array_push($ifollow,$userid);  
        $ifollowdatas = implode(',', $ifollow); 
        $ifollowdata  = $ifollowdatas . ',' . $userid;  
        $from         = $offset;    
        $upto         = 10; 
            
            
            
            
        $infraselect = $this->db2->query('SELECT u.id as userid,u.avatar,u.username,pd.id,pd.post_id,pd.ticker,pd.updated_date,pd.predicted_price,pd.stoploss_price,pd.current_price,pd.result,pd.status,p.date,p.message   
        FROM   post_dayfeel as pd   
        inner join posts as p on p.id= pd.post_id  AND p.user_id IN(' . $ifollowdata . ')   
        inner join users as u on u.id= p.user_id AND p.post_level is null   
        group by pd.post_id order by p.date_lastcomment desc,pd.id desc limit ' . $from . ' ,' . $upto . ' ');  
            
        while ($result = $this->db2->fetch_object($infraselect)) {  
            $data[] = $result;  
        }   
            
        return $data;   
            
            
    }   
    public function getpeopleifollow($userid)   
    {   
        $ifollow = $this->db2->query('SELECT whom  FROM users_followed where    who="' . $userid . '" ');   
        while ($result = $this->db2->fetch_object($ifollow)) {  
            $data[] = $result->whom;    
        }   
            
        return $data;   
            
    }   
    public function myintradaycorrectdata($userid, $offset) 
    {   
        $from = $offset;    
        $upto = 10; 
            
            
            
        $infraselect = $this->db2->query('SELECT u.id as userid,u.avatar,u.username,pd.id,pd.post_id,pd.ticker,pd.updated_date,pd.predicted_price,pd.stoploss_price,pd.current_price,pd.result,pd.status,p.date,p.message   
        FROM  posts  as p   
        inner join post_dayfeel as pd on p.id= pd.post_id   
        inner join users as u on u.id= p.user_id    
        WHERE   p.user_id="' . $userid . '" AND result = 1 AND p.post_level is null  group by p.date_lastcomment desc,pd.post_id order by pd.id desc limit ' . $from . ' ,' . $upto . ' '); 
        while ($result = $this->db2->fetch_object($infraselect)) {  
            $data[] = $result;  
        }   
            
        return $data;   
            
            
    }   
    public function myintradaycorrectdatacalculation($userid)   
    {   
            
            
        $infraselect = $this->db2->query('SELECT count(pd.id) as correctcnt 
        FROM  posts  as p   
        inner join post_dayfeel as pd on p.id= pd.post_id   
        inner join users as u on u.id= p.user_id    
        WHERE   p.user_id="' . $userid . '" AND result = 1 ');  
        $result      = $this->db2->fetch_object($infraselect);  
            
        return $result; 
            
            
    }   
    public function totalintraday($userid)  
    {   
            
            
        $infraselect = $this->db2->query('SELECT count(pd.id) as totalcnt   
        FROM  posts  as p   
        inner join post_dayfeel as pd on p.id= pd.post_id   
        inner join users as u on u.id= p.user_id    
        WHERE   p.user_id="' . $userid . '" '); 
        $result      = $this->db2->fetch_object($infraselect);  
            
        return $result; 
            
            
    }   
    public function myintradayincorrectdata($userid, $offset)   
    {   
        $from = $offset;    
        $upto = 10; 
            
            
            
        $infraselect = $this->db2->query('SELECT u.id as userid,u.avatar,u.username,pd.id,pd.post_id,pd.ticker,pd.updated_date,pd.predicted_price,pd.stoploss_price,pd.current_price,pd.result,pd.status,p.date,p.message   
        FROM  posts  as p   
        inner join post_dayfeel as pd on p.id= pd.post_id   
        inner join users as u on u.id= p.user_id    
        WHERE   p.user_id="' . $userid . '" AND result = 0 AND p.post_level is null group by p.date_lastcomment desc,pd.post_id order by pd.id desc limit ' . $from . ' ,' . $upto . ' ');  
        while ($result = $this->db2->fetch_object($infraselect)) {  
            $data[] = $result;  
        }   
            
        return $data;   
            
            
    }   
    public function myintradayincorrectdatacalculation($userid) 
    {   
            
            
        $infraselect = $this->db2->query('SELECT count(pd.id) as correctcnt 
        FROM  posts  as p   
        inner join post_dayfeel as pd on p.id= pd.post_id   
        inner join users as u on u.id= p.user_id    
        WHERE   p.user_id="' . $userid . '" AND result = 0 ');  
        $result      = $this->db2->fetch_object($infraselect);  
            
        return $result; 
            
            
    }   
    public function assetdatacorrect($postid)   
    {   
        $aquerysset = $this->db2->query('SELECT id,ticker,predicted_price,stoploss_price,final_price,current_price,status,result FROM  post_dayfeel WHERE post_id="' . $postid . '" AND result=1 ', FALSE); 
        while ($result = $this->db2->fetch_object($aquerysset)) {   
            $data[] = $result;  
        }   
            
        return $data;   
            
    }   
    public function assetdataincoorect($postid) 
    {   
        $aquerysset = $this->db2->query('SELECT id,ticker,predicted_price,stoploss_price,final_price,current_price,status,result FROM  post_dayfeel WHERE post_id="' . $postid . '" AND result=0 ', FALSE); 
        while ($result = $this->db2->fetch_object($aquerysset)) {   
            $data[] = $result;  
        }   
            
        return $data;   
            
    }   
    public function mypredictionopenresults($userid, $status, $offset)  
    {   
        $from = $offset;    
        $upto = 10; 
            
        $mypredictresult = $this->db2->query('SELECT u.id as user_id,u.avatar,u.username,a.ticker,a.asset_name,p.date,pp.post_id,pp.prediction_base_price,pp.predict_reason,pp.predict_result,pp.end_date,pp.predict_value  
             FROM  post_prediction as pp    
             inner join posts as p ON p.id = pp.post_id 
             inner join users as u ON u.id =  pp.user_id    
             inner join assets as a on a.id= pp.asset_id    
            WHERE   pp.user_id="' . $userid . '" AND pp.status = "' . $status . '" order by pp.id desc limit ' . $from . ' ,' . $upto . ' ');   
        while ($result = $this->db2->fetch_object($mypredictresult)) {  
            $data[] = $result;  
        }   
        if (!empty($data)) {    
                
            return $data;   
        } else {    
            $data = array();    
            return $data;   
        }   
    }   
        
    public function allpredictions($userid, $offset)    
    {   
        $ifollow      = $this->getpeopleifollow($userid);   
        //$ifollows =array_push($ifollow,$userid);  
        $ifollowdatas = implode(',', $ifollow); 
        $ifollowdata  = $ifollowdatas . ',' . $userid;  
        $from         = $offset;    
        $upto         = 10; 
            
        $allselect = $this->db2->query('SELECT u.id as userid,u.avatar,u.username,a.ticker,a.asset_name,p.date,p.message,pp.post_id,pp.prediction_base_price,pp.predict_reason,pp.end_date,pp.predict_value,pp.status,pp.predict_result     
        FROM   post_prediction as pp    
        inner join posts as p on p.id= pp.post_id  AND p.user_id IN(' . $ifollowdata . ')   
        inner join users as u on u.id= p.user_id    
        inner join assets as a on a.id= pp.asset_id 
        group by pp.post_id order by pp.id desc limit ' . $from . ' ,' . $upto . ' ');  
            
        while ($result = $this->db2->fetch_object($allselect)) {    
            $data[] = $result;  
        }   
            
        if (!empty($data)) {    
                
            return $data;   
        } else {    
            $data = array();    
            return $data;   
        }   
            
            
    }   
    public function peopleyoufollowpredictions($userid, $offset)    
    {   
        $ifollow      = $this->getpeopleifollow($userid);   
        //$ifollows =array_push($ifollow,$userid);  
        $ifollowdatas = implode(',', $ifollow); 
        $ifollowdata  = $ifollowdatas;  
        $from         = $offset;    
        $upto         = 10; 
            
        $allselect = $this->db2->query('SELECT u.id as userid,u.avatar,u.username,a.ticker,a.asset_name,p.date,p.message,pp.post_id,pp.prediction_base_price,pp.predict_reason,pp.end_date,pp.predict_value,pp.status,pp.predict_result     
        FROM   post_prediction as pp    
        inner join posts as p on p.id= pp.post_id  AND p.user_id IN(' . $ifollowdata . ')   
        inner join users as u on u.id= p.user_id    
        inner join assets as a on a.id= pp.asset_id 
        group by pp.post_id order by pp.id desc limit ' . $from . ' ,' . $upto . ' ');  
            
        while ($result = $this->db2->fetch_object($allselect)) {    
            $data[] = $result;  
        }   
            
        if (!empty($data)) {    
                
            return $data;   
        } else {    
            $data = array();    
            return $data;   
        }   
            
            
    }   
    public function predictiondata($postid) 
    {   
            
        $aquerysset = $this->db2->query('SELECT p.id,p.predict_value,p.prediction_base_price,p.predict_reason,p.end_date,p.amount,p.predict_result,p.status,a.ticker,a.asset_name FROM  post_prediction as p    
                 INNER JOIN assets AS a ON  a.id =p.asset_id                    
                    
                WHERE p.post_id="' . $postid . '" ', FALSE);    
        while ($result = $this->db2->fetch_object($aquerysset)) {   
            $data[] = $result;  
        }   
            
        if (!empty($data)) {    
                
            return $data;   
        } else {    
            $data = array();    
            return $data;   
        }   
            
    }   
    public function replaycount($childid)   
    {   
        $r      = $this->db2->query('SELECT count(p.id) as replaycnt FROM post_replay as p  
                                         WHERE p.parent_id="' . $childid . '" ', FALSE);    
        $result = $this->db2->fetch_object($r); 
        return $result->replaycnt;  
    }   
    public function new_liked($postid)  
    {   
        $r      = $this->db2->query('select id  FROM  post_likes WHERE user_id="' . $this->user->id . '" AND post_id="' . $postid . '" LIMIT 1', FALSE);    
        $result = $this->db2->fetch_object($r); 
        return $result; 
            
    }   
    public function new_liked_count($postid)    
    {   
        $r      = $this->db2->query('select count(id) as likecount  FROM  post_likes WHERE  post_id="' . $postid . '" ', FALSE);    
        $result = $this->db2->fetch_object($r); 
        return $result; 
            
            
    }   
        
    public function new_reshared($postid)   
    {   
        $r      = $this->db2->query('select id  FROM post_reshares WHERE user_id="' . $this->user->id . '" AND post_id="' . $postid . '" LIMIT 1', FALSE);  
        $result = $this->db2->fetch_object($r); 
        return $result; 
    }   
    public function new_reshare_count($postid)  
    {   
        $r      = $this->db2->query('select count(id) as sharecount  FROM post_reshares WHERE  post_id="' . $postid . '"', FALSE);  
        $result = $this->db2->fetch_object($r); 
        return $result; 
            
    }   
    public function new_fav($postid)    
    {   
        $r      = $this->db2->query('select id  FROM  post_favs WHERE user_id="' . $this->user->id . '" AND post_id="' . $postid . '" LIMIT 1', FALSE); 
        $result = $this->db2->fetch_object($r); 
        return $result; 
            
            
    }   
     public function parsetext($text1)  
    {   
        global $C;  
            
        if (!empty($text1)) {   
            if (strpos($text1, '@') !== false) {    
                $str  = ''; 
                $text = explode(" ", $text1);   
                foreach ($text as $keys => $vals) { 
                    if (strpos($vals, '@') !== false) { 
                        $expl = explode("@", $vals);    
                        $expl = array_filter($expl);    
                        foreach ($expl as $arrkeys => $arrvals) {   
                                
                            $r      = $this->db2->query('select id  FROM  users  WHERE username="' . $arrvals . '"  LIMIT 1', FALSE);   
                            $result = $this->db2->fetch_object($r); 
                            if ($result->id != '') {    
                                $str .= ' <a href="' . $C->SITE_URL . '/' . $arrvals . '" title="' . $arrvals . '" class="bizcard" data-userid="' . $result->id . '"><span class="post_mentioned"><b>@</b>' . $arrvals . '</span></a> ';    
                            } else {    
                                $str .= ''; 
                            }   
                                
                                
                                
                                
                            // $str .=' <a href="http://localhost/streetbuzz/sdftt" title="kkll" class="bizcard" data-userid="55"><span class="post_mentioned"><b>@</b>sdftt</span></a>';   
                                
                                
                        }   
                            
                    } else {    
                        $str .= $vals . ' ';    
                    }   
                        
                        
                }   
                  if (strpos($str, '#') !== false) {    
                if( preg_match_all('/\#([\pL0-9]{1,50})/iu', $str, $matches, PREG_PATTERN_ORDER) ) {    
                foreach($matches[1] as $tg) {   
                    $returnposttags = '#'.mb_strtolower(trim($tg)); 
                    $returnposttag  = mb_strtolower(trim($tg)); 
                    $replacestr = '<a href="'.$C->SITE_URL.'search/tab:tags/s:'.$returnposttag.'" title="'.$returnposttag.'"><span class="post_tag"><b></b>'.$returnposttags.'</span></a>'; 
                    $str    =str_replace($returnposttags,$replacestr,$str); 
                }   
            }   
                  } 
                return $str;    
                    
            } else {    
               if (strpos($text1, '#') !== false) { 
                if( preg_match_all('/\#([\pL0-9]{1,50})/iu', $text1, $matches, PREG_PATTERN_ORDER) ) {  
                foreach($matches[1] as $tg) {   
                    $returnposttags = '#'.mb_strtolower(trim($tg)); 
                    $returnposttag  = mb_strtolower(trim($tg)); 
                    $replacestr = '<a href="'.$C->SITE_URL.'search/tab:tags/s:'.$returnposttag.'" title="'.$returnposttag.'"><span class="post_tag"><b></b>'.$returnposttags.'</span></a>'; 
                    $text1    =str_replace($returnposttags,$replacestr,$text1); 
                }   
            }   
             return $text1; 
                  }else{    
                       return $text1;   
                        
                  } 
            
                    
                    
            }   
        } else {    
            return $text1;  
                
        }   
            
            
    }   
    public function getbuzztype($childid)   
    {   
        $r      = $this->db2->query('SELECT p.action_type as action_type FROM post_replay as p  
                                         WHERE p.replay_id="' . $childid . '" ', FALSE);    
        $result = $this->db2->fetch_object($r); 
        return $result->action_type;    
    }   
    public function geteventdetails($childid)   
    {   
         
            
        $r      = $this->db2->query('SELECT e.id,ev.post_id,e.group_id,e.admin_id,address,e.latitude, e.longitude,e.event_name,e.event_description,e.start_date,e.start_time,e.end_date,e.end_time,e.event_description,e.status,e.tag_name,e.url,e.street_group,e.street_user,ev.edit_status,p.event_status FROM  event_posts as ev 
                                         inner join events as e ON ev.event_id = e.id   
                                         inner join post_userbox as p ON p.post_id =ev.post_id  
                                         WHERE ev.post_id="' . $childid . '" ', FALSE); 
        $result = $this->db2->fetch_object($r); 
        //print_r($result);exit;    
        if (!empty($result)) {  
            return $result; 
                
        } else {    
            return '';  
                
        }   
        //print_r($result);exit;    
    }   
    public function getgroupname($grpid)    
    {   
        $r      = $this->db2->query('SELECT groupname,title FROM  groups WHERE id="' . $grpid . '"  '); 
        $result = $this->db2->fetch_object($r); 
        return $result; 
            
    }   
    public function checkreplies($postid)   
    {   
            
        $r      = $this->db2->query('SELECT u.username as handler,u.id as userid,pr.alternate_parent_id,pr.parent_id,pr.replay_id FROM post_replay AS pr    
               INNER JOIN posts AS p ON pr.parent_id = p.id 
               INNER JOIN users AS u ON p.user_id = u.id    
               WHERE pr.replay_id="' . $postid . '" ', FALSE);  
        $result = $this->db2->fetch_object($r); 
        return $result; 
            
            
    }   
        
    public function get_parent_id($postid)  
    {   
        $r      = $this->db2->query('SELECT pr.parent_id FROM post_replay pr    
                
               WHERE pr.replay_id="' . $postid . '" ', FALSE);  
        $result = $this->db2->fetch_object($r); 
        return $result; 
            
    }   
    public function replay_is_poll($post_id)    
    {   
        $data = array();    
        $r    = $this->db2->query('SELECT posts.*,pol.*,pola.* FROM polls as pol inner join polls_answers as pola on pol.poll_id=pola.poll_id inner join posts on posts.id=pol.posts_id  WHERE posts_id="' . $post_id . '"', FALSE);    
        while ($result = $this->db2->fetch_object($r)) {    
            $data[] = $result;  
        }   
        return $data;   
            
    }   
    public function replay_is_video($post_id)    
    { 
        $data = array();    
        $r    = $this->db2->query('SELECT * FROM posts WHERE id="' . $post_id . '" AND posttype=3', FALSE);    
        while ($result = $this->db2->fetch_object($r)) {    
            $data[] = $result;  
        }   
        return $data;   
            
    }   
    public function replay_is_captionimage($post_id)    
    { 
        $data = array();    
        $r    = $this->db2->query('SELECT * FROM posts WHERE id="' . $post_id . '" AND posttype=1', FALSE);    
        while ($result = $this->db2->fetch_object($r)) {    
            $data[] = $result;  
        }   
        return $data;   
            
    }   
    public function presentuser()   
    {   
        return $this->user->id; 
            
    }   
    public function assethtml($parentid)    
    {   
        $assetdata = $this->assetdata($parentid);   
        if ($assetdata[0]->ticker != '') {  
            $str           = $this->parsetext($assetdata[0]->message);  
//START - For Desktop Screen    
$assetdatahtml = '<div class="hidden-xs intraday-box"><div>' . $str . '</div>   
    <table class="table table-bordered intraday-table" width="100%">    
    <thead> 
      <tr class="box-sub-title intraday-title"> 
        <th>Asset</th>  
        <th>Price @ Buzzing</th>    
        <th>Stop Loss</th>  
        <th>Target Price</th>   
        <th>Current Price</th>  
        <th>Result</th> 
      </tr> 
    </thead>    
    <tbody>';   
            foreach ($assetdata as $assetkeys => $assetvals) {  
            $assetmarketpresentprice = $this->assetmarketpresentprice($assetvals->ticker);  
                    
                if ($assetkeys / 2 == 0) {  
                    $css = "#f6fbfc";   
                        
                } else {    
                    $css = "#e3f8fe";   
                }   
                if ($assetvals->result == "1") {    
                    $img = '<img  src="' . $C->SITE_URL . 'static/images/tick.png" width="16"/>';   
                        
                } elseif ($assetvals->result == "0") {  
                    $img = '<img  src="' . $C->SITE_URL . 'static/images/wrong.png"/>'; 
                        
                } else {    
                    $img = 'Open';  
                        
                }   
                $assetdatahtml .= ' 
                    
    
      <tr style="background-color:' . $css . '; color: #66757F; font-size:12px;font-weight:normal;">    
        <td>$' . $assetvals->ticker . '</td>    
        <td>'.$assetvals->current_price.'</td>  
        <td>' . $assetvals->stoploss_price . '</td> 
        <td>' . $assetvals->predicted_price . '</td>    
        <td>' . $assetmarketpresentprice . '</td>   
        <td>' . $img . '</td>   
      </tr> 
        
        
        
                    
                ';  
                    
            }   
            $assetdatahtml .= '</tbody> 
  </table></div>';  
  // END - For Desktop Screen   
//START - For Small Screens 
  $assetdatahtml .='<div class="visible-xs intraday-box intrady-child"> 
   <table class="table intraday-table" width="100%">    
    <thead> 
      <tr>  
        <th class="intraday-title">Asset</th>'; 
        foreach($assetdata as $assetkeys=>$assetvals){  
            
         $assetdatahtml .='<td class="intraday-content">$'.$assetvals->ticker.'</td>';  
    }   
         $assetdatahtml .='</tr>    
         <tr>   
       <th class="intraday-title">Price @ Buzzing</th>';    
        foreach($assetdata as $assetkeys=>$assetvals){  
        $assetdatahtml .='<td class="intraday-content">'.$assetvals->current_price.'</td>'; 
        }   
        $assetdatahtml .='</tr> 
         <tr>   
        <th class="intraday-title">Stop Loss</th>'; 
        foreach($assetdata as $assetkeys=>$assetvals){  
         $assetdatahtml .='<td class="intraday-content">'.$assetvals->stoploss_price.'</td>';   
        }   
         $assetdatahtml .='</tr>    
         <tr>   
        <th class="intraday-title">Target Price</th>';  
         foreach($assetdata as $assetkeys=>$assetvals){ 
        $assetdatahtml .='<td class="intraday-content">'.$assetvals->predicted_price.'</td>';   
         }  
        $assetdatahtml .='</tr> 
         <tr>   
        <th class="intraday-title">Current Price</th>'; 
         foreach($assetdata as $assetkeys=>$assetvals){ 
        $assetmarketpresentmarketprice = $this->assetmarketpresentprice($assetvals->ticker);    
        $assetdatahtml .='<td class="intraday-content">'.$assetmarketpresentmarketprice.'</td>';    
         }  
        $assetdatahtml .='</tr> 
          <tr>  
       <th class="intraday-title">Result</th>'; 
       foreach($assetdata as $assetkeys=>$assetvals){   
                        if($assetvals->result == "1"){  
                    $img ='<img  src="'.$C->SITE_URL.'static/images/tick.png" width="16"/>';    
                        
                }elseif($assetvals->result =="0"){  
                    $img ='<img  src="'.$C->SITE_URL.'static/images/wrong.png"/>';  
                        
                }else{  
                    $img ='Open';   
                        
                }   
        $assetdatahtml .='<td class="intraday-content">'.$img.'</td>';  
       }    
        $assetdatahtml .='</tr> 
   </thead> 
    
  </table>  
  </div>';  
  //END - Small Screens 
                
            $parentmessage = $assetdatahtml;    
            return $parentmessage;  
                
                
        } else {    
            return '';  
                
        }   
    }   
    public function eventhtml($parentid)    
    {   
            
        $user->id = $this->user->id;    
            
        $eventdetails = $this->geteventdetails($parentid);  
            
            
        if ($eventdetails->group_id != '') {    
            $groupname = $this->getgroupname($eventdetails->group_id);  
        }   
            
        $finalcon = ''; 
        $finalcon .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 event-list-blue-bg" style="padding:10px 10px 0px 10px;">  
        
    <!-- start : event title -->    
    <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz- zeropadding">    
    <ul class="list-inline single-line">    
    <li><img src="' . $C->SITE_URL . 'apps/events/static/images/icon-calendar-event.png" class="img-responsive">    
    </li>   
    <li>    
    <a href="' . $C->SITE_URL . 'plugin/events/view/id:' . $eventdetails->id . '/postid:' . $eventdetails->post_id . '"  class="buzz-title">    
    ' . $eventdetails->event_name . '</a>   
    </li>   
    </ul>   
    </div>  
    <!-- end : event title -->  
';  
        $finalcon .= '    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding"> 
    <!-- start : event location --> 
    <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-12 buzz-content zeropadding">    
    <ul class="list-inline single-line zeropadding">    
    <li><img src="' . $C->SITE_URL . 'apps/events/static/images/icon-location-event.png" class="img-responsive"></li>   
    <li>' . $eventdetails->address . '</li> 
    </ul>   
    </div>  
    <!-- end : event location -->'; 
        $time      = $eventdetails->start_date . ' ' . $eventdetails->start_time;   
        $date_time = date("M d,Y h:i:s A", strtotime($time));   
        $finalcon .= '<!-- start : event date & time -->    
    <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-12 buzz-content zeropadding">    
    <ul class="list-inline single-line zeropadding">    
    <li><img src="' . $C->SITE_URL . 'apps/events/static/images/icon-calendar-event.png" class="img-responsive"></li>   
    <li>' . $date_time . '</li> 
    </ul>   
    </div>  
    <!-- end : event date & time -->    
    </div>';    
            
            
            
        if (!empty($eventdetails->url) || !empty($eventdetails->tag_name)) {    
            if (!empty($eventdetails->url)) {   
                $finalcon .= ' <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">    
    <!-- start : event url -->  
    <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-12 buzz-content zeropadding">    
    <ul class="list-inline single-line zeropadding">    
    <li><img src="' . $C->SITE_URL . 'apps/events/static/images/icon-url-event.png" class="img-responsive"></li>    
    <li>' . $eventdetails->url . '</li> 
    </ul>   
    </div>';    
                if (!empty($eventdetails->tag_name)) {  
                    $hastagarr  = explode("#", trim($eventdetails->tag_name));  
                    $strret_arr = array_filter($hastagarr); 
                    $hascon     = '';   
                    foreach ($strret_arr as $keys => $vals) {   
                        if ($keys == 1) {   
                            $hascon .= '<span><a href="' . $C->SITE_URL . '/search/tab:tags/s:' . $vals . '"><strong>#' . $vals . '</strong></a>';  
                        } else {    
                            $hascon .= '<strong>#' . $vals . '</strong></span>';    
                        }   
                            
                    }   
                    $finalcon .= '<!-- end : event url -->  
    <!-- start : event hashtag -->  
    <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-12 buzz-content zeropadding">    
    <ul class="list-inline single-line">    
    <li><img src="' . $C->SITE_URL . 'apps/events/static/images/icon-hashtag-event.png" class="img-responsive"></li>    
    <li>' . $hascon . ' </li>   
    </ul>   
    </div>  
    <!-- end event hashtag -->';    
                }   
                $finalcon .= '</div>';  
                    
            }   
                
        }   
            
            
        if ($eventdetails->status == 1) {   
            $st = "Active"; 
                
        }elseif($eventdetails->status ==2) {    
            $st = "Cancelled";  
                
        }elseif($eventdetails->status ==0) {    
            $st = "Expired";    
                
        }   
        if ($user->id != $eventdetails->admin_id) { 
            if ($eventdetails->event_status != 2) { 
                if($eventdetails->status !=0){  
                    
                if (($eventdetails->event_status == '' && $eventdetails->edit_status == '')) {  
                    $finalcon .= '<div  class="col-lg-12 col-md-12 col-sm-12 col-xs-12 event-list-blue-bg" style="padding:0px 10px 0px 0px; display:'.$display.';"  id="acc-' . $eventdetails->post_id . '">    
                    <img class="icon-calander" src="'.$C->SITE_URL.'apps/events/static/images/icon-user-response.png">&nbsp; <span class="user-response">User Response:</span>  
                    <input type="radio"  class="accept" name="accept" onclick="myFunction(' . $eventdetails->post_id . '1)" value="' . $eventdetails->post_id . '-1"><span class="user-response-accept-reject">Accept</span>    
                    &nbsp;&nbsp;    
                    <input type="radio" class="accept" name="accept" onclick="myFunction(' . $eventdetails->post_id . '3)"  value="' . $eventdetails->post_id . '-3"><span class="user-response-accept-reject">Reject</span></div>';    
                        
                }   
                }   
                if ($eventdetails->event_status == 1) { 
                    $display = "block"; 
                        
                } else {    
                    $display = "none";  
                }   
                if ($eventdetails->event_status == 3) { 
                    $displayreject = "block";   
                        
                } else {    
                    $displayreject = "none";    
                }   
                    
                if (($eventdetails->event_status != 2 || $eventdetails->edit_status != 4) && ($eventdetails->event_status != 2 && $eventdetails->edit_status != 4)) {   
                        
                        
                    $finalcon .= '<div style="display:' . $display . ';"id="accept-' . $eventdetails->post_id . '"><strong>User Response:</strong>Event Accepted</div>';    
                    $finalcon .= '<div style="display:' . $displayreject . ';"id="reject-' . $eventdetails->post_id . '"><strong>User Response:</strong>Event Rejected</div>';  
                        
                    $finalcon .= '<input type="hidden" id="attach-' . $eventdetails->post_id . '"  value="' . $eventdetails->attachment_id . '">';  
                } else {    
                    if ((($eventdetails->event_status != 4) && ($eventdetails->edit_status == 4))) {    
                            
                        $finalcon .= '<div style="display:' . $display . ';"id="accept-' . $eventdetails->post_id . '"><strong>User Response:</strong>Event Accepted</div>';    
                        $finalcon .= '<div style="display:' . $displayreject . ';"id="reject-' . $link->post_id . '"><strong>User Response:</strong>Event Rejected</div>';  
                            
                            
                    } else {    
                        $finalcon .= '<div>This event was no longer available.</div>';  
                    }   
                }   
                if ($eventdetails->event_status == 5) { 
                    $finalcon .= '<div>This event was modified.</div>'; 
                        
                }   
                $finalcon .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz-content event-list-blue-bg" style="padding:0px 0px 10px 0px;">   
    <ul class="list-inline">    
    <li><img src="' . $C->SITE_URL . 'apps/events/static/images/icon-status-event.png" class="img-responsive"></li> 
    <li>Status - <span class="txt-accepted">' . $st . '</span></li> 
    </ul>   
    </div>';    
                    
                    
                    
                    
            } else {    
                    
                $finalcon .= '<div>Event Cancelled</div>';  
                    
                    
            }   
        } else {    
            if ($eventdetails->event_status != 2 || $eventdetails->event_status != 4 || $eventdetails->event_status != 5) { 
                $finalcon .= '<div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz-content event-list-blue-bg"  style="padding:0px 0px 10px 0px;"> 
    <ul class="list-inline">    
    <li><img src="' . $C->SITE_URL . 'apps/events/static/images/icon-status-event.png" class="img-responsive"></li> 
    <li>Status - <span class="txt-accepted">' . $st . '</span></li> 
    </ul>   
    </div>';    
                    
                $finalcon .= '<div class="btn-download-padding"><a href="' . $C->SITE_URL . 'dashboard?pid=' . $eventdetails->post_id . '"><input class="button-submit-results" type="button" name="download" value="Download Results"></a></div>'; 
                    
            } else {    
                if ($eventdetails->status == 2 && $eventdetails->edit_status != 4) {    
                    $finalcon .= '<div><strong>status:</strong>Cancel</div>';   
                        
                        
                } else {    
                    $finalcon .= '<div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz-content event-list-blue-bg"  style="padding:0px 0px 10px 0px;"> 
    <ul class="list-inline">    
    <li><img src="' . $C->SITE_URL . 'apps/events/static/images/icon-status-event.png" class="img-responsive"></li> 
    <li>Status - <span class="txt-accepted">' . $st . '</span></li> 
    </ul>   
    </div>';    
                        
                    $finalcon .= '<div>This event was no longer available.</div>';  
                        
                }   
                    
                    
            }   
                
        }   
        $finalcon .= '</div>    
        ';  
        return $finalcon;   
            
            
    }   
    public function pollchildhtml($parentid)    
    {   
           global $C;   
            
        $user->id = $this->user->id;    
            
            
        $poll = $this->replay_is_poll($parentid);   
            
        $pollanswer = $this->is_pollanswer($user->id, $poll[0]->poll_id);   
        $uservote  =$this->userpollanswer($user->id,$poll[0]->poll_id); 
       $maintype=0; 
        if(count($pollanswer)<=0){  
                $changehtml ='<div>&nbsp;</div>';   
        }else{  
                $changehtml ='<div id="changevote'.$maintype.''.$poll[0]->poll_id.'" class="pull-right zeropadding box-sub-desc"><a onclick="changeopenion('.$user->id.','.$poll[0]->poll_id.','.$parentid.','.$maintype.')" ><span class="glyphicon glyphicon-edit"></span> Change Vote</a></div>';    
        }   
            
            $pollhtml ='';  
            $pollhtml .='<!-- start - 1st vote poll --> 
            '.$changehtml.' 
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 poll-list-orange-bg">   
        
    <!-- start : poll title --> 
    <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz-title">   
    <ul class="list-inline">    
    <li><img src="'.$C->SITE_URL.'static/images/icon-poll-24.png" class="img-responsive"></li>  
    <li>'.$poll[0]->poll_question.' 
    </li>   
    </ul>   
    </div>      
    <!-- end : poll title -->'; 
    if(count($pollanswer) <=0){ 
            $pollhtml .='<div id="replace'.$poll[0]->poll_id.'">';  
            foreach($poll as $keys=>$row)   
            {   
                if($row->answer!="" && count($pollanswer)<=0)   
                {   
     $pollhtml .='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding"> 
    <!-- start : TIMELINE CHILD - poll results -->  
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz-content poll-child-radio-margin">  
    <ul class="list-unstyled poll-radio">   
    <li>    
<input onclick="changeurl('.$row->poll_id.','.$row->poll_answer_id.')" id="'.$keys.$row->poll_id.'" type="radio" name="radio11"  class="radios option'.$row->poll_answer_id.' radio'.$row->poll_id.' "/>    
<label for="'.$keys.$row->poll_id.'">&nbsp;</label>'.$row->answer.'</li>    
    
    </ul>   
    </div>  
    <!-- end : poll results --> 
    </div>';    
        
     }  
                else if($row->answer!="")   
                {   
                    $countpollanswer=$this->is_countpollanswer($row->poll_id,$row->poll_answer_id); 
                            
 $r      = $this->db2->query('SELECT shares  FROM  posts_details
          WHERE post_id="'.$postid.'"');

$rows=$r->num_rows;

if($rows>0){
while($result = $this->db2->fetch_object($r)){
    $data= $result->shares;
}
return $data;
}
                }   
            }   
            $pollhtml .='</div>';           
    }else{  
    $pollper =$this->getpercentagesofpollanswers($poll[0]->poll_id);    
    $totalpollcnt =$this->totalpollcnt($poll[0]->poll_id);  
    $pollhtml .='   
<div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz-content" id="replace'.$poll[0]->poll_id.'">'; 
 foreach($pollper as $keys=>$vals){ 
     if($vals->answer !=''){    
     $percentage = ($vals->cnt/$totalpollcnt->totalcnt)*100;    
      if($vals->poll_answer_id == $uservote){   
         $userclass=' <span class="glyphicon glyphicon-ok"></span>';    
     }else{ 
        $userclass='';  
     }  
     if($percentage <10){   
         $width= '10';  
            
     }else{ 
          $width= $percentage;  
            
     }  
     if($keys == 0){    
         $clor ='success';  
     }elseif($keys == 1){   
          $clor ='info';    
            
     }elseif($keys == 2){   
         $clor ='warning';  
     }  
     elseif($keys == 3){    
          $clor ='danger';  
            
     }  
     elseif($keys == 4){    
        $clor ='least';     
     }  
    $pollhtml .='<strong>'.$vals->answer.'</strong> 
    <div class="progress">  
    <div class="progress-bar progress-bar-'.$clor.'" role="progressbar" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100" style="width:'.$width.'%">  
      <strong>'.$this->network->format_num($vals->usercnt).' vote('.round($percentage,2).'%)</strong>'.$userclass.'     
    </div>  
  </div>';  
 }  
 }  
    $pollhtml .='</div> 
    ';  
            
        
    }   
    $pollhtml .='<!-- start : poll button download results -->  
    <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12" >';   
    if(count($pollanswer) <=0){ 
        $cildhtml =0;   
        $pollhtml .='<a  class="button-vote" style="cursor:default"  onclick="vote('.$cildhtml.','.$row->poll_id.')"  id="pollvote'.$row->poll_id.'" >Vote</a>';    
        if($poll[0]->user_id == $user->id){ 
        $pollhtml .='<a type="submit" class="download'.$maintype.''.$poll[0]->poll_id.'"   id="suboption'.$poll[0]->poll_id.'" href="'.$C->SITE_URL.'plugin/poll/admin?action=download&poll_id='.$row->poll_id.'" ><button type="submit" class="button-submit-results btn-download-event-child-padding">Download Results</button></a>'; 
                
        }   
        
   }else{   
        if($poll[0]->user_id == $user->id){ 
        $pollhtml .='<div class="btn-download-padding"><a type="submit" class="download'.$maintype.''.$poll[0]->poll_id.'" id="suboption'.$poll[0]->poll_id.'" href="'.$C->SITE_URL.'plugin/poll/admin?action=download&poll_id='.$poll[0]->poll_id.'" ><button type="submit" class="button-submit-results btn-download-event-child-padding">Download Results</button></a></div>';   
                
        }   
   }    
     $pollhtml .='</div>    
    <!-- end : poll button download results --> 
    
    </div>  
    <!-- end - 1st vote poll -->';  
    return $pollhtml;   
            
            
    }   
     //checking chield  
    public function pollhtml($parentid,$image_name='') 
    {   
        
            global $db2, $C;

        $user->id = $this->user->id;    
            
            
        $poll = $this->replay_is_poll($parentid);   
            
        $pollanswer = $this->is_pollanswer($user->id, $poll[0]->poll_id);   
        $uservote  =$this->userpollanswer($user->id,$poll[0]->poll_id); 
        if(count($pollanswer)<=0){  
                $changehtml ='';   
        }else{  
            $maintype=1;    
                $changehtml ='<div id="changevote'.$maintype.''.$poll[0]->poll_id.'" class="pull-right zeropadding box-sub-desc"><a onclick="changeopenion('.$user->id.','.$poll[0]->poll_id.','.$parentid.','.$maintype.')" ><span class="glyphicon glyphicon-edit"></span> Change Vote</a></div>';    
        }   
            
                $r      = $this->db2->query('SELECT data,type  FROM  posts_attachments
          WHERE post_id="'.$parentid.'"');

$rows=$r->num_rows;
$imagedes="";
if($rows>0){
while($result = $this->db2->fetch_object($r)){
            
              $str = (unserialize($result->data));
     $ext = $str->file_preview;
                    $imagedes=""; 
      $imagedes = '<a target="_blank" href="'.($C->STORAGE_URL.'attachments/1/'.$ext).'" class="lightbox-image image-thumb cboxElement 22"><img width="100%" alt="Image" src="'.$C->STORAGE_URL.'attachments/1/'.$ext.'" /></a>';

            
}
    
}

            $pollhtml ='<!-- start - 1st vote poll  5555555--> 
        '.$changehtml.' 
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 poll-list-orange-bg">   
               '.$imagedes.' 
    <!-- start : poll title --> 
    <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz-title">   
    <ul class="list-inline">    
    <li><img src="'.$C->SITE_URL.'static/images/icon-poll-24.png" class="img-responsive"></li>  
    <li>'.$poll[0]->poll_question.' 
    </li>   
    </ul>   
    </div>      
    <!-- end : poll title -->'; 
    if(count($pollanswer) <=0){ 
            $pollhtml .='<div id="replace1'.$poll[0]->poll_id.'">'; 
            foreach($poll as $keys=>$row)   
            {   
                if($row->answer!="" && count($pollanswer)<=0)   
                {   
     $pollhtml .='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding"> 
    <!-- start : TIMELINE CHILD - poll results -->  
    <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz-content poll-parent-radio-margin">    
    <ul class="list-unstyled poll-radio">   
    <li>    
<input onclick="changeurl('.$row->poll_id.','.$row->poll_answer_id.')" id="replay'.$keys.$row->poll_id.'" type="radio" name="radio11"  class="radios option'.$row->poll_answer_id.' radio'.$row->poll_id.' "/>  
<label for="replay'.$keys.$row->poll_id.'">&nbsp;</label>'.$row->answer.'</li>  
    
    </ul>   
    </div>  
    <!-- end : poll results --> 
    </div>';    
        
     }  
                else if($row->answer!="")   
                {   
                    $countpollanswer=$this->is_countpollanswer($row->poll_id,$row->poll_answer_id); 
                        
                }   
            }   
    $pollhtml .='</div>';           
    }else{  
    $pollper =$this->getpercentagesofpollanswers($poll[0]->poll_id);    
    $totalpollcnt =$this->totalpollcnt($poll[0]->poll_id);  
    $pollhtml .='   
<div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz-content" id="replace1'.$poll[0]->poll_id.'"> ';   
 foreach($pollper as $keys=>$vals){ 
     if($vals->answer !=''){    
     $percentage = ($vals->cnt/$totalpollcnt->totalcnt)*100;    
      if($vals->poll_answer_id == $uservote){   
         $userclass=' <span class="glyphicon glyphicon-ok"></span>';    
     }else{ 
        $userclass='';  
     }  
     if($percentage <10){   
         $width= '10';  
            
     }else{ 
          $width= $percentage;  
            
     }  
     if($keys == 0){    
         $clor ='success';  
     }elseif($keys == 1){   
          $clor ='info';    
            
     }elseif($keys == 2){   
         $clor ='warning';  
     }  
     elseif($keys == 3){    
          $clor ='danger';  
            
     }  
     elseif($keys == 4){    
        $clor ='least';     
     }  
    $pollhtml .='<strong>'.$vals->answer.'</strong> 
    <div class="progress">  
    <div class="progress-bar progress-bar-'.$clor.'" role="progressbar" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100" style="width:'.$width.'%">  
         <strong>'.$this->network->format_num($vals->usercnt).' vote('.round($percentage,2).'%)</strong>'.$userclass.'      
    </div>  
  </div>';  
 }  
 }  
    $pollhtml .='</div> 
    ';  
            
        
    }   
    $pollhtml .='<!-- start : poll button download results -->  
    <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12">';    
    $maintype=1;    
    if(count($pollanswer) <=0){ 
        $cildhtml =1;   
            
        $pollhtml .='<a  class="button-vote" style="cursor:default"  onclick="vote('.$cildhtml.','.$row->poll_id.')"  id="pollvote'.$row->poll_id.'" >Vote</a>';    
        if($poll[0]->user_id == $user->id){ 
        $pollhtml .='<a type="submit"  class="download'.$maintype.''.$poll[0]->poll_id.'"  id="suboption'.$row->poll_id.'" href="'.$url.'plugin/poll/admin?action=download&poll_id='.$poll[0]->poll_id.'" ><button type="submit" class="button-submit-results btn-download-event-child-padding">Download Results</button></a>'; 
                
        }   
        
   }else{   
        if($poll[0]->user_id == $user->id){ 
        $pollhtml .='<div class="btn-download-padding"><a type="submit"  class="download'.$maintype.''.$poll[0]->poll_id.'"  id="suboption'.$poll[0]->poll_id.'" href="'.$url.'plugin/poll/admin?action=download&poll_id='.$poll[0]->poll_id.'" ><button type="submit" class="button-submit-results btn-download-event-child-padding">Download Results</button></a></div>'; 
                
        }   
   }    
     $pollhtml .='</div>    
    <!-- end : poll button download results --> 
    
    </div>  
    <!-- end - 1st vote poll -->';  
    return $pollhtml;   
            
            
    }   
    //checking chield   
    public function is_chield_replay($postid)   
    {   
        $data = array();    
        $r    = $this->db2->query('SELECT p.id,p.group_id,p.message,p.date,p.group_name,users.id as userid,users.avatar as pic, users.username as username,p.group_id FROM posts as p   
                                        inner join post_replay as pr ON p.id = pr.replay_id         
                                        inner join users on p.user_id=users.id WHERE pr.parent_id="' . $postid . '" order by p.date ASC', FALSE);   
        while ($result = $this->db2->fetch_object($r)) {    
            $data[] = $result;  
        }   
            
            
        return $data;   
    }   
     //checking chield  
    public function is_notificationchield_replay($postid,$event)    
    {   
        $data = array();    
        $r    = $this->db2->query('SELECT p.id,p.group_id,p.message,p.date,p.group_name,users.id as userid,users.avatar as pic, users.username as username,p.group_id FROM posts as p   
                                        inner join post_replay as pr ON p.id = pr.replay_id         
                                        inner join users on p.user_id=users.id WHERE pr.parent_id="' . $postid . '" AND pr.action_type="' . $event . '" order by p.date ASC', FALSE);   
        while ($result = $this->db2->fetch_object($r)) {    
            $data[] = $result;  
        }   
            
            
        return $data;   
    }   
    public function isfav($userid, $postid) 
    {   
        $r      = $this->db2->query('select *  FROM  post_favs WHERE user_id="' . $userid . '" AND post_id="' . $postid . '" LIMIT 1'); 
        $result = $this->db2->fetch_object($r); 
            
            
        return $result; 
    }   
    public function getpercentagesofpollanswers($pollid)    
    {   
        $r = $this->db2->query('select count(pv.ANSWER_ID) as cnt,count(pv.VOTER_USER_ID)as usercnt,pa.`answer`,pa.`poll_answer_id` from polls_answers as pa left join post_poll_votes as pv ON pa.poll_answer_id = pv.ANSWER_ID where pa.`POLL_ID` ="' . $pollid . '" AND pa.answer is not null  group by pa.`poll_answer_id` order by cnt desc', FALSE);  
        while ($result = $this->db2->fetch_object($r)) {    
            $data[] = $result;  
        }   
            
        return $data;   
            
    }   
    public function totalpollcnt($pollid)   
    {   
        $r      = $this->db2->query('select count(ID) as totalcnt  FROM  post_poll_votes WHERE      POLL_ID="' . $pollid . '" LIMIT 1');    
        $result = $this->db2->fetch_object($r); 
        return $result; 
    }   
     public static function replay_parse_date($timestamp, $return_words = 'auto', $return_dt_format = '%b %d %Y, %H:%M')    
    {   
        if ($return_words == FALSE) {   
            return strftime($return_dt_format, $timestamp); 
        }   
        $time = time() - $timestamp;    
        $h    = floor($time / 3600);    
        $time -= $h * 3600; 
        $m = floor($time / 60); 
        $time -= $m * 60;   
        $s = $time; 
        if ($return_words === 'auto' && $h >= 12) { 
            return strftime($return_dt_format, $timestamp); 
        }   
        $txt = '##BEFORE## ';   
        if ($h > 0) {   
            $txt .= $h; 
            $txt .= $h == 1 ? ' ##HOUR##' : ' ##HOURS##';   
        }   
        if ($h >= 3) {  
            //$txt .= ' ##AGO##';   
            //return post::_parse_date_replace_strings($txt);   
        }   
        if ($m > 0) {   
            if ($h > 0) {   
                $txt .= ' ##AND## ';    
            }   
            $txt .= $m; 
            $txt .= $m == 1 ? ' ##MIN##' : ' ##MINS##'; 
            if ($h > 0) {   
                //$txt .= ' ##AGO##';   
               // return post::_parse_date_replace_strings($txt);   
            }   
        }   
        if ($h == 0 && $m == 0) {   
            if ($s == 0) {  
                //return post::_parse_date_replace_strings('##NOW##');  
            }   
            $txt .= $s; 
            $txt .= $s == 1 ? ' ##SEC##' : ' ##SECS##'; 
        }   
        $txt .= ' ##AGO##'; 
            
        return $txt;    
    }   
        public function notificationeventhtml($parentid,$url)   
    {   
            
        $user->id = $this->user->id;    
            
        $eventdetails = $this->geteventdetails($parentid);  
            
            
        if ($eventdetails->group_id != '') {    
            $groupname = $this->getgroupname($eventdetails->group_id);  
        }   
            
        $finalcon = ''; 
        $finalcon .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 event-list-blue-bg"   
style="padding:10px 10px 0px 10px;">    
        
    <!-- start : event title -->    
    <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz-content zeropadding"> 
    <ul class="list-inline single-line">    
    <li><img src="' . $url . 'apps/events/static/images/icon-calendar-event.png" class="img-responsive">    
    </li>   
    <li>    
    <a href="' .$url . 'plugin/events/view/id:' . $eventdetails->id . '/postid:' . $eventdetails->post_id . '"  class="buzz-title"> 
    ' . $eventdetails->event_name . '</a>   
    </li>   
    </ul>   
    </div>  
    <!-- end : event title -->  
';  
        $finalcon .= '    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding"> 
    <!-- start : event location --> 
    <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-12 buzz-content zeropadding">    
    <ul class="list-inline single-line zeropadding">    
    <li><img src="' .$url . 'apps/events/static/images/icon-location-event.png" class="img-responsive"></li>    
    <li>' . $eventdetails->address . '</li> 
    </ul>   
    </div>  
    <!-- end : event location -->'; 
        $time      = $eventdetails->start_date . ' ' . $eventdetails->start_time;   
        $date_time = date("M d,Y h:i:s A", strtotime($time));   
        $finalcon .= '<!-- start : event date & time -->    
    <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-12 buzz-content zeropadding">    
    <ul class="list-inline single-line zeropadding">    
    <li><img src="' .$url . 'apps/events/static/images/icon-calendar-event.png" class="img-responsive"></li>    
    <li>' . $date_time . '</li> 
    </ul>   
    </div>  
    <!-- end : event date & time -->    
    </div>';    
            
            
            
        if (!empty($eventdetails->url) || !empty($eventdetails->tag_name)) {    
            if (!empty($eventdetails->url)) {   
                $finalcon .= ' <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding">    
    <!-- start : event url -->  
    <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-12 buzz-content zeropadding">    
    <ul class="list-inline single-line zeropadding">    
    <li><img src="' . $url. 'apps/events/static/images/icon-url-event.png" class="img-responsive"></li> 
    <li>' . $eventdetails->url . '</li> 
    </ul>   
    </div>';    
                if (!empty($eventdetails->tag_name)) {  
                    $hastagarr  = explode("#", trim($eventdetails->tag_name));  
                    $strret_arr = array_filter($hastagarr); 
                    $hascon     = '';   
                    foreach ($strret_arr as $keys => $vals) {   
                        if ($keys == 1) {   
                            $hascon .= '<span><a href="' .$url . '/search/tab:tags/s:' . $vals . '"><strong>#' . $vals . '</strong></a>';   
                        } else {    
                            $hascon .= '<strong>#' . $vals . '</strong></span>';    
                        }   
                            
                    }   
                    $finalcon .= '<!-- end : event url -->  
    <!-- start : event hashtag -->  
    <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-12 buzz-content zeropadding">    
    <ul class="list-inline single-line">    
    <li><img src="' .$url. 'apps/events/static/images/icon-hashtag-event.png" class="img-responsive"></li>  
    <li>' . $hascon . ' </li>   
    </ul>   
    </div>  
    <!-- end event hashtag -->';    
                }   
                $finalcon .= '</div>';  
                    
            }   
                
        }   
            
            
        if ($eventdetails->status == 1) {   
            $st = "Active"; 
                
        } elseif($eventdetails->status == 2){   
            $st = "Cancelled";  
                
        }elseif($eventdetails->status == 0){    
              $st = "Expired";  
                
        }   
        if ($user->id != $eventdetails->admin_id) { 
            if ($eventdetails->event_status != 2) { 
                if($eventdetails->status != 0){ 
                    
                if (($eventdetails->event_status == '' && $eventdetails->edit_status == '')) {  
                    $finalcon .= '<div  class="col-lg-12 col-md-12 col-sm-12 col-xs-12 event-list-blue-bg" style="padding:0px 10px 0px 0px; display:'.$display.';"  id="acc-' . $eventdetails->post_id . '">    
                    <img class="icon-calander" src="'.$C->SITE_URL.'apps/events/static/images/icon-user-response.png">&nbsp; <span class="user-response">User Response:</span>  
                    <input type="radio"  class="accept" name="accept" onclick="myFunction(' . $eventdetails->post_id . '1)" value="' . $eventdetails->post_id . '-1"><span class="user-response-accept-reject">Accept</span>    
                    &nbsp;&nbsp;    
                    <input type="radio" class="accept" name="accept" onclick="myFunction(' . $eventdetails->post_id . '3)"  value="' . $eventdetails->post_id . '-3"><span class="user-response-accept-reject">Reject</span></div>';    
                        
                }   
                }   
                if ($eventdetails->event_status == 1) { 
                    $display = "block"; 
                        
                } else {    
                    $display = "none";  
                }   
                if ($eventdetails->event_status == 3) { 
                    $displayreject = "block";   
                        
                } else {    
                    $displayreject = "none";    
                }   
                    
                if (($eventdetails->event_status != 2 || $eventdetails->edit_status != 4) && ($eventdetails->event_status != 2 && $eventdetails->edit_status != 4)) {   
                        
                        
                    $finalcon .= '<div style="display:' . $display . ';"id="accept-' . $eventdetails->post_id . '"><strong>User Response:</strong>Event Accepted</div>';    
                    $finalcon .= '<div style="display:' . $displayreject . ';"id="reject-' . $eventdetails->post_id . '"><strong>User Response:</strong>Event Rejected</div>';  
                        
                    $finalcon .= '<input type="hidden" id="attach-' . $eventdetails->post_id . '"  value="' . $eventdetails->attachment_id . '">';  
                } else {    
                    if ((($eventdetails->event_status != 4) && ($eventdetails->edit_status == 4))) {    
                            
                        $finalcon .= '<div style="display:' . $display . ';"id="accept-' . $eventdetails->post_id . '"><strong>User Response:</strong>Event Accepted</div>';    
                        $finalcon .= '<div style="display:' . $displayreject . ';"id="reject-' . $eventdetails->post_id . '"><strong>User Response:</strong>Event Rejected</div>';  
                            
                            
                    } else {    
                        $finalcon .= '<div>This event was no longer available.</div>';  
                    }   
                }   
                if ($eventdetails->event_status == 5) { 
                    $finalcon .= '<div>This event was modified.</div>'; 
                        
                }   
                $finalcon .= '<div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz-content event-list-blue-bg"  style="padding:0px 0px 10px 0px;"> 
    <ul class="list-inline">    
    <li><img src="' .$url . 'apps/events/static/images/icon-status-event.png" class="img-responsive"></li>  
    <li>Status - <span class="txt-accepted">' . $st . '</span></li> 
    </ul>   
    </div>';    
                    
                    
                    
                    
            } else {    
                    
                $finalcon .= '<div>Event Cancelled</div>';  
                    
                    
            }   
        } else {    
            if ($eventdetails->event_status != 2 || $eventdetails->event_status != 4 || $eventdetails->event_status != 5) { 
                $finalcon .= '<div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz-content event-list-blue-bg"  style="padding:0px 0px 10px 0px;"> 
    <ul class="list-inline">    
    <li><img src="' .$url. 'apps/events/static/images/icon-status-event.png" class="img-responsive"></li>   
    <li>Status - <span class="txt-accepted">' . $st . '</span></li> 
    </ul>   
    </div>';    
                    
                $finalcon .= '<div class="btn-download-padding"><a href="' .$url. 'dashboard?pid='.$eventdetails->post_id . '"> 
                <input class="button-submit-results btn-download-event-child-padding" type="button" name="download" value="Download Results"></a></div>';   
                    
            } else {    
                if ($eventdetails->status == 2 && $eventdetails->edit_status != 4) {    
                    $finalcon .= '<div><strong>status:</strong>Cancel</div>';   
                        
                        
                } else {    
                    $finalcon .= '<div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz-content event-list-blue-bg"  style="padding:0px 0px 10px 0px;"> 
    <ul class="list-inline">    
    <li><img src="' .$url . 'apps/events/static/images/icon-status-event.png" class="img-responsive"></li>  
    <li>Status - <span class="txt-accepted">' . $st . '</span></li> 
    </ul>   
    </div>';    
                        
                    $finalcon .= '<div>This event was no longer available.</div>';  
                        
                }   
                    
                    
            }   
                
        }   
        $finalcon .= '</div>    
        ';  
        return $finalcon;   
            
            
    }   
            public function notificationpollhtml($parentid,$url,$eventtype) 
    {   
            
        $user->id = $this->user->id;    
            
            
        $poll = $this->replay_is_poll($parentid);   
            
        $pollanswer = $this->is_pollanswer($user->id, $poll[0]->poll_id);   
        $uservote  =$this->userpollanswer($user->id,$poll[0]->poll_id); 
        if(count($pollanswer)<=0){  
                $changehtml ='';    
            }else{  
                $changehtml ='<div id="changevote'.$eventtype.''.$poll[0]->poll_id.'" class="pull-right zeropadding box-sub-desc"><a onclick="changeopenion('.$user->id.','.$eventtype.','.$poll[0]->poll_id.','.$parentid.')" ><span class="glyphicon glyphicon-edit"></span> Change Vote</a></div>';  
            }   
            
            $pollhtml ='';  
            $pollhtml .='<!-- start - 1st vote poll --> 
            '.$changehtml.' 
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 poll-list-orange-bg">   
        
    <!-- start : poll title --> 
    <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz-title">   
    <ul class="list-inline">    
    <li><img src="'.$url.'static/images/icon-poll-24.png" class="img-responsive"></li>  
    <li>'.$poll[0]->poll_question.' 
    </li>   
    </ul>   
    </div>      
    <!-- end : poll title -->'; 
    if(count($pollanswer) <=0){ 
         $pollhtml .='<div id="replace'.$eventtype.$poll[0]->poll_id.'">';  
            foreach($poll as $keys=>$row)   
            {   
                if($row->answer!="" && count($pollanswer)<=0)   
                {   
     $pollhtml .='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding"> 
    <!-- start : NOTIFICATION PARENT - poll radio -->   
     <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz-content poll-parent-radio-margin">   
    <ul class="list-unstyled poll-radio">   
    <li>    
<input onclick="changeurl('.$row->poll_id.','.$row->poll_answer_id.','.$eventtype.')"  id="'.$eventtype.$keys.$row->poll_id.'" type="radio" name="radio11"  class="radios option'.$row->poll_answer_id.' radio'.$row->poll_id.' "/> 
<label for="'.$eventtype.$keys.$row->poll_id.'">&nbsp;</label>'.$row->answer.'</li> 
    
    </ul>   
    </div>  
    <!-- end : poll radio -->   
    </div>';    
        
     }  
                else if($row->answer!="")   
                {   
                    $countpollanswer=$this->is_countpollanswer($row->poll_id,$row->poll_answer_id); 
                        
                }   
            }   
        $pollhtml .='</div>';   
    }else{  
    $pollper =$this->getpercentagesofpollanswers($poll[0]->poll_id);    
    $totalpollcnt =$this->totalpollcnt($poll[0]->poll_id);  
    $pollhtml .='   
<div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz-content" id="replace'.$eventtype.$poll[0]->poll_id.'">';  
 foreach($pollper as $keys=>$vals){ 
     if($vals->answer !=''){    
     $percentage = ($vals->cnt/$totalpollcnt->totalcnt)*100;    
      if($vals->poll_answer_id == $uservote){   
         $userclass=' <span class="glyphicon glyphicon-ok"></span>';    
     }else{ 
        $userclass='';  
     }  
     if($percentage <10){   
         $width= '10';  
            
     }else{ 
          $width= $percentage;  
            
     }  
     if($keys == 0){    
         $clor ='success';  
     }elseif($keys == 1){   
          $clor ='info';    
            
     }elseif($keys == 2){   
         $clor ='warning';  
     }  
     elseif($keys == 3){    
          $clor ='danger';  
            
     }  
     elseif($keys == 4){    
        $clor ='least';     
     }  
    $pollhtml .='<strong>'.$vals->answer.'</strong> 
    <div class="progress">  
    <div class="progress-bar progress-bar-'.$clor.'" role="progressbar" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100" style="width:'.$width.'%">  
    <strong>'.$this->network->format_num($vals->usercnt).' vote ('.round($percentage,2).'%)</strong>'.$userclass.'      
    </div>  
  </div>';  
 }  
 }  
    $pollhtml .='</div> 
    ';  
            
        
    }   
    $pollhtml .='<!-- start : poll button download results -->  
    <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12">';    
    if(count($pollanswer) <=0){ 
        $pollhtml .='<a  class="button-vote" onclick="vote('.$eventtype.','.$row->poll_id.')"  data-event ="'.$eventtype.'" data-poll="'.$row->poll_id.'" id="suboption'.$eventtype.$row->poll_id.'"  >Vote</a>';   
        if($poll[0]->user_id == $user->id){ 
        $pollhtml .='<a type="submit"  class="download'.$eventtype.''.$row->poll_id.'" id="suboption'.$eventtype.''.$row->poll_id.'" href="'.$url.'plugin/poll/admin?action=download&poll_id='.$row->poll_id.'" ><button type="submit" class="button-submit-results btn-download-event-child-padding">Download Results</button></a>';   
                
        }   
        
   }else{   
        if($poll[0]->user_id == $user->id){ 
        $pollhtml .='<a type="submit" class="download'.$eventtype.''.$poll[0]->poll_id.'"  id="suboption'.$eventtype.''.$poll[0]->poll_id.'" href="'.$url.'plugin/poll/admin?action=download&poll_id='.$poll[0]->poll_id.'" ><button type="submit" class="button-submit-results btn-download-event-child-padding">Download Results</button></a>';   
                
        }   
   }    
     $pollhtml .='</div>    
    <!-- end : poll button download results --> 
    
    </div>  
    <!-- end - 1st vote poll -->';  
    return $pollhtml;   
            
            
    }   
    public function geolocation($postid){   
         $r      = $this->db2->query('SELECT p.location FROM posts as p 
                                         WHERE p.id="'. $postid . '" ', FALSE); 
        $result = $this->db2->fetch_object($r); 
        return $result->location;   
            
            
    }   
 public function notificationpollchildhtml($parentid,$url,$eventtype)   
    {   
            
        $user->id = $this->user->id;    
            
            
        $poll = $this->replay_is_poll($parentid);   
            
            
        $pollanswer = $this->is_pollanswer($user->id, $poll[0]->poll_id);   
        $uservote  =$this->userpollanswer($user->id,$poll[0]->poll_id); 
        if(count($pollanswer)<=0){  
                $changehtml ='<div>&nbsp;</div>';   
        }else{  
                $changehtml ='<div id="changevote'.$eventtype.''.$poll[0]->poll_id.'" class="pull-right zeropadding box-sub-desc"><a onclick="changeopenion('.$user->id.','.$eventtype.','.$poll[0]->poll_id.','.$parentid.')" ><span class="glyphicon glyphicon-edit"></span> Change Vote</a></div>';  
        }   
            
            $pollhtml ='';  
            $pollhtml .='<!-- start - 1st vote poll --> 
            '.$changehtml.' 
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 poll-list-orange-bg">   
        
    <!-- start : poll title --> 
    <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz-title">   
    <ul class="list-inline">    
    <li><img src="'.$url.'static/images/icon-poll-24.png" class="img-responsive"></li>  
    <li>'.$poll[0]->poll_question.' 
    </li>   
    </ul>   
    </div>      
    <!-- end : poll title -->'; 
    if(count($pollanswer) <=0){ 
        $pollhtml .='<div id="replace'.$eventtype.$poll[0]->poll_id.'">';   
            foreach($poll as $keys=>$row)   
            {   
                if($row->answer!="" && count($pollanswer)<=0)   
                {   
     $pollhtml .='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 zeropadding"> 
    <!-- start : NOTIFICATION CHILD - poll radio -->    
     <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz-content poll-child-radio-margin"> 
    <ul class="list-unstyled poll-radio">   
    <li>    
<input onclick="changeurl('.$row->poll_id.','.$row->poll_answer_id.','.$eventtype.')" id="'.$eventtype.$keys.$row->poll_id.'" type="radio" name="radio11"  class="radios option'.$row->poll_answer_id.' radio'.$row->poll_id.' "/>  
<label for="'.$eventtype.$keys.$row->poll_id.'">&nbsp;</label>'.$row->answer.'</li> 
    
    </ul>   
    </div>  
    <!-- end : poll radio -->   
    </div>';    
        
     }  
                else if($row->answer!="")   
                {   
                    $countpollanswer=$this->is_countpollanswer($row->poll_id,$row->poll_answer_id); 
                        
                }   
            }   
    $pollhtml .='</div>';           
    }else{  
    $pollper =$this->getpercentagesofpollanswers($poll[0]->poll_id);    
    $totalpollcnt =$this->totalpollcnt($poll[0]->poll_id);  
    $pollhtml .='   
<div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12 buzz-content" id="replace'.$eventtype.$poll[0]->poll_id.'">';  
 foreach($pollper as $keys=>$vals){ 
     if($vals->answer !=''){    
     $percentage = ($vals->cnt/$totalpollcnt->totalcnt)*100;    
     if($vals->poll_answer_id == $uservote){    
         $userclass=' <span class="glyphicon glyphicon-ok"></span>';    
     }else{ 
        $userclass='';  
     }  
        
     if($percentage <10){   
         $width= '10';  
            
     }else{ 
          $width= $percentage;  
            
     }  
     if($keys == 0){    
         $clor ='success';  
     }elseif($keys == 1){   
          $clor ='info';    
            
     }elseif($keys == 2){   
         $clor ='warning';  
     }  
     elseif($keys == 3){    
          $clor ='danger';  
            
     }  
     elseif($keys == 4){    
        $clor ='least';     
     }  
    $pollhtml .='<strong>'.$vals->answer.'</strong> 
    <div class="progress">  
    <div class="progress-bar progress-bar-'.$clor.'" role="progressbar" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100" style="width:'.$width.'%">  
     <strong>'.$this->network->format_num($vals->usercnt).' vote('.round($percentage,2).'%)</strong>'.$userclass.'      
    </div>  
  </div>';  
 }  
 }  
    $pollhtml .='</div> 
    ';  
            
        
    }   
    $pollhtml .='<!-- start : poll button download results -->  
    <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12">';    
    if(count($pollanswer) <=0){ 
        $pollhtml .='<a  class="button-vote" onclick="vote('.$eventtype.','.$row->poll_id.')"  data-event ="'.$eventtype.'" data-poll="'.$row->poll_id.'" id="suboption'.$eventtype.$row->poll_id.'"  >Vote</a>';   
        if($poll[0]->user_id == $user->id){ 
        $pollhtml .='<a type="submit" class="download'.$eventtype.''.$poll[0]->poll_id.'"  id="suboption'.$eventtype.''.$row->poll_id.'" href="'.$url.'plugin/poll/admin?action=download&poll_id='.$poll[0]->poll_id.'" ><button type="submit" class="button-submit-results btn-download-event-child-padding">Download Results</button></a>';   
                
        }   
        
   }else{   
        if($poll[0]->user_id == $user->id){ 
        $pollhtml .='<a type="submit" class="download'.$eventtype.''.$poll[0]->poll_id.'"  id="suboption'.$eventtype.''.$row->poll_id.'" href="'.$url.'plugin/poll/admin?action=download&poll_id='.$poll[0]->poll_id.'" ><button type="submit" class="button-submit-results btn-download-event-child-padding">Download Results</button></a>';   
                
        }   
   }    
     $pollhtml .='</div>    
    <!-- end : poll button download results --> 
    
    </div>  
    <!-- end - 1st vote poll -->';  
    return $pollhtml;   
            
            
    }   
        
    public function gethandlerofreplayuser($postid){    
         $r    = $this->db2->query('SELECT users.id as userid,users.avatar as pic, users.username as username FROM posts as p   
                                        inner join users on p.user_id=users.id WHERE p.id="' . $postid . '"  ', FALSE); 
        $result = $this->db2->fetch_object($r); 
        return $result; 
        
            
    }   
    public function predictionhtml($postid){    
        $prediction_data =$this->predictiondata($postid);   
                if(!empty($prediction_data)){   
                $myincorrectintradatahtml ='';  
                if($prediction_data[0]->status =="OPEN"){   
                //calculations for up rate  
                $predict_value = $prediction_data[0]->predict_value;    
                $prediction_base_price = $prediction_data[0]->prediction_base_price;    
                $percentage             =(($predict_value-$prediction_base_price)/($prediction_base_price))*100;    
                $percentage = number_format((float)$percentage, 2, '.', '');    
                    
                if (strpos($percentage, '-') !== false) {   
                    $con ='down';   
                   $imag ='down-arrow-prediction.png';  
                }else{  
                    $con ='up'; 
                    $imag ='up-arrow-prediction.png';   
                }   
                                        
                $myincorrectintradatahtml .='<div class="prediction-buzz-data">'.$prediction_data[0]->asset_name.'($'.$prediction_data[0]->ticker.')<img src="'.$C->SITE_URL.'/static/images/icons/'.$imag.'"> to be '.$con.' by '.$percentage.'% from '.$prediction_data[0]->prediction_base_price.' in '.substr($prediction_data[0]->end_date,0,10).' because of '.$prediction_data[0]->predict_reason.'.</div>'; 
                }else{  
                    //calculations for up rate  
                $predict_result = $prediction_data[0]->predict_result;  
                if($predict_result =='CORRECT'){    
                     $imag ='hit.png';  
                     $type ="Hit";  
                     $percentage='';    
                        
                }else{  
                     $imag ='miss.png'; 
                        
                        
                      $predict_value = $prediction_data[0]->predict_value;  
                      $prediction_base_price = $prediction_data[0]->prediction_base_price;  
                      $percentage             =(($predict_value-$prediction_base_price)/($prediction_base_price))*100;  
                      $percentage = substr(number_format((float)$percentage, 2, '.', ''),1);    
                      $type =" Mis by ".$percentage."%";    
                        
                }   
                if($buff->post_user->id == $user->id){  
                    $handset ='If you want to change Hindsight reason please <a  class="mymodal" data-toggle="modal" data-target="#myModal-'.$prediction_data[0]->post_id.'"  >click here </a>  
    
  <!-- Modal -->    
  <div class="modal fade-'.$prediction_data[0]->post_id.'" id="myModal-'.$prediction_data[0]->post_id.'" role="dialog"> 
    <div class="modal-dialog">  
        
      <!-- Modal content--> 
      <div class="modal-content">   
        <div class="modal-header">  
          <button type="button" class="close" data-dismiss="modal">&times;</button> 
          <h4 class="modal-title">Handset reason </h4>  
        </div>  
        <div class="modal-body">    
        <div class="row">   
         <div>Reason :<input type="text" value="'.$prediction_data[0]->hindsight_reason.'" id="hindsight-'.$prediction_data[0]->post_id.'" onkeyup="validate(this,'.$prediction_data[0]->post_id.')">   
         </div> 
          <div id="handsetreason-error-'.$prediction_data[0]->post_id.'"class="notifyjs-container" style="top: 37px; left: 168px; overflow: hidden; display: hidden;"><div class="notifyjs-bootstrap-base notifyjs-bootstrap-error">    
            <span data-notify-text="" class="notifyjs-text">This field is required</span>   
         </div></div>   
                   <button type="button" class="btn btn-default btn-primary"  data-toggle="modal"  onclick="changehandset('.$prediction_data[0]->post_id.')">Change</button>    
        </div>  
            
        </div>  
        <div class="modal-footer">  
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> 
      </div>    
        
      </div>    
        
    </div>  
  </div>    
    
                        
                    ';  
                }else{  
                    $handset ='';   
                        
                }   
                $myincorrectintradatahtml .='<div style="background-color:#e3f8fe;font-size:12px;height:auto;padding: 10px;"> Your prediction on '.$prediction_data[0]->asset_name.'($'.$prediction_data[0]->ticker.') done  on '.substr($prediction_data[0]->end_date,0,10).' was a '.$type.' <img src="'.$C->SITE_URL.'static/images/icons/'.$imag.'">.'.$handset.'   
                </div>';    
                }   
                echo $myincorrectintradatahtml; 
            
                
        }   
            
    }   
    public function postcomments($postid){  
            
         $r    = $this->db2->query('select ppr.*,u.username,u.avatar from `posts_pr_comments` as ppr inner join users as u ON u.id = ppr.user_id where ppr.post_id="' .$postid . '" ', FALSE);  
         while ($resultwq = $this->db2->fetch_object($r)) { 
            $data[] = $resultwq;    
        }   
        return $data;   
            
            
        
            
    }   
    public function checktype_post($commentid){ 
         $r    = $this->db2->query('select type,data  from `posts_pr_attachments` where post_id="' .$commentid . '" ', FALSE);  
         while ($resultwq = $this->db2->fetch_object($r)) { 
            $data[] = $resultwq;    
        }   
        return $data;   
            
            
    }   
    public function myintradayopendatacalculation($userid)  
    {   
        $status="open"; 
            
            
        $infraselect = $this->db2->query('SELECT count(pd.id) as correctcnt 
        FROM  posts  as p   
        inner join post_dayfeel as pd on p.id= pd.post_id   
        inner join users as u on u.id= p.user_id    
        WHERE   p.user_id="' . $userid . '" AND pd.status ="'.$status.'" ');    
        $result      = $this->db2->fetch_object($infraselect);  
            
        return $result; 
            
            
    }   
    public function is_event($postid){  
             $r    = $this->db2->query('select id  from `event_posts` where post_id="' .$postid . '" ', FALSE); 
             $result = $this->db2->fetch_object($r);    
             return $result;    
            
    }   
     //this functionality for user voted or not 
    public function userpollanswer($userid,$pollid){    
        $user =  $this->db2->fetch_field('SELECT ANSWER_ID  FROM post_poll_votes WHERE POLL_ID="'.$pollid.'" AND VOTER_USER_ID="'.$userid.'" ');    
            return $user;   
            
    }   
     //this functionality for user voted or not 
    public function findoutchild($postid){  
        $user =  $this->db2->fetch_field('SELECT id  FROM post_replay WHERE replay_id="'.$postid.'"  ');    
            return $user;   
    }   
    //this functionality for present market price   
    public function assetmarketpresentprice($assetid){  
        $market_data =  $this->db2->fetch_field('SELECT market_data  FROM  asset_marketetails WHERE ticker="'.$assetid.'" ');   
            return $market_data;    
    }   
        public function attchmentreplaydisplay($postid){    
    global $C;  
    $res =$this->replayattachdata($postid); 
    if(!empty($res[0])){    
            
    foreach($res as $keys=>$vals){  
        //separating images here    
        if(($vals->type =='image')){    
            $newarray = (unserialize($vals->data)); 
            if(!is_array($newarray)){   
                $dataimage[] =$newarray;    
                    
            }else{  
                $dataimage =$newarray;  
            }   
        }   
        //separating links here 
        if(($vals->type =='link')){ 
            $newarray1 = (unserialize($vals->data));    
            if(!is_array($newarray1)){  
                $datalink[] =$newarray1;    
            }else{  
                $datalink =$newarray1;  
            }   
        }   
        //separating youtube videos here    
        if(($vals->type =='videoembed')){   
            $newarray2 = (unserialize($vals->data));    
            if(!is_array($newarray2)){  
                $datavideoembed[] =$newarray2;  
            }else{  
                $datavideoembed =$newarray2;    
            }   
                
        }   
        //separating any text files  here   
        if(($vals->type =='file')){ 
            $newarray3 = (unserialize($vals->data));    
            if(!is_array($newarray3)){  
                $datafile[] =$newarray3;    
            }else{  
                $datafile =$newarray3;  
            }   
                
                
        }   
    }   
        
    
        if(!empty($dataimage)){ 
                  $attachmentimagehtml =''; 
         /*         $attachmentimagehtml .='<div class="images">  
        <div class="list-link-container">'; */
        
        $attachmentimagehtml='
<link rel="stylesheet" href="'.$C->SITE_URL.'themes/FishingEnthusiastTheme/css/w3-slider.css">
<style>
.mySlides {display:none;}
/* Bottom right text */
.bottom-right {
    position: absolute;
    bottom: 20px;
    left: 50%;
    color: white;
    font-size: 22px;
    transform: translate(-50%, -50%);
  
}
</style>
<div class="w3-content w3-display-container">';
        
        
        
        
        $rn = rand(10,100);
            foreach($dataimage as $imagekeys=>$imagevals){  
        /*    $attachmentimagehtml .='    
            <a target="_blank" rel="'.$postid.'" href="'.($C->STORAGE_URL.'attachments/'.$this->network->id.'/'.$imagevals->file_preview).'" class="lightbox-image image-thumb cboxElement">
            
            <img alt="Image" src="'.$C->STORAGE_URL.'attachments/'.$this->network->id.'/'.($imagevals->file_preview).'" />
            
            </a>  
        ';  */
        
        $attachmentimagehtml.='<div class="w3-display-container cmySlides-r'.$postid.'_'.$rn.'">
<img  class="" width="100%" alt="Image" src="'.$C->STORAGE_URL.'attachments/'.$this->network->id.'/'.($imagevals->file_preview).'" />
  <div class="text-center">
  </div>
</div>';
        
            }   
        
        $attachmentimagehtml.='
<button class="w3-button w3-black w3-display-left" onclick="plusDivs'.$postid.'_'.$rn.'(-1)">&#10094;</button>
  <button class="w3-button w3-black w3-display-right" onclick="plusDivs'.$postid.'_'.$rn.'(1)">&#10095;</button>
</div>

<script>
var slideIndex = 1;
showDivs'.$postid.'_'.$rn.'(slideIndex);

function showDivs'.$postid.'_'.$rn.'(n) {
  var i;
  var x = document.getElementsByClassName("cmySlides-r'.$postid.'_'.$rn.'");
  if (n > x.length) {slideIndex = 1}
  if (n < 1) {slideIndex = x.length}
  for (i = 0; i < x.length; i++) {
    x[i].style.display = "none";  
  }
  x[slideIndex-1].style.display = "block";  
}

function plusDivs'.$postid.'_'.$rn.'(n) {
  showDivs'.$postid.'_'.$rn.'(slideIndex += n);
}


</script>';
              
            
            
            
            
            
            
        /*    
             $attachmentimagehtml .='</div> 
        </div>';  */  
        }   
            
            
            
            
            
            
            
            
            
            
            
            
            
            
        if(!empty($datavideoembed)){    
             $attachmentvideoembedhtml ='<div class="links">';  
            foreach($datavideoembed as $videoembedkeys=>$videoembedvals){   
            $attachmentvideoembedhtml .='   
            <div class="youtube-container container">   
        <a data-type="youtube" data-embed="'.htmlspecialchars($videoembedvals->embed_code).'" class="thumb video-youtube">  
        <span class="play-icon"></span> <img src="'.$C->STORAGE_URL.'attachments/'.$this->network->id.'/'.$videoembedvals->file_thumbnail .'" alt="Video thumbnail">    
        </a>    
        <div class="content">   
            <a target="_blank" href="'.$videoembedvals->orig_url.'" class="link-title">"'.$videoembedvals->title.'"</a> 
            <span>"'.$videoembedvals->description.'"</span> 
        </div>  
        <div class="clear"></div>   
        <div class="video-placeholder" style="display: none;"></div>    
        </div>  
        
        ';  
            }   
         $attachmentvideoembedhtml .='</div>';  
                
        }else{  
            $attachmentvideoembedhtml ='';  
        }   
        if(!empty($datafile)){  
            $datafilehtml ='<div class="files">';   
            foreach($datafile as $datafilekeys=>$datafileval){  
                $ext = pathinfo($datafileval->file_original, PATHINFO_EXTENSION);   
                if($ext=='mp4'){    
                    $fileor =$C->SITE_URL.'storage/attachments/'.$this->network->id.'/'.$datafileval->file_original;    
                    $datafilehtml .=' <video controls width="100%" ><source src="'.$fileor.'" type="video/mp4"></video>';   
                }else{  
                    $datafilehtml .='<a class="icon file '.(isset($datafileval->filetype)? $datafileval->filetype : '').'" href="'.$C->SITE_URL.'getfile/pid:'.$datafileval->post_id.'/attid:'.intval($datafilekeys).'" title="'.$datafileval->title.'">'.$datafileval->title.'</a><span class="clear-right"></span>';  
                }   
                    
            }   
            $datafilehtml  .='</div>';  
                
        }   
            
        
        
    $html ='';  
    $html .='<div class="attachments lightbox-enabled">';   
    $html .=$attachmentimagehtml.$attachmentvideoembedhtml.$datafilehtml;   
        
        
$html  .='</div>';  
    }else{  
    $html ='';      
    }   
    unset($dataimage);unset($datalink);unset($datavideoembed);unset($datafile); 
return $html;   
            
            
    }   
public function replayattachdata($postid){  
        
 $r      = $this->db2->query('SELECT * from  posts_attachments  
                                    WHERE post_id="' . $postid . '" ', FALSE);  
$data =array(); 
while($result = $this->db2->fetch_object($r)){  
    $data[] = $result;  
        
}   
return $data;   
        
        
}   
public function usereventsttus($postid){    
        
    $status = $this->db2->fetch_field('SELECT e.status from  event_posts as ep  
    inner join events as e  ON e.id =ep.event_id    
    where ep.post_id="'.$postid.'"  '); 
    return $status; 
        
}   
#new notifications  
public function get_own_user($postid){  
         $r    = $this->db2->query('select user_id  from `posts` where id="' .$postid . '" ', FALSE);   
        $result = $this->db2->fetch_object($r); 
        return $result; 
            
    }   
     public function checkemptyuser($userid){   
         $res   =$this->db2->query('select user_id from users_notif_rules where user_id="' .$userid . '" ', FALSE); 
        return $res;    
            
     }      
     public function checknotrules($user_id,$type){ 
            $user =  $this->db2->fetch_field('SELECT '.$type.'  FROM users_notif_rules WHERE user_id="'.$user_id.'" '); 
            return $user;   
                
    }   
    public function typeofpostofevent($postid){ 
            
        $res    =$this->db2->query('select id from event_posts where post_id="' .$postid . '" ', FALSE);    
        return $res;    
    }   
        public function typeofpostofpoll($postid){  
            
        $res    =$this->db2->query('select poll_id from polls where posts_id="' .$postid . '" ', FALSE);    
        return $res;    
    }   
    public function typelinks($postid){ 
        $res    =$this->db2->query('select type,data from posts_attachments where post_id="' .$postid . '" ', FALSE);   
        $result = $this->db2->fetch_object($res);   
        return $result; 
    }   
    public function insert_active_notifications($ownuserid,$postid,$notifytype,$type,$standrdtype){ 
        $date =time();  
        $this->db2->query('insert into active_notifications  values ("","","'. $this->user->id .'","'.$ownuserid.'","'.$postid.'","'.$notifytype.'","'.$type.'","'.$date.'")'); 
        $groupid =0;    
        $notif_object_type ='post'; 
        $notif_object_id =$this->user->id;  
                        
        $this->db2->query('insert into notifications  (notif_type, to_user_id, in_group_id, from_user_id,notif_object_type,notif_object_id,date,noti_postid) values  ("'.$standrdtype.'","'. $ownuserid .'","'.$groupid.'","'.$this->user->id.'","'.$notif_object_type.'","'.$notif_object_id.'","'.$date.'","'.$postid.'")');  
       //user existing in user dashboard tabs   
        $userdash =$this->userdashboardtabsuser($ownuserid);    
            
        if(!empty($userdash)){  
            $newpost = $userdash+1; 
            $tab ='notifications';  
            $this->db2->query('update users_dashboard_tabs set  newposts="'.$newpost.'" WHERE user_id="'.$ownuserid.'" AND tab="'.$tab.'" ');   
                
        }else{  
            $tab ="notifications";  
             $state = 1;    
             $this->db2->query('insert into users_dashboard_tabs  values ("'.$ownuserid.'","'. $tab .'","'.$state.'","'.$state.'")');   
                
        }   
        
    }   
    public function userdashboardtabsuser($user_id){    
          $notifytype='notifications';  
            $user =  $this->db2->fetch_field('SELECT newposts  FROM users_dashboard_tabs WHERE user_id="'.$user_id.'" AND tab="'.$notifytype.'"  ');    
            return $user;   
                
    }   
    public function updatechatdate($senderid,$receiverid){  
        $db_date        = time();   
        $pid = $this->db2->fetch_field('SELECT id FROM  posts_pr  WHERE user_id="'.$senderid.'" AND to_user="'.$receiverid.'" order by id desc limit 1');   
        if(!empty($pid)){   
        $this->db2->query('update posts_pr set last_chatdate="'.$db_date.'" WHERE id="'.$pid.'"  ');    
        }   
            
    }   
    public function get_profile_likes($userid){ 
            
        $r = $this->db2->query('select u.id,u.username ,u.avatar from  user_favourites  as uf   
        inner join users as u ON u.id = uf.who  
        where uf.whom="'.$userid.'" '); 
        $data =array(); 
        while($result = $this->db2->fetch_object($r)){  
            $data[] = $result;  
                
        }   
        return $data;   
            
    }   
        public function myintradayopendata($userid, $offset)    
    {   
        $from = $offset;    
        $upto = 10; 
            
            
            
        $infraselect = $this->db2->query('SELECT u.id as userid,u.avatar,u.username,pd.id,pd.post_id,pd.ticker,pd.updated_date,pd.predicted_price,pd.stoploss_price,pd.current_price,pd.result,pd.status,p.date,p.message   
        FROM  posts  as p   
        inner join post_dayfeel as pd on p.id= pd.post_id   
        inner join users as u on u.id= p.user_id    
        WHERE   p.user_id="' . $userid . '" AND result is null  AND p.post_level is null group by p.date_lastcomment desc,pd.post_id order by pd.id desc limit ' . $from . ' ,' . $upto . ' '); 
        while ($result = $this->db2->fetch_object($infraselect)) {  
            $data[] = $result;  
        }   
            
        return $data;   
            
            
    }   
     public function myopendatacalculation($userid) 
    {   
            
            
        $infraselect = $this->db2->query('SELECT count(pd.id) as correctcnt 
        FROM  posts  as p   
        inner join post_dayfeel as pd on p.id= pd.post_id   
        inner join users as u on u.id= p.user_id    
        WHERE   p.user_id="' . $userid . '" AND result is null ');  
        $result      = $this->db2->fetch_object($infraselect);  
            
        return $result; 
    }   
    public function assetopen($postid)  
    {   
        $aquerysset = $this->db2->query('SELECT id,ticker,predicted_price,stoploss_price,final_price,current_price,status,result FROM  post_dayfeel WHERE post_id="' . $postid . '" AND result is null ', FALSE);   
        while ($result = $this->db2->fetch_object($aquerysset)) {   
            $data[] = $result;  
        }   
            
        return $data;   
            
    }   
    public function findlink($postid){  
            
        $type="link";   
        $event    =$this->is_event($postid);    
       if(empty($event)){   
        $link = $this->db2->fetch_field('SELECT data  FROM  posts_attachments  WHERE post_id="'.$postid.'"  AND type="'.$type.'"'); 
       }else{   
           $link ='';   
            
       }    
        return $link;   
            
            
    }   
    public function linkhtml($postid){  
        $type="link";   
        global $C;  
            $aquerysset = $this->db2->query('SELECT data FROM  posts_attachments WHERE post_id="' . $postid . '" AND type="'.$type.'" ', FALSE);    
                
            $results = $this->db2->fetch_object($aquerysset);   
            $finddata = (unserialize($results->data));  
            if(is_object($finddata)){   
                $linkhtml ='';  
             $select = $this->db2->query('SELECT data FROM  posts_attachments WHERE post_id="' . $postid . '" AND type="'.$type.'" ', FALSE);   
             while ($result = $this->db2->fetch_object($select)) {  
                 $unserializedata = (unserialize($result->data));   
                    
                 $link =(isset($unserializedata->link))? $unserializedata->link:''; 
                 $title =(isset($unserializedata->title))? $unserializedata->title:'';  
                 $description =(isset($unserializedata->description))?$unserializedata->description:''; 
                 $mainurl =(isset($unserializedata->mainurl))?$unserializedata->mainurl:''; 
                 $image =$unserializedata->image;   
                 if(!empty($image)){    
                        $imagetag ='<a target="_blank" href="'.$link.'" class="thumb"><img src="'.$C->STORAGE_URL.'/attachments/1/'.$image.'"></a>';    
                 }else{ 
                    $imagetag ='';  
                 }  
                 $linkhtml .='<div class="container-buzzurl-timeline col-md-12 col-lg-12" style="width:100%">   
    <div class="col-md-3 col-md-3 col-lg-3">    
    '.$imagetag.'   
</div>  
    <div class=" col-md-9 col-lg-9">    
        <a target="_blank" href="'.$link.'" class="link-title">'.$title.'</a>   
        <div class="desc">  
        '.$description.'    
        </div>  
        <div class="buzzurl"><a target="_blank" href="'.$link.'" class="thumb">'.$mainurl.'</a></div>   
    </div>  
    <div class="clear"></div>   
</div>';    
                    
            }   
                
                
                    
        }   
        if(is_array($finddata)){    
             $linkhtml = $this->timelinelinkhtml($postid);  
            }   
            
return $linkhtml;   
            
            
    }   
    public function timelinelinkhtml($postid){  
        $type="link";   
        global $C;  
            $aquerysset = $this->db2->query('SELECT data FROM  posts_attachments WHERE post_id="' . $postid . '" AND type="'.$type.'" ', FALSE);    
                
                
            $newlink ='';   
            if($aquerysset->num_rows > 0){  
             while ($result = $this->db2->fetch_object($aquerysset)) {  
                 $unserializedata = (unserialize($result->data));   
                    }   
                for($i=0;$i<count($unserializedata);$i++){  
                        
                 $link =(isset($unserializedata[$i]->link))? $unserializedata[$i]->link:''; 
                 $title =(isset($unserializedata[$i]->title))? $unserializedata[$i]->title:'';  
                 $description =(isset($unserializedata[$i]->description))?$unserializedata[$i]->description:''; 
                 $mainurl =(isset($unserializedata[$i]->mainurl))?$unserializedata[$i]->mainurl:''; 
                 $image =$unserializedata[$i]->image;   
                 if(!empty($image)){    
                        $imagetag ='<a target="_blank" href="'.$link.'" class="thumb"><img src="'.$C->STORAGE_URL.'/attachments/1/'.$image.'"></a>';    
                 }else{ 
                    $imagetag ='';  
                 }  
                 $newlink .='<div class="container-buzzurl-timeline col-md-12 col-lg-12" style="width:100%">    
    <div class="col-md-3 col-md-3 col-lg-3">    
    '.$imagetag.'   
</div>  
    <div class=" col-md-9 col-lg-9">    
        <a target="_blank" href="'.$link.'" class="link-title">'.$title.'</a>   
        <div class="desc">  
        '.$description.'    
        </div>  
        <div class="buzzurl"><a target="_blank" href="'.$link.'" class="thumb">'.$mainurl.'</a></div>   
    </div>  
    <div class="clear"></div>   
</div>';    
                }   
                    }else{  
                        $newlink  ='';  
                            
                    }   
            
return $newlink;    
            
            
    }   
    public function findcountmentions($userid){ 
        $tab ='@me';    
        $res = $this->db2->fetch_field('SELECT (ub.newposts)  as findcnt FROM users_dashboard_tabs as ub where user_id="'.$userid.'" AND tab="'.$tab.'"');  
        if( $res > 0){  
            return $res;    
                
        }else{  
            return 0;   
        }   
            
            
    }   
        
                public function attchmentreplaydisplayfortags($postid){ 
    global $C;  
    $res =$this->replayattachdata($postid); 
    if(!empty($res[0])){    
            
    foreach($res as $keys=>$vals){  
        //separating images here    
        if(($vals->type =='image')){    
            $newarray = (unserialize($vals->data)); 
            if(!is_array($newarray)){   
                $dataimage[] =$newarray;    
                    
            }else{  
                $dataimage =$newarray;  
            }   
        }   
        //separating links here 
        if(($vals->type =='link')){ 
            $newarray1 = (unserialize($vals->data));    
            if(!is_array($newarray1)){  
                $datalink[] =$newarray1;    
            }else{  
                $datalink =$newarray1;  
            }   
        }   
        //separating youtube videos here    
        if(($vals->type =='videoembed')){   
            $newarray2 = (unserialize($vals->data));    
            if(!is_array($newarray2)){  
                $datavideoembed[] =$newarray2;  
            }else{  
                $datavideoembed =$newarray2;    
            }   
                
        }   
        //separating any text files  here   
        if(($vals->type =='file')){ 
            $newarray3 = (unserialize($vals->data));    
            if(!is_array($newarray3)){  
                $datafile[] =$newarray3;    
            }else{  
                $datafile =$newarray3;  
            }   
                
                
        }   
    }   
        
    
        if(!empty($dataimage)){ 
                    
            foreach($dataimage as $imagekeys=>$imagevals){  
                if($imagevals !=''){  
                  
               
                $imageurl[] = $C->STORAGE_URL.'attachments/'.$this->network->id.'/'.$imagevals->file_preview;  
                 
                $type[]='image';    
                    
}   
            
            }   
                
        }   
        if(!empty($datafile)){  
            
            foreach($datafile as $datafilekeys=>$datafileval){  
                $ext = pathinfo($datafileval->file_original, PATHINFO_EXTENSION);   
                if($ext=='mp4'){    
                        
                    $imageurl[] =$C->SITE_URL.'storage/attachments/'.$this->network->id.'/'.$datafileval->file_original;    
                     $type[]='video';   
                    
            }   
                
        }   
            
        
        
        
        
        
    }   
$data['url'] = $imageurl[0];    
$data['type'] = $type[0];   
return $data;   
            
    }   
            }   
                
            public function tagmessage($postid){    
        
 $r      = $this->db2->query('SELECT message,title,posttype from  posts    
                                    WHERE id="' . $postid . '" ', FALSE);   
$data =array(); 
while($result = $this->db2->fetch_object($r)){  
    $data[] = $result;  
        
}   
return $data;   
       
        
}   





        public function post_detail($postid){    
        
 $r= $this->db2->query('SELECT * from  posts     WHERE id="' . $postid . '" ');   
$result = $this->db2->fetch_object($r); 

 $user_id=$result->user_id; 
      
      $r1= $this->db2->query('SELECT * from  users  WHERE id="' . $user_id . '" ');   
        $row = $this->db2->fetch_object($r1);  
        
        if($row->is_reporter=="1"){
            
        $r2= $this->db2->query('SELECT * from  sb_reporter_coverage_language  WHERE user_id="' . $user_id . '" ');   
        $ro= $this->db2->fetch_object($r2); 
        
        if($ro->language_id){
        $language_id=$ro->language_id;
        $r3= $this->db2->query('SELECT * from  sb_languages  WHERE id="' . $language_id . '" ');   
        $res= $this->db2->fetch_object($r3); 
        if($res->iso_key){
        $data=$res->iso_key;
        }else{
            $data='hi';
        }
        }else{
            $data='hi';
        }
        }else{
             $data='hi';
        }

return $data; 

//se
       
        
}  

public function meta_title_detail($postid){    
    $r= $this->db2->query('SELECT * from  posts     WHERE id="' . $postid . '" ');   
$result = $this->db2->fetch_object($r); 

 $user_id=$result->user_id; 
      
      $r1= $this->db2->query('SELECT * from  users  WHERE id="' . $user_id . '" ');   
        $row = $this->db2->fetch_object($r1);  
    $data = $row->fullname.' '.$result->title;
    return $data;
}
public function check_location_id($userId){  
     $r= $this->db2->query('SELECT * from  users     WHERE id="' . $userId . '" '); 
     $result = $this->db2->fetch_object($r); 

 $location_id=$result->location_id;
     
    return $location_id;
}
public function page_title_detail($postid){  
     $r= $this->db2->query('SELECT * from  posts     WHERE id="' . $postid . '" ');   
$result = $this->db2->fetch_object($r); 

 $user_id=$result->user_id; 
      
      $r1= $this->db2->query('SELECT * from  users  WHERE id="' . $user_id . '" ');   
        $row = $this->db2->fetch_object($r1);  
        $url = $_SERVER['REQUEST_URI'];
         $data = $result->title.' '.$row->fullname;
          
    return $data;
}
public function post_detail_new($postid){  
     $r= $this->db2->query('SELECT * from  posts     WHERE id="' . $postid . '" ');   
    $result = $this->db2->fetch_object($r); 
    return $result;
}
public function user_location($postid){  
     $r= $this->db2->query('SELECT * from  posts     WHERE id="' . $postid . '" ');   
$result = $this->db2->fetch_object($r); 
 $user_id=$result->user_id; 
     
      $r1= $this->db2->query('SELECT * from  users  WHERE id="' . $user_id . '" ');  
       $r2= $this->db2->query('SELECT * from  sb_reporter_coverage_category  WHERE user_id="' . $user_id . '" ');  
        $row = $this->db2->fetch_object($r1);
        $row1 = $this->db2->fetch_object($r2);
        
        $category_id=$row1->category_id; 
        
        $category = '';
            $r3= $this->db2->query('SELECT * from  categeory_master  WHERE cat_id="' . $category_id . '" ');
            $row2 = $this->db2->fetch_object($r3);
            $category = $row2->cat_name;
         $data = $row->location.', '.$category;
          
    return $data;
}
public function news_meta_tags($postid){  
     $r= $this->db2->query('SELECT * from  posts     WHERE id="' . $postid . '" ');   
$result = $this->db2->fetch_object($r); 
 $user_id=$result->user_id; 
     
      $user_q= $this->db2->query('SELECT * from  users  WHERE id="' . $user_id . '" ');  
       $category_q= $this->db2->query('SELECT * from  sb_reporter_coverage_category  WHERE user_id="' . $user_id . '" ');  
        $user_row = $this->db2->fetch_object($user_q);
        $category_row = $this->db2->fetch_object($category_q);
        
        $category_id=$category_row->category_id; 
        
        $category = '';
            $master_category_q= $this->db2->query('SELECT * from  categeory_master  WHERE cat_id="' . $category_id . '" ');
            $master_category_row = $this->db2->fetch_object($master_category_q);
            $category = $master_category_row->cat_name;
         
            
            
         $data = $user_row->location.', '.$category;
          
    return $data;
}
public function addviews($postid){  
      $ipaddr =$_SERVER['REMOTE_ADDR']; 
        $this->db2->query('insert into  post_views_list  (post_id, ip_addr) values  ("'.$postid.'","'. $ipaddr.'")');   
        
    }   
     public function is_post_view_cnt($postid)  
    {   
        $r   = $this->db2->query('SELECT pa.cnt FROM post_views_list as pa WHERE  pa.post_id="' . $postid . '" ');  
        $res = $this->db2->fetch_object($r);    
        return $res;    
            
            
    }   
     public function checkadexist($post_userid) 
    {   
       $adstype = 2;    
         $r   = $this->db2->query('SELECT ai.id,ai.sort_image,ai.big_image,ai.display_url,ai.contact_number,ai.ads_access_source,ai.whatsapp_number,ai.callnow_number,ai.ad_display_type FROM ads_tags as at   
         left join ads_info as ai ON at.ad_id=ai.id WHERE  at.user_id="' . $post_userid . '" AND ai.ads_type="'.$adstype.'" AND ai.status=1 limit 1 '); 
            
        $res = $this->db2->fetch_object($r);    
        return $res;    
    }   
     public function checkcommercialadds($post_userid,$postdate)    
    {   
        $adstype = 1;   
        
       /*  $r   = $this->db2->query('SELECT ai.id,ai.sort_image,ai.big_image,ai.display_url,ai.contact_number,ai.ads_access_source,ai.whatsapp_number,ai.callnow_number,ai.ad_display_type FROM ads_tags as at   
         left join ads_info as ai ON at.ad_id=ai.id WHERE  at.user_id="' . $post_userid . '" AND ai.status=1 AND ai.start_date < "'.$postdate.'" AND ai.end_date > "'.$postdate.'"   AND ai.ads_type="'.$adstype.'" '); */
          $r   = $this->db2->query('SELECT ai.id,ai.sort_image,ai.big_image,ai.display_url,ai.contact_number,ai.ads_access_source,ai.whatsapp_number,ai.callnow_number,ai.ad_display_type FROM ads_tags as at   
         left join ads_info as ai ON at.ad_id=ai.id WHERE  at.user_id="' . $post_userid . '" AND ai.status=1 AND ai.end_date > "'.$postdate.'"   AND ai.ads_type="'.$adstype.'" ');
        
       while( $res = $this->db2->fetch_object($r)){ 
           $fetchres[] = $res;  
            
       }    
        return $fetchres;   
     }  
      public function checkparagaphexist1($post_userid) 
    {   
        $adstype = 3;   
         $r   = $this->db2->query('SELECT ai.id,ai.sort_image,ai.big_image,ai.display_url,ai.contact_number,ai.ads_type,ai.ads_access_source,ai.whatsapp_number,ai.callnow_number,ai.ad_display_type FROM ads_tags as at   
         left join ads_info as ai ON at.ad_id=ai.id WHERE  at.user_id="' . $post_userid . '" AND ai.status=1 AND ai.ads_type="'.$adstype.'"  limit 1 ');    
      $res = $this->db2->fetch_object($r);  
        return $res;    
        
            
    }   
      public function checkparagaphexist2($post_userid) 
    {   
         $adstype1=4;   
         $r   = $this->db2->query('SELECT ai.id,ai.sort_image,ai.big_image,ai.display_url,ai.contact_number,ai.ads_type,ai.ads_access_source,ai.whatsapp_number,ai.callnow_number,ai.ad_display_type FROM ads_tags as at   
         left join ads_info as ai ON at.ad_id=ai.id WHERE  at.user_id="' . $post_userid . '" AND ai.status=1 AND ai.ads_type="'.$adstype1.'"  limit 1 ');   
      $res = $this->db2->fetch_object($r);  
        return $res;    
        
            
    }   
    public function checkparagaphexist3($post_userid)   
    {   
         $adstype1=5;   
         $r   = $this->db2->query('SELECT ai.id,ai.sort_image,ai.big_image,ai.display_url,ai.contact_number,ai.ads_type,ai.ads_access_source,ai.whatsapp_number,ai.callnow_number,ai.ad_display_type FROM ads_tags as at   
         left join ads_info as ai ON at.ad_id=ai.id WHERE  at.user_id="' . $post_userid . '" AND ai.status=1 AND ai.ads_type="'.$adstype1.'"  limit 1 ');   
      $res = $this->db2->fetch_object($r);  
        return $res;    
        
            
    }   
    public function dialphonehtml($mobile,$adid,$postid){ 
        $display_url ="tel:".$mobile;   
        $new_html = "\n<a class='adsaction adimagehove' data-customadid=".$adid." data-custompostid=".$postid." style='font-size:12px;background-color:#075e54;font-size: 12px;
    background-color: #25d366;
    color: white;
    border-color: #25d366;padding: 5px; position: absolute;
  top: 60%;
  left: 50%;
  transform: translate(-50%, -50%);
  -ms-transform: translate(-50%, -50%);
  background-color: #555;
  color: white;
  font-size: 16px;
  padding: 12px 24px;
  border: none;
  cursor: pointer;
  border-radius: 5px;
  text-align: center    ' href='".$display_url."'>Call Now</a>\n";  
         return $new_html;  
    }   
     public function whatsuphtml($mobile,$adid,$postid){  
          $display_url ="https://api.whatsapp.com/send?phone=".$mobile."&text=Hello";   
        $new_html = "\n<a class='adsaction adimagehove' data-customadid=".$adid." data-custompostid=".$postid." style='font-size:12px;background-color:#075e54;font-size: 12px;
    background-color: #25d366;
    color: white;
    border-color: #25d366;padding: 5px; position: absolute;
  top: 60%;
  left: 50%;
  transform: translate(-50%, -50%);
  -ms-transform: translate(-50%, -50%);
  background-color: #555;
  color: white;
  font-size: 16px;
  padding: 12px 24px;
  border: none;
  cursor: pointer;
  border-radius: 5px;
  text-align: center' href='".$display_url."'>WhatsApp</a>\n";   
         return $new_html;  
    }   
    public function knowmorehtml($url,$adid,$postid){ 
        $new_html = "\n <a class='adsaction adimagehove' data-customadid=".$adid." data-custompostid=".$postid."  target= '_blank' style='font-size:12px;
    color: #00BFFF;
    border-color: #00BFFF;    border-radius: 13px;border: 1px solid;
    padding: 5px; position: absolute;
  margin-top:-200px;
  left: 50%;
  transform: translate(-50%, -50%);
  -ms-transform: translate(-50%, -50%);
  background-color: #555;
  color: white;
  font-size: 16px;
  padding: 12px 24px;
  border: none;
  cursor: pointer;
  border-radius: 5px;
  text-align: center' href='".$url."'>Know More</a>\n";    
         return $new_html;  
        
    }   
     public function checkposttype($postid) 
    {   
         $r   = $this->db2->query('SELECT p.posttype from posts as p WHERE  p.id="' . $postid . '"  limit 1 '); 
      $res = $this->db2->fetch_object($r);  
        return $res;    
        
            
    }   
    
    
        public function get_thumb($postid) 
    {   
         $r   = $this->db2->query('SELECT p.thumb from posts as p WHERE  p.id="' . $postid . '"  limit 1 '); 
      $res = $this->db2->fetch_object($r);  
        return $res;    
        
            
    }  
    
    
    
    
     public function createLongreadElements($key,$value)    
    {   
        $str ='';   
        if($key == "h3"){   
          $str ="<h3>". $value."</h3>";     
                
        }   
        if($key == "h2"){   
          $str ="<h2>". $value."</h2>";     
                
        }   
        if($key == "h1"){   
          $str ="<h1>". $value."</h1>";     
                
        }   
        if($key == "break"){    
          $str ="<br />";   
                
        }   
         if($key == "text"){    
          $str ="<p>". $value."</p>";   
                
        }   
        if($key == "bold"){ 
          $str ="<strong>". $value."</strong>";     
                
        }   
        if($key == "italics"){  
          $str ="<em>". $value."</em>";     
                
        }   
        if($key == "bold_italics"){ 
          $str ="<strong><em>". $value."</em></strong>";    
                
        }   
         if($key == "bold_italics"){    
          $str ="<strong><em>". $value."</em></strong>";    
                
        }   
        if($key == "img"){  
            $src = $value['src'];   
            $id = $value['id']; 
           
           $str = '<img src="'.$src.'" id="'.$id.'" uploading="true" width="100%"> ';   
            
        }   
        return $str;    
            
            
            
        
            
    }   
    public function checkparagaphexist4($post_userid)   
    {   
         $adstype1=6;   
         $r   = $this->db2->query('SELECT ai.id,ai.sort_image,ai.big_image,ai.display_url,ai.contact_number,ai.ads_type,ai.ads_access_source,ai.whatsapp_number,ai.callnow_number,ai.ad_display_type FROM ads_tags as at   
         left join ads_info as ai ON at.ad_id=ai.id WHERE  at.user_id="' . $post_userid . '" AND ai.status=1 AND ai.ads_type="'.$adstype1.'"  limit 1 ');   
      $res = $this->db2->fetch_object($r);  
        return $res;    
        
            
    }   
     public function checkparagaphexist5($post_userid)  
    {   
         $adstype1=7;   
         $r   = $this->db2->query('SELECT ai.id,ai.sort_image,ai.big_image,ai.display_url,ai.contact_number,ai.ads_type,ai.ads_access_source,ai.whatsapp_number,ai.callnow_number,ai.ad_display_type FROM ads_tags as at   
         left join ads_info as ai ON at.ad_id=ai.id WHERE  at.user_id="' . $post_userid . '" AND ai.status=1 AND ai.ads_type="'.$adstype1.'"  limit 1 ');   
      $res = $this->db2->fetch_object($r);  
        return $res;    
        
            
    }   
    
        
          public function photo_storyview($postid)
           {
               
$data='<style>
* {box-sizing: border-box}
body {font-family: Verdana, sans-serif; margin:0}
.mySlides {display: none}
img {vertical-align: middle;}

/* Slideshow container */
.slideshow-container {
  max-width: 1000px;
  position: relative;
  margin: auto;
}

/* Next & previous buttons */
.prev, .next {
  cursor: pointer;
  position: absolute;
  top: 50%;
  width: auto;
  padding: 16px;
  margin-top: -22px;
  color: white;
  font-weight: bold;
  font-size: 18px;
  transition: 0.6s ease;
  border-radius: 0 3px 3px 0;
  user-select: none;
}

/* Position the "next button" to the right */
.next {
  right: 0;
  border-radius: 3px 0 0 3px;
}

/* On hover, add a black background color with a little bit see-through */
.prev:hover, .next:hover {
  background-color: rgba(0,0,0,0.8);
}

/* Caption text */
.text {
  color: #f2f2f2;
  font-size: 15px;
  padding: 8px 12px;
  position: absolute;
  bottom: 8px;
  width: 100%;
  text-align: center;
}

/* Number text (1/3 etc) */
.numbertext {
  color: #f2f2f2;
  font-size: 12px;
  padding: 8px 12px;
  position: absolute;
  top: 0;
}

/* The dots/bullets/indicators */
.dot {
  cursor: pointer;
  height: 15px;
  width: 15px;
  margin: 0 2px;
  background-color: #bbb;
  border-radius: 50%;
  display: inline-block;
  transition: background-color 0.6s ease;
}

.active, .dot:hover {
  background-color: #717171;
}

/* Fading animation */
.fade {
  -webkit-animation-name: fade;
  -webkit-animation-duration: 1.5s;
  animation-name: fade;
  animation-duration: 1.5s;
}

@-webkit-keyframes fade {
  from {opacity: .4} 
  to {opacity: 1}
}

@keyframes fade {
  from {opacity: .4} 
  to {opacity: 1}
}

/* On smaller screens, decrease text size */
@media only screen and (max-width: 300px) {
  .prev, .next,.text {font-size: 11px}
}
</style>
</head>
<body>

<div class="slideshow-container">

<div class="mySlides fade" >
  <div class="numbertext">1 / 3</div>
  <img src="https://specials-images.forbesimg.com/imageserve/5d3703e2f1176b00089761a6/960x0.jpg?cropX1=836&amp;cropX2=5396&amp;cropY1=799&amp;cropY2=3364" style="width:100%">
  <div class="text">Caption Text</div>
</div>

<div class="mySlides fade">
  <div class="numbertext">2 / 3</div>
  <img src="https://specials-images.forbesimg.com/imageserve/5d3703e2f1176b00089761a6/960x0.jpg?cropX1=836&amp;cropX2=5396&amp;cropY1=799&amp;cropY2=3364" style="width:100%">
  <div class="text">Caption Two</div>
</div>

<div class="mySlides fade">
  <div class="numbertext">3 / 3</div>
  <img src="https://specials-images.forbesimg.com/imageserve/5d3703e2f1176b00089761a6/960x0.jpg?cropX1=836&amp;cropX2=5396&amp;cropY1=799&amp;cropY2=3364" style="width:100%">
  <div class="text">Caption Three</div>
</div>

<a class="prev" onclick="plusSlides(-1)">&#10094;</a>
<a class="next" onclick="plusSlides(1)">&#10095;</a>

</div>
<br>

<div style="text-align:center">
  <span class="dot" onclick="currentSlide(1)"></span> 
  <span class="dot" onclick="currentSlide(2)"></span> 
  <span class="dot" onclick="currentSlide(3)"></span> 
</div>

<script>
var slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
  showSlides(slideIndex += n);
}

function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  var i;
  var slides = document.getElementsByClassName("mySlides");
  var dots = document.getElementsByClassName("dot");
  if (n > slides.length) {slideIndex = 1}    
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";  
  }
  for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";  
  dots[slideIndex-1].className += " active";
}
</script>';
              
//return $data;           
           }    
        
                
              public function posttype($postid){

     
    
 $r      = $this->db2->query('SELECT posttype  FROM  posts
          WHERE id="'.$postid.'"');

while($result = $this->db2->fetch_object($r)){
    $data= $result->posttype;
}
return $data;

    }   
    
             
              public function share_count($postid){

     
    
 $r      = $this->db2->query('SELECT shares  FROM  posts_details
          WHERE post_id="'.$postid.'"');

$rows=$r->num_rows;

if($rows>0){
while($result = $this->db2->fetch_object($r)){
    $data= $result->shares;
}
return $data;
}

else {
    return 0;

}

    }   
    
    
    
    public function image_caption($attachmentid){


 $r      = $this->db2->query('SELECT content  FROM  posts_attachments
          WHERE id="'.$attachmentid.'"');

while($result = $this->db2->fetch_object($r)){
    $data= $result->content;
}

return $data;

}


   public function video_caption($attachmentid){


 $r      = $this->db2->query('SELECT content  FROM  posts_attachments
          WHERE post_id="'.$attachmentid.'"');

while($result = $this->db2->fetch_object($r)){
    $data= $result->content;
}
return $data;

    }
    
  
   public function getvideoid($attachmentid){


 $r      = $this->db2->query('SELECT video_id  FROM  posts_attachments
          WHERE post_id="'.$attachmentid.'"');

while($result = $this->db2->fetch_object($r)){
    $data= $result->video_id;
}
return $data;

    }   
    
       public function getvideourl($attachmentid){


 $r = $this->db2->query('SELECT video_url  FROM  posts_attachments
          WHERE post_id="'.$attachmentid.'"');

while($result = $this->db2->fetch_object($r)){
    $data= $result->video_url;
}
return $data;

    }   
    
      public function displayadscontentmedia($ad_display_type,$display_url,$displayimage){
        if(isset($ad_display_type) && $ad_display_type == "video"){
              $paraads1   ="\n <a class='adslink'  href='".$display_url."' target='_blank'><video controls muted  width='100%' autoplay='autoplay'>
                               <source src='".$displayimage."' type='video/mp4'>

                           </video></a> \n";
        }else{
            $paraads1 = "\n <a class='adslink'  href='".$display_url."'><img width=100%' src='".$displayimage."'></a> \n";
        }
        return $paraads1;
        
    }
         
        public function get_poll_image($postid){
            
             $r      = $this->db2->query("SELECT `data`,`type` FROM `posts_attachments` where post_id=".$postid, FALSE);    
        $result = $this->db2->fetch_object($r); 
        return $result; 
        
        
        
             

         } 
         
        public function get_video($postid){
            
             $r      = $this->db2->query("SELECT `data`,`type` FROM `posts_attachments` where post_id=".$postid, FALSE);    
        $result = $this->db2->fetch_object($r); 
        return $result; 
        
        
        
             

         }
          public function getpollquestion($postid){   
             
        
 $r      = $this->db2->query('SELECT poll_question from  polls    
                                    WHERE posts_id="' . $postid . '" ', FALSE);   
$data =array(); 
while($result = $this->db2->fetch_object($r)){  
    $data[] = $result;  
        
}   
return $data;   
        
        
}   
         
         
         
        
}   
?>  
