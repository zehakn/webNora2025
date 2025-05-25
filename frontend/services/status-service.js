/**
 * Status Service for handling all status-related operations in the frontend
 */
var StatusService = {
    /**
     * Initialize the status service
     */
    init: function() {
        // Initialize status-related functionality
    },
    
    /**
     * Get all statuses
     * @param {Function} successCallback - Function to call on success
     * @param {Function} errorCallback - Function to call on error
     */
    getAllStatuses: function(successCallback, errorCallback) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "test-statuses",
            type: "GET",
            contentType: "application/json",
            dataType: "json",
            beforeSend: function(xhr) {
                const token = localStorage.getItem("user_token");
                if (token) {
                    xhr.setRequestHeader("Authorization", "Bearer " + token);
                }
            },
            success: function(result) {
                if (successCallback) successCallback(result);
            },
            error: function(xhr) {
                if (errorCallback) {
                    errorCallback(xhr.responseText || "Failed to fetch statuses");
                } else {
                    toastr.error(xhr.responseText || "Failed to fetch statuses");
                }
            }
        });
    },
    
    /**
     * Get status by name
     * @param {String} name - Status name
     * @param {Function} successCallback - Function to call on success
     * @param {Function} errorCallback - Function to call on error
     */
    getStatusByName: function(name, successCallback, errorCallback) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "test-status-by-name/" + encodeURIComponent(name),
            type: "GET",
            contentType: "application/json",
            dataType: "json",
            beforeSend: function(xhr) {
                const token = localStorage.getItem("user_token");
                if (token) {
                    xhr.setRequestHeader("Authorization", "Bearer " + token);
                }
            },
            success: function(result) {
                if (successCallback) successCallback(result);
            },
            error: function(xhr) {
                if (errorCallback) {
                    errorCallback(xhr.responseText || "Failed to fetch status");
                } else {
                    toastr.error(xhr.responseText || "Failed to fetch status");
                }
            }
        });
    },
    
    /**
     * Create a new status
     * @param {Object} statusData - Status data
     * @param {Function} successCallback - Function to call on success
     * @param {Function} errorCallback - Function to call on error
     */
    createStatus: function(statusData, successCallback, errorCallback) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "test-status",
            type: "POST",
            data: JSON.stringify(statusData),
            contentType: "application/json",
            dataType: "json",
            beforeSend: function(xhr) {
                const token = localStorage.getItem("user_token");
                if (token) {
                    xhr.setRequestHeader("Authorization", "Bearer " + token);
                }
            },
            success: function(result) {
                toastr.success("Status created successfully");
                if (successCallback) successCallback(result);
            },
            error: function(xhr) {
                if (errorCallback) {
                    errorCallback(xhr.responseText || "Failed to create status");
                } else {
                    toastr.error(xhr.responseText || "Failed to create status");
                }
            }
        });
    },
    
    /**
     * Update an existing status
     * @param {Number} id - Status ID
     * @param {Object} statusData - Updated status data
     * @param {Function} successCallback - Function to call on success
     * @param {Function} errorCallback - Function to call on error
     */
    updateStatus: function(id, statusData, successCallback, errorCallback) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "test-status/" + id,
            type: "PUT",
            data: JSON.stringify(statusData),
            contentType: "application/json",
            dataType: "json",
            beforeSend: function(xhr) {
                const token = localStorage.getItem("user_token");
                if (token) {
                    xhr.setRequestHeader("Authorization", "Bearer " + token);
                }
            },
            success: function(result) {
                toastr.success("Status updated successfully");
                if (successCallback) successCallback(result);
            },
            error: function(xhr) {
                if (errorCallback) {
                    errorCallback(xhr.responseText || "Failed to update status");
                } else {
                    toastr.error(xhr.responseText || "Failed to update status");
                }
            }
        });
    },
    
    /**
     * Delete a status
     * @param {Number} id - Status ID
     * @param {Function} successCallback - Function to call on success
     * @param {Function} errorCallback - Function to call on error
     */
    deleteStatus: function(id, successCallback, errorCallback) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "test-status/" + id,
            type: "DELETE",
            contentType: "application/json",
            dataType: "json",
            beforeSend: function(xhr) {
                const token = localStorage.getItem("user_token");
                if (token) {
                    xhr.setRequestHeader("Authorization", "Bearer " + token);
                }
            },
            success: function(result) {
                toastr.success("Status deleted successfully");
                if (successCallback) successCallback(result);
            },
            error: function(xhr) {
                if (errorCallback) {
                    errorCallback(xhr.responseText || "Failed to delete status");
                } else {
                    toastr.error(xhr.responseText || "Failed to delete status");
                }
            }
        });
    },
    
    /**
     * Get status options for dropdown menus
     * @param {Function} callback - Function to call with formatted status options
     */
    getStatusOptions: function(callback) {
        this.getAllStatuses(
            function(statuses) {
                let options = statuses.map(function(status) {
                    return {
                        id: status.id,
                        text: status.name,
                        color: status.color || null
                    };
                });
                if (callback) callback(options);
            },
            function(error) {
                console.error("Error fetching status options:", error);
                if (callback) callback([]);
            }
        );
    },
    
    /**
     * Render a status badge with appropriate styling
     * @param {String} statusName - Name of the status
     * @param {String} defaultColor - Default color if status not found
     * @returns {String} HTML string for the status badge
     */
    renderStatusBadge: function(statusName, defaultColor = "#6c757d") {
        // Create a placeholder badge with the status name
        let badgeHtml = `<span class="badge bg-secondary">${statusName || 'Unknown'}</span>`;
        
        // Try to find the status to get its color
        this.getStatusByName(statusName, 
            function(status) {
                if (status && status.color) {
                    const textColor = this.getContrastTextColor(status.color);
                    badgeHtml = `<span class="badge" style="background-color: ${status.color}; color: ${textColor}">${status.name}</span>`;
                    // Replace the placeholder badge with the styled one
                    $(`.status-badge[data-status="${statusName}"]`).replaceWith(badgeHtml);
                }
            }.bind(this)
        );
        
        return `<span class="status-badge" data-status="${statusName}">${badgeHtml}</span>`;
    },
    
    /**
     * Calculate contrast text color (black or white) based on background color
     * @param {String} bgColor - Background color in hex format
     * @returns {String} Either black or white as the appropriate text color
     */
    getContrastTextColor: function(bgColor) {
        // Remove the hash if it exists
        bgColor = bgColor.replace('#', '');
        
        // Convert to RGB
        const r = parseInt(bgColor.substr(0, 2), 16);
        const g = parseInt(bgColor.substr(2, 2), 16);
        const b = parseInt(bgColor.substr(4, 2), 16);
        
        // Calculate luminance
        const luminance = (0.299 * r + 0.587 * g + 0.114 * b) / 255;
        
        // Return black or white based on luminance
        return (luminance > 0.5) ? '#000000' : '#FFFFFF';
    }
};

// Initialize the status service when document is ready
$(document).ready(function() {
    StatusService.init();
});
