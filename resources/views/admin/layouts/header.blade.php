<header class="sticky top-0 z-50 flex items-center justify-between bg-white px-6 py-4 shadow">

	{{-- Left: Toggle + Title --}}
	<div class="flex items-center gap-3">

		{{-- Sidebar Toggle --}}
		<button id="sidebar-toggle" class="text-gray-600 hover:text-gray-800 focus:outline-none">
			<svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
			</svg>
		</button>

		<h1 class="text-xl font-semibold text-gray-800">
			@yield("page-title", "Dashboard")
		</h1>
	</div>

	{{-- Right: Admin Info --}}
	<div class="flex items-center gap-4">

		<span class="text-sm text-gray-600">
			{{ auth("admin")->user()->name }}
		</span>

		<form method="POST" action="{{ route("admin.logout") }}">
			@csrf
			<button class="text-sm font-semibold text-red-600 hover:underline">
				Logout
			</button>
		</form>

	</div>

</header>
