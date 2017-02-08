function initPushwoosh_Windows() {
    var pushNotification = cordova.require("pushwoosh-cordova-plugin.PushNotification");
 
    //set push notifications handler
    document.addEventListener('push-notification', function(event) {
                //get the notification payload
                var notification = event.notification;
 
                //display alert to the user for example
                alert(JSON.stringify(notification));
    });
 
    //initialize the plugin
    pushNotification.onDeviceReady({ appid: "83A27-DD3C0", serviceName: "" });
 
    //register for pushes
    pushNotification.registerDevice(
        function(status) {
            var pushToken = status;
            console.warn('push token: ' + pushToken);
        },
        function(status) {
            console.warn(JSON.stringify(['failed to register ', status]));
        }
    );
}