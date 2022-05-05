window.App || ( window.App = {} );

App.hTimer = 0;
App.time_ms = 7000;

App.init = function() {

    if (App.hTimer) clearTimeout(App.hTimer);
    App.run();
};

App.run = function() {

    $.ajax({
        type: "GET",
        url: "/notifications.php",
        data: "action=getNotificationsCount",
        success: function(text) {

            var count = parseInt(text);

            $("span#notifications_counter").text(count);

            if (count < 1) $("span#notifications_counter_cont").hide();
            if (count > 0) $("span#notifications_counter_cont").show()
        },
        complete: function() {

            // console.log(update.time_ms)
            // Добавляем 4 секунд для следуещего обновления
            App.time_ms = App.time_ms + 4000;

            App.hTimer = setTimeout(function() {

                App.init();

            }, App.time_ms);
        }
    });

    $.ajax({
        type: "GET",
        url: "/messages.php",
        data: "action=getMessagesCount",
        success: function(text) {

            var count = parseInt(text);

            $("span#messages_counter").text(count);

            if (count < 1) $("span#messages_counter_cont").hide();
            if (count > 0) $("span#messages_counter_cont").show()
        }
    });

    $.ajax({
        type: "GET",
        url: "/friends.php",
        data: "action=getFriendsCount",
        success: function(text) {

            var count = parseInt(text);

            $("span#friends_counter").text(count);

            if (count < 1) $("span#friends_counter_cont").hide();
            if (count > 0) $("span#friends_counter_cont").show()
        }
    });

    $.ajax({
        type: "GET",
        url: "/guests.php",
        data: "action=getGuestsCount",
        success: function(text) {

            var count = parseInt(text);

            $("span#guests_counter").text(count);

            if (count < 1) $("span#guests_counter_cont").hide();
            if (count > 0) $("span#guests_counter_cont").show()
        }
    });
};