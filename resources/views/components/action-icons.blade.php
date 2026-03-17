@props([
    'record' => null,
    'showRoute' => '',
    'editRoute' => '',
    'deleteAction' => '',
    'approveAction' => '',
    'rejectAction' => '',
    'layout' => 'desktop', // desktop or mobile
    'module' => null // module name for permission checking
])

@if($layout === 'desktop')
    <!-- Desktop Action Icons -->
    <td class="px-4 py-2 table-data text-center space-x-1">
        <!-- Accept/Reject for Pending Status -->
        @if(isset($record->status) && ($record->status === 'pending' || $record->status === 'Menunggu'))
            @if($module && auth()->user()->hasPermission($module, 'approve'))
                <x-icons.approve-icon :id="$record->id" :nama="$record->nama" size="desktop" />
            @endif
            @if($module && auth()->user()->hasPermission($module, 'reject'))
                <x-icons.reject-icon :id="$record->id" :nama="$record->nama" size="desktop" />
            @endif
        @endif

        <!-- Approve/Reject for Pembayaran Bantuan (Belum Bayar) -->
        @if(isset($record->status_pembayaran) && $record->status_pembayaran === 'Belum Bayar')
            @if($module && auth()->user()->hasPermission($module, 'update'))
                <x-icons.sahkan-icon :id="$record->id" :nama="$record->no_pembayaran" size="desktop" />
            @endif
            @if($module && auth()->user()->hasPermission($module, 'delete'))
                <x-icons.batal-bayaran-icon :id="$record->id" :nama="$record->no_pembayaran" size="desktop" />
            @endif
        @endif

        <!-- Standard Actions -->
        <x-icons.view-icon :route="$showRoute" size="desktop" />
        @if($module && auth()->user()->hasPermission($module, 'update'))
            <x-icons.edit-icon :route="$editRoute" size="desktop" />
        @endif

        <!-- Suspend/Unsuspend Actions -->
        @if(isset($record->status) && ($record->status === 'active' || $record->status === 'Aktif') && $module && auth()->user()->hasPermission($module, 'suspend'))
            <x-icons.suspend-icon :id="$record->id" :nama="$record->nama" size="desktop" />
        @elseif(isset($record->status) && ($record->status === 'suspended' || $record->status === 'Digantung') && $module && auth()->user()->hasPermission($module, 'reactivate'))
            <x-icons.unsuspend-icon :id="$record->id" :nama="$record->nama" size="desktop" />
        @endif

        @if($module && auth()->user()->hasPermission($module, 'delete'))
            <x-icons.delete-icon :id="$record->id" :nama="$record->nama" size="desktop" />
        @endif
    </td>
@else
    <!-- Mobile Action Icons -->
    <div class="flex items-center space-x-2">
        <!-- Accept/Reject for Pending Status -->
        @if(isset($record->status) && ($record->status === 'pending' || $record->status === 'Menunggu'))
            @if($module && auth()->user()->hasPermission($module, 'approve'))
                <x-icons.approve-icon :id="$record->id" :nama="$record->nama" size="mobile" />
            @endif
            @if($module && auth()->user()->hasPermission($module, 'reject'))
                <x-icons.reject-icon :id="$record->id" :nama="$record->nama" size="mobile" />
            @endif
        @endif

        <!-- Approve/Reject for Pembayaran Bantuan (Belum Bayar) -->
        @if(isset($record->status_pembayaran) && $record->status_pembayaran === 'Belum Bayar')
            @if($module && auth()->user()->hasPermission($module, 'update'))
                <x-icons.sahkan-icon :id="$record->id" :nama="$record->no_pembayaran" size="mobile" />
            @endif
            @if($module && auth()->user()->hasPermission($module, 'delete'))
                <x-icons.batal-bayaran-icon :id="$record->id" :nama="$record->no_pembayaran" size="mobile" />
            @endif
        @endif

        <!-- Standard Actions -->
        <x-icons.view-icon :route="$showRoute" size="mobile" />
        @if($module && auth()->user()->hasPermission($module, 'update'))
            <x-icons.edit-icon :route="$editRoute" size="mobile" />
        @endif

        <!-- Suspend/Unsuspend Actions -->
        @if(isset($record->status) && ($record->status === 'active' || $record->status === 'Aktif') && $module && auth()->user()->hasPermission($module, 'suspend'))
            <x-icons.suspend-icon :id="$record->id" :nama="$record->nama" size="mobile" />
        @elseif(isset($record->status) && ($record->status === 'suspended' || $record->status === 'Digantung') && $module && auth()->user()->hasPermission($module, 'reactivate'))
            <x-icons.unsuspend-icon :id="$record->id" :nama="$record->nama" size="mobile" />
        @endif

        @if($module && auth()->user()->hasPermission($module, 'delete'))
            <x-icons.delete-icon :id="$record->id" :nama="$record->nama" size="mobile" />
        @endif
    </div>
@endif
