!function(i,n,t,a){"use strict";i(t).ready((function(){i(t.body).on("click",".barn2-notice .notice-dismiss",(function(){const n=i(this).parent(),t=n.data();t.id=t.id||n.prop("id"),t.action="barn2_dismiss_notice",i.ajax({url:ajaxurl,type:"POST",data:t,xhrFields:{withCredentials:!0}})}))}))}(jQuery,window,document);