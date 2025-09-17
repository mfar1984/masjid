@props([
    'compact' => false,
    'showDetails' => false,
    'showRefresh' => false
])

@php
    // Get health check results using SystemStatusService
    $statusService = app(\App\Services\SystemStatusService::class);
    $statusSummary = $statusService->getStatusSummary();

    $overallStatus = $statusSummary['overall_status'];
    $totalChecks = $statusSummary['total_checks'];
    $failedChecks = $statusSummary['failed_checks'];
    $warningChecks = $statusSummary['warning_checks'];
    $okChecks = $statusSummary['ok_checks'];
@endphp

<div {{ $attributes->merge(['class' => 'bg-white border border-gray-200 rounded-sm overflow-hidden']) }}>
    @if($compact)
        <!-- Compact Status Display -->
        <div class="p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    @if($overallStatus === 'ok')
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900">Sistem Sihat</h3>
                            <p class="text-xs text-gray-600">Semua {{ $totalChecks }} pemeriksaan berjaya</p>
                        </div>
                    @else
                        <div class="w-3 h-3 bg-red-500 rounded-full mr-3"></div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900">Sistem Bermasalah</h3>
                            <p class="text-xs text-gray-600">{{ $failedChecks }} daripada {{ $totalChecks }} pemeriksaan gagal</p>
                        </div>
                    @endif
                </div>
                <div class="flex items-center space-x-2">
                    @if($showRefresh)
                        <form action="{{ route('bantuan.status-sistem.refresh') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center justify-center h-[28px] px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded transition-colors">
                                <span class="material-icons text-[10px] mr-1">refresh</span>
                                Kemaskini
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('bantuan.status-sistem') }}" class="inline-flex items-center justify-center h-[28px] px-2 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs rounded transition-colors">
                        <span class="material-icons text-[10px] mr-1">visibility</span>
                        Lihat
                    </a>
                </div>
            </div>
        </div>
    @else
        <!-- Full Status Display -->
        <div class="bg-green-50 px-4 py-3 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    @if($overallStatus === 'ok')
                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800 mr-3">
                            SIHAT
                        </span>
                        <h3 class="text-sm font-bold text-gray-900">Status Sistem Normal</h3>
                    @else
                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800 mr-3">
                            BERMASALAH
                        </span>
                        <h3 class="text-sm font-bold text-gray-900">Status Sistem Bermasalah</h3>
                    @endif
                </div>
                <div class="text-xs text-gray-600">
                    Dikemaskini: {{ now()->format('d/m/Y H:i') }}
                </div>
            </div>
            <p class="text-xs text-gray-600 mt-2">
                {{ $okChecks }} berjaya, {{ $warningChecks }} amaran, {{ $failedChecks }} gagal daripada {{ $totalChecks }} pemeriksaan
            </p>
        </div>

        @if($showDetails)
            <!-- Status Details -->
            <div class="p-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <!-- OK Status -->
                    <div class="text-center">
                        <div class="w-12 h-12 bg-green-100 rounded-sm flex items-center justify-center mx-auto mb-2">
                            <span class="material-icons text-green-600 text-lg">check_circle</span>
                        </div>
                        <div class="text-lg font-bold text-green-600">{{ $okChecks }}</div>
                        <div class="text-xs text-gray-600">Berjaya</div>
                    </div>

                    <!-- Warning Status -->
                    <div class="text-center">
                        <div class="w-12 h-12 bg-yellow-100 rounded-sm flex items-center justify-center mx-auto mb-2">
                            <span class="material-icons text-yellow-600 text-lg">warning</span>
                        </div>
                        <div class="text-lg font-bold text-yellow-600">{{ $warningChecks }}</div>
                        <div class="text-xs text-gray-600">Amaran</div>
                    </div>

                    <!-- Failed Status -->
                    <div class="text-center">
                        <div class="w-12 h-12 bg-red-100 rounded-sm flex items-center justify-center mx-auto mb-2">
                            <span class="material-icons text-red-600 text-lg">error</span>
                        </div>
                        <div class="text-lg font-bold text-red-600">{{ $failedChecks }}</div>
                        <div class="text-xs text-gray-600">Gagal</div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="flex justify-center space-x-2">
                    @if($showRefresh)
                        <form action="{{ route('bantuan.status-sistem.refresh') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center justify-center h-[32px] px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded transition-colors">
                                <span class="material-icons text-[10px] mr-2">refresh</span>
                                Kemaskini
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('bantuan.status-sistem') }}" class="inline-flex items-center justify-center h-[32px] px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs rounded transition-colors">
                        <span class="material-icons text-[10px] mr-2">visibility</span>
                        Lihat Butiran
                    </a>
                </div>
            </div>
        @endif
    @endif
</div>
