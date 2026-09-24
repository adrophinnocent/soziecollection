@extends('admin.layout')

@section('page_title', 'Admin Users & RBAC Team')

@section('content')
<div class="space-y-6">

    <!-- Role Explanation Banner -->
    <div class="bg-gradient-to-br from-[#29241F] to-[#3D352C] text-[#F8F5EF] p-6 polygon-card border border-[#A8895F]/40 shadow-xl space-y-2">
        <span class="text-[10px] font-extrabold uppercase tracking-[0.25em] text-[#A8895F]">ATELIER ROLE-BASED ACCESS CONTROL (RBAC)</span>
        <h3 class="font-serif font-bold text-2xl text-[#F8F5EF]">Administrative Team Roles</h3>
        <p class="text-xs text-[#D8C9B8]">6 strict permission scopes: Super Admin, Manager, Inventory Manager, Order Manager, Content Manager, and Customer.</p>
    </div>

    <!-- Admin Users List -->
    <div class="bg-[#F8F5EF] border border-[#D8C9B8] polygon-card shadow-sm overflow-hidden">
        <div class="p-5 border-b border-[#D8C9B8] bg-[#EDE5D8]/50 flex justify-between items-center">
            <h3 class="font-serif font-bold text-lg text-[#29241F]">Active Atelier Administrative Users</h3>
            <span class="text-xs font-bold text-[#A8895F] uppercase">{{ count($adminUsers) }} Assigned Accounts</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#EDE5D8] text-[#29241F] font-extrabold uppercase text-[10px] tracking-wider border-b border-[#D8C9B8]">
                    <tr>
                        <th class="p-4">User Name</th>
                        <th class="p-4">Email Address</th>
                        <th class="p-4">Phone Number</th>
                        <th class="p-4">Assigned Role</th>
                        <th class="p-4 text-right">Access Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#D8C9B8]/60">
                    @foreach($adminUsers as $user)
                    <tr class="hover:bg-white/60 transition-colors">
                        <td class="p-4 font-bold text-[#29241F]">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#A8895F] to-[#29241F] text-white flex items-center justify-center font-serif font-bold text-xs">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span>{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="p-4 font-medium text-gray-700">{{ $user->email }}</td>
                        <td class="p-4 font-medium text-gray-700">{{ $user->phone ?? 'N/A' }}</td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 bg-[#29241F] text-[#EDE5D8] border border-[#A8895F]/50 rounded polygon-badge font-extrabold text-[10px] uppercase">
                                {{ $user->role_label }}
                            </span>
                        </td>
                        <td class="p-4 text-right">
                            <span class="px-2 py-1 bg-emerald-100 text-emerald-900 border border-emerald-300 font-extrabold text-[9px] uppercase rounded">
                                Active
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
