function toggleTableCollapse(sectionId) {
    const tableContainer = document.getElementById(`table-container-${sectionId}`);
    const collapseBtn = document.getElementById(`collapse-btn-${sectionId}`);
    const collapseText = collapseBtn.querySelector('.collapse-text');
    const collapseIcon = collapseBtn.querySelector('svg');
    
    if (tableContainer.style.display === 'none') {
        tableContainer.style.display = 'block';
        collapseText.textContent = 'Collapse Table';
        collapseIcon.style.transform = 'rotate(0deg)';
    } else {
            tableContainer.style.display = 'none';
        collapseText.textContent = 'Expand Table';
        collapseIcon.style.transform = 'rotate(180deg)';
    }
}

function showSection(sectionName) {
    const sections = document.querySelectorAll('.appointment-section, .bg-red-50\\/60');
    sections.forEach(section => {
        section.style.display = 'none';
    });
    
    const buttons = document.querySelectorAll('[id$="-btn"]');
    buttons.forEach(btn => {
        btn.classList.remove('bg-nhd-blue/80', 'text-white', 'bg-red-500/80');
        btn.classList.add('bg-gray-200/80', 'text-gray-700');
    });
    
    if (sectionName === 'all') {
        sections.forEach(section => {
            section.style.display = 'block';
        });
        document.getElementById('all-btn').classList.remove('bg-gray-200/80', 'text-gray-700');
        document.getElementById('all-btn').classList.add('bg-nhd-blue/80', 'text-white');
    } else {
        const targetSection = document.getElementById(sectionName + '-section') || 
                            document.querySelector('.bg-red-50\\/60'); 
        if (targetSection) {
            targetSection.style.display = 'block';
        }
        
        const targetBtn = document.getElementById(sectionName + '-btn');
        if (targetBtn) {
            targetBtn.classList.remove('bg-gray-200/80', 'text-gray-700');
            if (sectionName === 'pending-cancellation-requests') {
                targetBtn.classList.add('bg-red-500/80', 'text-white');
            } else {
                targetBtn.classList.add('bg-nhd-blue/80', 'text-white');
            }
        }
    }
}

class PaginationManager {
    constructor() {
        this.paginationStates = {};
        this.init();
    }

    init() {
        const sections = document.querySelectorAll('[data-section]');
        sections.forEach(section => {
            const sectionId = section.getAttribute('data-section');
            this.initializeSection(sectionId);
        });

        const mobileSections = document.querySelectorAll('[id^="mobile-view-"]');
        mobileSections.forEach(section => {
            const sectionId = section.id.replace('mobile-view-', '');
            this.initializeSection(sectionId);
        });
    }

    initializeSection(sectionId) {
        this.paginationStates[sectionId] = {
            currentPage: 1,
            rowsPerPage: 5,
            totalRows: 0,
            totalPages: 1
        };

        this.updateRowCount(sectionId);
        this.updatePagination(sectionId);
    }

    updateRowCount(sectionId) {
        const tableBody = document.getElementById(`table-body-${sectionId}`);
        const mobileView = document.getElementById(`mobile-view-${sectionId}`);
        
        let totalRows = 0;
        
        if (tableBody) {
            totalRows = tableBody.querySelectorAll('.table-row').length;
        } else if (mobileView) {
            totalRows = mobileView.querySelectorAll('.table-row').length;
        } else {
            const pendingTable = document.querySelector(`[data-section="${sectionId}"]`);
            if (pendingTable) {
                totalRows = pendingTable.querySelectorAll('.table-row').length;
            }
        }

        this.paginationStates[sectionId].totalRows = totalRows;
        this.paginationStates[sectionId].totalPages = Math.ceil(totalRows / this.paginationStates[sectionId].rowsPerPage);
    }

