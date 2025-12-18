<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>@yield("title", "Admin Panel")</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	{{-- Tailwind CDN --}}
	<script src="https://cdn.tailwindcss.com"></script>
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
