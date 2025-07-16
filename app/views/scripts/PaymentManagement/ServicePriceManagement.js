// Service Price Management JavaScript
class ServicePriceManager {
    constructor() {
        console.log('ServicePriceManager constructor called');
        this.baseUrl = window.BASE_URL;
        console.log('BASE_URL:', this.baseUrl);
        
        this.initializeEventListeners();
        this.loadServicePrices();
        this.loadOverdueConfig();
    }

    initializeEventListeners() {
        // Service price modal events
        document.getElementById('addServiceBtn').addEventListener('click', () => {
            this.showServicePriceModal();
        });

        document.getElementById('closeServicePriceModal').addEventListener('click', () => {
            this.hideServicePriceModal();
        });

        document.getElementById('cancelServicePriceBtn').addEventListener('click', () => {
            this.hideServicePriceModal();
        });

        document.getElementById('servicePriceForm').addEventListener('submit', (e) => {
            e.preventDefault();
            this.saveServicePrice();
        });

        document.getElementById('servicePriceModal').addEventListener('click', (e) => {
            if (e.target.id === 'servicePriceModal') {
                this.hideServicePriceModal();
            }
        });

        document.getElementById('editConfigBtn').addEventListener('click', () => {
            this.showConfigForm();
        });

        document.getElementById('saveConfigBtn').addEventListener('click', () => {
            this.saveOverdueConfig();
        });

        document.getElementById('cancelConfigBtn').addEventListener('click', () => {
            this.hideConfigForm();
        });

        document.getElementById('refreshDataBtn').addEventListener('click', () => {
            this.loadServicePrices();
            this.loadOverdueConfig();
        });
     }

    async loadServicePrices() {
        try {
            const response = await fetch(`${this.baseUrl}/dentalassistant/get-service-prices`);
            const data = await response.json();
            
            if (data.success) {
                this.renderServicePricesTable(data.services);
            } else {
                this.showToast('Error loading service prices', 'error');
            }
        } catch (error) {
            console.error('Error loading service prices:', error);
            this.showToast('Error loading service prices', 'error');
        }
    }

