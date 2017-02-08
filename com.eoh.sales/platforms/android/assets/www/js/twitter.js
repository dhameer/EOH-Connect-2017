////Twitter////
// JavaScript Document  // URL to be redirected after the OAuth authentication is done
      var callbackUrl = "http://www.example.com";
      
      // jsOAuth object
      var oauth = OAuth({
        consumerKey: "rfKooPZad9YKBOs4QrxMnw60J", // REPLACE HERE TO YOUR CONSUMER KEY or API KEY
        consumerSecret: "aLpC4JWZNyIsR6vE65SJq87x0AIDJW9vx4g1DHfPQCzGe1jkbj", // REPLACE HERE TO YOUR CONSUMER SECRET OR API SECRET
         callbackUrl: callbackUrl,
        requestTokenUrl: "https://api.twitter.com/oauth/request_token",
        authorizationUrl: "https://api.twitter.com/oauth/authorize",
        accessTokenUrl: "https://api.twitter.com/oauth/access_token"
      });
 
      // Get oAuth Request Token and display authentication window (3-legged request)
      
      function connect() {
      
          oauth.fetchRequestToken(function (url) {
            console.log("Opening Request Token URL: " + url);
            showAuthWindow(url);
          }, function (data) {
			
            console.log(JSON.stringify(data));
          });
      }
     
      // Display Twitter authentication page.
      // When the user logs in, obtain a verifier and get Access Token
      function showAuthWindow(url) {
        var browser = window.open(url, '_blank', 'location=yes');
        
        browser.addEventListener('loadstart', function(event) {
            
          if (event.url.indexOf(callbackUrl) >= 0) {
            event.url.match(/oauth_verifier=([a-zA-Z0-9]+)/);
            oauth.setVerifier(RegExp.$1);
            oauth.fetchAccessToken(function (data) {
              tweet();
              browser.close();
            }, function (data) {
              console.log(JSON.stringify(data));
            });
          }
        });
        
      }
      
      
      
	function Twitterpost(){
				connect();
				
	}
	

	  function tweet(){
		  var status;
		 
	  
			  status = document.getElementById('Message').value;
			  
	  
		oauth.post('https://api.twitter.com/1.1/statuses/update.json',
                    { 'status' : status,
					// document.getElementById('myimage').textContent,  // jsOAuth encodes for us
                      'trim_user' : 'true'
					  },
                    function(data) {
                        var entry = JSON.parse(data.text);
						console.log(entry);
						exit();
	window.cookies.clear(function() {
    console.log('Cookies cleared!');
});
                    },
                    function(data) { 
						console.log(data);
                    }
            );		
	
	  }
