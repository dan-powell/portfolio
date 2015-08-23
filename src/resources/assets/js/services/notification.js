/*  Notification Service
    -------------------------------------------------------------------------------------------------
    Manages pop-up alert info & danger messages that are displayed on page
*/
app.service('notificationService', function ($timeout) {

    var notifications = [];

    // Retrieve all messages
    this.get = function() {
        return notifications;
    };

    // Remove new notification
    this.add = function(message, type, delay) {

        type = typeof type !== 'undefined' ? type : 'info';
        delay = typeof delay !== 'undefined' ? delay : 6000;

        var notification = {
            type : type,
            message : message,
        };

        notifications.push(notification);

        if (delay != 0 && delay != null && delay != 'none') {

            // Set the alert to be removed after a delay
            $timeout(this.removeByElement, delay, true, notification); // maybe '}, 3000, false);' to avoid calling apply

        }

    };

    // Remove notification by array index
    this.removeByIndex = function(index) {

        debug('removing one message: ' + index, 'notification');

        notifications.splice(index, 1);
    }

    // Remove all notifications of type
    this.removeByType = function(type) {

        debug('clearing ' + type + ' messages', 'notification');

        for (i=0; i < notifications.length; i++) {
            if (notifications[i].type == type) {
                notifications.splice(i, 1);
            }
        }
    }

    // Remove notification by array index
    this.removeByElement = function(element) {

        debug('removing message: ' + element, 'notification');

        notifications.splice(notifications.indexOf(element), 1);

    }

    // Clear all notifications
    this.clear = function() {

        debug('clearing all messages', 'notification');

        notifications = [];
    }

});
