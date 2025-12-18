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

</body>

</html>
