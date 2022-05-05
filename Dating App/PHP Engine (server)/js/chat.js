window.App || ( window.App = {} );

App.hChatTimer = 0;
App.chat_time_ms = 2000;

App.chatInit = function(chat_id, user_id, access_token) {

    if (App.hChatTimer) clearTimeout(App.hChatTimer);
    App.chatRun(chat_id, user_id, access_token);
};

App.chatRun = function(chat_id, user_id, access_token) {

    if (typeof options.pageId !== typeof undefined && options.pageId === "chat") {

        Messages.update(chat_id, user_id, access_token)
    }
};


window.Messages || ( window.Messages = {} );

Messages.update = function (chat_id, user_id, access_token) {

  var message_id = $("li.collection-item").last().attr("data-id");

  $.ajax({
    type: 'POST',
    url: '/ajax/chatUpdate.php',
    data: 'access_token=' + access_token + "&chat_id=" + chat_id + "&user_id=" + user_id + "&message_id=" + message_id,
    dataType: 'json',
    timeout: 30000,
    success: function(response){

      if (response.hasOwnProperty('html')) {

        $("ul.collection").append(response.html);
      }

      if (response.hasOwnProperty('items_all')) {

        items_all = response.items_all;
        items_loaded = $('li.collection-item').length;
      }

      App.chat_time_ms = App.chat_time_ms + 1000;

      App.hChatTimer = setTimeout(function() {

        App.chatInit(chat_id, user_id, access_token);

      }, App.chat_time_ms);
    },
    error: function(xhr, status, error) {

      //var err = eval("(" + xhr.responseText + ")");
      //alert(err.Message);
    }
  });
};

Messages.create = function (chat_id, user_id, access_token) {


  var message_text = $('input[name=message_text]').val();
  var message_img = $('input[name=message_image]').val();
  var message_id = $("li.collection-item").last().attr("data-id");

  $.ajax({
    type: 'POST',
    url: '/ajax/msg.php',
    data: 'message_text=' + encodeURIComponent(message_text) + '&message_img=' + message_img + '&access_token=' + access_token + "&chat_id=" + chat_id + "&user_id=" + user_id + "&message_id=" + message_id,
    dataType: 'json',
    timeout: 30000,
    success: function(response){

      if (response.hasOwnProperty('html')) {

        if ($("div.empty-list").length > 0) {

          $("div.empty-list").remove();
          $("div.chat-content").prepend("<ul class=\"collection\"></ul>");
        }

        $("ul.collection").append(response.html);
        $("input[name=message_text]").val("");
        $("input[name=message_image]").val("");
          $("div.msg-image-preview-container").hide();
          $("img.msg-image-preview").attr("src", "");
          $("i.msg-image-change").html("image");

          if (response.hasOwnProperty('chat_id') && chat_id == 0) {

              chat_id = response.chat_id;
              App.chatInit(chat_id, user_id, access_token);
          }
      }

      if (response.hasOwnProperty('items_all')) {

        items_all = response.items_all;
        items_loaded = $('li.collection-item').length;
      }
    },
    error: function(xhr, type){

    }
  });
};

Messages.more = function (chat_id, user_id) {

  var message_id = $("li.collection-item").first().attr("data-id");

  $('div.more_cont').hide();

  $.ajax({
    type: 'POST',
    url: '/ajax/msgMore.php',
    data: "chat_id=" + chat_id + "&user_id=" + user_id + "&message_id=" + message_id + "&messages_loaded=" + items_loaded,
    dataType: 'json',
    timeout: 30000,
    success: function(response){

        $('div.more_cont').remove();

      if (response.hasOwnProperty('html')) {

        $("ul.collection").prepend(response.html);
      }

      if (response.hasOwnProperty('html2')) {

        $("div.messages_cont").prepend(response.html2);
      }

      if (response.hasOwnProperty('items_all')) {

        items_all = response.items_all;
        items_loaded = $('li.collection-item').length;
      }
    },
    error: function(xhr, type){

        alert("error");
        $('div.more_cont').show();
    }
  });
};

Messages.removeChat = function(chat_id, user_id, access_token) {

  $.ajax({
    type: 'POST',
    url: '/ajax/chatRemove.php',
    data: 'access_token=' + access_token + "&chat_id=" + chat_id + "&user_id=" + user_id,
    dataType: 'json',
    timeout: 30000,
    success: function(response){

      $('li.collection-item[data-id=' + chat_id + ']').remove();
    },
    error: function(xhr, type){


    }
  });
};
