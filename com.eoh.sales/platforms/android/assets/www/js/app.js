(function() {
    'use strict';

    var app = angular.module('app', ['onsen', 'angular-images-loaded', 'ngMap','ngAnimate', 'ngTouch']);
    var api = 'http://eohict.co.za/api/';
    // StatusBar.overlaysWebView(false);

    // Filter to convert HTML content to string by removing all HTML tags
    app.filter('htmlToPlaintext', function() {
        return function(text) {
            return String(text).replace(/<[^>]+>/gm, '');
        };
    });

    // app.factory('spAuthService', function ($http, $q) {
    //     var authenticate = function (userId, password, url) {
    //         var signInurl = 'https://' + url + '/_forms/default.aspx?wa=wsignin1.0';
    //         var deferred = $q.defer();
    //         var message = getSAMLRequest(userId, password, signInurl);

    //         $http({
    //             method: 'POST',
    //             url: 'https://login.microsoftonline.com/extSTS.srf',
    //             data: message,
    //             headers: {
    //                 'Content-Type': "text/xml; charset=\"utf-8\""
    //             }
    //         }).success(function (data) {
    //             getBearerToken(data, signInurl).then(function (data) {
    //                 deferred.resolve(data);
    //             }, function (data) {
    //                 deferred.reject(data);
    //             });
    //         });

    //         return deferred.promise;
    //     };

    //     return {
    //         authenticate: authenticate
    //     };

    //     function getSAMLRequest(userID, password, url) {
    //         return '<s:Envelope \
    //                     xmlns:s="http://www.w3.org/2003/05/soap-envelope" \
    //                     xmlns:a="http://www.w3.org/2005/08/addressing" \
    //                     xmlns:u="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd"> \
    //                     <s:Header> \
    //                         <a:Action s:mustUnderstand="1">http://schemas.xmlsoap.org/ws/2005/02/trust/RST/Issue</a:Action> \
    //                         <a:ReplyTo> \
    //                             <a:Address>http://www.w3.org/2005/08/addressing/anonymous</a:Address> \
    //                         </a:ReplyTo> \
    //                         <a:To s:mustUnderstand="1">https://login.microsoftonline.com/extSTS.srf</a:To> \
    //                         <o:Security \
    //                             s:mustUnderstand="1" \
    //                             xmlns:o="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd"> \
    //                             <o:UsernameToken> \
    //                                 <o:Username>' + userID + '</o:Username> \
    //                                 <o:Password>' + password + '</o:Password> \
    //                             </o:UsernameToken> \
    //                         </o:Security> \
    //                     </s:Header> \
    //                     <s:Body> \
    //                         <t:RequestSecurityToken xmlns:t="http://schemas.xmlsoap.org/ws/2005/02/trust"> \
    //                             <wsp:AppliesTo xmlns:wsp="http://schemas.xmlsoap.org/ws/2004/09/policy"> \
    //                                 <a:EndpointReference> \
    //                                     <a:Address>' + url + '</a:Address> \
    //                                 </a:EndpointReference> \
    //                             </wsp:AppliesTo> \
    //                             <t:KeyType>http://schemas.xmlsoap.org/ws/2005/05/identity/NoProofKey</t:KeyType> \
    //                             <t:RequestType>http://schemas.xmlsoap.org/ws/2005/02/trust/Issue</t:RequestType> \
    //                             <t:TokenType>urn:oasis:names:tc:SAML:1.0:assertion</t:TokenType> \
    //                         </t:RequestSecurityToken> \
    //                     </s:Body> \
    //                 </s:Envelope> \
    //                 ';
    //     }

    //     function getBearerToken(result, url) {
    //         var deferred = $q.defer();
    //         var securityToken = $($.parseXML(result)).find("BinarySecurityToken").text();

    //         if (securityToken.length === 0) {
    //             deferred.reject();
    //         } else {
    //             $http({
    //                 method: 'POST',
    //                 url: url,
    //                 data: securityToken,
    //                 headers: {
    //                     Accept: "application/json;odata=verbose"
    //                 }
    //             }).success(function (data) {
    //                 deferred.resolve(data);
    //             }).error(function () {
    //                 deferred.reject();
    //             });
    //         }

    //         return deferred.promise;
    //     }
    // });

    // app.controller('loginController', ['$scope', 'spAuthService', function($scope, spAuthService) {
    //     var userId = 'paul.vonzeuner@eoh.co.za';
    //     var password = 'Jan@2016';
    //     var url = 'eoh.sharepoint.com';

    //     spAuthService.authenticate(userId, password, url);
    // }]);

app.controller('loginCtrl', ['$http', '$scope', '$rootScope', '$q', function($http, $scope, $rootScope, $q) {
if (localStorage.getItem("email") === null) {
  console.log();
}
else {
$scope.menu.setMainPage('home.html', {
    closeMenu: true
});      
}

    $scope.login = function(){
        var getEmail = document.getElementById("email").value;
        $http({
            method : "GET",
            url : "http://eohconnect.com/eoh_api/contact/"+getEmail
        }).then(function mySucces(response) {
            $scope.myWelcome = response.data;
            console.log("Full get "+ $scope.myWelcome);
            $scope.email = $scope.myWelcome.mainEmail;
            console.log($scope.email);

            if (typeof $scope.email !== 'undefined') {
                console.log("data gotten");
                            $scope.menu.setMainPage('home.html', {
                                closeMenu: true
                            });   
                localStorage.setItem("email", getEmail);                           
            }
            else{
                console.log("data not found");  
                ons.notification.alert({
                title: "Please note",    
                message: 'Please register your details',
                callback: function() {
                            $scope.menu.setMainPage('register.html', {
                                closeMenu: true
                            });
                }
                });                
            }

        }, function myError(response) {
            $scope.myWelcome = response.statusText;
        });        
    }


}])    

app.controller('registerCtrl', ['$http', '$scope', '$rootScope', '$q', function($http, $scope, $rootScope, $q) {
    $scope.gotoRegister = function(){
        $scope.ons.navigator.pushPage('home.html');
    }

    $scope.register = function(){
        var lsemail = document.getElementById("mainEmail").value;

        var firstname = "firstName="+document.getElementById("firstName").value+"&";
        var lastname = "lastName="+document.getElementById("lastName").value+"&"; 
        var email = "mainEmail="+document.getElementById("mainEmail").value+"&";
        var company = "company="+document.getElementById("company").value+"&"; 
        var cell = "cellnumber="+document.getElementById("cellnumber").value+"&";   

        var e1 = document.getElementById("diet");
        var dietary = "dietary="+e1.options[e1.selectedIndex].text+"&";    

        var e2 = document.getElementById("track1");
        var PreferredTrack1 = "PreferredTrack1="+e2.options[e2.selectedIndex].text+"&"; 

        var e3 = document.getElementById("track2");
        var PreferredTrack2 = "PreferredTrack2="+e3.options[e3.selectedIndex].text;   
        
        var data = firstname+lastname+email+company+cell+dietary+PreferredTrack1+PreferredTrack2;

        var xhr = new XMLHttpRequest();
        xhr.withCredentials = true;

        xhr.addEventListener("readystatechange", function () {
        if (this.readyState === 4) {
            console.log(this.responseText);
            localStorage.setItem("email", lsemail); 
                ons.notification.alert({
                title: "Success",    
                message: 'Great, you are registered',
                callback: function() {
                            $scope.menu.setMainPage('home.html', {
                                closeMenu: true
                            });
                }
                });                    
        }
        });

        xhr.open("POST", "http://eohconnect.com/eoh_api/contact/");
        xhr.setRequestHeader("content-type", "application/x-www-form-urlencoded");
        xhr.setRequestHeader("insecure", "insecure=cool");
        xhr.setRequestHeader("cache-control", "no-cache");
        xhr.setRequestHeader("postman-token", "4c256173-4fb1-aeeb-33d6-0e24539477de");

        xhr.send(data);   

    }
   



}]) 

    // Log In Controller
    // app.controller('loginController', ['$http', '$scope', '$rootScope', '$q', function($http, $scope, $rootScope, $q) {
    //     var deviceType = (navigator.userAgent.match(/iPad/i))  == "iPad" ? "iPad" : (navigator.userAgent.match(/iPhone/i))  == "iPhone" ? "iPhone" : (navigator.userAgent.match(/Android/i)) == "Android" ? "Android" : (navigator.userAgent.match(/BlackBerry/i)) == "BlackBerry" ? "BlackBerry" : "null";

    //     if (deviceType === "Android") {
    //         $scope.fld_focus = function(has_focus) {
    //             if (has_focus) {
    //                 document.querySelector(".login-logo").style.top = "-100px";
    //             } else {
    //                 document.querySelector(".login-logo").style.top = "20px";
    //             }
    //         };
    //     }

    //     if (window.localStorage.getItem("spLoginCookie") !== null) {
    //         $scope.menu.setMainPage('home.html', {
    //             closeMenu: true
    //         });
    //     } else {
    //         $scope.login = function() {
    //             if ($scope.username === '' && $scope.password === '') {
    //                 ons.notification.alert({
    //                     message: "You can't leave any fields empty"
    //                 });
    //             } else {
    //                 modal.show();
    //                 var spAuth = authenticate($scope.username, $scope.password, 'eoh.sharepoint.com/teams/ict');
    //             }

    //             function authenticate (userId, password, url) {
    //                 var signInurl = 'https://' + url + '/_forms/default.aspx?wa=wsignin1.0';
    //                 var deferred = $q.defer();
    //                 var message = getSAMLRequest(userId, password, signInurl);

    //                 $http({
    //                     method: 'POST',
    //                     url: 'https://login.microsoftonline.com/extSTS.srf',
    //                     data: message,
    //                     headers: {
    //                         'Content-Type': "text/xml; charset=\"utf-8\""
    //                     }
    //                 }).success(function (data) {
    //                     var securityToken = $($.parseXML(data)).find("BinarySecurityToken").text();

    //                     window.localStorage.setItem("spLoginCookie", securityToken);
    //                     window.localStorage.setItem("ictUsername", userId);
    //                     modal.hide();
    //                     if (securityToken.length === 0) {
    //                         ons.notification.alert({
    //                             message: 'Your username/password was incorrect, please try again.'
    //                         });
    //                     } else {
    //                         // wsignin(securityToken, signInurl);

    //                         $scope.menu.setMainPage('home.html', {
    //                             closeMenu: true
    //                         });
    //                     }
    //                 });

    //                 return deferred.promise;
    //             }

    //             return {
    //                 authenticate: authenticate
    //             };

    //             function wsignin(token, signInurl) {
    //                 $.ajax({
    //                     'url': signInurl,
    //                     dataType: 'text',
    //                     type:'POST',
    //                     'data':token,
    //                     headers: {
    //                         Accept : "application/x-www-form-urlencoded"
    //                     },
    //                     success: function(result, textStatus, jqXHR) {
    //                         console.log('done wsignin: ' + result);
    //                     },
    //                     error:function (jqXHR, textStatus, errorThrown){
    //                         console.log('error wsignin: ' + jqXHR.responseText);
    //                     },
    //                     complete:function(jqXHR, textStatus) {
    //                         console.log('complete wsignin: ' + textStatus);
    //                     }
    //                 });
    //             }

    //             function getSAMLRequest(userID, password, url) {
    //                 return '<s:Envelope \
    //                             xmlns:s="http://www.w3.org/2003/05/soap-envelope" \
    //                             xmlns:a="http://www.w3.org/2005/08/addressing" \
    //                             xmlns:u="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd"> \
    //                             <s:Header> \
    //                                 <a:Action s:mustUnderstand="1">http://schemas.xmlsoap.org/ws/2005/02/trust/RST/Issue</a:Action> \
    //                                 <a:ReplyTo> \
    //                                     <a:Address>http://www.w3.org/2005/08/addressing/anonymous</a:Address> \
    //                                 </a:ReplyTo> \
    //                                 <a:To s:mustUnderstand="1">https://login.microsoftonline.com/extSTS.srf</a:To> \
    //                                 <o:Security \
    //                                     s:mustUnderstand="1" \
    //                                     xmlns:o="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd"> \
    //                                     <o:UsernameToken> \
    //                                         <o:Username>' + userID + '</o:Username> \
    //                                         <o:Password>' + password + '</o:Password> \
    //                                     </o:UsernameToken> \
    //                                 </o:Security> \
    //                             </s:Header> \
    //                             <s:Body> \
    //                                 <t:RequestSecurityToken xmlns:t="http://schemas.xmlsoap.org/ws/2005/02/trust"> \
    //                                     <wsp:AppliesTo xmlns:wsp="http://schemas.xmlsoap.org/ws/2004/09/policy"> \
    //                                         <a:EndpointReference> \
    //                                             <a:Address>' + url + '</a:Address> \
    //                                         </a:EndpointReference> \
    //                                     </wsp:AppliesTo> \
    //                                     <t:KeyType>http://schemas.xmlsoap.org/ws/2005/05/identity/NoProofKey</t:KeyType> \
    //                                     <t:RequestType>http://schemas.xmlsoap.org/ws/2005/02/trust/Issue</t:RequestType> \
    //                                     <t:TokenType>urn:oasis:names:tc:SAML:1.0:assertion</t:TokenType> \
    //                                 </t:RequestSecurityToken> \
    //                             </s:Body> \
    //                         </s:Envelope>';
    //             }
    //         };
    //     }
    // }]);

    // Sign up Controller
    app.controller('signupController', ['$http', '$scope', '$rootScope', function($http, $scope, $rootScope) {

        $scope.username = '';
        $scope.password = '';
        $scope.email = '';

        $scope.register = function() {

            if ($scope.username === '' && $scope.password === '' && $scope.email === '') {

                ons.notification.alert({
                    message: "You can't leave any fields empty"
                });

            } else if ($scope.password.length < 6) {

                ons.notification.alert({
                    message: "Your password must have 6 characters or more."
                });

            } else {

                modal.show();

                $http.jsonp(api + 'get_nonce/?controller=user&method=register&callback=JSON_CALLBACK').success(
                    function(response) {
                        console.log(response);
                        if (response.status == 'ok') {

                            $scope.nonce = response.nonce;

                            $http.jsonp(api + 'user/register/?username=' + $scope.username + '&email=' + $scope.email + '&nonce=' + $scope.nonce + '&user_pass=' + $scope.password + '&display_name=' + $scope.username + '&notify=no&callback=JSON_CALLBACK').success(
                                function(response) {

                                    if (response.status == 'ok') {

                                        // We save the cookie
                                        window.localStorage.setItem("rootsCookie", response.cookie);
                                        modal.hide();
                                        $scope.menu.setMainPage('home.html', {
                                            closeMenu: true
                                        });

                                    } else if (response.status == 'error') {

                                        modal.hide();
                                        ons.notification.alert({
                                            message: response.error
                                        });

                                    } else {

                                        modal.hide();
                                        ons.notification.alert({
                                            message: 'There was a problem trying to create your account, please try again.'
                                        });

                                    }

                                }
                            );

                        } else {

                            modal.hide();
                            ons.notification.alert({
                                message: 'There was an error trying to connect to the server, please try again.'
                            });

                        }
                    }
                );

            }

        };

    }]);

    app.directive('datePicker', function() {
        return {
            link: function postLink(scope, element, attrs) {
                scope.$watch(attrs.datePicker, function() {
                    if (attrs.datePicker === 'start') {
                        //element.pickadate();
                    }
                });
            }
        };
    });

    app.controller('networkController', function($scope) {

        // Check if is Offline
        document.addEventListener("offline", function() {

            offlineMessage.show();

            /* 
             * With this line of code you can hide the modal in 8 seconds but the user will be able to use your app
             * If you want to block the use of the app till the user gets internet again, please delete this line.       
             */

            setTimeout('offlineMessage.hide()', 8000);

        }, false);

        document.addEventListener("online", function() {
            // If you remove the "setTimeout('offlineMessage.hide()', 8000);" you must remove the comment for the line above      
            // offlineMessage.hide();
        });

    });

    // This functions will help us save the JSON in the localStorage to read the website content offline
    Storage.prototype.setObject = function(key, value) {
        this.setItem(key, JSON.stringify(value));
    };

    Storage.prototype.getObject = function(key) {
        var value = this.getItem(key);
        return value && JSON.parse(value);
    };

    // This directive will allow us to cache all the images that have the img-cache attribute in the <img> tag
    app.directive('imgCache', ['$document', function($document) {
        return {
            link: function(scope, ele, attrs) {
                var target = $(ele);

                scope.$on('ImgCacheReady', function() {

                    ImgCache.isCached(attrs.src, function(path, success) {
                        if (success) {
                            ImgCache.useCachedFile(target);
                        } else {
                            ImgCache.cacheFile(attrs.src, function() {
                                ImgCache.useCachedFile(target);
                            });
                        }
                    });
                }, false);

            }
        };
    }]);


    app.controller('menuController', ['$http', '$scope', '$rootScope', function($http, $scope, $rootScope) {
        $scope.menuAPI = 'http://eohict.co.za/api/get_category_index';
        $scope.menuItems = [];
        $scope.isFetchingMenu = true;

document.addEventListener("deviceready", onDeviceReady, false);
function onDeviceReady() {
    StatusBar.hide();
}        

        // This function pulls the menu from your website
        $scope.pullContent = function() {
            $http.jsonp($scope.menuAPI + '/?callback=JSON_CALLBACK').success(function(response) {
                $scope.menuItems = $scope.menuItems.concat(response.categories);
                window.localStorage.setObject('rootsMenu', $scope.menuItems); // we save the posts in localStorage
                window.localStorage.setItem('rootsMenuDate', new Date());

                // For dev purposes you can remove the comment for the line below to check on the console the size of your JSON in local Storage
                // for(var x in localStorage)console.log(x+"="+((localStorage[x].length * 2)/1024/1024).toFixed(2)+" MB");

                $scope.isFetchingMenu = false;
            });
        };

        // This function opens the category.html and prints all the posts from the category you selected on the menu
        $scope.showCategory = function(index) {
            $rootScope.categoryID = $scope.menuItems[index];
            $scope.menu.setMainPage('category.html', {
                closeMenu: true
            });
        };

        // If we rootsMenu Date and rootsMenu are not null that means we already have a version saved on the mobile
        if (window.localStorage.getItem("rootsMenuDate") !== null && window.localStorage.getObject("rootsMenu") !== null) {

            var now = new Date();
            var saved = new Date(window.localStorage.getItem('rootsMenuDate'));

            var difference = Math.abs(now.getTime() - saved.getTime()) / 3600000;

            // Lets compare the current dateTime with the one we saved when we got the menu items.
            // If the difference between the dates is more than 12 hours I think is time to get fresh content
            // You can change the 12 to something shorter or longer

            if (difference > 12) {

                window.localStorage.removeItem('rootsMenu');
                window.localStorage.removeItem('rootsMenuDate');

                $scope.pullContent();

            } else {

                $scope.menuItems = window.localStorage.getObject('rootsMenu');
                $scope.isFetchingMenu = false;

            }

        } else {
            $scope.pullContent();
        }
        
        // pushwoosh
        document.addEventListener("deviceready", function() {
            if (device.platform == "Android") {
                initPushwoosh_Android();
                
                document.addEventListener('push-notification', function(event) {
                    var title = event.notification.title;
                    var userData = event.notification.userdata;
                
                    console.warn('user data: ' + JSON.stringify(userData));
                    alert(title);
                });
            }
            if (device.platform == "iPhone" || device.platform == "iOS") {
                initPushwoosh_IOS();
                
                document.addEventListener('push-notification', function(event) {
                    var notification = event.notification;
                    alert(notification.aps.alert);
                    pushNotification.setApplicationIconBadgeNumber(0);
                });
            }
            if (device.platform == "Windows") {
                initPushwoosh_Windows();

                document.addEventListener('push-notification', function(event) {
                    //get the notification payload
                    var notification = event.notification;
                
                    //display alert to the user for example
                    alert(JSON.stringify(notification));
                });
            }
        }, false);

        $scope.internationalDialling = function() {
            var ref = window.open('http://eohict.co.za/bu-info/', '_blank', 'location=yes');
        };  

    }]);

   // Agenda Controller 
  // This controller gets all the posts from our WordPress site and inserts them into a variable called $scope.items
  app.controller('agendaController', [ '$http', '$scope', '$rootScope', function($http, $scope, $rootScope){ 
            

    $scope.yourAPI = 'http://eohict.co.za/api/get_category_posts/?id=11';
    $scope.items = [];
    $scope.totalPages = 0;
    $scope.currentPage = 1;
    $scope.pageNumber = 1;
    $scope.isFetching = true;
    $scope.lastSavedPage = 0;

    // Let's initiate this on the first Controller that will be executed.
    ons.ready(function() {
      
      // Cache Images Setup
      // Set the debug to false before deploying your app
      ImgCache.options.debug = true;

      ImgCache.init(function(){

        //console.log('ImgCache init: success!');
        $rootScope.$broadcast('ImgCacheReady');
        // from within this function you're now able to call other ImgCache methods
        // or you can wait for the ImgCacheReady event

      }, function(){
        //console.log('ImgCache init: error! Check the log for errors');
      });

    });


    $scope.pullContent = function(){
      
      $http.jsonp($scope.yourAPI+'/?page='+$scope.pageNumber+'&callback=JSON_CALLBACK').success(function(response) {

        if($scope.pageNumber > response.pages){

          // hide the more news button
          $('#moreButton[rel=home]').fadeOut('fast');  

        } else {

          $scope.items = $scope.items.concat(response.posts);
          window.localStorage.setObject('rootsPostsHome', $scope.items); // we save the posts in localStorage
          window.localStorage.setItem('rootsDateHome', new Date());
          window.localStorage.setItem("rootsLastPageHome", $scope.currentPage);
          window.localStorage.setItem("rootsTotalPagesHome", response.pages);

          // For dev purposes you can remove the comment for the line below to check on the console the size of your JSON in local Storage
          // for(var x in localStorage)console.log(x+"="+((localStorage[x].length * 2)/1024/1024).toFixed(2)+" MB");

          $scope.totalPages = response.pages;
          $scope.isFetching = false;

          if($scope.pageNumber == response.pages){

            // hide the more news button
            $('#moreButton[rel=home]').fadeOut('fast'); 

          }

        }

      });

    };

    $scope.getAllRecords = function(pageNumber){

      $scope.isFetching = true;    

      if (window.localStorage.getItem("rootsLastPageHome") === null ) {

        $scope.pullContent();

      } else {
        
        var now = new Date();
        var saved = new Date(window.localStorage.getItem("rootsDateHome"));

        var difference = Math.abs( now.getTime() - saved.getTime() ) / 3600000;

        // Lets compare the current dateTime with the one we saved when we got the posts.
        // If the difference between the dates is more than 24 hours I think is time to get fresh content
        // You can change the 24 to something shorter or longer

        if(difference > 24){
          // Let's reset everything and get new content from the site.
          $scope.currentPage = 1;
          $scope.pageNumber = 1;
          $scope.lastSavedPage = 0;
          window.localStorage.removeItem("rootsLastPageHome");
          window.localStorage.removeItem("rootsPostsHome");
          window.localStorage.removeItem("rootsTotalPagesHome");
          window.localStorage.removeItem("rootsDateHome");

          $scope.pullContent();
        
        } else {
          
          $scope.lastSavedPage = window.localStorage.getItem("rootsLastPageHome");

          // If the page we want is greater than the last saved page, we need to pull content from the web
          if($scope.currentPage > $scope.lastSavedPage){

            $scope.pullContent();
          
          // else if the page we want is lower than the last saved page, we have it on local Storage, so just show it.
          } else {

            $scope.items = window.localStorage.getObject('rootsPostsHome');
            $scope.currentPage = $scope.lastSavedPage;
            $scope.totalPages = window.localStorage.getItem("rootsTotalPagesHome");
            $scope.isFetching = false;

          }

        }

      }

    };

    $scope.imgLoadedEvents = {
        done: function(instance) {
            angular.element(instance.elements[0]).removeClass('is-loading').addClass('is-loaded');
        }
    };

    $scope.showPost = function(index){
        
      $rootScope.postContent = $scope.items[index];
      $scope.ons.navigator.pushPage('agendaMore.html');

    };

    $scope.nextPage = function(){

      $scope.currentPage++; 
      $scope.pageNumber = $scope.currentPage;                 
      $scope.getAllRecords($scope.pageNumber);        

    };

  }]);
  
  // Speakers Controller 
  // This controller gets all the posts from our WordPress site and inserts them into a variable called $scope.items
  app.controller('speakersController', [ '$http', '$scope', '$rootScope', function($http, $scope, $rootScope){

    $scope.nextSpeakerPage = function(){

      $scope.currentPage++; 
      $scope.pageNumber = $scope.currentPage;                 
      $scope.getAllRecords($scope.pageNumber);        

    };

    $scope.yourAPI = 'http://eohict.co.za/api/get_category_posts/?id=4';
    $scope.items = [];
    $scope.totalPages = 0;
    $scope.currentPage = 1;
    $scope.pageNumber = 1;
    $scope.isFetching = true;
    $scope.lastSavedPage = 0;

    // Let's initiate this on the first Controller that will be executed.
    ons.ready(function() {
      
      // Cache Images Setup
      // Set the debug to false before deploying your app
      ImgCache.options.debug = true;

      ImgCache.init(function(){

        //console.log('ImgCache init: success!');
        $rootScope.$broadcast('ImgCacheReady');
        // from within this function you're now able to call other ImgCache methods
        // or you can wait for the ImgCacheReady event

      }, function(){
        //console.log('ImgCache init: error! Check the log for errors');
      });

    });


    $scope.pullContent = function(){
      
      $http.jsonp($scope.yourAPI+'&page='+$scope.pageNumber+'&callback=JSON_CALLBACK').success(function(response) {

        if($scope.pageNumber > response.pages){

          // hide the more news button
          $('#moreButton[rel=home]').fadeOut('fast');  

        } else {

          $scope.items = $scope.items.concat(response.posts);
          window.localStorage.setObject('rootsSpeakerHome', $scope.items); // we save the posts in localStorage
          window.localStorage.setItem('rootsDateSpeaker', new Date());
          window.localStorage.setItem("rootsLastSpeakerHome", $scope.currentPage);
          window.localStorage.setItem("rootsTotalSpeakerHome", response.pages);

          // For dev purposes you can remove the comment for the line below to check on the console the size of your JSON in local Storage
          // for(var x in localStorage)console.log(x+"="+((localStorage[x].length * 2)/1024/1024).toFixed(2)+" MB");

          $scope.totalPages = response.pages;
          $scope.isFetching = false;

          if($scope.pageNumber == response.pages){

            // hide the more news button
            $('#moreButton[rel=home]').fadeOut('fast'); 

          }

        }

      });

    };

    $scope.getAllRecords = function(pageNumber){

      $scope.isFetching = true;    

      if (window.localStorage.getItem("rootsLastSpeakerHome") === null ) {

        $scope.pullContent();

      } else {
        
        var now = new Date();
        var saved = new Date(window.localStorage.getItem("rootsDateSpeaker"));

        var difference = Math.abs( now.getTime() - saved.getTime() ) / 3600000;

        // Lets compare the current dateTime with the one we saved when we got the posts.
        // If the difference between the dates is more than 24 hours I think is time to get fresh content
        // You can change the 24 to something shorter or longer

        if(difference > 24){
          // Let's reset everything and get new content from the site.
          $scope.currentPage = 1;
          $scope.pageNumber = 1;
          $scope.lastSavedPage = 0;
          window.localStorage.removeItem("rootsLastSpeakerHome");
          window.localStorage.removeItem("rootsSpeakerHome");
          window.localStorage.removeItem("rootsTotalSpeakerHome");
          window.localStorage.removeItem("rootsDateSpeaker");

          $scope.pullContent();
        
        } else {
          
          $scope.lastSavedPage = window.localStorage.getItem("rootsLastSpeakerHome");

          // If the page we want is greater than the last saved page, we need to pull content from the web
          if($scope.currentPage > $scope.lastSavedPage){

            $scope.pullContent();
          
          // else if the page we want is lower than the last saved page, we have it on local Storage, so just show it.
          } else {

            $scope.items = window.localStorage.getObject('rootsSpeakerHome');
            $scope.currentPage = $scope.lastSavedPage;
            $scope.totalPages = window.localStorage.getItem("rootsTotalSpeakerHome");
            $scope.isFetching = false;

          }

        }

      }

    };

    $scope.imgLoadedEvents = {
        done: function(instance) {
            angular.element(instance.elements[0]).removeClass('is-loading').addClass('is-loaded');
        }
    };

    $scope.showPost = function(index){
        
      $rootScope.postContent = $scope.items[index];
      $scope.ons.navigator.pushPage('speakerMore.html');

    };

  }]);
  
  // This controller let us print the Post Content in the post.html template
  app.controller('SpostController', [ '$scope', '$rootScope', '$sce', function($scope, $rootScope, $sce){
    $scope.item = $rootScope.postContent;
    $scope.renderHtml = function (htmlCode) {
        return $sce.trustAsHtml(htmlCode);
    };
    $scope.imgLoadedEvents = {
        done: function(instance) {
            angular.element(instance.elements[0]).removeClass('is-loading').addClass('is-loaded');
        }
    };    
  }]);

    // This controller let us print the Post Content in the post.html template
    app.controller('ApostController', ['$scope', '$rootScope', '$sce', function($scope, $rootScope, $sce) {
        $scope.item = $rootScope.postContent;
        $scope.renderHtml = function(htmlCode) {
            return $sce.trustAsHtml(htmlCode);
        };
        $scope.imgLoadedEvents = {
            done: function(instance) {
                angular.element(instance.elements[0]).removeClass('is-loading').addClass('is-loaded');
            }
        };
    }]);

    ///Twitter feed controller
    app.controller('twitterController', function($scope) {
        $scope.openWebsite = function() {
            var ref = window.open(encodeURI('http://k-rudy.github.io/phonegap-twitter-timeline?410453165654278145'), '_blank', 'location=yes');
        };
    });

    ///uber feed controller
    app.controller('uberController', function($scope) {
        $scope.openUber = function() {
		   var ref = window.open('GET https://m.uber.com', '_blank', 'location=yes');
        };
    });
	 ///Presentation feed controller
    app.controller('preseController', function($scope) {
        $scope.openpres = function() {
		   var ref = window.open('http://eohict.co.za/presentations/', '_blank', 'location=yes');
        };
    });

	///BU feed controller
    app.controller('buController', function($scope) {
        $scope.openbu = function() {
            var ref = window.open('http://eohict.co.za/bu-info/', '_blank', 'location=yes');
        };
    });

    ///Feedback form controller
    app.controller('feedbackController', function($scope) {
    //     $scope.openfeed = function() {
    //         var ref = window.open('http://eohict.co.za/form/form.php', '_blank', 'location=yes');
    //     };
    });
    
    ///Feedback Overall controller
    app.controller('fbOverallCtrl', ['$scope', '$http', function($scope, $http) {
        // Posting data to php file
        $scope.user = {};
        $scope.user.username = window.localStorage.getItem("ictUsername");

        $scope.submitForm = function() {
            $http({
                method    : 'POST',
                url       : 'http://eohict.co.za/form/feedback_Overall.php',
                data      : $scope.user, // forms user object
                headers   : {'Content-Type': 'application/x-www-form-urlencoded'}
            })
            .success(function(data) {
                if (data.errors) {
                    // Showing errors.
                    document.querySelector(".errors").style.display = "block";

                    $scope.errorfname       = data.errors.fname;
                    $scope.errorbu          = data.errors.bu;
                    $scope.errorfbsession   = data.errors.fbsession;
                } else {
                    console.log("success");
                    // Clear errors
                    document.querySelector(".errors").style.display = "none";

                    $scope.errorfname       = '';
                    $scope.errorbu          = '';
                    $scope.errorfbsession   = '';

                    $scope.menu.setMainPage('feedbackThankyou.html', {
                        closeMenu: true
                    });
                }
            });
        };
    }]);

    ///Feedback Session controller
    app.controller('fbSessionCtrl', ['$scope', '$http', function($scope, $http) {
        // Posting data to php file
        $scope.user = {};
        $scope.user.username = window.localStorage.getItem("ictUsername");
        $scope.user.form = document.getElementById("form").value;

        $scope.submitForm = function() {
            $http({
                method    : 'POST',
                url       : 'http://eohict.co.za/form/feedback_Session.php',
                data      : $scope.user, // forms user object
                headers   : {'Content-Type': 'application/x-www-form-urlencoded'}
            })
            .success(function(data) {
                console.log(data);
                if (data.errors) {
                    // Showing errors.
                    document.querySelector(".errors").style.display = "block";

                    $scope.errorfname       = data.errors.fname;
                    $scope.errorrate        = data.errors.rate;
                    $scope.errorfbsession   = data.errors.fbsession;
                } else {
                    console.log("success");
                    // Clear errors
                    document.querySelector(".errors").style.display = "none";

                    $scope.errorfname       = '';
                    $scope.errorrate        = '';
                    $scope.errorfbsession   = '';

                    $scope.menu.setMainPage('feedbackThankyou.html', {
                        closeMenu: true
                    });
                }
            });
        };
    }]);

    /////day1 checkin controller
    app.controller('day1Controller', function($scope) {
    //   $.getScript('http://eohict.co.za/Scripts/checkin.js', function() {
    //     //script is loaded and executed put your dependent JS here
    //     console.log("this happened");
    //   });
    });

    // Venue Controller
    app.controller('venueController', ['$scope', function($scope) {
        var wrapper = document.getElementById('wrapper');
        var myScroll = new IScroll(wrapper,{
            // freeScroll: true,
            scrollX: true,
            zoom: true
        });
    }]);

    // Map Markers Controller
    app.controller('markersController', function($scope, $compile) {
        $scope.infoWindow = {
            title: 'title',
            content: 'content'
        };

        $scope.markers = [{
                'title': 'Indaba Hotal',
                'content': 'William Nicol Drive Fourways Johannesburg  +27 (0) 11 840 6600 ',
                'location': [-25.998520553134, 28.014000525964]
            },
        ];

        $scope.showMarker = function(event) {
            $scope.marker = $scope.markers[this.id];
            $scope.infoWindow = {
                title: $scope.marker.title,
                content: $scope.marker.content
            };
            $scope.$apply();
            $scope.showInfoWindow(event, 'marker-info', this.getPosition());
        };
    });

    // Plugins Controller
    app.controller('pluginsController', function($scope, $compile) {

        $scope.openWebsite = function() {
            var ref = window.open(encodeURI('http://k-rudy.github.io/phonegap-twitter-timeline?410453165654278145'), '_blank', 'location=no');
        };

        $scope.openSocialSharing = function() {

            window.plugins.socialsharing.share('Message, image and link', null, 'https://www.google.com/images/srpr/logo4w.png', 'http://www.google.com');

            /*
       *  Social Sharing Examples
       *  For more examples check the documentation: https://github.com/EddyVerbruggen/SocialSharing-PhoneGap-Plugin
   
        window.plugins.socialsharing.share('Message only')
        window.plugins.socialsharing.share('Message and subject', 'The subject')
        window.plugins.socialsharing.share(null, null, null, 'http://www.google.com')
        window.plugins.socialsharing.share('Message and link', null, null, 'http://www.google.com')
        window.plugins.socialsharing.share(null, null, 'https://www.google.com/images/srpr/logo4w.png', null)
        window.plugins.socialsharing.share('Message and image', null, 'https://www.google.com/images/srpr/logo4w.png', null)
        window.plugins.socialsharing.share('Message, image and link', null, 'https://www.google.com/images/srpr/logo4w.png', 'http://www.google.com')
        window.plugins.socialsharing.share('Message, subject, image and link', 'The subject', 'https://www.google.com/images/srpr/logo4w.png', 'http://www.google.com')
      *
      */

        };


        $scope.openEmailClient = function() {

            ons.ready(function() {

                cordova.plugins.email.open({
                    to: 'han@solo.com',
                    subject: 'Hey!',
                    body: 'May the <strong>force</strong> be with you',
                    isHtml: true
                });

            });

        };

        $scope.getDirectionsApple = function() {

            window.location.href = "maps://maps.apple.com/?q=37.774929,-122.419416";

        };

        $scope.getDirectionsGoogle = function() {

            var ref = window.open('http://maps.google.com/maps?q=37.774929,-122.419416', '_system', 'location=yes');

        };

        $scope.getDate = function() {

            var options = {
                date: new Date(),
                mode: 'date'
            };

            datePicker.show(options, function(date) {
                alert("date result " + date);
            });

        };

    });

   app.controller('aboutController',function($scope) {

        $scope.try = function() {
            var data = "firstName=test&lastName=test&mainEmail=test6%40test6.com&company=test%40test.com";
            var xhr = new XMLHttpRequest();
            xhr.withCredentials = true;

            xhr.addEventListener("readystatechange", function () {
            if (this.readyState === 4) {
                console.log(this.responseText);


                for (var i = 0; i < this.responseText.length; i++) {
                    this.responseText[i].post_title;   
                    console.log(this.responseText[i].post_title);            
                }                

                // var now             = new Date().getTime(),
                //     _5_sec_from_now = new Date(now + 5*1000);

                // cordova.plugins.notification.local.schedule({
                //     text: "Delayed Notification",
                //     at: _5_sec_from_now,
                //     led: "FF0000",
                //     sound: null
                // });                 
            }
            });

            xhr.open("GET", "http://eohconnect.com/eoh_api/agenda?meta=");
            xhr.setRequestHeader("content-type", "application/json");
            xhr.setRequestHeader("insecure", "insecure=cool");
            xhr.setRequestHeader("cache-control", "no-cache");
            xhr.setRequestHeader("postman-token", "3d74a064-a7a8-31bd-1b99-696221ca7b9d");

            xhr.send(data);            

        }

        $scope.internationalDialling = function() {
            var ref = window.open('http://eohict.co.za/bu-info/', '_blank', 'location=yes');
        };       

        $scope.slides = [
            {image: 'images/march/home_icon01.png', description: 'Image 00'},
            {image: 'images/march/home_icon02.png', description: 'Image 01'},
            {image: 'images/march/home_icon03.png', description: 'Image 02'},
            {image: 'images/march/home_icon04.png', description: 'Image 03'},
            {image: 'images/march/home_icon05.png', description: 'Image 04'},
            {image: 'images/march/home_icon06.png', description: 'Image 05'},
            {image: 'images/march/home_icon07.png', description: 'Image 06'}            
        ];

        $scope.direction = 'left';
        $scope.currentIndex = 0;

        $scope.setCurrentSlideIndex = function (index) {
            $scope.direction = (index > $scope.currentIndex) ? 'left' : 'right';
            $scope.currentIndex = index;
        };

        $scope.isCurrentSlideIndex = function (index) {
            return $scope.currentIndex === index;
        };

        $scope.prevSlide = function () {
            $scope.direction = 'left';
            $scope.currentIndex = ($scope.currentIndex < $scope.slides.length - 1) ? ++$scope.currentIndex : 0;
        };

        $scope.nextSlide = function () {
            $scope.direction = 'right';
            $scope.currentIndex = ($scope.currentIndex > 0) ? --$scope.currentIndex : $scope.slides.length - 1;
        };
    })
    .animation('.slide-animation', function () {
        return {
            beforeAddClass: function (element, className, done) {
                var scope = element.scope();

                if (className == 'ng-hide') {
                    var finishPoint = element.parent().width();
                    if(scope.direction !== 'right') {
                        finishPoint = -finishPoint;
                    }
                    TweenMax.to(element, 0.5, {left: finishPoint, onComplete: done });
                }
                else {
                    done();
                }
            },
            removeClass: function (element, className, done) {
                var scope = element.scope();

                if (className == 'ng-hide') {
                    element.removeClass('ng-hide');

                    var startPoint = element.parent().width();
                    if(scope.direction === 'right') {
                        startPoint = -startPoint;
                    }

                    TweenMax.fromTo(element, 0.5, { left: startPoint }, {left: 0, onComplete: done });
                }
                else {
                    done();
                }
            }
        };

    });

})();