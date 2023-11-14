<html>
  <body>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <!-- <div id="g_id_onload"
         data-client_id="252316539031-kv21d9r60qq7r6okculku9d38vn2rkpb.apps.googleusercontent.com"
         data-callback="handleCredentialResponse">
    </div> -->
    <div id="g_id_onload"
         data-client_id="252316539031-kv21d9r60qq7r6okculku9d38vn2rkpb.apps.googleusercontent.com"
         data-ux_mode="redirect"
         data-login_uri="{{env('APP_URL')}}/testAfterAuthSave">
    </div>
    <div class="g_id_signin" data-type="standard"></div>

    <!-- <div class="g_id_signin" data-type="standard"></div> -->
    <!-- <script type="text/javascript">
        function handleCredentialResponse(googleUser) {
        	console.log(googleUser)
        	console.log(parseJwt(googleUser.credential))
        }

        function parseJwt (token) {
			var base64Url = token.split('.')[1];
			var base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
			var jsonPayload = decodeURIComponent(window.atob(base64).split('').map(function(c) {
			    return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
			}).join(''));

			return JSON.parse(jsonPayload);
		};


    </script> -->
  </body>
</html>
