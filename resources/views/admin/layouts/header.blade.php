<header class="sticky top-0 z-50 flex items-center justify-between bg-white px-6 py-4 shadow">

	{{-- Left: Toggle + Title --}}
	<div class="flex items-center gap-3">

		{{-- Sidebar Toggle --}}
		<button id="sidebar-toggle" class="text-gray-600 hover:text-gray-800 focus:outline-none md:hidden">
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

		<div class="relative">
			<button id="profile-dropdown-toggle" class="flex items-center gap-2 focus:outline-none">
				<span class="text-sm text-gray-600">
					{{ auth("admin")->user()->name }}
				</span>
				@if (auth("admin")->user()->image)
					<img src="{{ asset("storage/" . auth("admin")->user()->image) }}" alt="Profile Image"
						class="h-8 w-8 rounded-full object-cover">
				@else
					<div class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-300">
						<span class="text-sm font-medium text-gray-700">{{ substr(auth("admin")->user()->name, 0, 1) }}</span>
					</div>
				@endif
			</button>

			<div id="profile-dropdown" class="absolute right-0 z-50 mt-2 hidden w-48 rounded-md bg-white shadow-lg">
				<div class="py-1">
					<form method="POST" action="{{ route("admin.logout") }}">
						@csrf
						<button type="submit" class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100">
							Logout
						</button>
					</form>
				</div>
			</div>
		</div>
	</div>

</header>

<script>
	document.getElementById('profile-dropdown-toggle').addEventListener('click', function() {
		var dropdown = document.getElementById('profile-dropdown');
		dropdown.classList.toggle('hidden');
	});

	// Close dropdown when clicking outside
	document.addEventListener('click', function(event) {
		var toggle = document.getElementById('profile-dropdown-toggle');
		var dropdown = document.getElementById('profile-dropdown');
		if (!toggle.contains(event.target) && !dropdown.contains(event.target)) {
			dropdown.classList.add('hidden');
		}
	});
</script>
