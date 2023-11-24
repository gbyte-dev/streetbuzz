// Sharetronix Activities namespace
var Activities = function () {

    // --- declare private methods --- //

    var checknewTimeoutId = null;
    var checknewInterval = 15000;
    var pageTitle = '';
    
    function showLoading() { $('.activity-feed').find('.loading-container:first').show().css('left', '0'); }

    function hideLoading() { $('.activity-feed').find('.loading-container:first').hide(); }

    function postSuccess(response, context) {
        if(response.status == 'ERROR'){
            return;
        }
        var switc = $("#switch").val();
        $("#slide").html('');
        $("#slide1").hide();
        
        var editor = $(context).parents('.data-content-placeholder').find('.htmlarea textarea');
        var activityContent = $(response.html).css('display', 'none');
        var counter = $(editor).parents('.status-editor-container').find('.characters-counter');
        counter.text(counter.data('value'));
        
        if ($('.noposts').length > 0) $('.noposts').fadeOut();

        $('.activity-feed-list').prepend(activityContent);

        if ($('.new-activities-count').length > 0) {
            $('.new-activities-count').after(activityContent);
        }

        
        Htmlarea.reset(editor, 'activity');
        activityContent.animate({ height: 'show' });
        activityContent.find('.activity').effect('highlight', {}, 3000);

        
        //STX.colorboxInit(activityContent);
        STX.colorboxInit();
        Attachments.updateToken($(context).parents('.data-content-placeholder'), 'status');
        
        //$('#last_activity').val(response.inserted_activities_id)
        
        if ($('#activities_type').length > 0 && $('#last_activity').length > 0) 
            resetChecknewTimer();
        
        /*if($('.activity-feed-list .activity-feed-list').css('display') == 'none'){
            $('.activity-feed-list .activity-feed-list').hide();
        }*/
        
        if($('.attachment-group-field-container').length > 0){
            $('.attachment-group-field').val('');
             $('.attachment-group-field').val('');
            $('#googlecity').val('');
            $('#imagecnt').val('');
            $(".group-att").html('');
            $(".txt-group").hide();
            $("#pageurlpage").val("");

            $(".deletegrpforhome").css("display","none");

            $(".nicEdit-main").html('');
            
            //$('.attachment-button.group').css('background-image', 'url('+siteurl+'/static/images/GROUP.png)');
           // $('.attachment-group-field-container').find('.attachment-group-field').css('color', 'black');
            $('.attachment-button.group').html('<span class="group attachment-button"><img class="grayscale-mob visible-xs" src="'+siteurl+'static/images/icons/GROUP-mob.png" title="Create Group"><img class="grayscale hidden-xs" src="'+siteurl+'static/images/icons/GROUP.png" title="Create Group"><span class="tooltip"><span>Post in Group</span></span></span>');
        }
        
    }
    
    function editSuccess(response, context) {
        if(response.status == 'ERROR'){
            commandFail(response, '');
            return;
        }
        STX.showMessage('Post edited successfully', "success");
        
        var post_selector = $(context).parents('.activity-footer').siblings('.activity-content');
        var text = $(post_selector).children('textarea').val();
        $(post_selector).children('textarea').remove();
        $(post_selector).text(text);
        
        $('#cancel_editing').remove();
        
        $(context).replaceWith('<a href="" class="edit_post" data-role="services" data-namespace="activities" data-action="show_edit_box" data-posttype="'+ posttype +'" data-postid="'+ postid +'">Edit post</a>');
    }

    
    function deleteSuccess(response, context) {
        if (response.status == 'OK') {
        //  $.browser.msie ?
        //      $(context).parents('.activity:first').animate({ height: 'toggle' }, 'slow'):
        //      $(context).parents('.activity:first').animate({ opacity: 'toggle', height: 'toggle' });
        //
          location.reload();
         } else {
            STX.showMessage(response.message, "error");
        }
        
    }
    
    function commandFail(response) {
        STX.showMessage(response.message, "error");
        //console.log(error);
    };
    
    function bookmarkSuccess(response, context) {
         
        context.hasClass('icons') ?
            context.removeClass('icons'):
            context.addClass('icons');
    }
    function getCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for(var i = 0; i <ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}
    
    function getallSuccess(response, context) {
        var result = $(response.html).html();
            $(".show-more").show();
                    $(".show-more-container").css("height","30px");
                $(".loading-container").css("position","absolute")
        $('.activity-feed-list').append(result);
        var value = $(context).data('value');
        if (response.last_activities_id != 0) {
            value.activities_id = response.last_activities_id;
            $(context).data('value', value);
        } else {
            $('.show-more-container').remove();
        }
        STX.colorboxInit();
    }

    
    
    
    function clearChecknewTimer() {
        if (checknewTimeoutId != null) {
            clearTimeout(checknewTimeoutId);
            checknewTimeoutId = null;
        }
    }
    
    function setChecknewTimer() { checknewTimeoutId = setTimeout(checknew, checknewInterval); }
    
    function resetChecknewTimer() {
        clearChecknewTimer();
        setChecknewTimer();
    }
    
    function checknew() {
        if (pageTitle == '') pageTitle = $(document).attr('title');
        
        var data = {
            activities_type: $('#activities_type').val(),
            last_activity: $('#last_activity').val(),
            activities_tab: $('#activities_tab').val(),
            last_activity_date: $('#last_activity_date').val(),

            activities_group: $('#activities_group').val()
        }
        
        var args = {
                //type: 'post',
                module: 'activities',
                action: 'checknew',
                data: data
        }

        Services.invoke(args, checknewSuccess, commandFail);
        
        //clearChecknewTimer();
        
    }
  
    
    
    
    
    function getnewSuccess(response, context) {
        //console.log(context);
        $(context).remove();
                $(".replayhide-"+response.first_activities_id).hide();

        
        var result = $(response.html).html();
        
        htmlContent = $('<div />').html(result).css('display', 'none');
        $('.activity-feed-list').prepend(htmlContent);
        $(htmlContent).animate({ height: 'show' });
        $(htmlContent).find('.activity').effect('highlight', {}, 3000);
        //
        
        $(document).attr('title', pageTitle);
        
        $('#last_activity').val(response.first_activities_id);
        $('#last_activity_date').val(response.last_activity_date);
        $('.totalpro').each(function(){
        if($(this).val() !=''){
            var mainheight = $("#main"+$(this).val()).height();
            var childheight = $("#child"+$(this).val()).height();
            var final = mainheight-childheight;
            $(".janeesh"+$(this).val()).css("height",final);
             
        }else{
        }
    });

        
        /*
        htmlContent = $('<div />').html(newActivitiesHtml).css('display', 'none');
        $('.activity-feed-list').prepend($(htmlContent));
        $(htmlContent).animate({ height: 'show' });
        $(htmlContent).find('.activity').effect('highlight', {}, 3000);
        $(this).remove();

        newActivitiesHtml = '';
        newActivitiesCount = 0;
        $(document).attr('title', pageTitle);
        */
        
        //STX.colorboxInit(htmlContent);
        STX.colorboxInit();

    }
    
    function likeSuccess(response, context) {
        var a = ((response.html).indexOf("ERROR"));
       // alert(a);

            if(a ==0){
            var user =$("#actuserid").val();

            if(user ==''){
           var actpollid =$("#actpollid").val();
           var title='Sign up';
          
            $.ajax({
                    async: true, 
                   cache: false,
                    dataType : "html",
                    type:"POST",
                    data:{postid:actpollid,title:title},
                    url:siteurl+"sighnuppopup",

                    success:function(msg){

                         $('#replaypopup-'+actpollid).html('');
                         $('#replaypopup-'+actpollid).html(msg);
                         $('#replaypopup-'+actpollid).modal('show'); 
                        
                        
                    }
                });


                
            }
            return false;
        }
        $(context).parents('.like-list').replaceWith(response.html);
    }
    
    function showLikesSuccess(response, context) {
        Dialogs.alert(response.html, "Show");
    }

    function unlikeSuccess(response, context) {
        $(context).parents('.like-list').replaceWith(response.html);
    }
        function agreeSuccess(response, context) {
        if(response.html ==''){
            var user =$("#actuserid").val();

            if(user ==''){
           var actpollid =$("#actpollid").val();

            var title='Sign up';
            $.ajax({
                    async: true, 
                   cache: false,
                    dataType : "html",
                    type:"POST",
                    data:{postid:actpollid,title:title},
                    url:siteurl+"sighnuppopup",

                    success:function(msg){

                         $('#replaypopup-'+actpollid).html('');
                         $('#replaypopup-'+actpollid).html(msg);
                         $('#replaypopup-'+actpollid).modal('show'); 
                        
                        
                    }
                });

            return false;

                
            }
        }
        $(context).parents('.agree-list').replaceWith(response.html);

    }
    function disagreeSuccess(response, context){
        $(context).parents('.agree-list').replaceWith(response.html);

        
    }
    
    
    
    function checknewSuccess(response, context) {
        //console.log(response);
        newActivitiesCount = response.new_activities_dashboard;
        
        new_activities_tab_at = response.new_activities_tab_at;
        new_activities_tab_commented = response.new_activities_tab_commented;
        new_messages = response.new_messages;
        new_notifications = response.new_notifications;
        
        
        if (newActivitiesCount != 0) {
        
            $(document).attr('title', '(' + newActivitiesCount + ') ' + pageTitle);
            if ($('.new-activities-count').length > 0) {
                $('.new-activities-count').html(response.html);
            } else {
                statusContainer = $('<div />').addClass('new-activities-count').html(response.html).css('display', 'none');
                
                statusContainer.click(function () {
                
                    var data = {
                            activities_type: $('#activities_type').val(),
                            last_activity: $('#last_activity').val(),
                            activities_tab: $('#activities_tab').val(),
                            last_activity_date: $('#last_activity_date').val(),

                            activities_group: $('#activities_group').val()
                    }
                    
                    var args = {
                            //type: 'post',
                            module: 'activities',
                            action: 'getnew',
                            data: data
                        }
    
                    Services.invoke(args, getnewSuccess, commandFail, $(this));
                    
                    
                });
                
                var n          =(response.html).indexOf("ERROR");
                if(n !=0){
                $('.activity-feed-list').prepend(statusContainer);
                $(statusContainer).animate({ height: 'show' });
                }
            }
            
        }
        
        if (new_activities_tab_at != 0) {
            if ($('.feed-navigation .at .new-items-count').length > 0) {
                $('.feed-navigation .at .new-items-count span').text(new_activities_tab_at)
            } else {
                var new_items_count = $('<span />').addClass('new-items-count');
                var new_items_count_content = $('<span />').text(new_activities_tab_at);
                new_items_count.append(new_items_count_content);
                $('.feed-navigation .at').append(new_items_count)
                
            }
        }
        
        if (new_activities_tab_commented != 0) {
            if ($('.feed-navigation .comments .new-items-count').length > 0) {
                $('.feed-navigation .comments .new-items-count span').text(new_activities_tab_commented)
            } else {
                var new_items_count = $('<span />').addClass('new-items-count');
                var new_items_count_content = $('<span />').text(new_activities_tab_commented);
                new_items_count.append(new_items_count_content);
                $('.feed-navigation .comments').append(new_items_count)
                
            }
            
        }
            
        if (new_messages != 0) {
            $('#ctl00_uxHeader_lblPrivateCount').text(new_messages).css('display','block');
            count = parseInt(new_messages) + parseInt(new_notifications);
            $('#ctl00_uxHeader_lblTotalCount').text(count);
        }
        
        if (new_notifications != 0) {           
            $('#ctl00_uxHeader_lblNotifCount').text(new_notifications).css('display','block');
            count = parseInt(new_messages) + parseInt(new_notifications);
            $('#ctl00_uxHeader_lblTotalCount').text(count);
        }
        if(new_messages != 0 || new_notifications != 0){
            $('#ctl00_uxHeader_hlNotifications').addClass('full');
        }
        
        setChecknewTimer();
    }
    
    function _init() {
        if ($('#activities_type').length > 0 && $('#last_activity').length > 0) {
            setChecknewTimer();
            STX.colorboxInit();
        } else {
            STX.colorboxInit();
        }
    }
    
    // --- declare public methods --- //
    return {
 
        init: _init,
        
        set: function(el, value, event) {
            var switc = $("#switch").val();
            if(switc.trim() =="off"){
                var editor = $(el).parents('.data-content-placeholder').find('.htmlarea textarea');
            }
            if(switc.trim() =="ON"){
                var editor = $(el).parents('.data-content-placeholder').find('#st_text textarea');
            }
            
            var token = $(el).parents('.data-content-placeholder').attr('data-token');
            if(switc.trim() =="off"){
                var activityContent = editor.val().trim();
            }
            if(switc.trim() =="ON"){
                var activityContent = $(".nicEdit-main").html();
            }
            
            //console.log(value);if (str.toLowerCase().indexOf("yes") >= 0)

            var imglen   = $("#imagecnt").val();
             if(imglen !=""){
                
                if(activityContent ==""){
                var activityContent =false;
                    
                }else{
                    var activityContent = activityContent;
                }
                
            }else{
                var activityContent = activityContent;
                
            }
                var textariatitle = $('#textariatitle').val();

             if(activityContent == "" || textariatitle == "" ){
                $("#post").css("display","block");
                 $("#show-newssection-close1").css("display","block");
                  el.data('status', 'enabled').removeClass('disabled');
                  if(textariatitle == ""){
                       $('#textariatitle').focus();
                      
                        STX.showMessage("Please enter title", "error"); 
                  }
                  if(activityContent == ""){
                       editor.focus();
                  }
                 return false;
                }
            
            
            
            if ((activityContent == '') && (imglen=='') ) { 
                activityContent = '';
                el.data('status', 'enabled').removeClass('disabled');
                editor.focus();
            } else {
                
                var sharemarketdata = [];

                $('.assets').each(function(){
                       var valu  =$(this).val();
                       var stockdata         =$(".assetdata"+valu).val();
                        var ticker         =$(".tickers"+valu).val();
                       sharemarketdata[valu] = [stockdata,ticker];
                        
                    
                });
                var googlecity = $("#googlecity").val();
                if(googlecity !=""){
                    var googlecity = googlecity;
                    
                }else{
                    var googlecity ="";
                    
                }
                var group_new = $('.attachment-group-field').val();
                var pageurlpage = $("#pageurlpage").val();
                    if(pageurlpage !=""){
                        var pageurlpage = pageurlpage;

                    }else{
                        var pageurlpage ="";

                    }
                    
                   var textariatitle = $('#textariatitle').val();
                    var coverimage = $('#coverimage').val();

                        STX.showMessage("Your Buzz has been Posted Successfully !", "success"); 

                var data = { 
                        activities_text:activityContent,
                        coverimage,coverimage,
                        activities_title: textariatitle,
                        activities_type: value.activities_type,
                        activities_group: value.activities_group,
                        activities_username: value.activities_username,
                        activities_sharemarketdata:sharemarketdata,
                        googlecity:googlecity,
                        group_new:group_new,
                        pageurlpage:pageurlpage,


                        
                        token: token
                    }
                var args = {
                        //type: 'post',
                        module: 'activities',
                        action: 'set',
                        data: data
                    }
                Services.invoke(args, postSuccess, commandFail, el);
            }
        },
        
        deleteActivity: function(el, value, event) {

            Dialogs.confirm(
                    'Are you sure you want to delete this post?', 
                    function() {
                        var args = {
                                //type: 'post',
                                module: 'activities',
                                action: 'delete',
                                data: value
                            }
                        Services.invoke(args, deleteSuccess, commandFail, el);
                    }
            );
            
            
            
        },
        
        
        bookmark: function(el, value, event) {
            var args = {
                    //type: 'post',
                    module: 'activities',
                    action: 'bookmark',
                    data: value
                }
            Services.invoke(args, bookmarkSuccess, commandFail, el);
        },
        
        getMore: function(el, value, event) {
        
            //Dialogs.alert('Loading ...');
            $(".show-more").hide();
            $(".show-more-container").css("height","30px");
                $(".loading-container").css("position","static")
        
        
            
            
            var args = {
                    //type: 'post',
                    module: 'activities',
                    action: 'getall',
                    data: value
                }
            Services.invoke(args, getallSuccess, commandFail, el);
            
        },
    
        like: function(el, value, event) {
            var args = {
                    module: 'activities',
                    action: 'like',
                    data: value
                }
            Services.invoke(args, likeSuccess, commandFail, el);
            
        },
        
        unlike: function(el, value, event) {
            var args = {
                    module: 'activities',
                    action: 'unlike',
                    data:  value
                }
            Services.invoke(args, unlikeSuccess, commandFail, el);
        },
        agree: function(el, value, event) {
            var args = {
                    module: 'activities',
                    action: 'agree',
                    data: value
                }
            Services.invoke(args, agreeSuccess, commandFail, el);
            
        },
        disagree: function(el, value, event) {
            var args = {
                    module: 'activities',
                    action: 'disagree',
                    data: value
                }
            Services.invoke(args, disagreeSuccess, commandFail, el);
            
        },
 showloves: function(el, value, event) {
            var args = {
                    module: 'activities',
                    action: 'showloves',
                    data:  value
                }
            Services.invoke(args, showLikesSuccess, commandFail, el);
        },
        
        showlikes: function(el, value, event) {
            var args = {
                    module: 'activities',
                    action: 'showlikes',
                    data:  value
                }
            Services.invoke(args, showLikesSuccess, commandFail, el);
        },
        
        show_edit_box: function(el, value, event) {
            var post_content_selector = $(el).parents('.activity-footer').siblings('.activity-content');
            var text = $(post_content_selector).text(); 

            $(post_content_selector).html('<textarea id="post_edit_text" cols="50" rows="5" style="width: 100%; height: 60px;">'+ text +'</textarea>');             
            $(el).replaceWith('<a href="" class="edit_post" data-role="services" data-namespace="activities" data-action="edit">Save Changes</a> <a href="" id="cancel_editing" data-role="services" data-namespace="activities" data-action="cancel">Cancel editing</a>');
            
            postid = $(el).data('postid');
            posttype = $(el).data('posttype');
        },
        
        edit: function(el, value, event) {
            var args = {
                    module: 'activities',
                    action: 'edit',
                    data: {activities_id: postid, activities_type: posttype, message: $('#post_edit_text').val()}
                }
            Services.invoke(args, editSuccess, commandFail, el);
            
        },
        
        cancel: function(el, value, event){
            var post_selector = $(el).parents('.activity-footer').siblings('.activity-content');
            var text = $(post_selector).children('textarea').val();
            $(post_selector).children('textarea').remove();
            $(post_selector).text(text);
            
            $(el).siblings('.edit_post').replaceWith('<a href="" class="edit_post" data-role="services" data-namespace="activities" data-action="show_edit_box" data-posttype="'+ posttype +'" data-postid="'+ postid +'">Edit post</a>');
            $(el).remove();
        }

    }
} ();

