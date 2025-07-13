<?php if (isset($_SESSION["user_name"])): ?>
<div class="header-component">
    <div class="w-full p-4 bg-white">
        <div class="flex items-center justify-between">
            <!-- Search Bar - Left Side -->
            <div class="flex-1 max-w-md relative px-3">
                <div class="relative">
                    <input 
                        type="text" 
                        id="global-search" 
                        placeholder="Search pages, appointments, patients..." 
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl bg-gray-50 focus:bg-white focus:border-nhd-blue focus:outline-none focus:ring-2 focus:ring-nhd-blue/20 transition-all duration-200 text-sm"
                        autocomplete="off"
                    >
                    <svg class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                
                <!-- Search Results Dropdown -->
                <div 
                    id="search-results" 
                    class="absolute top-full left-0 right-0 mt-2 bg-white border border-gray-200 rounded-xl shadow-lg z-50 hidden max-h-96 overflow-y-auto"
                >
                    <div id="search-loading" class="hidden p-4 text-center text-gray-500">
                        <svg class="w-5 h-5 animate-spin mx-auto mb-2" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Searching...
                    </div>
                    
                    <!-- Search Results Container -->
                    <div id="search-results-container"></div>
                    
                    <!-- No Results State -->
                    <div id="search-no-results" class="hidden p-4 text-center text-gray-500">
                        <svg class="w-8 h-8 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        No results found
                    </div>
                </div>
            </div>

            <!-- Right Side Actions -->
            <div class="flex items-center space-x-2 ml-4">
                <!-- Current Time Display -->
                <div class="bg-gray-100/80 border-1 border-gray-200 shaodow-sm px-3 py-2 text-gray-700 text-sm font-medium rounded-2xl">
                    <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span id="current-time"><?php echo date('H:i:s'); ?></span>
                </div>
                
                <a href="<?php echo BASE_URL; ?>/<?php echo ($_SESSION[
    "user_type"
] ??
    "") ===
"Patient"
    ? "patient"
    : "staff"; ?>/profile" 
                   class="header-action-btn rounded-2xl" 
                   title="Profile">
                   <div class="w-6 h-6 bg-nhd-blue/80 rounded-full flex items-center justify-center text-nhd-pale text-xs font-semibold">
                    <?php echo strtoupper(
                        substr($_SESSION["user_name"], 0, 1)
                    ); ?>
                </div>
                    <span class="hidden sm:inline ml-2"><?php echo $_SESSION[
                        "user_name"
                    ]; ?></span>
                </a>

                <!-- FAQ Button -->
                <a href="<?php echo BASE_URL; ?>/faq" 
                   class="glass-card bg-nhd-blue/80 p-2 text-white"
                   title="FAQ">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </a>

                <!-- Logout Button -->
                <a href="<?php echo BASE_URL; ?>/logout" 
                   class="glass-card bg-red-600/80 p-2 text-white flex flex-row items-center"
                   title="Logout">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    &nbsp; Logout
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Search Bar JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('global-search');
    const searchResults = document.getElementById('search-results');
    const searchLoading = document.getElementById('search-loading');
    const searchResultsContainer = document.getElementById('search-results-container');
    const searchNoResults = document.getElementById('search-no-results');
    
    let searchTimeout;
    let currentRequest;
    
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        
        clearTimeout(searchTimeout);
        
        if (query.length < 2) {
            hideSearchResults();
            return;
        }
        
        searchTimeout = setTimeout(() => {
            performSearch(query);
        }, 300);
    });
    
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            hideSearchResults();
        }
    });
    
    // Handle keyboard navigation
    searchInput.addEventListener('keydown', function(e) {
        const activeResults = searchResults.querySelectorAll('.search-result-item');
        let currentActive = searchResults.querySelector('.search-result-item.active');
        
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (!currentActive) {
                if (activeResults[0]) activeResults[0].classList.add('active');
            } else {
                currentActive.classList.remove('active');
                const nextItem = currentActive.nextElementSibling;
                if (nextItem && nextItem.classList.contains('search-result-item')) {
                    nextItem.classList.add('active');
                } else if (activeResults[0]) {
                    activeResults[0].classList.add('active');
                }
            }
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (!currentActive) {
                if (activeResults[activeResults.length - 1]) {
                    activeResults[activeResults.length - 1].classList.add('active');
                }
            } else {
                currentActive.classList.remove('active');
                const prevItem = currentActive.previousElementSibling;
                if (prevItem && prevItem.classList.contains('search-result-item')) {
                    prevItem.classList.add('active');
                } else if (activeResults[activeResults.length - 1]) {
                    activeResults[activeResults.length - 1].classList.add('active');
                }
            }
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (currentActive) {
                const link = currentActive.querySelector('a');
                if (link) link.click();
            }
        } else if (e.key === 'Escape') {
            hideSearchResults();
        }
    });
    
    function performSearch(query) {
        // Cancel previous request
        if (currentRequest) {
            currentRequest.abort();
            currentRequest = null;
        }
        
        showSearchResults();
        showLoading();
        
        currentRequest = fetch(`${window.BASE_URL}/search`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ query: query })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            currentRequest = null;
            hideLoading();
            displayResults(data.results || []);
        })
        .catch(error => {
            currentRequest = null;
            if (error.name !== 'AbortError') {
                console.error('Search error:', error);
                hideLoading();
                displayResults([]);
            }
        });
    }
    
    function showSearchResults() {
        searchResults.classList.remove('hidden');
    }
    
    function hideSearchResults() {
        searchResults.classList.add('hidden');
        searchResultsContainer.innerHTML = '';
        searchNoResults.classList.add('hidden');
    }
    
    function showLoading() {
        searchLoading.classList.remove('hidden');
        searchResultsContainer.innerHTML = '';
        searchNoResults.classList.add('hidden');
    }
    
    function hideLoading() {
        searchLoading.classList.add('hidden');
    }
    
    function displayResults(results) {
        if (results.length === 0) {
            searchNoResults.classList.remove('hidden');
            return;
        }
        
        searchNoResults.classList.add('hidden');
        
        const html = results.map(result => `
            <div class="search-result-item p-3 hover:bg-gray-50 border-b border-gray-100 last:border-b-0 cursor-pointer transition-colors duration-150">
                <a href="${result.url}" class="block">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            ${getResultIcon(result.type)}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-medium text-gray-900 truncate">
                                ${result.title}
                            </div>
                            ${result.description ? `<div class="text-xs text-gray-500 truncate">${result.description}</div>` : ''}
                        </div>
                        <div class="flex-shrink-0">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                ${result.type}
                            </span>
                        </div>
                    </div>
                </a>
            </div>
        `).join('');
        
        searchResultsContainer.innerHTML = html;
    }
    
    function getResultIcon(type) {
        const icons = {
            'page': '<svg class="w-5 h-5 text-nhd-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>',
            'appointment': '<svg class="w-5 h-5 text-nhd-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>',
            'patient': '<svg class="w-5 h-5 text-nhd-brown" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>',
            'payment': '<svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>'
        };
        
        return icons[type] || icons['page'];
    }
    
    function updateCurrentTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', { 
            hour12: false, 
            hour: '2-digit', 
            minute: '2-digit', 
            second: '2-digit' 
        });
        const timeElement = document.getElementById('current-time');
        if (timeElement) {
            timeElement.textContent = timeString;
        }
    }
    
    // Update immediately and then every second
    updateCurrentTime();
    setInterval(updateCurrentTime, 1000);
});
</script>
<?php endif; ?>
