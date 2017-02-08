var blueCatsAppToken = 'e14e08a7-3dd3-46f7-85db-15e2fc4a64bc';
// alert('for testing');
var app = {
    initialize: function() {
        this.bindEvents();
    },
   
    bindEvents: function() {
        document.addEventListener('deviceready', this.onDeviceReady, false);
    },
   
    onDeviceReady: function() {
       document.addEventListener('bluetoothUpdateState', 
    function(e) {
        if (e.detail.state == 'CBCentralManagerStatePoweredOff') {
          
        }
        else if (e.detail.state == 'CBCentralManagerStatePoweredOn') {
           
        }
        
    }, 
    false
);
        app.receivedEvent('received');
        app.watchBeacons();
    },
    
    receivedEvent: function(event) {
        if (event == 'apptokenrequired') {
            
        } else if (event == 'bluecatspurring') {
           
        };
        console.log('Received Event: ' + event);
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
            filter:{
                //Configure additional filters here e.g.
                //sitesName:['BlueCats HQ', 'Another Site'],
                //categoriesNamed:['Entrance'],
                //maximumAccuracy:0.5
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
			 var wel = 0;
			 if(wel = 0){
				WelcomeMsg(); 
			 }
			

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

		///Local notification 1
        function localnotcat1() {
			
			
            function success() {
                console.log('Scheduled notification');
					
            };

            function error() {
                console.log('error!');
            };

            var categoryone = {
                name: 'Sales Conference'
            };
            var categoryoneid = {
                id: 'e69aa600-d0a4-2087-a546-e92d88018acc'
            };
			  
			 var customNotificationData = {
                someKey: 'key1',
                anotherKey: 'other data'
            };
          
            var localNotification1 = {
                fireInCategories: [categoryone, categoryoneid],
                fireAfterDelayInSeconds: 30, //Delay the earliest time this notification can trigger. Once the delay has passed, the other criteria will need to be met before triggering.
                alertAction: 'Open',
                alertBody: 'Please Check-In To The Session',
               	userInfo: customNotificationData,
				
				 };

            com.bluecats.beacons.scheduleLocalNotification(localNotification1, success, error);

            function success(notificationData) {
                 console.log('Notification received' + JSON.stringify(notificationData));
				 
			if(notificationData.alertAction == "Open"){
	
	showAlert();}
			};

            function error() {
                alert('error!');
            };

            com.bluecats.beacons.localNotificationReceived(success, error);
        }

		function WelcomeMsg(){
			var wel = 1;
			var q = new Date();
				var m = q.getMonth();
				var d = q.getDate();
				var y = q.getFullYear();
					
				var date = new Date(y,m,d);
				
				mydate=new Date('2015-08-04');
				console.log(date);
				console.log(mydate)
				
				if(date=mydate)
				{
			function onConfirm(buttonIndex) {
					 console.log("Welcome msg sent")
					}
					navigator.notification.beep(2);
					navigator.notification.confirm(
						'Welcome To the EOH ICT Application.\n Dont forget to swith on location and bluetooth!\n Thank You', // message
						 onConfirm,            // callback to invoke with index of button pressed
						'Welcome',           // title
						['Open']     // buttonLabels
					);
				}
				else
				{
			console.log("ths msg will not go out")
				}
			}

function showAlert() {
     function onConfirm(buttonIndex) {
   menu.setMainPage('Checkins/day1.html', {closeMenu: true})
}
function exit(buttonIndex) {
   menu.setMainPage('Checkins/day1.html', {closeMenu: true})
}
navigator.notification.beep(2);
navigator.notification.confirm(
    'Please Check-In To The Session!', // message
     onConfirm,
	 exit,            // callback to invoke with index of button pressed
    'Check-In!',           // title
    ['Check-in','No Thanx']     // buttonLabels
);

}

}

};


app.initialize();