
var blueCatsAppToken = 'e14e08a7-3dd3-46f7-85db-15e2fc4a64bc';
alert("it got here")
var app = {

    // Application Constructor
    initialize: function() {
        this.bindEvents();
    },
    // Bind Event Listeners
    //
    // Bind any events that are required on startup. Common events are:
    // 'load', 'deviceready', 'offline', and 'online'.
    bindEvents: function() {
        document.addEventListener('deviceready', this.onDeviceReady, false);
    },
    // deviceready Event Handler
    //
    // The scope of 'this' is the event. In order to call the 'receivedEvent'
    // function, we must explicity call 'app.receivedEvent(...);'
    onDeviceReady: function() {
        alert('it got here')
        app.receivedEvent('received');
        app.watchBeacons();
    },
    // Update DOM on a Received Event
    receivedEvent: function(event) {
        //var parentElement = document.getElementById('deviceready');
        //var listeningElement = parentElement.querySelector('.listening');
        // var receivedElement = parentElement.querySelector('.received');

        //listeningElement.setAttribute('style', 'display:none;');
        // receivedElement.setAttribute('style', 'display:block;');

        if (event == 'apptokenrequired') {
            //  receivedElement.innerHTML = 'App token not set'
        } else if (event == 'bluecatspurring') {
            // receivedElement.innerHTML = 'Looking for beacons'
        };

        alert('Received Event: ' + event);
    },

    watchBeacons: function() {

        var watchIdForEnterBeacon, watchIdForExitBeacon, watchIdForClosestBeacon = null;
        var beaconDisplayList = null;

        if (blueCatsAppToken == 'BLUECATS-APP-TOKEN') {
            //BlueCats app token hasn't been configured
            app.receivedEvent('apptokenrequired');
            return;
        }

        var sdkOptions = {
            useLocalStorage: true
        };

        var beaconWatchOptions = {
            minimumTriggerIntervalInSeconds: 2, //Integer. Minimum seconds between callbacks (default 1)
            repeatCount: 3, //Integer. Default repeat infinite
            filter: {
                //Configure additional filters here e.g.
                //sitesName:['BlueCats HQ', 'Another Site'],
                categoriesNamed: ['Sales Conference'],
                maximumAccuracy: 0.5,
                //etc.
            }
        };

        com.bluecats.beacons.startPurringWithAppToken(
            blueCatsAppToken,
            purringSuccess, logError, sdkOptions);

        function purringSuccess() {
            app.receivedEvent('bluecatspurring');
            watchBeaconEntryAndExit();
            watchClosestBeacon();
            localnotcat1();
            beaconarea();

        }

        function watchBeaconEntryAndExit() {
            if (watchIdForEnterBeacon != null) {
                com.bluecats.beacons.clearWatch(watchIdForEnterBeacon);

            };

            if (watchIdForExitBeacon != null) {
                com.bluecats.beacons.clearWatch(watchIdForExitBeacon);
            };

            watchIdForEnterBeacon = com.bluecats.beacons.watchEnterBeacon(
                function(watchData) {
                    displayBeacons('Entered', watchData);

                }, logError, beaconWatchOptions);
            watchIdForExitBeacon = com.bluecats.beacons.watchExitBeacon(
                function(watchData) {
                    displayBeacons('Exited', watchData);
                }, logError, beaconWatchOptions);
        }

        function watchClosestBeacon() {
            if (watchIdForClosestBeacon != null) {
                com.bluecats.beacons.clearWatch(watchIdForClosestBeacon);
            };

            watchIdForClosestBeacon = com.bluecats.beacons.watchClosestBeaconChange(
                function(watchData) {
                    displayBeacons('Closest to', watchData);
                }, logError, beaconWatchOptions);
        }


        function displayBeacons(description, watchData) {
            var beacons = watchData.filteredMicroLocation.beacons;
            var beaconNames = [];

            for (var i = 0; i < beacons.length; i++) {
                beaconNames.push(beacons[i].name);
            };

            var displayText = description + ' ' + beacons.length + ' beacons: ' + beaconNames.join(',');
            console.log(displayText);

            if (!beaconDisplayList) {
                var appElement = document.querySelector('.app');
                beaconDisplayList = document.createElement('ol');
                beaconDisplayList.setAttribute('id', 'beacons');
                appElement.appendChild(beaconDisplayList);
            }

            var li = document.createElement('li');
            li.appendChild(document.createTextNode(displayText));
            beaconDisplayList.appendChild(li);

        }
        alert(displayBeacons());

        function logError() {
            alert('Error occurred watching beacons');
        }

        function localnotcat1() {
            function success() {
                alert('Scheduled notification');
            };

            function error() {
                alert('error!');
            };

            var categorytwo = {
                name: 'Category 2'
            };
            var categorytwoid = {
                id: '7e6c4300-b7a2-a5ab-6140-0566821faad3'
            };
            var customNotificationData = {
                someKey: 'key1',
                anotherKey: 'other data'
            };
            var localNotification = {
                fireInCategories: [categorytwo, categorytwoid],
                fireAfterDelayInSeconds: 60, //Delay the earliest time this notification can trigger. Once the delay has passed, the other criteria will need to be met before triggering.
                alertAction: 'View this',
                alertBody: 'Welcome back. Here is some great info for you.',
                userInfo: customNotificationData //This object will be provided as notificationData to com.bluecats.beacons.localNotificationReceived callback
            };

            com.bluecats.beacons.scheduleLocalNotification(localNotification, success, error);

            function success(notificationData) {
                alert('Notification received' + JSON.stringify(notificationData));
            };

            function error() {
                alert('error!');
            };

            com.bluecats.beacons.localNotificationReceived(success, error);
        }


    }


};


app.initialize();