    renderServicePricesTable(services) {
        const tbody = document.getElementById('servicesTableBody');
        tbody.innerHTML = '';

        services.forEach(service => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-50';
            
            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    ${service.ServiceName}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ₱${parseFloat(service.ServicePrice).toFixed(2)}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs font-semibold rounded-full ${
                        parseInt(service.IsActive) === 1 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'
                    }">
                        ${parseInt(service.IsActive) === 1 ? 'Active' : 'Inactive'}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <div class="flex space-x-2">
                        <button onclick="servicePriceManager.editServicePrice(${service.ServicePriceID})" 
                                class="glass-card bg-nhd-blue/80 text-white px-3 py-1 rounded-2xl text-xs hover:bg-nhd-blue transition-colors">
                            <svg class="w-3 h-3 mr-1 inline" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                            </svg>Edit
                        </button>
                        <button onclick="servicePriceManager.toggleServiceStatus(${service.ServicePriceID})" 
                                class="glass-card bg-gray-600/80 text-white px-3 py-1 rounded-2xl text-xs hover:bg-gray-600 transition-colors">
                            <svg class="w-3 h-3 mr-1 inline" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>Toggle
                        </button>
                        <button onclick="servicePriceManager.deleteServicePrice(${service.ServicePriceID})" 
                                class="glass-card bg-red-500 text-white px-3 py-1 rounded-2xl text-xs hover:bg-red-600 transition-colors">
                            <svg class="w-3 h-3 mr-1 inline" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>Delete
                        </button>
                    </div>
                </td>
            `;
            
            tbody.appendChild(row);
        });
    }

    showServicePriceModal(serviceData = null) {
        const modal = document.getElementById('servicePriceModal');
        const title = document.getElementById('servicePriceModalTitle');
        const form = document.getElementById('servicePriceForm');
        
        // Reset form
        form.reset();
        
        if (serviceData) {
            // Edit mode
            title.textContent = 'Edit Service Price';
            document.getElementById('servicePriceID').value = serviceData.ServicePriceID;
            document.getElementById('serviceName').value = serviceData.ServiceName;
            document.getElementById('servicePrice').value = serviceData.ServicePrice;
            document.getElementById('isActive').checked = serviceData.IsActive == 1;
        } else {
            // Add mode
            title.textContent = 'Add Service Price';
            document.getElementById('servicePriceID').value = '';
            document.getElementById('isActive').checked = true;
        }
        
        modal.classList.remove('hidden');
    }

    hideServicePriceModal() {
        const modal = document.getElementById('servicePriceModal');
        modal.classList.add('hidden');
    }

    async saveServicePrice() {
        const servicePriceID = document.getElementById('servicePriceID').value;
        const serviceName = document.getElementById('serviceName').value.trim();
        const servicePrice = parseFloat(document.getElementById('servicePrice').value);
        const isActive = document.getElementById('isActive').checked ? 1 : 0;

        if (!serviceName || servicePrice <= 0) {
            this.showToast('Please enter a valid service name and price', 'error');
            return;
        }

        const isEdit = servicePriceID !== '';
        const action = isEdit ? 'update' : 'create';
        const message = isEdit ? 
            `Are you sure you want to update the price for "${serviceName}" to ₱${servicePrice.toFixed(2)}?` :
            `Are you sure you want to add "${serviceName}" with price ₱${servicePrice.toFixed(2)}?`;

        // Show confirmation modal
        if (!await this.showConfirmationModal('Confirm Service Price Change', message)) {
            return;
        }

        const url = isEdit ? 
            `${this.baseUrl}/dentalassistant/update-service-price` : 
            `${this.baseUrl}/dentalassistant/create-service-price`;

        const payload = {
            serviceName: serviceName,
            servicePrice: servicePrice,
            isActive: isActive
        };

        if (isEdit) {
            payload.servicePriceID = parseInt(servicePriceID);
        }

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(payload)
            });

            const data = await response.json();

            if (data.success) {
                this.showToast(data.message, 'success');
                this.hideServicePriceModal();
                this.loadServicePrices();
            } else {
                this.showToast(data.message || 'Error saving service price', 'error');
            }
        } catch (error) {
            console.error('Error saving service price:', error);
            this.showToast('Error saving service price', 'error');
        }
    }

    async editServicePrice(servicePriceID) {
        try {
            const response = await fetch(`${this.baseUrl}/dentalassistant/get-service-prices`);
            const data = await response.json();
            
            if (data.success) {
                const service = data.services.find(s => s.ServicePriceID == servicePriceID);
                if (service) {
                    this.showServicePriceModal(service);
                } else {
                    this.showToast('Service not found', 'error');
                }
            } else {
                this.showToast('Error loading service data', 'error');
            }
        } catch (error) {
            console.error('Error loading service data:', error);
            this.showToast('Error loading service data', 'error');
        }
    }

    async toggleServiceStatus(servicePriceID) {
        try {
            // Get current service data to show in confirmation
            const response = await fetch(`${this.baseUrl}/dentalassistant/get-service-prices`);
            const data = await response.json();
            
            if (data.success) {
                const service = data.services.find(s => s.ServicePriceID == servicePriceID);
                if (service) {
                    const newStatus = service.IsActive == 1 ? 'inactive' : 'active';
                    const message = `Are you sure you want to make "${service.ServiceName}" ${newStatus}?`;
                    
                    if (!await this.showConfirmationModal('Confirm Status Change', message)) {
                        return;
                    }
                } else {
                    this.showToast('Service not found', 'error');
                    return;
                }
            } else {
                this.showToast('Error loading service data', 'error');
                return;
            }
        } catch (error) {
            console.error('Error loading service data:', error);
            this.showToast('Error loading service data', 'error');
            return;
        }

        try {
            const response = await fetch(`${this.baseUrl}/dentalassistant/toggle-service-price-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    servicePriceID: servicePriceID
                })
            });

            const data = await response.json();

            if (data.success) {
                this.showToast(data.message, 'success');
                this.loadServicePrices();
            } else {
                this.showToast(data.message || 'Error toggling service status', 'error');
            }
        } catch (error) {
            console.error('Error toggling service status:', error);
            this.showToast('Error toggling service status', 'error');
        }
    }

    async deleteServicePrice(servicePriceID) {
        try {
            // Get current service data to show in confirmation
            const response = await fetch(`${this.baseUrl}/dentalassistant/get-service-prices`);
            const data = await response.json();
            
            if (data.success) {
                const service = data.services.find(s => s.ServicePriceID == servicePriceID);
                if (service) {
                    const message = `Are you sure you want to delete "${service.ServiceName}" (₱${parseFloat(service.ServicePrice).toFixed(2)})? This action cannot be undone.`;
                    
                    if (!await this.showConfirmationModal('Confirm Delete', message, 'Delete', 'danger')) {
                        return;
                    }
                } else {
                    this.showToast('Service not found', 'error');
                    return;
                }
            } else {
                this.showToast('Error loading service data', 'error');
                return;
            }
        } catch (error) {
            console.error('Error loading service data:', error);
            this.showToast('Error loading service data', 'error');
            return;
        }

        try {
            const response = await fetch(`${this.baseUrl}/dentalassistant/delete-service-price`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    servicePriceID: servicePriceID
                })
            });

            const data = await response.json();

            if (data.success) {
                this.showToast(data.message, 'success');
                this.loadServicePrices();
            } else {
                this.showToast(data.message || 'Error deleting service price', 'error');
            }
        } catch (error) {
            console.error('Error deleting service price:', error);
            this.showToast('Error deleting service price', 'error');
        }
    }   

    async loadOverdueConfig() {
        console.log('Loading overdue config...');
        try {
            const response = await fetch(`${this.baseUrl}/dentalassistant/get-overdue-config`);
            const data = await response.json();
            
            console.log('Overdue config response:', data);
            
            if (data.success) {
                this.renderOverdueConfig(data.config);
                this.showToast('Overdue configuration loaded successfully', 'success');
            } else {
                console.error('Failed to load overdue config:', data);
                this.showToast('Error loading overdue configuration', 'error');
            }
        } catch (error) {
            console.error('Error loading overdue configuration:', error);
            this.showToast('Error loading overdue configuration', 'error');
        }
    }

    renderOverdueConfig(config) {
        // Update the display values
        document.getElementById('currentPercentage').textContent = `${parseFloat(config.OverduePercentage).toFixed(2)}%`;
        document.getElementById('currentGracePeriod').textContent = `${config.GracePeriodDays} days`;
        document.getElementById('lastUpdated').textContent = config.UpdatedAt ? 
            new Date(config.UpdatedAt).toLocaleDateString() : 'Never';
        
        // Update the form fields
        document.getElementById('overduePercentage').value = config.OverduePercentage;
        document.getElementById('gracePeriodDays').value = config.GracePeriodDays;
        document.getElementById('configName').value = config.ConfigName || 'Configuration';
    }

    showConfigForm() {
        document.getElementById('configForm').classList.remove('hidden');
        document.getElementById('editConfigBtn').classList.add('hidden');
        document.getElementById('refreshDataBtn').classList.add('hidden');
    }

    hideConfigForm() {
        document.getElementById('configForm').classList.add('hidden');
        document.getElementById('editConfigBtn').classList.remove('hidden');
        document.getElementById('refreshDataBtn').classList.remove('hidden');
    }

    async saveOverdueConfig() {
        const overduePercentage = parseFloat(document.getElementById('overduePercentage').value);
        const gracePeriodDays = parseInt(document.getElementById('gracePeriodDays').value);
        const configName = document.getElementById('configName').value.trim();

        if (isNaN(overduePercentage) || isNaN(gracePeriodDays)) {
            this.showToast('Please enter valid overdue percentage and grace period days', 'error');
            return;
        }

        if (overduePercentage < 0 || overduePercentage > 100) {
            this.showToast('Overdue percentage must be between 0 and 100', 'error');
            return;
        }

        if (gracePeriodDays < 0) {
            this.showToast('Grace period days cannot be negative', 'error');
            return;
        }

        if (!configName) {
            this.showToast('Please enter a configuration name', 'error');
            return;
        }

        const payload = {
            overduePercentage: overduePercentage,
            gracePeriodDays: gracePeriodDays,
            configName: configName
        };

        try {
            const response = await fetch(`${this.baseUrl}/dentalassistant/update-overdue-config`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(payload)
            });

            const data = await response.json();

            if (data.success) {
                this.showToast(data.message, 'success');
                this.hideConfigForm();
                this.loadOverdueConfig();
            } else {
                this.showToast(data.message || 'Error saving configuration', 'error');
            }
        } catch (error) {
            console.error('Error saving configuration:', error);
            this.showToast('Error saving configuration', 'error');
        }
    }

    showToast(message, type = 'info') {
        console.log(`Toast: ${type} - ${message}`); 
        
        if (window.toast) {
            switch (type) {
                case 'success':
                    window.toast.success(message);
                    break;
                case 'error':
                    window.toast.error(message);
                    break;
                case 'warning':
                    window.toast.warning(message);
                    break;
                default:
                    window.toast.info(message);
                    break;
            }
        } else {
            console.error('Toast system not available');
        }
    }

    showConfirmationModal(title, message, confirmText = 'Confirm', type = 'primary') {
        return new Promise((resolve) => {
            const modalHTML = `
                <div id="confirmationModal" class="fixed inset-0 bg-gray-600/50 flex items-center justify-center p-4 z-50">
                    <div class="bg-white w-full max-w-md rounded-2xl shadow-lg">
                        <div class="flex items-center justify-between p-5 border-b border-gray-200">
                            <h3 class="text-xl font-semibold text-nhd-brown">${title}</h3>
                            <button id="closeConfirmationModal" class="glass-card bg-nhd-blue/80 text-white text-sm transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <div class="p-5">
                            <p class="text-gray-700 mb-6">${message}</p>
                            
                            <div class="flex justify-end space-x-3">
                                <button id="cancelConfirmationBtn" class="glass-card px-4 py-2 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-colors">
                                    Cancel
                                </button>
                                <button id="confirmConfirmationBtn" class="glass-card px-4 py-2 rounded-xl text-white transition-colors ${
                                    type === 'danger' ? 'bg-red-500 hover:bg-red-600' : 'bg-nhd-blue hover:bg-nhd-blue/90'
                                }">
                                    ${confirmText}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Add modal to body
            document.body.insertAdjacentHTML('beforeend', modalHTML);

            const modal = document.getElementById('confirmationModal');
            const closeBtn = document.getElementById('closeConfirmationModal');
            const cancelBtn = document.getElementById('cancelConfirmationBtn');
            const confirmBtn = document.getElementById('confirmConfirmationBtn');

            // Event listeners
            const cleanup = () => {
                modal.remove();
            };

            const handleConfirm = () => {
                cleanup();
                resolve(true);
            };

            const handleCancel = () => {
                cleanup();
                resolve(false);
            };

            closeBtn.addEventListener('click', handleCancel);
            cancelBtn.addEventListener('click', handleCancel);
            confirmBtn.addEventListener('click', handleConfirm);

            // Close modal when clicking outside
            modal.addEventListener('click', (e) => {
                if (e.target.id === 'confirmationModal') {
                    handleCancel();
                }
            });

            // Close modal on escape key
            const handleEscape = (e) => {
                if (e.key === 'Escape') {
                    document.removeEventListener('keydown', handleEscape);
                    handleCancel();
                }
            };
            document.addEventListener('keydown', handleEscape);
        });
    }
}

// Initialize the service price manager when the page loads
let servicePriceManager;
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing ServicePriceManager...');
    servicePriceManager = new ServicePriceManager();
    console.log('ServicePriceManager initialized:', servicePriceManager);
    
    // Test toast system manually
    window.testToast = function(message, type) {
        servicePriceManager.showToast(message, type);
    };
});
