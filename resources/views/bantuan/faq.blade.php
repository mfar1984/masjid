<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'Soalan Lazim (FAQ) - E-Masjid' }}</title>

    <!-- Favicon -->
    <x-favicon />

    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans min-h-screen flex flex-col" data-theme="corporate" style="font-family: 'Poppins', sans-serif;" x-data="faqAccordion()">
    <x-double-navbar :user="auth()->user()" />
    
    <main class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <!-- Main Container -->
            <div class="bg-white shadow-lg border-x border-gray-200 p-6">
                <!-- Header Section -->
                <div class="mb-6">
                    <h1 class="text-xl font-bold text-gray-900 mb-1">Soalan Lazim (FAQ)</h1>
                    <p class="text-xs text-gray-600">Jawapan kepada soalan-soalan yang sering ditanya tentang Sistem E-Masjid</p>
                </div>

                <!-- Search Section -->
                <div class="mb-6">
                    <div class="relative max-w-md">
                        <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">search</span>
                        <input type="text"
                               x-model="searchQuery"
                               placeholder="Cari soalan atau jawapan..."
                               class="w-full pl-9 pr-3 py-2 border border-gray-200 rounded-sm text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 text-gray-900">
                    </div>
                    <!-- Search Results Counter -->
                    <div x-show="searchQuery" class="mt-2">
                        <p class="text-xs text-gray-500" x-text="getSearchResultsCount() + ' soalan dijumpai'"></p>
                    </div>
                </div>

                <!-- No Results Message -->
                <div x-show="searchQuery && !hasResults()" class="text-center py-8">
                    <span class="material-icons text-gray-400 mb-2 text-5xl">search_off</span>
                    <p class="text-xs text-gray-500">Tiada soalan dijumpai untuk carian "<span x-text="searchQuery"></span>"</p>
                </div>

                <!-- FAQ Categories -->
                <div class="space-y-6">
                    @foreach($faqs as $categoryIndex => $category)
                    <div class="bg-gray-50 rounded-sm border border-gray-200 overflow-hidden"
                         x-show="categoryHasResults({{ $categoryIndex }})">
                        <!-- Category Header -->
                        <div class="bg-{{ $category['color'] }}-50 border-b border-{{ $category['color'] }}-100 px-4 py-3">
                            <div class="flex items-center">
                                <span class="material-icons text-{{ $category['color'] }}-600 mr-3 text-sm">{{ $category['icon'] }}</span>
                                <h2 class="text-sm font-semibold text-{{ $category['color'] }}-800">{{ $category['category'] }}</h2>
                            </div>
                        </div>

                        <!-- FAQ Items -->
                        <div class="space-y-3 p-4">
                            @foreach($category['questions'] as $questionIndex => $faq)
                            <div class="bg-white rounded-sm border border-gray-200 overflow-hidden"
                                 x-show="filterFAQ('{{ addslashes($faq['question']) }}', '{{ addslashes($faq['answer']) }}')">
                                <!-- Question Header -->
                                <button @click="toggleFAQ({{ $categoryIndex }}, {{ $questionIndex }})"
                                        class="w-full px-4 py-3 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                                    <span class="text-xs font-medium text-gray-900 pr-4">{{ $faq['question'] }}</span>
                                    <span class="material-icons text-gray-500 transition-transform duration-200 text-sm"
                                          :class="{ 'rotate-180': openFAQ === '{{ $categoryIndex }}-{{ $questionIndex }}' }">
                                        expand_more
                                    </span>
                                </button>

                                <!-- Answer Content -->
                                <div x-show="openFAQ === '{{ $categoryIndex }}-{{ $questionIndex }}'"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 transform -translate-y-2"
                                     x-transition:enter-end="opacity-100 transform translate-y-0"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 transform translate-y-0"
                                     x-transition:leave-end="opacity-0 transform -translate-y-2"
                                     class="border-t border-gray-200 bg-gray-50">
                                    <div class="px-4 py-3">
                                        <p class="text-xs text-gray-700 leading-relaxed">{{ $faq['answer'] }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Footer Info -->
                <div class="mt-8 text-center">
                    <div class="bg-blue-50 border border-blue-200 rounded-sm p-4">
                        <span class="material-icons text-blue-600 mb-2 text-2xl">info</span>
                        <p class="text-xs font-semibold text-blue-800 mb-2">Masih ada soalan?</p>
                        <p class="text-xs text-blue-700">Hubungi sokongan teknikal atau semak Status Sistem untuk maklumat lanjut.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <x-footer />

    <script>
        function faqAccordion() {
            return {
                openFAQ: null,
                searchQuery: '',
                
                toggleFAQ(categoryIndex, questionIndex) {
                    const faqKey = `${categoryIndex}-${questionIndex}`;
                    if (this.openFAQ === faqKey) {
                        this.openFAQ = null;
                    } else {
                        this.openFAQ = faqKey;
                    }
                },
                
                filterFAQ(question, answer) {
                    if (!this.searchQuery) return true;
                    
                    const query = this.searchQuery.toLowerCase();
                    return question.toLowerCase().includes(query) || 
                           answer.toLowerCase().includes(query);
                },
                
                categoryHasResults(categoryIndex) {
                    if (!this.searchQuery) return true;
                    
                    const category = @json($faqs)[categoryIndex];
                    return category.questions.some(faq => 
                        this.filterFAQ(faq.question, faq.answer)
                    );
                },
                
                hasResults() {
                    if (!this.searchQuery) return true;
                    
                    return @json($faqs).some(category => 
                        category.questions.some(faq => 
                            this.filterFAQ(faq.question, faq.answer)
                        )
                    );
                },
                
                getSearchResultsCount() {
                    if (!this.searchQuery) return 0;
                    
                    let count = 0;
                    @json($faqs).forEach(category => {
                        category.questions.forEach(faq => {
                            if (this.filterFAQ(faq.question, faq.answer)) {
                                count++;
                            }
                        });
                    });
                    return count;
                }
            }
        }
    </script>


</body>
</html>
