<!DOCTYPE html>
<html lang="cn">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>{{ env('APP_NAME') }}</title>
		<link href="/assets/css/bootstrap.min.css" rel="stylesheet" />
		<link rel="stylesheet" href="{{ cAsset('assets/css/font-awesome.min.css') }}" />
		<link rel="icon" type="image/png" href="{{ cAsset('/assets/css/img/logo.png') }}" sizes="192x192">
		<link rel="stylesheet" href="/assets/css/font-awesome-ie7.min.css" />


		<link rel="stylesheet" href="{{ cAsset('assets/css/ace.min.css') }}" />
		<link rel="stylesheet" href="{{ cAsset('assets/css/theme.css') }}" />
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	</head>
	<body>
		@yield('content')
	</body>
</html>
<script src="{{ asset('/assets/js/firebase-app.js') }}"></script>
<script type="text/javascript">
    var session_id = "{!! (Session::getId())?Session::getId():'' !!}";
    var user_id = "{!! (Auth::user())?Auth::user()->id:'' !!}";

    // Your web app's Firebase configuration
    var firebaseConfig = {
        apiKey: "FIREBASE_API_KEY",
        authDomain: "FIREBASE_AUTH_DOMAIN",
        databaseURL: "FIREBASE_DATABASE_URL",
        storageBucket: "FIREBASE_STORAGE_BUCKET",
    };
    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);

    var database = firebase.database();

    if ({!! Auth::user() !!}) {
        firebase.database().ref('/users/' + user_id + '/session_id').set(session_id);
    }

    firebase.database().ref('/users/' + user_id).on('value', function (snapshot2) {
        var v = snapshot2.val();

        if (v.session_id !== session_id) {
			
            console.log("Your account login from another device!!");

            setTimeout(function () {
                window.location = '/login';
            }, 4000);
        }
    });
</script>