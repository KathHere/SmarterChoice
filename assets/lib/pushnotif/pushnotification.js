jQuery(document).ready(function($){
		//Track state of push permission
		var pushStatus;
		var sw_support=false;

		window.onload = function() {
		  
		  if ('serviceWorker' in navigator) {
		  //	console.log(base_url);
		    navigator.serviceWorker.register(base_url+'sw.js').then(function(registration) {
		        // Registration was successful.
		       console.log('ServiceWorker registration successful with scope: ',    registration.scope);
		        //Check subscription state

		        //Attache listener
		        // do unsubscribe or subcribe depending on the usertype
		        // subscribe if admin
		        // unsubscribe if not
		        $.get('main/getusertype',function(response){
		            if(response=="dme_admin")
		            {
		                 checkSubscription();
		                 //check if push status is unsubscribe then do subscribe
		                 subscribePush();
		            }
		            else
		            {
		            	//checksubscription and if pushstatus=true then do unsubscribe
		            	unsubscribePush();
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
		        console.log('Unable to register for push', e);
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
		        console.log('Error unsubscribing.', e);  
		      });  
		  });  
		}

		function sendSub(pushSubscription) 
		{
		    //get endpoint
		    const endPoint = pushSubscription.endpoint.slice(pushSubscription.endpoint.lastIndexOf('/')+1);
		   $.get(base_url+"subscribe/?sid="+endPoint+"&act=sub",function(response){

		  	})
		}
		function checkSubscription() 
		{
		  navigator.serviceWorker.ready.then(function(serviceWorkerRegistration) {
		    serviceWorkerRegistration.pushManager.getSubscription().then(
		      function(pushSubscription) {
		      	 const endPoint = pushSubscription.endpoint.slice(pushSubscription.endpoint.lastIndexOf('/')+1);
		      	 console.log(endPoint);
		        if(!!pushSubscription) {
		          pushStatus = true;
		          sendSub(pushSubscription);
		        }
		        else {
		          pushStatus = false;
		        }
		        
		      }.bind(this)).catch(function(e) {
		        console.log('Error getting subscription', e);
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
		        console.log('Error thrown while unsubscribing from push messaging.', e);
		      }); 
		  }); 
		}


		function cancelSub(pushSubscription) 
		{
		  const endPoint = pushSubscription.endpoint.slice(pushSubscription.endpoint.lastIndexOf('/')+1);
		  $.get(base_url+"subscribe/?sid="+endPoint+"&act=unsub",function(response){

		  })
		}
}); //end of jquery