    updatePagination(sectionId) {
        const state = this.paginationStates[sectionId];
        if (!state) return;

        const startRow = ((state.currentPage - 1) * state.rowsPerPage) + 1;
        const endRow = Math.min(state.currentPage * state.rowsPerPage, state.totalRows);
        
        const paginationInfo = document.getElementById(`pagination-info-${sectionId}`);
        if (paginationInfo) {
            paginationInfo.textContent = `Showing ${startRow}-${endRow} of ${state.totalRows} entries`;
        }

        const currentPageSpan = document.getElementById(`currentPage-${sectionId}`);
        const totalPagesSpan = document.getElementById(`totalPages-${sectionId}`);
        if (currentPageSpan) currentPageSpan.textContent = state.currentPage;
        if (totalPagesSpan) totalPagesSpan.textContent = state.totalPages;

        const prevBtn = document.getElementById(`prevBtn-${sectionId}`);
        const nextBtn = document.getElementById(`nextBtn-${sectionId}`);
        
        if (prevBtn) {
            prevBtn.disabled = state.currentPage <= 1;
        }
        if (nextBtn) {
            nextBtn.disabled = state.currentPage >= state.totalPages;
        }

        this.showCurrentPageRows(sectionId);
    }

    showCurrentPageRows(sectionId) {
        const state = this.paginationStates[sectionId];
        if (!state) return;

        const startIndex = (state.currentPage - 1) * state.rowsPerPage;
        const endIndex = startIndex + state.rowsPerPage;

        const tableRows = document.querySelectorAll(`#table-body-${sectionId} .table-row`);
        tableRows.forEach((row, index) => {
            if (index >= startIndex && index < endIndex) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });

        const mobileRows = document.querySelectorAll(`#mobile-view-${sectionId} .table-row`);
        mobileRows.forEach((row, index) => {
            if (index >= startIndex && index < endIndex) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });

        if (sectionId === 'pending-cancellation-requests') {
            const pendingDesktopRows = document.querySelectorAll(`[data-section="${sectionId}"] tbody .table-row`);
            pendingDesktopRows.forEach((row, index) => {
                if (index >= startIndex && index < endIndex) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            const pendingMobileRows = document.querySelectorAll('.bg-red-50\\/60 .block.lg\\:hidden .table-row');
            pendingMobileRows.forEach((row, index) => {
                if (index >= startIndex && index < endIndex) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    }

    changePage(sectionId, direction) {
        const state = this.paginationStates[sectionId];
        if (!state) return;

        if (direction === 'prev' && state.currentPage > 1) {
            state.currentPage--;
        } else if (direction === 'next' && state.currentPage < state.totalPages) {
            state.currentPage++;
        }

        this.updatePagination(sectionId);
    }

    changeRowsPerPage(sectionId, newRowsPerPage) {
        const state = this.paginationStates[sectionId];
        if (!state) return;

        state.rowsPerPage = parseInt(newRowsPerPage);
        state.currentPage = 1; 
        state.totalPages = Math.ceil(state.totalRows / state.rowsPerPage);

        this.updatePagination(sectionId);
    }

    refreshPagination(sectionId) {
        this.updateRowCount(sectionId);
        this.updatePagination(sectionId);
    }
}

class SortableTableManager {
    constructor() {
        this.sortStates = {};
        this.paginationManager = new PaginationManager();
        this.init();
    }

    init() {
        const sortableHeaders = document.querySelectorAll('.sortable-header');
        
        sortableHeaders.forEach(header => {
            header.addEventListener('click', () => {
                const table = header.closest('table');
                const section = table.getAttribute('data-section');
                const sortKey = header.getAttribute('data-sort');
                
                if (!this.sortStates[section]) {
                    this.sortStates[section] = { key: null, direction: 'asc' };
                }
                
                if (this.sortStates[section].key === sortKey) {
                    this.sortStates[section].direction = this.sortStates[section].direction === 'asc' ? 'desc' : 'asc';
                } else {
                    this.sortStates[section].key = sortKey;
                    this.sortStates[section].direction = 'asc';
                }
                
                this.sortTableBySection(section, sortKey, this.sortStates[section].direction);
                this.updateSortIndicators(table, sortKey, this.sortStates[section].direction);
                
                this.paginationManager.refreshPagination(section);
            });
        });
    }

    sortTableBySection(section, sortKey, direction) {
        const tableBody = document.getElementById(`table-body-${section}`);
        const mobileView = document.getElementById(`mobile-view-${section}`);
        
        if (!tableBody && !mobileView) return;
        
        if (tableBody) {
            this.sortRows(tableBody, sortKey, direction);
        }
        
        if (mobileView) {
            this.sortRows(mobileView, sortKey, direction, true);
        }
        
        if (section === 'pending-cancellation-requests') {
            const pendingTableBody = document.querySelector(`[data-section="${section}"] tbody`);
            if (pendingTableBody) {
                this.sortRows(pendingTableBody, sortKey, direction);
            }
        }
    }

    sortRows(container, sortKey, direction, isMobileView = false) {
        const originalOpacity = container.style.opacity;
        container.style.opacity = '0.5';
        
        try {
            const rows = Array.from(container.querySelectorAll('.table-row'));
            
            rows.sort((a, b) => {
                let aValue, bValue;
                
                if (isMobileView) {
                    switch(sortKey) {
                        case 'DateTime':
                            const aDateText = a.querySelector('.bg-nhd-blue\\/10, .bg-red-100')?.textContent || '';
                            const bDateText = b.querySelector('.bg-nhd-blue\\/10, .bg-red-100')?.textContent || '';
                            aValue = new Date(aDateText.replace('•', '').replace('#', '').trim());
                            bValue = new Date(bDateText.replace('•', '').replace('#', '').trim());
                            break;
                        case 'DoctorFirstName':
                        case 'PatientFirstName':
                            aValue = a.querySelector('h4')?.textContent.toLowerCase() || '';
                            bValue = b.querySelector('h4')?.textContent.toLowerCase() || '';
                            break;
                        case 'PatientEmail':
                            const aEmailEl = a.querySelectorAll('.text-sm.text-gray-600')[0];
                            const bEmailEl = b.querySelectorAll('.text-sm.text-gray-600')[0];
                            aValue = aEmailEl?.textContent.toLowerCase() || '';
                            bValue = bEmailEl?.textContent.toLowerCase() || '';
                            break;
                        case 'AppointmentType':
                            aValue = a.querySelector('.bg-gray-100\\/60')?.textContent.toLowerCase() || '';
                            bValue = b.querySelector('.bg-gray-100\\/60')?.textContent.toLowerCase() || '';
                            break;
                        case 'Status':
                            aValue = a.querySelector('.px-2.py-1.rounded-full')?.textContent.toLowerCase() || '';
                            bValue = b.querySelector('.px-2.py-1.rounded-full')?.textContent.toLowerCase() || '';
                            break;
                        default:
                            return 0;
                    }
                } else {
                    switch(sortKey) {
                        case 'DateTime':
                            const aDateSelector = a.querySelector('td:nth-child(2) .font-medium') || a.querySelector('td:nth-child(1) .font-medium');
                            const aBoldSelector = a.querySelector('td:nth-child(2) .font-bold') || a.querySelector('td:nth-child(1) .font-bold');
                            const bDateSelector = b.querySelector('td:nth-child(2) .font-medium') || b.querySelector('td:nth-child(1) .font-medium');
                            const bBoldSelector = b.querySelector('td:nth-child(2) .font-bold') || b.querySelector('td:nth-child(1) .font-bold');
                            
                            const aDateText = aDateSelector?.textContent + ' ' + aBoldSelector?.textContent;
                            const bDateText = bDateSelector?.textContent + ' ' + bBoldSelector?.textContent;
                            aValue = new Date(aDateText);
                            bValue = new Date(bDateText);
                            break;
                            
                        case 'DoctorFirstName':
                            aValue = a.querySelector('td:nth-child(3) .font-medium')?.textContent.toLowerCase() || '';
                            bValue = b.querySelector('td:nth-child(3) .font-medium')?.textContent.toLowerCase() || '';
                            break;
                            
                        case 'PatientFirstName':
                            aValue = a.querySelector('td:nth-child(2) .font-medium')?.textContent.toLowerCase() || '';
                            bValue = b.querySelector('td:nth-child(2) .font-medium')?.textContent.toLowerCase() || '';
                            break;
                            
                        case 'PatientEmail':
                            aValue = a.querySelector('td:nth-child(3) .text-sm')?.textContent.toLowerCase() || '';
                            bValue = b.querySelector('td:nth-child(3) .text-sm')?.textContent.toLowerCase() || '';
                            break;
                            
                        case 'AppointmentType':
                            aValue = a.querySelector('td:nth-child(4) span')?.textContent.toLowerCase() || '';
                            bValue = b.querySelector('td:nth-child(4) span')?.textContent.toLowerCase() || '';
                            break;
                            
                        case 'Status':
                            aValue = a.querySelector('td:nth-child(5) span')?.textContent.toLowerCase() || '';
                            bValue = b.querySelector('td:nth-child(5) span')?.textContent.toLowerCase() || '';
                            break;
                            
                        case 'Reason':
                            const aReasonEl = a.querySelector('td:nth-child(5) .text-sm');
                            const bReasonEl = b.querySelector('td:nth-child(5) .text-sm');
                            aValue = aReasonEl?.textContent.toLowerCase() || '';
                            bValue = bReasonEl?.textContent.toLowerCase() || '';
                            break;
                            
                        default:
                            return 0;
                    }
                }
                
                if (aValue < bValue) return direction === 'asc' ? -1 : 1;
                if (aValue > bValue) return direction === 'asc' ? 1 : -1;
                return 0;
            });
            
            rows.forEach((row, index) => {
                row.setAttribute('data-row-index', index);
            });
            
            container.innerHTML = '';
            rows.forEach(row => container.appendChild(row));
            
        } catch (error) {
            console.error('Failed to sort appointments:', error);
        } finally {
            container.style.opacity = originalOpacity || '1';
        }
    }

    updateSortIndicators(table, currentSortKey, direction) {
        const allHeaders = table.querySelectorAll('.sortable-header');
        allHeaders.forEach(header => {
            header.classList.remove('sorting');
            const defaultIcon = header.querySelector('.sort-icon-default');
            const activeIcon = header.querySelector('.sort-icon-active');
            
            if (defaultIcon) defaultIcon.style.display = 'inline-block';
            if (activeIcon) {
                activeIcon.style.display = 'none';
                activeIcon.classList.remove('asc', 'desc');
            }
        });
        
        const currentHeader = table.querySelector(`[data-sort="${currentSortKey}"]`);
        if (currentHeader) {
            currentHeader.classList.add('sorting');
            const defaultIcon = currentHeader.querySelector('.sort-icon-default');
            const activeIcon = currentHeader.querySelector('.sort-icon-active');
            
            if (defaultIcon) defaultIcon.style.display = 'none';
            if (activeIcon) {
                activeIcon.style.display = 'inline-block';
                activeIcon.classList.add(direction);
            }
        }
    }
}

function navigatePage(sectionId, direction) {
    if (window.tableManager && window.tableManager.paginationManager) {
        window.tableManager.paginationManager.changePage(sectionId, direction);
    }
}

function handleRowsPerPageChange(sectionId, newRowsPerPage) {
    if (window.tableManager && window.tableManager.paginationManager) {
        window.tableManager.paginationManager.changeRowsPerPage(sectionId, newRowsPerPage);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    window.tableManager = new SortableTableManager();
    
    window.toggleTableCollapse = toggleTableCollapse;
    window.showSection = showSection;
    window.navigatePage = navigatePage;
    window.handleRowsPerPageChange = handleRowsPerPageChange;
    
    if (typeof showSection === 'function' && document.getElementById('all-btn')) {
        showSection('all');
    }
}); 