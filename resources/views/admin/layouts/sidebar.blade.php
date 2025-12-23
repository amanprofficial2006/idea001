{{-- Sidebar --}}
<aside id="sidebar"
	class="fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full transform flex-col bg-slate-900 text-white transition-transform duration-300 ease-in-out md:translate-x-0">

	{{-- Brand --}}
	<div class="border-b border-slate-700 px-6 py-4 text-xl font-bold">
		@if (App\Models\Organisation::first())
			<div class="flex items-center gap-3">
				@if (App\Models\Organisation::first()->logo)
					<img src="{{ asset("storage/" . App\Models\Organisation::first()->logo) }}" alt="Logo" class="h-8 w-8 rounded">
				@endif
				<span>{{ App\Models\Organisation::first()->name ?? "Admin Panel" }}</span>
			</div>
		@else
			Admin Panel
		@endif
	</div>

	{{-- Menu --}}
	<nav class="flex-1 space-y-1 px-4 py-4 text-sm">

		<a href="{{ route("admin.dashboard") }}"
			class="{{ request()->routeIs("admin.dashboard") ? "bg-slate-800" : "hover:bg-slate-700" }} flex items-center gap-3 rounded-lg px-4 py-2">
			<span>📊</span>
			<span>Dashboard</span>
		</a>

		<a href="{{ route("admin.users.index") }}"
			class="{{ request()->routeIs("admin.users.*") ? "bg-slate-800" : "hover:bg-slate-700" }} flex items-center gap-3 rounded-lg px-4 py-2">
			<span>👤</span>
			<span>Users</span>
		</a>

		<a href="#" class="flex items-center gap-3 rounded-lg px-4 py-2 hover:bg-slate-700">
			<span>📌</span>
			<span>Tasks</span>
		</a>

		<a href="#" class="flex items-center gap-3 rounded-lg px-4 py-2 hover:bg-slate-700">
			<span>💰</span>
			<span>Payments</span>
		</a>

		<a href="#" class="flex items-center gap-3 rounded-lg px-4 py-2 hover:bg-slate-700">
			<span>🚨</span>
			<span>Reports</span>
		</a>

		<a href="{{ route("admin.settings.index") }}"
			class="{{ request()->routeIs("admin.settings.*") ? "bg-slate-800" : "hover:bg-slate-700" }} flex items-center gap-3 rounded-lg px-4 py-2">
			<span>⚙️</span>
			<span>Settings</span>
		</a>

	</nav>

	{{-- Footer --}}
	<div class="border-t border-slate-700 p-4 text-center text-xs text-slate-400">
		v1.0 • Admin Panel
	</div>

</aside>

{{-- Overlay (Mobile only) --}}
<div id="sidebar-overlay" class="fixed inset-0 z-30 hidden bg-black bg-opacity-50 md:hidden">
</div>
