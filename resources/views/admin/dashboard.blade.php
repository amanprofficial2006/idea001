@extends("admin.layouts.master")

@section("title", "Admin Dashboard")
@section("page-title", "Dashboard")

@section("content")

	{{-- Stats Cards --}}
	<div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">

		<div class="rounded-xl bg-white p-6 shadow">
			<p class="text-sm text-gray-500">Total Users</p>
			<h2 class="mt-2 text-3xl font-bold text-gray-800">1,245</h2>
		</div>

		<div class="rounded-xl bg-white p-6 shadow">
			<p class="text-sm text-gray-500">Online Users</p>
			<h2 class="mt-2 text-3xl font-bold text-green-600">132</h2>
		</div>

		<div class="rounded-xl bg-white p-6 shadow">
			<p class="text-sm text-gray-500">Open Tasks</p>
			<h2 class="mt-2 text-3xl font-bold text-blue-600">48</h2>
		</div>

		<div class="rounded-xl bg-white p-6 shadow">
			<p class="text-sm text-gray-500">Completed Tasks</p>
			<h2 class="mt-2 text-3xl font-bold text-indigo-600">9,820</h2>
		</div>

	</div>

	{{-- Revenue Section --}}
	<div class="mb-8 grid grid-cols-1 gap-6 lg:grid-cols-3">

		<div class="rounded-xl bg-white p-6 shadow">
			<p class="text-sm text-gray-500">Today’s Commission</p>
			<h2 class="mt-2 text-3xl font-bold text-purple-600">₹ 1,250</h2>
		</div>

		<div class="rounded-xl bg-white p-6 shadow">
			<p class="text-sm text-gray-500">This Month</p>
			<h2 class="mt-2 text-3xl font-bold text-purple-600">₹ 42,300</h2>
		</div>

		<div class="rounded-xl bg-white p-6 shadow">
			<p class="text-sm text-gray-500">Total Commission</p>
			<h2 class="mt-2 text-3xl font-bold text-purple-600">₹ 3,25,000</h2>
		</div>

	</div>

	{{-- Recent Activity --}}
	<div class="rounded-xl bg-white p-6 shadow">

		<h3 class="mb-4 text-lg font-semibold text-gray-800">
			Recent Activity
		</h3>

		<ul class="space-y-3 text-sm text-gray-600">
			<li>🟢 New task posted: <b>Buy samosa from Bikaner</b></li>
			<li>🟡 Task accepted by user <b>Rahul</b></li>
			<li>🔵 Task completed successfully</li>
			<li>🔴 User <b>Amit</b> blocked by admin</li>
			<li>🟣 Payment processed for task #120</li>
		</ul>

	</div>

@endsection
