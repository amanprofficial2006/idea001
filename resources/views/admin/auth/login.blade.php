<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Admin Login</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	{{-- Tailwind CDN --}}
	<script src="https://cdn.tailwindcss.com"></script>

	<style>
		body {
			background: linear-gradient(135deg, #0f172a, #020617);
		}
	</style>
</head>

<body class="flex min-h-screen items-center justify-center">

	<div class="w-full max-w-md rounded-2xl bg-white p-8 shadow-2xl">

		{{-- Header --}}
		<div class="mb-6 text-center">
			<h1 class="text-3xl font-bold text-gray-800">Admin Panel</h1>
			<p class="mt-1 text-gray-500">Login to continue</p>
		</div>

		{{-- Error Message --}}
		@if ($errors->any())
			<div class="mb-4 rounded-lg bg-red-100 px-4 py-3 text-sm text-red-700">
				{{ $errors->first() }}
			</div>
		@endif

		{{-- Login Form --}}
		<form method="POST" action="{{ route("admin.login.submit") }}" class="space-y-5">
			@csrf

			{{-- Email --}}
			<div>
				<label class="mb-1 block text-sm font-medium text-gray-700">
					Email Address
				</label>
				<input type="email" name="email" required autofocus
					class="w-full rounded-lg border px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
					placeholder="admin@example.com">
			</div>

			{{-- Password --}}
			<div>
				<label class="mb-1 block text-sm font-medium text-gray-700">
					Password
				</label>
				<input type="password" name="password" required
					class="w-full rounded-lg border px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
					placeholder="••••••••">
			</div>

			{{-- 🔥 HIDDEN FIELDS FOR FIREBASE --}}
			<input type="hidden" name="device_token" id="device_token">
			<input type="hidden" name="device_type" value="web">

			{{-- Submit --}}
			<button type="submit"
				class="w-full rounded-lg bg-indigo-600 py-2.5 font-semibold text-white transition duration-200 hover:bg-indigo-700">
				Login
			</button>
		</form>

		{{-- Footer --}}
		<div class="mt-6 text-center text-sm text-gray-400">
			© {{ date("Y") }} Your Platform. All rights reserved.
		</div>

	</div>

	{{-- Firebase JS for device token --}}
	<script type="module">
		// Import the functions you need from the SDKs you need
		import {
			initializeApp
		} from "https://www.gstatic.com/firebasejs/12.8.0/firebase-app.js";
		import {
			getMessaging,
			getToken
		} from "https://www.gstatic.com/firebasejs/12.8.0/firebase-messaging.js";

		// Your web app's Firebase configuration
		const firebaseConfig = {
			apiKey: "AIzaSyBpyCu8DzHm-sEV8vQWeOpvELMKwEeaBAI",
			authDomain: "dohelp-7d140.firebaseapp.com",
			projectId: "dohelp-7d140",
			storageBucket: "dohelp-7d140.firebasestorage.app",
			messagingSenderId: "228064919901",
			appId: "1:228064919901:web:b6d1f42822b129419ea8b4",
			measurementId: "G-61V67TYW8N"
		};

		// Initialize Firebase
		const app = initializeApp(firebaseConfig);
		const messaging = getMessaging(app);

		async function getDeviceToken() {
			try {
				const token = await getToken(messaging, {
					vapidKey: 'BIWhgwdKKixoudiW4sKuSMi_eMpE1r4JxQpePCraP2i8O6XiEJbkkoL_CtSio5J4omUl8_pFliP2l2vN4qR9U-U'
				});
				return token;
			} catch (error) {
				console.error('Error getting FCM token:', error);
				return null;
			}
		}

		document.addEventListener("DOMContentLoaded", async () => {
			try {
				if (Notification.permission !== "granted") {
					await Notification.requestPermission();
				}

				const token = await getDeviceToken();

				if (token) {
					document.getElementById("device_token").value = token;
					console.log("Admin FCM Token:", token);
				} else {
					console.warn("FCM token not generated");
				}
			} catch (error) {
				console.error("Firebase token error:", error);
			}
		});
	</script>

</body>

</html>