//--- declare page load events --- //
$(document).ready(function () {
    Activities.init();
    //  $('.show-more').click();
       $(window).scroll(function() {
      $('.totalpro').each(function(){
        if($(this).val() !=''){
            var mainheight = $("#main"+$(this).val()).height();
            var childheight = $("#child"+$(this).val()).height();
            var final = mainheight-childheight;
            $(".janeesh"+$(this).val()).css("height",final);
             
        }else{
        }
    });
       
    if( (Math.ceil($(window).scrollTop()) + Math.ceil($(window).height()) >= Math.ceil($(document).height()) -400) || (Math.ceil($(window).scrollTop()) + Math.ceil($(window).height()) == Math.ceil($(document).height()))) {
       
         // $('.show-more').click();
         
       }else{
           /*alert(parseInt(Math.ceil($(window).scrollTop())) + parseInt(Math.ceil($(window).height())));
            alert(Math.ceil($(document).height()) -400); */

}
    });
    
$(window).on('scroll', function() {
            if ($(window).scrollTop() >= $(
              '.show-more').offset().top + $('.show-more').
                outerHeight() - window.innerHeight) {
                
                $('.show-more').click();
            }
        });


    $(".ctypes").click(function(){
        var dtype   =$(this).attr("data-type");
        $("#hserch").val(dtype);
        
    });
    var timesRun = 0;
var interval = setInterval(function(){
    timesRun += 1;
    if(timesRun === 2){
       // clearInterval(interval);
    }
$(".share").click(function(){
    }); 
             $.ajax({
                    async: true, 
                   cache: false,
                    dataType : "html",
                    type:"POST",
                    data:{activenotification:1},
                    url:siteurl+'autocomplete',
                     success:function(message){
                        if(message !=''){
                        
                         var notifires =jQuery.parseJSON(message);
                         if(notifires.from_user_id !=''){
                                $(".badge-workspace").html(notifires.notcnt);

                            
                             if(notifires.message !='st200'){
                                var bodymes = notifires.noti_type+' your buzz:'+notifires.message;
                             }else{
                                var bodymes = notifires.noti_type+' your ' +notifires.post_type; 
                             }

                                Push.create(notifires.username, {
                                body:bodymes,
                                icon: {
                                    x16: siteurl+'/storage/avatars/thumbs1/'+notifires.avatar,
                                    x32:siteurl+'/storage/avatars/thumbs1/'+notifires.avatar
                                },
                            });
                             
                        
                         $.ajax({
                    async: true, 
                   cache: false,
                    dataType : "html",
                    type:"POST",
                    data:{notificationid:notifires.notification_id},
                    url:siteurl+'autocomplete',
                    success:function(message){
                        
                    }
                        
                    
                             
                         });
                          }

                         
                          }  
        
                    }
                });
    //do whatever here..
}, 20000); 

    
    
    
    
                $("#st_text").keyup(function(){
                var counter = $(".characters-counter").attr("data-value");
            
                                    if (counter.length > 0) {
                        
            
            counterValue = $(".characters-counter").attr("data-value");
            charactersCount = $(".nicEdit-main").html().length;
            charactersCount = $(".nicEdit-main").html().length;
            charactersLeft = counterValue - charactersCount;
            //console.log(charactersCount);
            $("#characterff").text(charactersLeft);
            if (charactersCount > counterValue) {
                //console.log('limit');
                //return false;
                /*editorString = $(".nicEdit-main").html();
                editorStrings = editorString.substring(0,counterValue);
                var end =parseInt($(".nicEdit-main").html().length)+parseInt(1);
                editorString = $(".nicEdit-main").html();
                editorStringadditional = editorString.substring(counterValue,end);
                var abc     = editorStrings + '<span style="color:red">'+editorStringadditional+'</span>';
                $(".nicEdit-main").html(abc);*/
                
                 
            
                 
                 
                
                $(".post-btn").prop("disabled",true);
                $(".post-btn").css("opacity","0.5");
                $("#characterff").css("color","red");
                       
        
                
                
                
                
            }else{
                if (charactersCount <= counterValue) {
                    $(".post-btn").css("opacity","");
                    $(".post-btn").prop("disabled",false);
                    $(".characters-counteref").css("color","");
            
                
                
                }
                
            }
            
        }

                });

});