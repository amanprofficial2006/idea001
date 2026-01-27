<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>@yield("title", "Admin Panel")</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	{{-- Favicon --}}
	@if ($organisation && $organisation->logo)
		<link rel="icon" href="{{ asset("storage/" . $organisation->logo) }}">
	@else
		<link rel="icon" type="image/x-icon" href="{{ asset("favicon.ico") }}">
	@endif

	{{-- Tailwind CDN --}}
	<script src="https://cdn.tailwindcss.com"></script>

	{{-- Firebase SDK --}}
	<script type="module">
		import {
			initializeApp
		} from 'https://www.gstatic.com/firebasejs/9.22.0/firebase-app.js';
		import {
			getMessaging,
			getToken,
			onMessage
		} from 'https://www.gstatic.com/firebasejs/9.22.0/firebase-messaging.js';

		// Your web app's Firebase configuration
		const firebaseConfig = {
			apiKey: "AIzaSyBpyCu8DzHm-sEV8vQWeOpvELMKwEeaBAI",
			authDomain: "dohelp-7d140.firebaseapp.com",
			projectId: "dohelp-7d140",
			storageBucket: "dohelp-7d140.firebasestorage.app",
			messagingSenderId: "228064919901",
			appId: "1:228064919901:web:b6d1f42822b129419ea8b4",
		};

		// Initialize Firebase
		const app = initializeApp(firebaseConfig);
		const messaging = getMessaging(app);

		// Register service worker
		if ('serviceWorker' in navigator) {
			navigator.serviceWorker.register('/firebase-messaging-sw.js')
				.then((registration) => {
					console.log('Service Worker registered successfully:', registration);
				})
				.catch((error) => {
					console.log('Service Worker registration failed:', error);
				});
		}

		// Make messaging available globally
		window.firebaseMessaging = messaging;
	</script>

	<script>
		const messaging = firebase.messaging();

		messaging.onMessage(function(payload) {
			console.log("Foreground message:", payload);

			// 🔥 MANUAL notification
			if (Notification.permission === "granted") {
				new Notification(payload.notification.title, {
					body: payload.notification.body,
					icon: payload.notification.icon || "/favicon.ico",
				});
			}
		});
	</script>

</head>

<body class="min-h-screen bg-gray-100">

	<div class="flex min-h-screen">

		{{-- Sidebar --}}
		@include("admin.layouts.sidebar")

		{{-- Main Content --}}
		<div id="main-content" class="flex flex-1 flex-col md:ml-64">

			{{-- Header --}}
			@include("admin.layouts.header")

			{{-- Page Content --}}
			<main class="flex-1 p-6">
				@yield("content")
			</main>

			{{-- Footer --}}
			@include("admin.layouts.footer")

		</div>
	</div>
	<script>
		const sidebar = document.getElementById('sidebar');
		const toggleBtn = document.getElementById('sidebar-toggle');
		const overlay = document.getElementById('sidebar-overlay');

		function openSidebar() {
			sidebar.classList.remove('-translate-x-full');
			overlay.classList.remove('hidden');
		}

		function closeSidebar() {
			sidebar.classList.add('-translate-x-full');
			overlay.classList.add('hidden');
		}

		toggleBtn?.addEventListener('click', () => {
			sidebar.classList.contains('-translate-x-full') ?
				openSidebar() :
				closeSidebar();
		});

		overlay?.addEventListener('click', closeSidebar);
	</script>

</body>

</html>
