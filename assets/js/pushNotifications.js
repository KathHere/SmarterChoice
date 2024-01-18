jQuery(document).ready(function($){
		//Track state of push permission
		var pushStatus;
		var sw_support=false;

		window.onload = function() {
		  
		  if ('serviceWorker' in navigator) {
		    navigator.serviceWorker.register('sw.js').then(function(registration) {
		        // Registration was successful.
		        console.log('ServiceWorker registration successful with scope: ',    registration.scope);
		        //Check subscription state
		        checkSubscription();

		        //Attache listener
		        // do unsubscribe or subcribe depending on the usertype
		        // subscribe if admin
		        // unsubscribe if not
		        $.get('checkusertype',function(response){
		            if(response.data.usertype=="dme_admin")
		            {
		                 checkSubscription();
		                 //check if push status is unsubscribe then do subscribe
		            }
		            else
		            {
		            	//checksubscription and if pushstatus=true then do unsubscribe
		            	
		            }
		        });
		    }).catch(function(err) {
		      // registration failed :(
		      console.log('ServiceWorker registration failed: ', err);
		    });
		  }
		  else {
		    console.log("ServiceWorker not supported :-(");
		  }
		};
		function subUnsubPush(e) 
		{
		  if(!pushStatus) subscribePush();
		  else  unsubscribePush();
		}

		function subscribePush() 
		{
		  navigator.serviceWorker.ready.then(function(serviceWorkerRegistration) {
		    //Need to check notification permission?
		    serviceWorkerRegistration.pushManager.subscribe({userVisibleOnly: true})
		      .then(function(pushSubscription) {
		        //sendSubscription(pushSubscription);
		        sendSub(pushSubscription);
		        pushStatus = true;
		      })
		      .catch(function(e) {
		        console.error('Unable to register for push', e);
		      });
		  });
		}

		function unsubscribePush() 
		{
		  navigator.serviceWorker.ready.then(function(serviceWorkerRegistration) {  
		 
		    serviceWorkerRegistration.pushManager.getSubscription().then(  
		      function(pushSubscription) {  
		        // Check we have a subscription to unsubscribe  
		        if (!pushSubscription) {  
		          // Nothing to unsubscribe
		          pushStatus = false;
		          return;  
		        }  
		        //Remove from application server
		        cancelSub(pushSubscription);
		        // We have a subscription, so unsubscribe  it  
		        pushSubscription.unsubscribe().then(function() {  
		          pushStatus = false;
		        }).catch(function(e) {  
		          console.log('Error unsubscribing: ', e);  
		        });  
		      }).catch(function(e) {  
		        console.error('Error unsubscribing.', e);  
		      });  
		  });  
		}

		function sendSub(pushSubscription) 
		{
		    //get endpoint
		    const endPoint = pushSubscription.endpoint.slice(pushSubscription.endpoint.lastIndexOf('/')+1);
		    //fetch("https://mobiforge.com/push/subscribe.php?sid=yyy"+pushSubscription.endpoint+"&act=sub").then(function(res) {
		   fetch("https://mobiforge.com/push/subscribe.php?sid="+endPoint+"&act=sub").then(function(res) {
		      res.json().then(function(data) {
		      }).catch(function(e) {
		          console.error('Error sending subscription to server:', e);
		        }); 
		    })
		}
		function checkSubscription() 
		{
		  navigator.serviceWorker.ready.then(function(serviceWorkerRegistration) {
		    serviceWorkerRegistration.pushManager.getSubscription().then(
		      function(pushSubscription) {
		        if(!!pushSubscription) {
		          console.log(pushSubscription);
		          pushStatus = true;
		          sendSub(pushSubscription);
		        }
		        else {
		          pushStatus = false;
		        }
		        
		      }.bind(this)).catch(function(e) {
		        console.error('Error getting subscription', e);
		      });
		  });
		}


		function disableNotifications() 
		{
		  navigator.serviceWorker.ready.then(function(serviceWorkerRegistration) {
		    serviceWorkerRegistration.pushManager.getSubscription().then(
		      function(pushSubscription) {
		        if(pushSubscription) {
		          pushSubscription.unsubscribe().then(function(successful) {
		            cancelSub(pushSubscription);
		          }).catch(function(e) {
		            console.log('Disabling push notifications failed: ', e);
		          });
		        }
		      }).catch(function(e) {
		        console.error('Error thrown while unsubscribing from push messaging.', e);
		      }); 
		  }); 
		}


		function cancelSub(pushSubscription) 
		{
		  const endPoint = pushSubscription.endpoint.slice(pushSubscription.endpoint.lastIndexOf('/')+1);
		  fetch("https://mobiforge.com/push/subscribe.php?sid="+endPoint+"&act=unsub").then(function(res) {
		    res.json().then(function(data) {
		    })
		  })
		}
}); //end of jquery